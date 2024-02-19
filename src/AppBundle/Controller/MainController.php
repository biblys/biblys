<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Admin\Entry;
use Biblys\Exception\ContactPageException;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Cloud\CloudService;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\Pagination;
use Biblys\Service\TemplateService;
use Biblys\Service\Updater\UpdaterException;
use CartManager;
use Exception;
use Framework\Controller;
use GuzzleHttp\Exception\GuzzleException;
use OrderManager;
use PostManager;
use Propel\Runtime\Exception\PropelException;
use RayonManager;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController extends Controller
{
    /**
     * @throws RuntimeError
     * @throws LoaderError
     * @throws SyntaxError
     * @throws PropelException
     * @throws Exception
     */
    public function homeAction(
        Request $request,
        Session $session,
        Mailer $mailer,
        Config $config,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator,
        TemplateService $templateService,
        MetaTagsService $metaTagsService,
    ): Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $opengraph = ['title' => $globalSite->get('title')];
        $twitterCards = ['title' => $globalSite->get('title')];

        $preview_image = $globalSite->getOpt('home_preview_image');
        if ($preview_image) {
            $opengraph['image'] = $preview_image;
            $twitterCards['image'] = $preview_image;
            $twitterCards['image:alt'] = $globalSite->get('title');
        }

        $preview_text = $globalSite->getOpt('home_preview_text');
        if ($preview_text) {
            $opengraph['description'] = $preview_text;
            $twitterCards['description'] = $preview_text;
        }

        $this->setOpengraphTags($opengraph);
        $this->setTwitterCardsTags($twitterCards);

        $homeOption = $currentSite->getOption("home");
        if ($homeOption) {
            // Custom Twig template
            if ($homeOption == 'custom') {
                return $this->render('AppBundle:Main:home.html.twig');

            // Display articles
            } elseif ($homeOption == 'articles') {
                $am = new ArticleManager();

                // Pagination
                $staticHomePage = (int) $request->query->get('p', 0);
                $totalCount = $am->countAll();
                $pagination = new Pagination($staticHomePage, $totalCount);

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
            } elseif ($homeOption == 'posts') {
                $pm = new PostManager();

                $posts = $pm->getAll(['post_status' => 1, 'post_date' => '<= '.date('Y-m-d H:i:s')], ['limit' => 10, 'order' => 'post_date', 'sort' => 'desc']);

                return $this->render('AppBundle:Main:home-posts.html.twig', ['posts' => $posts]);

            // Display ten last posts in a category
            } elseif (preg_match('/post_category:(\\d+)/', $homeOption, $matches)) {
                $pm = new PostManager();

                $posts = $pm->getAll(['category_id' => $matches[1], 'post_status' => 1, 'post_date' => '<= '.date('Y-m-d H:i:s')], ['limit' => 10, 'order' => 'post_date', 'sort' => 'desc']);

                return $this->render('AppBundle:Main:home-posts.html.twig', ['posts' => $posts]);

            // Display a rayon
            } elseif (preg_match('/rayon:(\\d+)/', $homeOption, $matches)) {
                $rm = new RayonManager();

                $rayonId = $matches[1];
                $rayon = $rm->getById($rayonId);
                if (!$rayon) {
                    throw new ResourceNotFoundException("Rayon $rayonId not found.");
                }

                return $this->render('AppBundle:Main:home-rayon.html.twig', [
                    'rayon' => $rayon,
                    'articles' => $rayon->getArticles(),
                ]);

            // Display a static page from db
            } elseif (preg_match('/page:([a-z-]+)/m', $homeOption, $matches)) {

                $staticPageSlug = $matches[1];
                $staticPageController = new StaticPageController();
                return $staticPageController->showAction($request, $currentSite, $staticPageSlug);

            // Old controller
            } elseif ($homeOption == 'old_controller') {
                $legacyController = new LegacyController();
                return $legacyController->defaultAction(
                    request: $request,
                    session: $session,
                    mailer: $mailer,
                    config: $config,
                    currentSite: $currentSite,
                    currentUser: $currentUser,
                    urlGenerator: $urlGenerator,
                    templateService: $templateService,
                    metaTagsService: $metaTagsService,
                );
            }
        }

        // Default home page
        return $this->render('AppBundle:Main:home.html.twig');
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws TransportExceptionInterface
     * @throws PropelException
     */
    public function contactAction(
        Request $request,
        CurrentUser $currentUserService,
        TemplateService $templateService,
        Mailer $mailer): Response
    {
        global $config;
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject') ?
            $request->request->get('subject') : $request->query->get('subject');
        $message = $request->request->get('message') ?
            $request->request->get('message') : $request->query->get('message');
        $honeyPot = $request->request->get('phone') ?
            $request->request->get('phone') : $request->query->get('phone');
        $error = null;
        $success = false;

        $request->attributes->set("page_title", "Contact");

        // ReCaptcha
        $recaptcha = false;
        $recaptcha_config = $config->get('recaptcha');
        $recaptcha_sitekey = false;
        if ($recaptcha_config && isset($recaptcha_config['secret']) && isset($recaptcha_config['sitekey']) && !$currentUserService->isAuthentified()) {
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

                if (strlen($subject) < 10) {
                    throw new ContactPageException(
                        "Le sujet doit être long d'au moins 6 caractères."
                    );
                }

                if (strlen($message) < 10) {
                    throw new ContactPageException(
                        "Le message doit être long d'au moins 10 caractères."
                    );
                }

                if (!empty($honeyPot)) {
                    throw new ContactPageException("Le message n'a pas pu être envoyé.");
                }

                $mailer->send(
                    $globalSite->get('site_contact'),
                    $subject,
                    nl2br($message),
                    [$globalSite->get('site_contact') => $name],
                    ['reply-to' => $email]
                );
                $success = true;
            } catch(ContactPageException | InvalidEmailAddressException $exception) {
                $error = $exception->getMessage();
            }
        }

        return $templateService->renderResponse('AppBundle:Main:contact.html.twig', [
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
     * @throws UpdaterException
     * @throws PropelException
     * @throws Exception
     * @throws GuzzleException
     */
    public function adminAction(
        Request $request,
        Config $config,
        UrlGenerator $urlGenerator,
        CloudService $cloud,
        CurrentUser $currentUser,
        CurrentSite $currentSite,
    ): Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        self::authAdmin($request);
        $request->attributes->set("page_title", "Administration Biblys");

        if ($cloud->isConfigured() && !$cloud->getSubscription()->isActive()) {
            return $this->render("AppBundle:Main:adminCloudSubscriptionExpired.html.twig");
        }

        // Display alert if Biblys has been updated since last visit
        $updatedAlert = false;
        if ($currentUser->getOption("last_version_known") != BIBLYS_VERSION) {
            $currentUser->setOption("last_version_known", BIBLYS_VERSION);
            $updatedAlert = true;
        }

        $smtpAlert = false;
        if (!$config->get('smtp')) {
            $smtpAlert = true;
        }

        $shortcuts = json_decode($currentUser->getOption("shortcuts") ?: "");
        if ($shortcuts === null) {
            $shortcuts = [];
        }

        $hotNewsBanner = $config->get("cloud.hot_news");
        if ($currentUser->getOption("hot_news_read")) {
            $hotNewsBanner = null;
        }

        $ebooksSection = null;
        if ($currentSite->getOption("downloadable_publishers") !== null) {
            $ebooksSection = Entry::generateUrlsForEntries(Entry::findByCategory('ebooks'), $urlGenerator);
        }

        return $this->render('AppBundle:Main:admin.html.twig', [
            'version' => BIBLYS_VERSION,
            'updated_alert' => $updatedAlert,
            'smtp_alert' => $smtpAlert,
            'shortcuts' => $shortcuts,
            'articles' => Entry::generateUrlsForEntries(Entry::findByCategory('articles'), $urlGenerator),
            'stock' => Entry::generateUrlsForEntries(Entry::findByCategory('stock'), $urlGenerator),
            'sales' => Entry::generateUrlsForEntries(Entry::findByCategory('sales'), $urlGenerator),
            'ebooks' => $ebooksSection,
            'content' => Entry::generateUrlsForEntries(Entry::findByCategory('content'), $urlGenerator),
            'stats' => Entry::generateUrlsForEntries(Entry::findByCategory('stats'), $urlGenerator),
            'site' => Entry::generateUrlsForEntries(Entry::findByCategory('site'), $urlGenerator),
            'biblys' => Entry::generateUrlsForEntries(Entry::findByCategory('biblys'), $urlGenerator),
            'custom' => Entry::generateUrlsForEntries(Entry::findByCategory('custom'), $urlGenerator),
            'site_title' => $globalSite->get('title'),
            "hot_news" => $hotNewsBanner,
        ]);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws UpdaterException
     */
    public function adminShortcutsAction(
        Request $request,
        UrlGenerator $urlGenerator,
        CurrentUser $currentUserService,
    ): RedirectResponse|JsonResponse|Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        self::authAdmin($request);

        // If XHR request, return the shortcuts as an JSON array
        if ($request->isXmlHttpRequest()) {
            $shortcuts = $currentUserService->getOption("shortcuts");

            // If user has no shortcuts yet, return an empty array
            if (!$shortcuts) {
                return new JsonResponse([]);
            }

            // else return a formatted array
            return new JsonResponse(json_decode($shortcuts));
        }

        if ($request->getMethod() == 'POST') {
            $shortcuts = $request->request->get('shortcuts');

            $currentUserService->setOption("shortcuts", $shortcuts);

            return new RedirectResponse($urlGenerator->generate('main_admin'));
        }

        // Default home page
        $request->attributes->set("page_title", "Gestion des raccourcis");
        return $this->render('AppBundle:Main:adminShortcuts.html.twig', [
            'shortcuts' => $currentUserService->getOption('shortcuts'),
            'articles' => Entry::generateUrlsForEntries(
                Entry::findByCategory('articles'),
                $urlGenerator
            ),
            'stock' => Entry::generateUrlsForEntries(Entry::findByCategory('stock'), $urlGenerator),
            'sales' => Entry::generateUrlsForEntries(Entry::findByCategory('sales'), $urlGenerator),
            'ebooks' => Entry::generateUrlsForEntries(
                Entry::findByCategory('ebooks'),
                $urlGenerator
            ),
            'content' => Entry::generateUrlsForEntries(
                Entry::findByCategory('content'),
                $urlGenerator
            ),
            'stats' => Entry::generateUrlsForEntries(Entry::findByCategory('stats'), $urlGenerator),
            'site' => Entry::generateUrlsForEntries(Entry::findByCategory('site'), $urlGenerator),
            'biblys' => Entry::generateUrlsForEntries(
                Entry::findByCategory('biblys'),
                $urlGenerator
            ),
            'custom' => Entry::generateUrlsForEntries(
                Entry::findByCategory('custom'),
                $urlGenerator
            ),
            'site_title' => $globalSite->get('title'),
        ]);
    }

    /**
     * Returns notifications for asked subscriptions.
     *
     * @throws PropelException
     */
    public function adminNotificationsAction(Request $request): JsonResponse
    {
        self::authAdmin($request);

        $subscriptions = explode(',', $request->query->get('subscriptions'));

        $notifications = [];

        // Orders to be shipped
        if (in_array('orders', $subscriptions)) {
            $om = new OrderManager();
            $notifications['orders'] = $om->count(['order_type' => 'web', 'order_payment_date' => 'NOT NULL', 'order_shipping_date' => 'NULL', 'order_cancel_date' => 'NULL']);
        }

        // Carts
        if (in_array('carts', $subscriptions)) {
            $cm = new CartManager();
            $notifications['carts'] = $cm->count(['cart_type' => 'web']);
        }

        // Search terms
        if (in_array('search-terms', $subscriptions)) {
            $am = new ArticleManager();
            $notifications['search-terms'] = $am->countAllWithoutSearchTerms();
        }

        return new JsonResponse($notifications);
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws GuzzleException
     */
    public function adminCloud(Request $request, Config $config, CloudService $cloud): Response
    {
        self::authAdmin($request);
        $cloudConfig = $config->get("cloud");
        if (!$cloudConfig) {
            throw new ResourceNotFoundException();
        }

        $request->attributes->set("page_title", "Abonnement Biblys Cloud");

        return $this->render("AppBundle:Main:adminCloud.html.twig", [
            "domains" => $cloudConfig["domains"] ?? [],
            "subscription" => $cloud->getSubscription(),
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws PropelException
     */
    public function adminCloudPortal(Request $request, CloudService $cloud): RedirectResponse
    {
        self::authAdmin($request);

        $returnUrl = $request->query->get("return_url");
        $portalUrl = $cloud->getPortalUrl($returnUrl);

        return new RedirectResponse($portalUrl);
    }

    /**
     * @throws PropelException
     */
    public function hotNewsMarkAsRead(
        Request $request,
        UrlGenerator $urlGenerator,
        CurrentUser $currentUser
    ): RedirectResponse
    {
        self::authAdmin($request);

        $currentUser->setOption("hot_news_read", 1);

        $dashboardUrl = $urlGenerator->generate("main_admin");
        return new RedirectResponse($dashboardUrl);
    }

}
