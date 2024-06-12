<?php


namespace AppBundle\Controller;

use Exception;
use Framework\Controller;
use PageManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class LegacyController extends Controller
{
    public function defaultAction(Request $request): Response
    {
        global $site, $config, $session,
               $_SITE, $_LOG, $_V, $_ECHO, $_SQL, $_PAGE_TITLE,
               $_JS_CALLS, $_CSS_CALLS, $urlgenerator;

        $_PAGE = $request->get('page', 'home');

        $_PAGE_TYPE = substr($_PAGE, 0, 4);
        if ($_PAGE_TYPE == 'adm_') {
            $this->auth('admin');
        }
        if ($_PAGE_TYPE == 'log_') {
            $this->auth();
        }

        // Get correct controller for called url
        $controller_path = get_controller_path($_PAGE);
        $twig_template = BIBLYS_PATH . '/public/' . $site->get('name') . '/html/' . $_PAGE . '.html.twig';

        // Twig template controller
        if ($site->get('html_renderer') && file_exists($twig_template)) {
            $_HTML = $twig_template;
            $_INCLUDE = get_controller_path('_twig');
        }

        // Native controller
        elseif ($controller_path) {
            $_INCLUDE = $controller_path;
        }

        // Controller for static page from DB
        else {
            $pm = new PageManager();

            $page_request = ['page_url' => $_PAGE];
            if (!$_V->isAdmin()) {
                $page_request['page_status'] = 1;
            }
            $page = $pm->get($page_request);

            if ($page) {
                $_INCLUDE = get_controller_path('_page');
            } else {
                throw new ResourceNotFoundException('Cannot find static page ' . $_PAGE);
            }
        }

        // INCLUDE PAGE EN COURS
        if (isset($_INCLUDE)) {
            $_ECHO = null;
            $response = require $_INCLUDE;

            if (isset($_ECHO)) {
                trigger_error("Using \$_ECHO in $_INCLUDE. Legacy controllers should return a Response.", E_USER_DEPRECATED);
                return new Response($_ECHO);
            }

            // Is this still used?
            if (isset($_JSON)) {
                trigger_error("Using \$_JSON in $_INCLUDE. Legacy controllers should return a Response", E_USER_DEPRECATED);
                $_JSON->send();
                die();
            }

            // If response is JSON, return immediately and die
            if ($response instanceof JsonResponse) {
                $response->send();
                die();
            }

            return $response;
        }

        throw new Exception("Could not generate any Response");
    }
}