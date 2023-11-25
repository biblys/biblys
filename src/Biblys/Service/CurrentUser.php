<?php

namespace Biblys\Service;

use DateTime;
use Exception;
use Model\Article;
use Model\Cart;
use Model\CartQuery;
use Model\Option;
use Model\OptionQuery;
use Model\Publisher;
use Model\Right;
use Model\RightQuery;
use Model\SessionQuery;
use Model\Site;
use Model\Stock;
use Model\StockQuery;
use Model\User;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CurrentUser
{
    private ?User $user;
    private ?string $token;
    private ?CurrentSite $currentSite = null;
    private bool $cartWasFetched = false;
    private ?Cart $fetchedCart = null;

    public function __construct(?User $user, ?string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @param Request $request
     * @return CurrentUser
     * @throws PropelException
     */
    private static function buildFromRequest(Request $request): CurrentUser
    {
        $cookieToken = $request->cookies->get("user_uid");
        $headerToken = $request->headers->get("AuthToken");

        $token = $cookieToken ?: $headerToken;

        if ($token === null) {
            $visitorUid = $request->cookies->get("visitor_uid");
            return new CurrentUser(null, $visitorUid);
        }

        $session = SessionQuery::create()->filterByToken($token)->findOne();
        if (!$session) {
            return new CurrentUser(null, $token);
        }

        if (($session->getExpiresAt() < new DateTime())) {
            return new CurrentUser(null, $token);
        }

        $user = $session->getUser();
        if (!$user) {
            return new CurrentUser(null, $token);
        }

        return new CurrentUser($user, $token);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public static function buildFromRequestAndConfig(Request $request, Config $config): CurrentUser
    {
        $currentUser = self::buildFromRequest($request);

        $currentSite = CurrentSite::buildFromConfig($config);
        $currentUser->injectCurrentSite($currentSite);

        return $currentUser;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->token = null;
    }

    public function isAuthentified(): bool
    {
        if ($this->user) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function isAdmin(): bool
    {
        if (!$this->isAuthentified()) {
            return false;
        }

        $site = $this->getCurrentSite()->getSite();
        return $this->isAdminForSite($site);
    }

    /**
     * @throws PropelException
     */
    public function isAdminForSite(Site $site): bool
    {
        $adminRight = RightQuery::create()
            ->filterByUser($this->user)
            ->filterBySite($site)
            ->findOne();

        if($adminRight) {
            return true;
        }

        return false;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        if ($this->user === null) {
            throw new UnauthorizedHttpException("", "Identification requise.");
        }

        return $this->user;
    }

    public function getAxysAccount(): User
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.75.0",
            "CurrentUser->getAxysAccount() is deprecated. Use CurrentUser->getUser() instead.",
        );

        return $this->getUser();
    }

    /**
     * @throws PropelException
     */
    public function hasRightForPublisher(Publisher $publisher): bool
    {
        $publisherRight = RightQuery::create()
            ->filterByUser($this->user)
            ->filterByPublisher($publisher)
            ->findOne();

        if ($publisherRight) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function hasPublisherRight(): bool
    {
        if (!$this->isAuthentified()) {
            return false;
        }

        $publisherRight = RightQuery::create()
            ->filterByUser($this->user)
            ->filterByPublisherId(null, Criteria::NOT_EQUAL)
            ->findOne();

        if ($publisherRight) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function getOption(string $key): ?string
    {
        if (!$this->user) {
            return null;
        }

        $option = OptionQuery::create()
            ->filterByUser($this->user)
            ->filterByKey($key)
            ->findOne();

        return $option?->getValue();
    }

    /**
     * @throws PropelException
     */
    public function setOption(string $key, string $value): void
    {
        $option = OptionQuery::create()
            ->filterByUser($this->user)
            ->filterByKey($key)
            ->findOne();

        if (!$option) {
            $option = new Option();
            $option->setUser($this->user);
            $option->setKey($key);
        }

        $option->setValue($value);
        $option->save();
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function getCart(): ?Cart
    {
        if (!$this->cartWasFetched) {
            $cartQuery = CartQuery::create()->filterBySite($this->getCurrentSite()->getSite());

            if ($this->isAuthentified()) {
                $cartQuery = $cartQuery->filterByUser($this->user);
            } else {
                $cartQuery = $cartQuery->filterByUid($this->token);
            }

            $this->fetchedCart = $cartQuery->findOne();
            $this->cartWasFetched = true;
        }

        return $this->fetchedCart;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function getOrCreateCart(): Cart
    {
        $cart = $this->getCart();

        if (!$cart) {
            $cart = new Cart();
            $cart->setSite($this->getCurrentSite()->getSite());
            $cart->setType("web");

            if ($this->isAuthentified()) {
                $cart->setUser($this->user);
            } else {
                $cart->setUid($this->token);
            }

            $cart->save();
        }

        return $cart;
    }

    /**
     * @throws PropelException
     */
    public function hasArticleInCart(Article $article): bool
    {
        $cart = $this->getCart();
        if (!$cart) {
            return false;
        }

        $articleInCartCount = StockQuery::create()
            ->filterByCart($cart)
            ->filterByArticle($article)
            ->count();

        return $articleInCartCount > 0;
    }

    /**
     * @throws PropelException
     */
    public function hasStockItemInCart(Stock $stockItem): bool
    {
        $cart = $this->getCart();
        if (!$cart) {
            return false;
        }

        $stockItemInCart = StockQuery::create()
            ->filterByCart($cart)
            ->filterById($stockItem->getId())
            ->count();

        return $stockItemInCart > 0;
    }

    public function injectCurrentSite(CurrentSite $currentSite): void
    {
        $this->currentSite = $currentSite;
    }

    public function getCurrentSite(): CurrentSite
    {
        return $this->currentSite;
    }

    /**
     * @throws PropelException
     */
    public function getCurrentRight(): ?Right
    {
        return RightQuery::create()
            ->filterByUser($this->user)
            ->findOne();
    }

    public function getEmail(): ?string
    {
        return $this->getUser()->getEmail();
    }

    public function authUser(): void
    {
        if (!$this->isAuthentified()) {
            throw new UnauthorizedHttpException("","Identification requise.");
        }
    }

    /**
     * @throws AccessDeniedHttpException
     * @throws Exception
     */
    public function authAdmin(
        $errorMessage = "Accès réservé aux administrateurs.",
    ): void
    {
        $this->authUser();

        if (!$this->isAdmin()) {
            throw new AccessDeniedHttpException($errorMessage);
        }
    }

    /**
     * @throws AccessDeniedHttpException
     * @throws PropelException
     */
    public function authPublisher(Publisher $publisher = null): void
    {
        $this->authUser();

        if ($publisher !== null && $this->hasRightForPublisher($publisher)) {
            return;
        }

        if ($publisher === null && $this->hasPublisherRight()) {
            return;
        }

        if ($this->isAdmin()) {
            return;
        }

        if ($publisher !== null) {
            throw new AccessDeniedHttpException(
                "Vous n'avez pas le droit de gérer l'éditeur {$publisher->getName()}."
            );
        }

        throw new AccessDeniedHttpException(
            "Vous n'avez pas le droit de gérer une maison d'édition."
        );
    }

    /**
     * @throws PropelException
     */
    public function transfertVisitorCartToUser(?string $visitorToken): void
    {
        if ($visitorToken === null) {
            return;
        }

        $visitorCart = CartQuery::create()->findOneByUid($visitorToken);
        if (!$visitorCart) {
            return;
        }

        $userCart = $this->getOrCreateCart();
        foreach ($visitorCart->getStocks() as $visitorCartItem) {
            $visitorCartItem->setCart($userCart);
            $visitorCartItem->save();
        }

        $amount = 0;
        $count = 0;
        foreach ($userCart->getStocks() as $userCartItem) {
            $amount += $userCartItem->getSellingPrice();
            $count++;
        }
        $userCart->setAmount($amount);
        $userCart->setCount($count);
        $userCart->save();

        $visitorCart->delete();
    }
}
