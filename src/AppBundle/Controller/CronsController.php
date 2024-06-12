<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CronsController extends Controller
{
    /**
     * GET /crons/.
     */
    public function tasksAction()
    {
        $this->auth('admin');

        $this->setPageTitle('Journal des tâches planifiées');

        $cjm = new \CronJobManager();
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
     */
    public function jobsAction($slug)
    {
        $this->auth('admin');

        global $config;

        if (!in_array($slug, ['test', 'export-pdl'])) {
            throw new ResourceNotFoundException('Unknown cron task '.htmlentities($slug));
        }

        $this->setPageTitle("Journal de la tâche planifiée $slug");

        $cjm = new \CronJobManager();
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
     */
    public function testAction(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        $this->auth('cron');

        $cjm = new \CronJobManager();
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
     */
    public function exportPdlAction(Request $request)
    {
        global $site;
        global $_SQL;
        global $config;

        $request->headers->set('Accept', 'application/json');

        $this->auth('cron');

        $pdl = $config->get('placedeslibraires');
        if (!$pdl) {
            throw new \Exception('Place des libraires credentials are missing in config file');
        }

        $cjm = new \CronJobManager();

        try {
            $ftpServer = 'ftp.titelive.com';
            $shopId = $pdl['shop_id'];
            $login = $pdl['login'];
            $password = $pdl['password'];

            $active_stock_query = null;
            $active_stock = $site->getOpt('active_stock');
            if ($active_stock) {
                $active_stock = "'".implode("','", explode(',', $active_stock))."'";
                $active_stock_query = ' AND `stock_stockage` IN ('.$active_stock.')';
            }

            $query = "SELECT `article_ean`, COUNT(`stock_id`) AS `qty`, `stock_selling_price`
                FROM `stock` JOIN articles USING(`article_id`)
                WHERE `site_id` = :site_id
                    AND `article_ean` IS NOT NULL
                    AND `stock_selling_date` IS NULL AND `stock_return_date` IS NULL
                        AND `stock_lost_date` IS NULL AND `stock_condition` = 'Neuf'
                    AND `stock_deleted` IS NULL AND `article_deleted` IS NULL
                    ".$active_stock_query.'
                GROUP BY `article_ean`';
            $stock = $_SQL->prepare($query);
            $stock->execute(['site_id' => $site->get('id')]);

            $title = 'EXTRACTION STOCK DU '.date('d/m/Y');
            $stockCount = 0;
            $articleCount = 0;

            $lines = [];
            while ($item = $stock->fetch(\PDO::FETCH_ASSOC)) {
                $ean = $item['article_ean'];
                $qty = str_pad($item['qty'], 4, '0', STR_PAD_LEFT);
                $price = str_pad($item['stock_selling_price'], 10, '0', STR_PAD_LEFT);

                $line = $shopId.$ean.$qty.$price;

                if (strlen($line) !== 31) {
                    throw new \Exception("Line for $ean is not 31 chars long: $line");
                }

                $stockCount += $item['qty'];
                ++$articleCount;

                $lines[] = $line;
            }

            $file = $title."\r\n".join($lines, "\r\n");

            $stream = stream_context_create(['ftp' => ['overwrite' => true]]);
            $ftp = "ftp://$login:$password@$ftpServer/".$shopId.'_ART.asc';
            file_put_contents($ftp, $file, 0, $stream);

            $message = "Export Place des Libraires réussi ($articleCount articles, $stockCount exemplaires).";

            $job = $cjm->create(
                [
                    'cron_job_task' => 'export-pdl',
                    'cron_job_result' => 'success',
                    'cron_job_message' => $message,
                ]
            );
        } catch (\Exception $exception) {
            $job = $cjm->create(
                [
                    'cron_job_task' => 'export-pdl',
                    'cron_job_result' => 'error',
                    'cron_job_message' => $exception->getMessage(),
                ]
            );
        }

        return new JsonResponse(
            [
                'id' => $job->get('id'),
                'result' => $job->get('result'),
                'message' => $job->get('message'),
                'date' => $job->get('created'),
            ]
        );
    }
}
