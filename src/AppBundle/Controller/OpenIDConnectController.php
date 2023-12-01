<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\OpenIDConnectProviderService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Service\TokenService;
use DateTime;
use Exception;
use Facile\OpenIDClient\Token\TokenSetInterface;
use Facile\OpenIDClient\Exception\OAuth2Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Framework\Controller;
use Http\Discovery\Psr17Factory;
use JsonException;
use Model\AlertQuery;
use Model\AuthenticationMethod;
use Model\AuthenticationMethodQuery;
use Model\CartQuery;
use Model\CustomerQuery;
use Model\OptionQuery;
use Model\OrderQuery;
use Model\PostQuery;
use Model\RightQuery;
use Model\Session;
use Model\StockItemListQuery;
use Model\StockQuery;
use Model\SubscriptionQuery;
use Model\User;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OpenIDConnectController extends Controller
{
    /**
     * @throws JsonException
     */
    public function axys(
        QueryParamsService           $queryParams,
        TokenService                 $tokenService,
        OpenIDConnectProviderService $openIDConnectProviderService,
    ): RedirectResponse
    {
        $queryParams->parse(["return_url" => ["type" => "string", "default" => ""]]);
        $returnUrl = $queryParams->get("return_url");
        $authorizationUri = $openIDConnectProviderService->getAuthorizationUri($tokenService, $returnUrl);
        $response = new RedirectResponse($authorizationUri);
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function callback(
        Request                      $request,
        CurrentSite                  $currentSite,
        CurrentUser                  $currentUser,
        Config                       $config,
        OpenIDConnectProviderService $openIDConnectProviderService,
        QueryParamsService           $queryParams,
        TemplateService              $templateService,
    ): Response|RedirectResponse
    {
        $error = $request->query->get("error");
        if ($error === "access_denied") {
            return $templateService->renderResponse("AppBundle:OpenIDConnect:callback.html.twig", [
                "siteTitle" => $currentSite->getTitle(),
            ]);
        }

        $queryParams->parse([
            "code" => ["type" => "string"],
            "state" => ["type" => "string"],
            "error" => ["type" => "string", "default" => ""],
        ]);

        try {
            $oidcTokens = OpenIDConnectController::_getOidcTokensFromIdentityProvider($request, $openIDConnectProviderService);
            [$externalId, $email, $sessionExpiresAt] = OpenIDConnectController::_getClaimsFromOidcTokens($oidcTokens);
            $returnUrl = OpenIDConnectController::_getReturnUrlFromState($request, $config);

            $authenticationMethod = AuthenticationMethodQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByIdentityProvider("axys")
                ->findOneByExternalId($externalId);
            if ($authenticationMethod === null) {
                $authenticationMethod = self::_importUserFromAxys(
                    $currentSite,
                    $email,
                    $externalId,
                    $oidcTokens
                );
            }

            // Save user last login date
            $user = $authenticationMethod->getUser();
            $user->setLastLoggedAt(new DateTime());
            $user->save();

            $sessionCookie = OpenIDConnectController::_createSession(
                $currentSite,
                $sessionExpiresAt,
                $user
            );

            $currentUser->setUser($user);
            $currentUser->transfertVisitorCartToUser(visitorToken: $request->cookies->get("visitor_uid"));

            $response = new RedirectResponse($returnUrl);
            $response->headers->setCookie($sessionCookie);
            $response->headers->set("X-Robots-Tag", "noindex, nofollow");

            return $response;
        } catch (OAuth2Exception $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
    /**
     * @param Request $request
     * @param OpenIDConnectProviderService $openIDConnectProviderService
     * @return TokenSetInterface
     */
    private static function _getOidcTokensFromIdentityProvider(Request $request, OpenIDConnectProviderService $openIDConnectProviderService): TokenSetInterface
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $psrRequest = $psrHttpFactory->createRequest($request);

        return $openIDConnectProviderService->getTokenSet($psrRequest);
    }

    /**
     * @throws Exception
     */
    private static function _getClaimsFromOidcTokens(TokenSetInterface $oidcTokens): array
    {
        $claims = $oidcTokens->claims();
        $externalId = $claims["sub"];
        $sessionExpiresAt = new DateTime("@" . $claims["exp"]);
        $email = $claims["email"];

        return [$externalId, $email, $sessionExpiresAt];
    }

    private static function _getReturnUrlFromState(Request $request, Config $config): string
    {
        $returnUrl = "/";
        $stateToken = $request->query->get("state");
        $decodedState = JWT::decode($stateToken, new Key($config->get("axys.client_secret"), "HS256"));
        if (isset($decodedState->return_url) && $decodedState->return_url !== "") {
            $returnUrl = $decodedState->return_url;
        }

        return $returnUrl;
    }

    /**
     * @throws PropelException
     */
    private static function _createSession(
        CurrentSite $currentSite,
        DateTime $sessionExpiresAt,
        User $user,
    ): Cookie
    {
        $session = new Session();
        $session->setUser($user);
        $session->setSite($currentSite->getSite());
        $session->setToken(Session::generateToken());
        $session->setExpiresAt($sessionExpiresAt);
        $session->save();

        return Cookie::create("user_uid")
            ->withValue($session->getToken())
            ->withExpires($sessionExpiresAt);
    }

    /**
     * @throws PropelException
     */
    private static function _importUserFromAxys(
        CurrentSite       $currentSite,
        string            $email,
        string            $externalId,
        TokenSetInterface $oidcTokens
    ): AuthenticationMethod
    {
        $user = new User();
        $user->setSite($currentSite->getSite());
        $user->setEmail($email);
        $user->save();

        $authenticationMethod = new AuthenticationMethod();
        $authenticationMethod->setSite($currentSite->getSite());
        $authenticationMethod->setUser($user);
        $authenticationMethod->setIdentityProvider("axys");
        $authenticationMethod->setExternalId($externalId);
        $authenticationMethod->setAccessToken($oidcTokens->getAccessToken());
        $authenticationMethod->setIdToken($oidcTokens->getIdToken());
        $authenticationMethod->save();

        $carts = CartQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($carts as $cart) {
            $cart->setUser($user);
            $cart->setAxysAccountId(null);
            $cart->save();
        }

        $customers = CustomerQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($customers as $customer) {
            $customer->setUser($user);
            $customer->setAxysAccountId(null);
            $customer->save();
        }

        $lists = StockItemListQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($lists as $list) {
            $list->setUser($user);
            $list->setAxysAccountId(null);
            $list->save();
        }

        $options = OptionQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($options as $option) {
            $option->setUser($user);
            $option->setAxysAccountId(null);
            $option->save();
        }

        $orders = OrderQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($orders as $order) {
            $order->setUser($user);
            $order->setAxysAccountId(null);
            $order->save();
        }

        $posts = PostQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($posts as $post) {
            $post->setUser($user);
            $post->setAxysAccountId(null);
            $post->save();
        }

        $adminRights = RightQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($adminRights as $adminRight) {
            $adminRight->setUser($user);
            $adminRight->setAxysAccountId(null);
            $adminRight->setIsAdmin(true);
            $adminRight->save();
        }

        $libraryItems = StockQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($libraryItems as $libraryItem) {
            $libraryItem->setUser($user);
            $libraryItem->setAxysAccountId(null);
            $libraryItem->save();
        }

        $subscriptions = SubscriptionQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByAxysAccountId($externalId)
            ->find();
        foreach ($subscriptions as $subscription) {
            $subscription->setUser($user);
            $subscription->setAxysAccountId(null);
            $subscription->save();
        }

        if ($currentSite->getOption("alerts")) {
            $alerts = AlertQuery::create()
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($alerts as $alert) {
                $alert->setSite($currentSite->getSite());
                $alert->setUser($user);
                $alert->setAxysAccountId(null);
                $alert->save();
            }
        }

        return $authenticationMethod;
    }
}
