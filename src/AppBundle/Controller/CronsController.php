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
            ], isPrivate: true
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
            ], isPrivate: true
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
}
