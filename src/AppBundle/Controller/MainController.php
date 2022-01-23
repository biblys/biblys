<?php

namespace AppBundle\Controller;

use Biblys\Admin\Entry;
use Biblys\Service\Config;
use Biblys\Service\Mailer;
use Biblys\Service\Updater\Updater;
use Biblys\Service\Updater\UpdaterException;
use DateTime;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Illuminate\Contracts\Routing\UrlGenerator;
use InvalidArgumentException;
use Propel\Runtime\Exception\PropelException;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class ContactPageException extends Exception {}

class MainController extends Controller
{
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
                $pagination = new \Biblys\Service\Pagination($page, $totalCount);

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
                    throw new Exception('Unable to find page '.$page_id);
                }

                $request->attributes->set('page', $page->get('url'));

                $legacyController = new LegacyController();
                return $legacyController->defaultAction($request);

            // Old controller
            } elseif ($behavior == 'old_controller') {
                $legacyController = new LegacyController();
                return $legacyController->defaultAction($request);
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
            try {
                if ($recaptcha) {
                    $answer = $request->request->get('g-recaptcha-response');
                    $check = $recaptcha->verify($answer, $request->getClientIp());

                    if (!$check->isSuccess()) {
                        throw new ContactPageException(
                            "Vous n'avez pas correctement complété le test anti-spam."
                        );
                    }
                }

                if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                    throw new ContactPageException(
                        "Tous les champs sont obligatoires."
                    );
                }

                $mailer = new Mailer();
                $mailer->send(
                    $site->get('site_contact'),
                    $subject,
                    nl2br($message),
                    [$site->get('site_contact') => $name],
                    ['reply-to' => $email]
                );
                $success = true;
            } catch(ContactPageException | InvalidArgumentException $exception) {
                $error = $exception->getMessage();
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

    /**
     * @throws AuthException
     * @throws UpdaterException
     * @throws PropelException
     * @throws Exception
     */
    public function adminAction(Request $request, Config $config, Updater $updater): Response
    {
        global $site, $container;

        $currentUser = self::authAdmin($request);
        $request->attributes->set("page_title", "Administration Biblys");

        // Display alert if Biblys has been updated since last visit
        $update_alert = false;
        if ($currentUser->getOption("last_version_known") != BIBLYS_VERSION) {
            $currentUser->setOption("last_version_known", BIBLYS_VERSION);
            $update_alert = true;
        }

        $smtpAlert = false;
        if (!$config->get('smtp')) {
            $smtpAlert = true;
        }

        $shortcuts = json_decode($currentUser->getOption("shortcuts"));
        if ($shortcuts === null) {
            $shortcuts = [];
        }

        $cloudExpired = false;
        $renewLink = "https://www.biblys.fr/contact/";
        $cloudConfig = $config->get("cloud");
        if ($cloudConfig && isset($cloudConfig['expires'])) {
            $cloudExpirationDate = new DateTime($cloudConfig['expires']);
            $now = new DateTime();
            if ($now > $cloudExpirationDate) {
                $cloudExpired = true;
            }
            if (isset($cloudConfig["renew_link"])) {
                $renewLink = $cloudConfig["renew_link"];
            }
        }

        $urlGenerator = $container->get("url_generator");
        $biblysEntries = Entry::generateUrlsForEntries(Entry::findByCategory('biblys'), $urlGenerator);
        $biblysEntriesWithUpdates = array_map(function($entry) use($updater, $site) {
            if ($entry->getName() === "Mise à jour") {
                $diff = time() - $site->getOpt('updates_last_checked');
                if ($diff > 60 * 60 * 24) {
                    $updater->downloadUpdates();
                    $site->setOpt('updates_last_checked', time());
                }
                if ($updater->isUpdateAvailable()) {
                    $entry->setTaskCount(1);
                }
            }
            return $entry;
        }, $biblysEntries);

        return $this->render('AppBundle:Main:admin.html.twig', [
            'version' => BIBLYS_VERSION,
            'update_alert' => $update_alert,
            'smtpAlert' => $smtpAlert,
            'shortcuts' => $shortcuts,
            'articles' => Entry::generateUrlsForEntries(Entry::findByCategory('articles'), $urlGenerator),
            'stock' => Entry::generateUrlsForEntries(Entry::findByCategory('stock'), $urlGenerator),
            'sales' => Entry::generateUrlsForEntries(Entry::findByCategory('sales'), $urlGenerator),
            'ebooks' => Entry::generateUrlsForEntries(Entry::findByCategory('ebooks'), $urlGenerator),
            'content' => Entry::generateUrlsForEntries(Entry::findByCategory('content'), $urlGenerator),
            'stats' => Entry::generateUrlsForEntries(Entry::findByCategory('stats'), $urlGenerator),
            'site' => Entry::generateUrlsForEntries(Entry::findByCategory('site'), $urlGenerator),
            'biblys' => $biblysEntriesWithUpdates,
            'custom' => Entry::generateUrlsForEntries(Entry::findByCategory('custom'), $urlGenerator),
            'site_title' => $site->get('title'),
            'cloud_expired' => $cloudExpired,
            'cloud_expiration_date' => $cloudConfig["expires"],
            'cloud_renew_link' => $renewLink,
        ]);
    }

    public function adminShortcutsAction(Request $request)
    {
        global $_V, $site;

        $this->auth('admin');

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
        $request->attributes->set("page_title", "Gestion des raccourcis");
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
