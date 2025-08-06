<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
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
use CartManager;
use Exception;
use Framework\Controller;
use GuzzleHttp\Exception\GuzzleException;
use OrderManager;
use PostManager;
use Propel\Runtime\Exception\PropelException;
use Rayon;
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
        $metaTagsService->setTitle($currentSite->getTitle());
        $metaTagsService->setUrl($urlGenerator->generate("main_home"));
        $previewImage = $currentSite->getOption("home_preview_image");
        if ($previewImage) {
            $metaTagsService->setImage($previewImage);
        }
        $previewText = $currentSite->getOption("home_preview_text");
        if ($previewText) {
            $metaTagsService->setDescription($previewText);
        }

        $homeOption = $currentSite->getOption("home");
        if ($homeOption) {
            // Custom Twig template
            if ($homeOption == 'custom') {
                return $templateService->renderResponse("AppBundle:Main:home.html.twig");

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

                return $templateService->renderResponse("AppBundle:Main:home-articles.html.twig", [
                    'articles' => $articles,
                    'pages' => $pagination,
                ]);

            // Display ten last posts
            } elseif ($homeOption == 'posts') {
                $pm = new PostManager();

                $homePostsLimit = $currentSite->getOption("home_posts_limit", 10);
                $posts = $pm->getAll(
                    ["post_status" => 1, "post_date" => "<= ".date("Y-m-d H:i:s")],
                    ["limit" => $homePostsLimit, "order" => "post_date", "sort" => "desc"]
                );
                return $templateService->renderResponse("AppBundle:Main:home-posts.html.twig", ['posts' => $posts]);

            // Display ten last posts in a category
            } elseif (preg_match('/post_category:(\\d+)/', $homeOption, $matches)) {
                $pm = new PostManager();

                $posts = $pm->getAll(['category_id' => $matches[1], 'post_status' => 1, 'post_date' => '<= '.date('Y-m-d H:i:s')], ['limit' => 10, 'order' => 'post_date', 'sort' => 'desc']);

                return $templateService->renderResponse("AppBundle:Main:home-posts.html.twig", ['posts' => $posts]);

            // Display a rayon
            } elseif (preg_match('/rayon:(\\d+)/', $homeOption, $matches)) {
                $rm = new RayonManager();

                $rayonId = $matches[1];

                /** @var Rayon $rayon */
                $rayon = $rm->getById($rayonId);
                if (!$rayon) {
                    throw new ResourceNotFoundException("Rayon $rayonId not found.");
                }

                return $templateService->renderResponse("AppBundle:Main:home-rayon.html.twig", [
                    'rayon' => $rayon,
                    'articles' => $rayon->getArticles(),
                ]);

            // Display a static page from db
            } elseif (preg_match('/page:([a-z-]+)/m', $homeOption, $matches)) {

                $staticPageSlug = $matches[1];
                $staticPageController = new StaticPageController();
                return $staticPageController->showAction(
                    $currentSite,
                    $currentUser,
                    $staticPageSlug
                );

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
        return $templateService->renderResponse("AppBundle:Main:home.html.twig");
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws TransportExceptionInterface
     */
    public function contactAction(
        Request $request,
        CurrentUser $currentUserService,
        TemplateService $templateService,
        Mailer $mailer,
        Config $config,
        CurrentSite $currentSite,
    ): Response
    {
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
        if ($recaptcha_config && isset($recaptcha_config['secret']) && isset($recaptcha_config['sitekey']) && !$currentUserService->isAuthenticated()) {
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

                $contactEmail = $currentSite->getSite()->getContact();
                $mailer->send(
                    $contactEmail,
                    $subject,
                    nl2br($message),
                    [$contactEmail => $name],
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
     * @throws PropelException
     * @throws Exception
     * @throws GuzzleException
     */
    public function adminAction(
        Config $config,
        UrlGenerator $urlGenerator,
        CloudService $cloud,
        CurrentUser $currentUser,
        CurrentSite $currentSite,
    ): Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $currentUser->authAdmin();

        if ($cloud->isConfigured()
            && $cloud->getSubscription() !== null
            && !$cloud->getSubscription()->isActive()) {
            return $this->render("AppBundle:Main:adminCloudSubscriptionExpired.html.twig", isPrivate: true);
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

        $cloudNews = [];
        if ($cloud->isConfigured()) {
            $cloudNews = $cloud->getNews();
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
            'catalog' => Entry::generateUrlsForEntries(Entry::findByCategory('catalog'), $urlGenerator),
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
            "cloud_news" => $cloudNews,
        ], isPrivate: true);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function adminShortcutsAction(
        Request $request,
        UrlGenerator $urlGenerator,
        CurrentUser $currentUserService,
    ): RedirectResponse|JsonResponse|Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $currentUserService->authAdmin();

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
        ], isPrivate: true);
    }

    /**
     * Returns notifications for asked subscriptions.
     *
     * @throws Exception
     */
    public function adminNotificationsAction(
        Request $request,
        CurrentUser $currentUser,
    ): JsonResponse
    {
        $currentUser->authAdmin();

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
    public function adminCloud(
        Request      $request,
        Config       $config,
        CloudService $cloud,
        CurrentUser  $currentUser,
    ): Response
    {
        $currentUser->authAdmin();

        $cloudConfig = $config->get("cloud");
        if (!$cloudConfig) {
            throw new ResourceNotFoundException();
        }

        return $this->render("AppBundle:Main:adminCloud.html.twig", [
            "domains" => $cloudConfig["domains"] ?? [],
            "subscription" => $cloud->getSubscription(),
        ], isPrivate: true);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function adminCloudPortal(
        Request      $request,
        CloudService $cloud,
        CurrentUser  $currentUser,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $returnUrl = $request->query->get("return_url");
        $portalUrl = $cloud->getPortalUrl($returnUrl);

        return new RedirectResponse($portalUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function hotNewsMarkAsRead(
        UrlGenerator $urlGenerator,
        CurrentUser $currentUser
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $currentUser->setOption("hot_news_read", 1);

        $dashboardUrl = $urlGenerator->generate("main_admin");
        return new RedirectResponse($dashboardUrl);
    }

}
