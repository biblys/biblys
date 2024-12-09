<?php /** @noinspection SqlCheckUsingColumns */
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use CronJobManager;
use EntityManager;
use Exception;
use Framework\Controller;
use PDO;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CronsController extends Controller
{
    /**
     * @param Config $config
     * @param Request $request
     * @throws Exception
     */
    private static function _authenticateCronRequest(Config $config, Request $request): void
    {
        $siteCron = $config->get('cron');
        if (!$siteCron) {
            throw new Exception('Cron is not configured for this site');
        }
        if (!isset($siteCron['key'])) {
            throw new Exception('Key is missing in cron configuration');
        }

        $requestCronKey = $request->headers->get('X-CRON-KEY');
        if (!$requestCronKey) {
            throw new UnauthorizedHttpException('Request lacks X-CRON-KEY header');
        }

        if ($requestCronKey !== $siteCron['key']) {
            throw new UnauthorizedHttpException('Wrong cron key');
        }
    }

    /**
     * GET /crons/.
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function tasksAction(CurrentUser $currentUser): Response
    {
        $currentUser->authAdmin();

        $cjm = new CronJobManager();
        $jobs = $cjm->getAll(
            [],
            ['order' => 'cron_job_created', 'sort' => 'desc']
        );

        return $this->render(
            'AppBundle:Crons:tasks.html.twig',
            [
                'jobs' => $jobs,
            ]
        );
    }

    /**
     * GET /crons/{slug}/jobs
     * Generic controller to display job logs of a cron task.
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function jobsAction(
        CurrentUser $currentUser, string $slug,
    ): Response
    {
        $currentUser->authAdmin();

        $config = LegacyCodeHelper::getGlobalConfig();;

        if (!in_array($slug, ['test', 'export-pdl'])) {
            throw new ResourceNotFoundException('Unknown cron task ' . htmlentities($slug));
        }

        $cjm = new CronJobManager();
        $jobs = $cjm->getAll(
            ['cron_job_task' => $slug],
            ['order' => 'cron_job_created', 'sort' => 'desc']
        );

        $cronKey = null;
        $cronConfig = $config->get('cron');
        if ($cronConfig) {
            $cronKey = $cronConfig['key'];
        }

        return $this->render(
            'AppBundle:Crons:jobs.html.twig', [
                'slug' => $slug,
                'jobs' => $jobs,
                'cronKey' => $cronKey,
            ]
        );
    }

    /**
     * GET /crons/test
     * A test cron task.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function testAction(Request $request): JsonResponse
    {
        $config = LegacyCodeHelper::getGlobalConfig();;

        $request->headers->set('Accept', 'application/json');

        self::_authenticateCronRequest($config, $request);

        $cjm = new CronJobManager();
        $job = $cjm->create(
            [
                'cron_job_task' => 'test',
                'cron_job_result' => 'success',
                'cron_job_message' => 'La tâche planifiée de test a bien été executée.',
            ]
        );

        return new JsonResponse(
            [
                'id' => $job->get('id'),
                'result' => $job->get('result'),
                'message' => $job->get('message'),
                'date' => $job->get('created'),
            ]
        );
    }

    /**
     * GET /crons/export-pdl
     * Export to Place des Libraires cron task.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function exportPdlAction(Request $request): JsonResponse
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();
        $config = LegacyCodeHelper::getGlobalConfig();;

        $request->headers->set('Accept', 'application/json');

        $response = new JsonResponse();

        self::_authenticateCronRequest($config, $request);

        $pdl = $config->get('placedeslibraires');
        if (!$pdl) {
            throw new Exception('Place des libraires credentials are missing in config file');
        }

        $cjm = new CronJobManager();

        $ftpServer = 'ftp.titelive.com';
        $shopId = $pdl['shop_id'];
        $login = $pdl['login'];
        $password = $pdl['password'];

        $active_stock_query = null;
        $active_stock = $globalSite->getOpt('active_stock');
        if ($active_stock) {
            $active_stock = "'" . implode("','", explode(',', $active_stock)) . "'";
            $active_stock_query = ' AND `stock_stockage` IN (' . $active_stock . ')';
        }

        $query = "
            SELECT 
                MAX(`article_ean`) AS `ean`, 
                COUNT(`stock_id`) AS `qty`,
                MAX(`stock_selling_price`) `price`
            FROM `stock` 
            JOIN articles USING(`article_id`)
            WHERE `site_id` = :site_id
                AND `article_ean` IS NOT NULL
                AND `stock_selling_date` IS NULL AND `stock_return_date` IS NULL
                AND `stock_lost_date` IS NULL AND `stock_condition` = 'Neuf'
                " . $active_stock_query . '
            GROUP BY `article_ean`';
        $stock = EntityManager::prepareAndExecute(
            $query,
            ['site_id' => $globalSite->get('id')]
        );

        $title = 'EXTRACTION STOCK DU ' . date('d/m/Y');
        $stockCount = 0;
        $articleCount = 0;

        $lines = [];
        while ($item = $stock->fetch(PDO::FETCH_ASSOC)) {
            $ean = $item['ean'];
            $qty = str_pad($item['qty'], 4, '0', STR_PAD_LEFT);
            $price = str_pad($item['price'], 10, '0', STR_PAD_LEFT);

            $line = $shopId . $ean . $qty . $price;

            if (strlen($line) !== 31) {
                throw new Exception("Line for $ean is not 31 chars long: $line");
            }

            $stockCount += $item['qty'];
            ++$articleCount;

            $lines[] = $line;
        }

        $file = $title . "\r\n" . join("\r\n", $lines);

        $stream = stream_context_create(['ftp' => ['overwrite' => true]]);
        $ftp = "ftp://$login:$password@$ftpServer/" . $shopId . '_ART.asc';

        if (getenv("PHP_ENV") !== "test") {
            file_put_contents($ftp, $file, 0, $stream);
        }

        $message = "Export Place des Libraires réussi ($articleCount articles, $stockCount exemplaires).";
        $result = "success";

        $job = $cjm->create([
            'cron_job_task' => 'export-pdl',
            'cron_job_result' => $result,
            'cron_job_message' => $message,
        ]);

        $response->setContent(
            json_encode([
                'id' => $job->get('id'),
                'result' => $job->get('result'),
                'message' => $job->get('message'),
                'date' => $job->get('created'),
            ])
        );

        return $response;
    }
}
