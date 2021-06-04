<?php

namespace AppBundle\Controller;

use Framework\Composer;
use Biblys\Service\Pagination;
use Biblys\Service\Updater\ReleaseNotFoundException;
use Biblys\Service\Updater\Updater;
use Biblys\Service\Updater\UpdaterException;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     */
    public function updateAction(Request $request, Updater $updater): Response
    {
        global $urlgenerator;

        $request->attributes->set("page_title", "Mise à jour de Biblys");
        $this->auth('admin');

        // Download available updates
        $error = null;
        try {
            $updater->downloadUpdates();
        } catch (UpdaterException $exception) {
            $error = "";
            while($exception instanceof Exception) {
                $error .= $exception->getMessage()."\n";
                $exception = $exception->getPrevious();
            }
        }

        // Get releases newer than current version
        $releases = $updater->getReleasesNewerThan(BIBLYS_VERSION);

        // Update for good
        if ($request->getMethod() == 'POST') {
            // Applying update
            $latest = $updater->getLatestRelease();
            $updater->applyRelease($latest);

            return new RedirectResponse($urlgenerator->generate('maintenance_updating', [
                'version' => $latest->version,
            ]));
        }

        return $this->render('AppBundle:Maintenance:update.html.twig', [
            'releases' => $releases,
            'version' => BIBLYS_VERSION,
            'error' => $error,
        ]);
    }

    /**
     * @throws AuthException
     */
    public function updatingAction(string $version, Request $request)
    {
        global $urlgenerator;

        $this->auth('admin');
        $request->attributes->set("page_title", "Mise à jour de Biblys en cours");

        if (BIBLYS_VERSION == $version) {
            return $this->redirect($urlgenerator->generate('maintenance_composer'));
        }

        return $this->render('AppBundle:Maintenance:updating.html.twig', [
            'current' => BIBLYS_VERSION,
            'target' => $version,
        ]);
    }

    /**
     * @throws AuthException
     */
    public function composerAction(): Response
    {
        $this->auth('admin');

        try {
            Composer::runScript('install');
        } catch (Exception $exception) {
            return $this->render('AppBundle:Maintenance:composer.html.twig', [
                'error' => $exception->getMessage(),
            ]);
        }

        return $this->render('AppBundle:Maintenance:composer.html.twig');
    }

    public function changelogIndexAction(Request $request, Updater $updater): Response
    {
        $request->attributes->set("page_title", "Historique des mises à jour");

        $releases = $updater->getReleases();

        $page = (int) $request->query->get('p', 0);
        $pagination = new Pagination($page, count($releases));
        $currentPageReleases = array_slice($releases, $pagination->getOffset(), $pagination->getLimit());

        return $this->render('AppBundle:Maintenance:changelogIndex.html.twig', [
            'releases' => $currentPageReleases,
            'pages' => $pagination,
        ]);
    }

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
