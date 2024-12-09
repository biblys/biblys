<?php
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



namespace ApiBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Exception;
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class LegacyController extends Controller
{
    /**
     * @throws Exception
     */
    public function defaultAction(
        Config            $config,
        Session           $session,
        CurrentUrlService $currentUrlService,
        UrlGenerator      $urlGenerator,
        CurrentUser       $currentUser,
        CurrentSite       $currentSite,
        Request           $request,
    ): void
    {
        LegacyCodeHelper::catchDeprecationNotices($config, $session);

        $response = new JsonResponse();

        // PAGE EN COURS
        $_PAGE = str_replace("/x/", "", $_SERVER["REQUEST_URI"]);
        $_PAGE = explode('?', $_PAGE);
        $_PAGE = $_PAGE[0];

        // Verification page utilisateur et admin
        $currentUrl = $currentUrlService->getRelativeUrl();
        $loginUrl = $urlGenerator->generate("user_login", ["return_url" => $currentUrl]);
        $_PAGE_TYPE = substr($_PAGE, 0, 4);

        if ($_PAGE_TYPE == "adm_" && !$currentUser->isAdmin() && !$currentUser->hasPublisherRight()) {
            json_error(0, "Cette action est réservée aux administrateurs (" . $_PAGE . "). Veuillez vous <a href='" . $loginUrl . "' rel='nofollow'>identifier</a>.");
        }
        if ($_PAGE_TYPE == "log_" and !$currentUser->isAuthenticated()) {
            json_error(0, "Action impossible. Veuillez vous <a href='" . $loginUrl . "' rel='nofollow'>identifier</a>.");
        }

        $_RESULT = null;

        $currentSiteController = __DIR__ . '/../../../controllers/' . $currentSite->getSite()->getName() . '/xhr/' . $_PAGE . '.php';
        $defaultController = __DIR__ . '/../../../controllers/common/xhr/' . $_PAGE . '.php';

        if (file_exists($currentSiteController)) {
            include($currentSiteController);
        } elseif (file_exists($defaultController)) {
            include($defaultController);
        } else {
            throw new NotFoundHttpException("Cannot find a legacy xhr controller for url $_PAGE.");
        }
        exit();
    }
}