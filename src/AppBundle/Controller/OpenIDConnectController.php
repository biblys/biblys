<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
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
use Model\Map\UserTableMap;
use Model\Option;
use Model\OptionQuery;
use Model\OrderQuery;
use Model\PostQuery;
use Model\RightQuery;
use Model\StockItemListQuery;
use Model\StockQuery;
use Model\SubscriptionQuery;
use Model\User;
use Model\UserQuery;
use Model\VoteQuery;
use Model\WishlistQuery;
use Model\WishQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

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
        Config                       $config,
        OpenIDConnectProviderService $openIDConnectProviderService,
        QueryParamsService           $queryParams,
        TemplateService              $templateService,
        TokenService                 $tokenService,
        UrlGenerator                 $urlGenerator,
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
            [$externalId, $email] = OpenIDConnectController::_getClaimsFromOidcTokens($oidcTokens);
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

            $user = $authenticationMethod->getUser();
            $user->setEmail($email);
            $user->save();

            $loginToken = $tokenService->createLoginToken($email, $returnUrl);
            $loginUrl = $urlGenerator->generate("user_login_with_token", [
                "token" => $loginToken
            ]);

            $response = new RedirectResponse($loginUrl);
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
    private static function _importUserFromAxys(
        CurrentSite       $currentSite,
        string            $email,
        string            $externalId,
        TokenSetInterface $oidcTokens
    ): AuthenticationMethod
    {
        $con = Propel::getWriteConnection(UserTableMap::DATABASE_NAME);

        $userWithEmail = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByEmail($email);
        if ($userWithEmail) {
            throw new AccessDeniedHttpException(
                "Il existe déjà un compte {$currentSite->getTitle()} pour l'adresse $email"
            );
        }

        try {
            $con->beginTransaction();

            $user = new User();
            $user->setSite($currentSite->getSite());
            $user->setEmail($email);
            $user->save($con);

            $authenticationMethod = new AuthenticationMethod();
            $authenticationMethod->setSite($currentSite->getSite());
            $authenticationMethod->setUser($user);
            $authenticationMethod->setIdentityProvider("axys");
            $authenticationMethod->setExternalId($externalId);
            $authenticationMethod->setAccessToken($oidcTokens->getAccessToken());
            $authenticationMethod->setIdToken($oidcTokens->getIdToken());
            $authenticationMethod->save($con);

            $carts = CartQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($carts as $cart) {
                $cart->setUser($user);
                $cart->setAxysAccountId(null);
                $cart->save($con);
            }

            $sellerCarts = CartQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterBySellerId($externalId)
                ->find();
            foreach ($sellerCarts as $cart) {
                $cart->setSellerUser($user);
                $cart->setAxysAccountId(null);
                $cart->save($con);
            }

            $customers = CustomerQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($customers as $customer) {
                $customer->setUser($user);
                $customer->setAxysAccountId(null);
                $customer->save($con);
            }

            $lists = StockItemListQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($lists as $list) {
                $list->setUser($user);
                $list->setAxysAccountId(null);
                $list->save($con);
            }

            $options = OptionQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($options as $option) {
                $option->setUser($user);
                $option->setAxysAccountId(null);
                $option->save($con);
            }

            $orders = OrderQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($orders as $order) {
                $order->setUser($user);
                $order->setAxysAccountId(null);
                $order->save($con);
            }

            $posts = PostQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($posts as $post) {
                $post->setUser($user);
                $post->setAxysAccountId(null);
                $post->save($con);
            }

            $adminRights = RightQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($adminRights as $adminRight) {
                $adminRight->setUser($user);
                $adminRight->setAxysAccountId(null);
                $adminRight->setIsAdmin(true);
                $adminRight->save($con);
            }

            $libraryItems = StockQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($libraryItems as $libraryItem) {
                $libraryItem->setUser($user);
                $libraryItem->setAxysAccountId(null);
                $libraryItem->save($con);
            }

            $subscriptions = SubscriptionQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByAxysAccountId($externalId)
                ->find();
            foreach ($subscriptions as $subscription) {
                $subscription->setUser($user);
                $subscription->setAxysAccountId(null);
                $subscription->save($con);
            }

            if ($currentSite->getOption("alerts")) {
                $alerts = AlertQuery::create()
                    ->filterByAxysAccountId($externalId)
                    ->find();
                foreach ($alerts as $alert) {
                    $alert->setSite($currentSite->getSite());
                    $alert->setUser($user);
                    $alert->setAxysAccountId(null);
                    $alert->save($con);
                }
            }

            if ($currentSite->getOption("publisher_rights_managment")) {
                $publisherRights = RightQuery::create()
                    ->filterBySiteId(null, Criteria::ISNULL)
                    ->filterByPublisherId(null, Criteria::ISNOTNULL)
                    ->filterByAxysAccountId($externalId)
                    ->find();
                foreach ($publisherRights as $adminRight) {
                    $adminRight->setSite($currentSite->getSite());
                    $adminRight->setUser($user);
                    $adminRight->setAxysAccountId(null);
                    $adminRight->save($con);
                }
            }

            if ($currentSite->getOption("voting")) {
                $votes = VoteQuery::create()
                    ->filterByAxysAccountId($externalId)
                    ->find();
                foreach ($votes as $vote) {
                    $vote->setUser($user);
                    $vote->setSite($currentSite->getSite());
                    $vote->setAxysAccountId(null);
                    $vote->save($con);
                }
            }

            if ($currentSite->getOption("wishlist")) {
                $wishlists = WishlistQuery::create()
                    ->filterByAxysAccountId($externalId)
                    ->find();
                foreach ($wishlists as $wishlist) {
                    $wishlist->setUser($user);
                    $wishlist->setSite($currentSite->getSite());
                    $wishlist->setAxysAccountId(null);
                    $wishlist->save($con);

                    $wishes = WishQuery::create()
                        ->filterByWishlistId($wishlist->getId())
                        ->find();
                    foreach ($wishes as $wish) {
                        $wish->setUser($user);
                        $wish->setSiteId($currentSite->getSite()->getId());
                        $wish->setAxysAccountId(null);
                        $wish->save($con);
                    }
                }
            }

            $importDateOption = new Option();
            $importDateOption->setSite($currentSite->getSite());
            $importDateOption->setUser($user);
            $importDateOption->setKey("imported_from_axys");
            $importDateOption->setValue(date("Y-m-d"));
            $importDateOption->save($con);

            $con->commit();

        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $authenticationMethod;
    }
}
