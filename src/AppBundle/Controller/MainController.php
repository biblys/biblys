<?php

namespace AppBundle\Controller;

use Biblys\Admin\Entry;
use Framework\Controller;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MainController extends Controller
{
    // Used for triggering old controllers
    public function defaultAction(Request $request)
    {
        global $site, $config,
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
            $pm = new \PageManager();

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
                $_ECHO = null;
            };

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
    }

    public function homeAction(Request $request)
    {
        global $site;

        $opengraph = ['title' => $site->get('title')];
        $twitterCards = ['title' => $site->get('title')];

        $preview_image = $site->getOpt('home_preview_image');
        if ($preview_image) {
            $opengraph['image'] = $preview_image;
            $twitterCards['image'] = $preview_image;
            $twitterCards['image:alt'] = $site->get('title');
        }

        $preview_text = $site->getOpt('home_preview_text');
        if ($preview_text) {
            $opengraph['description'] = $preview_text;
            $twitterCards['description'] = $preview_text;
        }

        $this->setOpengraphTags($opengraph);
        $this->setTwitterCardsTags($twitterCards);

        // If a home page behavior is defined
        $behavior = $site->getOpt('home');
        if ($behavior) {
            // Custom Twig template
            if ($behavior == 'custom') {
                return $this->render('AppBundle:Main:home.html.twig');

            // Display articles
            } elseif ($behavior == 'articles') {
                $am = $this->entityManager('Article');

                // Pagination
                $page = (int) $request->query->get('p', 0);
                $totalCount = $am->countAll();
                $pagination = new \Biblys\Utils\Pagination($page, $totalCount);

                $articles = $am->getAll(['article_pubdate' => '<= '.date('Y-m-d H:i:s')], [
                    'order' => 'article_pubdate',
                    'sort' => 'desc',
                    'limit' => $pagination->getLimit(),
                    'offset' => $pagination->getOffset(),
                ]);

                return $this->render('AppBundle:Main:home-articles.html.twig', [
                    'articles' => $articles,
                    'pages' => $pagination,
                ]);

            // Display ten last posts
            } elseif ($behavior == 'posts') {
                $pm = $this->entityManager('Post');

                $posts = $pm->getAll(['post_status' => 1, 'post_date' => '<= '.date('Y-m-d H:i:s')], ['limit' => 10, 'order' => 'post_date', 'sort' => 'desc']);

                return $this->render('AppBundle:Main:home-posts.html.twig', ['posts' => $posts]);

            // Display ten last posts in a category
            } elseif (preg_match('/post_category:(\\d+)/', $behavior, $matches)) {
                $pm = $this->entityManager('Post');

                $posts = $pm->getAll(['category_id' => $matches[1], 'post_status' => 1, 'post_date' => '<= '.date('Y-m-d H:i:s')], ['limit' => 10, 'order' => 'post_date', 'sort' => 'desc']);

                return $this->render('AppBundle:Main:home-posts.html.twig', ['posts' => $posts]);

            // Display a rayon
            } elseif (preg_match('/rayon:(\\d+)/', $behavior, $matches)) {
                $rm = $this->entityManager('Rayon');

                $rayonId = $matches[1];
                $rayon = $rm->getById($rayonId);
                if (!$rayon) {
                    throw new NotFoundException("Rayon $rayonId not found.");
                }

                $controller = new RayonController();

                return $this->render('AppBundle:Main:home-rayon.html.twig', [
                    'rayon' => $rayon,
                    'articles' => $rayon->getArticles(),
                ]);

            // Display a static page from db
            } elseif (preg_match('/page:(\\d+)/', $behavior, $matches)) {
                $page_id = $matches[1];

                $pm = new \PageManager();
                $page = $pm->getById($page_id);

                if (!$page) {
                    throw new \Exception('Unable to find page '.$page_id);
                }

                $request->attributes->set('page', $page->get('url'));

                return $this->defaultAction($request);

            // Old controller
            } elseif ($behavior == 'old_controller') {
                return $this->defaultAction($request);
            }
        }

        // Default home page
        return $this->render('AppBundle:Main:home.html.twig');
    }

    public function contactAction(Request $request)
    {
        global $site, $config;

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject') ?
            $request->request->get('subject') : $request->query->get('subject');
        $message = $request->request->get('message') ?
            $request->request->get('message') : $request->query->get('message');
        $error = null;
        $success = false;

        $this->setPageTitle('Contact');

        // ReCaptcha
        $recaptcha = false;
        $recaptcha_config = $config->get('recaptcha');
        $recaptcha_sitekey = false;
        if ($recaptcha_config && isset($recaptcha_config['secret']) && isset($recaptcha_config['sitekey']) && !auth()) {
            $recaptcha = new Recaptcha($recaptcha_config['secret']);
            $recaptcha_sitekey = $recaptcha_config['sitekey'];
        }

        if ($request->getMethod() == 'POST') {
            // Check captcha
            if ($recaptcha) {
                $answer = $request->request->get('g-recaptcha-response');
                $check = $recaptcha->verify($answer, $request->getClientIp());

                if (!$check->isSuccess()) {
                    $error = "Vous n'avez pas correctement complété le test anti-spam.";
                }
            }

            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $error = 'Tous les champs sont obligatoires.';
            }

            if (!$error) {
                $mailer = new \Mailer();
                $mailer->send(
                    $site->get('site_contact'),
                    $subject,
                    nl2br($message),
                    [$site->get('site_contact') => $name],
                    ['reply-to' => $email]
                );
                $success = true;
            }
        }

        return $this->render('AppBundle:Main:contact.html.twig', [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'error' => $error,
            'success' => $success,
            'recaptcha_key' => $recaptcha_sitekey,
        ]);
    }

    public function adminAction(Request $request)
    {
        global $_V, $site, $config;

        $this->setPageTitle('Administration Biblys');
        $this->auth('admin');

        // Display alert if Biblys has been updated since last visit
        $update_alert = false;
        if ($_V->getOpt('last_version_known') != BIBLYS_VERSION) {
            $_V->setOpt('last_version_known', BIBLYS_VERSION);
            $update_alert = true;
        }

        $smtpAlert = false;
        if (!$config->get('smtp')) {
            $smtpAlert = true;
        }

        $shortcuts = json_decode($_V->getOpt('shortcuts'));
        if ($shortcuts === null) {
            $shortcuts = [];
        }

        return $this->render('AppBundle:Main:admin.html.twig', [
            'version' => BIBLYS_VERSION,
            'update_alert' => $update_alert,
            'smtpAlert' => $smtpAlert,
            'shortcuts' => $shortcuts,
            'articles' => Entry::findByCategory('articles'),
            'stock' => Entry::findByCategory('stock'),
            'sales' => Entry::findByCategory('sales'),
            'ebooks' => Entry::findByCategory('ebooks'),
            'content' => Entry::findByCategory('content'),
            'stats' => Entry::findByCategory('stats'),
            'site' => Entry::findByCategory('site'),
            'biblys' => Entry::findByCategory('biblys'),
            'custom' => Entry::findByCategory('custom'),
            'site_title' => $site->get('title'),
        ]);
    }

    public function adminShortcutsAction(Request $request)
    {
        global $_V, $site;

        $this->auth('admin');
        $this->setPageTitle('Gestion des raccourcis');

        // If XHR request, return the shortcuts as an JSON array
        if ($request->isXmlHttpRequest()) {
            $shortcuts = $_V->getOpt('shortcuts');

            // If user has no shortcuts yet, return an empty array
            if (!$shortcuts) {
                return new JsonResponse([]);
            }

            // else return a formatted array
            return new JsonResponse(json_decode($shortcuts));
        }

        if ($request->getMethod() == 'POST') {
            $shortcuts = $request->request->get('shortcuts');

            $_V->setOpt('shortcuts', $shortcuts);

            return new RedirectResponse($this->generateUrl('main_admin'));
        }

        // Default home page
        return $this->render('AppBundle:Main:adminShortcuts.html.twig', [
            'shortcuts' => $_V->getOpt('shortcuts'),
            'articles' => Entry::findByCategory('articles'),
            'stock' => Entry::findByCategory('stock'),
            'sales' => Entry::findByCategory('sales'),
            'ebooks' => Entry::findByCategory('ebooks'),
            'content' => Entry::findByCategory('content'),
            'stats' => Entry::findByCategory('stats'),
            'site' => Entry::findByCategory('site'),
            'biblys' => Entry::findByCategory('biblys'),
            'custom' => Entry::findByCategory('custom'),
            'site_title' => $site->get('title'),
        ]);
    }

    /**
     * Returns notifications for asked subscriptions.
     *
     * @param Request $subscriptions A list of subscriptions
     *
     * @return JsonResponse An array of notifications
     */
    public function adminNotificationsAction(Request $request)
    {
        $this->auth('admin');

        $subscriptions = explode(',', $request->query->get('subscriptions'));

        $notifications = [];

        // Orders to be shipped
        if (in_array('orders', $subscriptions)) {
            $om = $this->entityManager('Order');
            $notifications['orders'] = $om->count(['order_type' => 'web', 'order_payment_date' => 'NOT NULL', 'order_shipping_date' => 'NULL', 'order_cancel_date' => 'NULL']);
        }

        // Carts
        if (in_array('carts', $subscriptions)) {
            $cm = $this->entityManager('Cart');
            $notifications['carts'] = $cm->count(['cart_type' => 'web']);
        }

        // Search terms
        if (in_array('search-terms', $subscriptions)) {
            $am = $this->entityManager('Article');
            $notifications['search-terms'] = $am->countAllWithoutSearchTerms();
        }

        return new JsonResponse($notifications);
    }
}
