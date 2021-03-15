<?php

namespace Framework;

use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

// TODO: use twig template for exceptions

class ExceptionController extends Controller
{
    function handlePageNotFound(string $errorMessage): Response
    {
        global $_SQL, $_V, $site;

        $currentUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($currentUrl);
        $redirectionOld = $parsedUrl["path"];
        if (!empty($parsedUrl["query"])) {
            $redirectionOld .= '?' . $parsedUrl["query"];
        }

        $redirections = $_SQL->prepare(
            "SELECT `redirection_id`, `redirection_new`
          FROM `redirections` WHERE (`redirection_old` = :redirection_old)
              AND `redirection_old` != `redirection_new`
              AND (`site_id` = :site_id OR `site_id` IS NULL) LIMIT 1"
        );
        $redirections->execute(
            [
                'redirection_old' => $redirectionOld,
                'site_id' => $site->get('id')
            ]
        );
        if ($r = $redirections->fetch(\PDO::FETCH_ASSOC)) {
            $response = new RedirectResponse($r['redirection_new'], 301);
        } else {
            $response = new Response();
            $response->setStatusCode(404);

            $this->setPageTitle("Erreur 404");
            $content = '
              <h2>Erreur 404</h2>
              <p>Cette page  n\'existe pas !</p>
          ';

            if ($_V->isAdmin()) {
                $content .= '

                  ' . (isset($errorMessage) ? '<p>Debug info: ' . $errorMessage . '</p>' : null) . '
                  <p>Page : ' . $redirectionOld . '</p>
              ';
            }

            $response->setContent($content);
        }
        return $response;
    }

    function handleServiceUnavailable(): Response
    {
        $response = new Response();
        $response->setStatusCode(503);
        $response->headers->set('Retry-After', 3600);
        $response->setContent('
            <div class="text-center">
                <h1>Maintenance en cours</h1>
                <p>Merci de réessayer dans quelques instants…</p>
            </div>
        ');

        return $response;
    }

    function handleUnauthorizedAccess(Request $request, $axys, string $message): Response
    {
        if (
            $request->isXmlHttpRequest()
            || $request->headers->get('Accept') == 'application/json'
        ) {
            $response = new JsonResponse(['error' => $message]);
            $response->setStatusCode(401);
            return $response;
        }

        $response = new Response();
        $response->setStatusCode(403);
        $response->setContent('
            <h1>Erreur d\'authentification</h1>
            <p>' . $message . '</p>
            <p>Pour continuer, veuillez <a href="' . $axys->getLoginUrl() . '">vous identifier</a> ou <a href="' . $axys->getSignupUrl() . '">créer un compte</a>.</p>
        ');

        return $response;
    }
}
