<?php

namespace AppBundle\Controller;

use Biblys\Service\Updater;
use Framework\Controller;
use Framework\Framework;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MaintenanceController extends Controller
{
    public function infosAction()
    {
        return new JsonResponse([
            'version' => BIBLYS_VERSION,
        ]);
    }

    public function updateAction(Request $request)
    {
        global $urlgenerator;

        $updater = new Updater(BIBLYS_PATH, BIBLYS_VERSION);

        $this->setPageTitle('Mise à jour de Biblys');
        $this->auth('admin');

        // Download available updates
        $offline = false;
        $download = $updater->downloadUpdates();
        if (!$download) {
            $offline = true;
        }

        // Get releases newer than current version
        $releases = $updater->getReleasesNewerThan(BIBLYS_VERSION);

        // Update for good
        if ($request->getMethod() == 'POST') {
            // Applying update
            $latest = $updater->getLatestRelease();
            $updater->applyRelease($latest);

            redirect($urlgenerator->generate('maintenance_updating', [
                'version' => $latest['version'],
            ]));
        }

        return $this->render('AppBundle:Maintenance:update.html.twig', [
            'releases' => $releases,
            'version' => BIBLYS_VERSION,
            'offline' => $offline,
        ]);
    }

    public function updatingAction($version)
    {
        global $urlgenerator;

        $this->auth('admin');
        $this->setPageTitle('Mise à jour de Biblys en cours');

        if (BIBLYS_VERSION == $version) {
            return $this->redirect($urlgenerator->generate('maintenance_composer'));
        }

        return $this->render('AppBundle:Maintenance:updating.html.twig', [
            'current' => BIBLYS_VERSION,
            'target' => $version,
        ]);
    }

    public function composerAction()
    {
        global $config;

        $this->auth('admin');

        try {
            Framework::runComposerCommand('install');
        } catch (Exception $exception) {
            return $this->render('AppBundle:Maintenance:composer.html.twig', [
                'error' => $exception->getMessage(),
            ]);
        }

        return $this->render('AppBundle:Maintenance:composer.html.twig');
    }

    public function changelogIndexAction()
    {
        $this->setPageTitle('Historique des mises à jour');

        $updater = new Updater(BIBLYS_PATH, BIBLYS_VERSION);

        $releases = $updater->getReleases();
        $releases = $updater->getReleasesDetails($releases);

        return $this->render('AppBundle:Maintenance:changelogIndex.html.twig', ['releases' => $releases]);
    }

    public function changelogShowAction($version)
    {
        $this->setPageTitle("Mise à jour $version");

        $updater = new Updater(BIBLYS_PATH, BIBLYS_VERSION);

        $release = $updater->getRelease($version);
        $release = $updater->getReleasesDetails([$release]);

        return $this->render('AppBundle:Maintenance:changelogShow.html.twig', [
            'release' => $release[0],
        ]);
    }
}
