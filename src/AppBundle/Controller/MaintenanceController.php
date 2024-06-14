<?php

namespace AppBundle\Controller;

use Biblys\Database\Database;
use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Biblys\Service\Updater\ReleaseNotFoundException;
use Biblys\Service\Updater\Updater;
use Biblys\Service\Updater\UpdaterException;
use Exception;
use Framework\Composer\ComposerException;
use Framework\Composer\ScriptRunner;
use Framework\Controller;
use Framework\Exception\AuthException;
use InvalidArgumentException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MaintenanceController extends Controller
{
    public function infosAction(): JsonResponse
    {
        return new JsonResponse([
            'version' => BIBLYS_VERSION,
        ]);
    }

    /**
     * @throws AuthException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws Exception
     */
    public function updateAction(
        Updater $updater,
        Config  $config,
        CurrentUser $currentUser,
    ): Response
    {
        $currentUser->authAdmin();

        // Download available updates
        $error = null;
        try {
            $updater->downloadUpdates($config);
        } catch (UpdaterException $exception) {
            $error = "";
            while($exception instanceof Exception) {
                $error .= $exception->getMessage()."\n";
                $exception = $exception->getPrevious();
            }
        }

        // Get releases newer than current version
        $releases = $updater->getReleasesNewerThan(BIBLYS_VERSION);

        return $this->render('AppBundle:Maintenance:update.html.twig', [
            'releases' => $releases,
            'version' => BIBLYS_VERSION,
            'error' => $error,
        ]);
    }
}
