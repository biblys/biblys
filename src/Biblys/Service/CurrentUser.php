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


namespace Biblys\Service;

use DateTime;
use Exception;
use Model\AlertQuery;
use Model\Article;
use Model\Cart;
use Model\CartQuery;
use Model\Customer;
use Model\CustomerQuery;
use Model\Option;
use Model\OptionQuery;
use Model\Publisher;
use Model\Right;
use Model\RightQuery;
use Model\SessionQuery;
use Model\Stock;
use Model\StockQuery;
use Model\User;
use Model\WishQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @throws PropelException
     */
    private static function buildFromRequest(
        Request $request,
        CurrentSite $currentSite,
    ): CurrentUser
    {
        $cookieToken = $request->cookies->get("user_uid");
        $headerToken = $request->headers->get("AuthToken");

        $token = $cookieToken ?: $headerToken;

        $isTokenUtf8Encoded = mb_check_encoding($token ?? "", "UTF-8");
        if (!$isTokenUtf8Encoded) {
            throw new BadRequestHttpException("Cookies must use charset UTF-8");
        }

        if ($token === null) {
            $visitorUid = $request->cookies->get("visitor_uid");

            $isCookieUtf8Encoded = mb_check_encoding($visitorUid ?? "", "UTF-8");
            if (!$isCookieUtf8Encoded) {
                throw new BadRequestHttpException("Cookies must use charset UTF-8");
            }

            return new CurrentUser(null, $visitorUid);
        }

        $session = SessionQuery::create()
            ->filterByToken($token)
            ->filterBySite($currentSite->getSite())
            ->findOne();
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
        $currentSite = CurrentSite::buildFromConfig($config);

        $currentUser = self::buildFromRequest($request, $currentSite);

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

        return RightQuery::create()->isUserAdminForSite($this->user, $site);
    }

    /**
     * @throws PropelException
     *
     * @deprecated CurrentUser->isAdminForSite() is deprecated. Use CurrentUser->isAdmin() instead.
     */
    public function isAdminForSite(): bool
    {
        return $this->isAdmin();
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

    /**
     * @throws PropelException
     */
    public function hasRightForPublisher(Publisher $publisher): bool
    {
        if (!$this->isAuthentified()) {
            return false;
        }

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

    /**
     * @throws Exception
     */
    public function getCurrentSite(): CurrentSite
    {
        if ($this->currentSite === null) {
            throw new Exception("CurrentSite service was not injected.");
        }

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

    /**
     * Wishlist
     */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function hasArticleInWishlist(Article $article): bool
    {
        if (!$this->isAuthentified()) {
            return false;
        }

        $articleInWishlistCount = WishQuery::create()
            ->filterBySiteId($this->getCurrentSite()->getId())
            ->filterByArticleId($article->getId())
            ->filterByUser($this->user)
            ->count();

        return $articleInWishlistCount > 0;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function hasAlertForArticle(Article $article): bool
    {
        if (!$this->isAuthentified()) {
            return false;
        }

        $alertsForArticle = AlertQuery::create()
            ->filterBySite($this->getCurrentSite()->getSite())
            ->filterByUser($this->user)
            ->filterByArticleId($article->getId())
            ->count();

        return $alertsForArticle > 0;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function getOrCreateCustomer(): ?Customer
    {
        $customer = CustomerQuery::create()
            ->filterBySite($this->getCurrentSite()->getSite())
            ->filterByUser($this->getUser())
            ->findOne();

        if ($customer === null) {
            $customer = new Customer();
            $customer->setSite($this->getCurrentSite()->getSite());
            $customer->setUser($this->getUser());
            $customer->save();
        }

        return $customer;

    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function hasPurchasedArticle(Article $article): bool
    {
        if (!$this->isAuthentified()) {
            return false;
        }


        $purchases = StockQuery::create()
            ->filterBySite($this->getCurrentSite()->getSite())
            ->filterByUser($this->getUser())
            ->filterByArticle($article)
            ->count();

        return $purchases > 0;
    }
}
