<?php

namespace AppBundle\Controller;

use Biblys\Database\Database;
use Biblys\Service\Config;
use Biblys\Service\Pagination;
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
     */
    public function updateAction(Request $request, Updater $updater, Config $config): Response
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Mise à jour de Biblys");

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

    /**
     * @param Request $request
     * @param Updater $updater
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function changelogIndexAction(Request $request, Updater $updater): Response
    {
        $request->attributes->set("page_title", "Historique des mises à jour");

        $releases = $updater->getReleases();

        try {
            $page = (int) $request->query->get('p', 0);
            $pagination = new Pagination($page, count($releases));
            $currentPageReleases = array_slice($releases, $pagination->getOffset(), $pagination->getLimit());
        } catch(InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return $this->render('AppBundle:Maintenance:changelogIndex.html.twig', [
            'releases' => $currentPageReleases,
            'pages' => $pagination,
        ]);
    }

    /**
     * @param string $version
     * @param Updater $updater
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function changelogShowAction(
        string $version,
        Updater $updater,
        Request $request
    ): Response
    {

        try {
            $release = $updater->getRelease($version);
        } catch(ReleaseNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception->getPrevious());
        }

        $request->attributes->set("page_title", "Mise à jour $version");
        return $this->render('AppBundle:Maintenance:changelogShow.html.twig', [
            'release' => $release,
        ]);
    }
}
