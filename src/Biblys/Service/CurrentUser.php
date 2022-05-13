<?php


namespace Biblys\Service;


use DateTime;
use Exception;
use Framework\Exception\AuthException;
use Model\Cart;
use Model\CartQuery;
use Model\Option;
use Model\OptionQuery;
use Model\Publisher;
use Model\SessionQuery;
use Model\Site;
use Model\User;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

class CurrentUser
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * @var string|null;
     */
    private $token;

    /**
     * @var CurrentSite
     */
    private $currentSite;

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
    public static function buildFromRequest(Request $request): CurrentUser
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
     */
    public static function buildFromRequestAndConfig(Request $request, Config $config): CurrentUser
    {
        $currentUser = self::buildFromRequest($request);

        $currentSite = CurrentSite::buildFromConfig($config);
        $currentUser->injectCurrentSite($currentSite);

        return $currentUser;
    }

    public function isAuthentified(): bool
    {
        if ($this->user) {
            return true;
        }

        return false;
    }

    public function isAdminForSite(Site $site): bool
    {
        if ($this->user) {
            return $this->user->isAdminForSite($site);
        }

        return false;
    }

    /**
     * @throws AuthException
     */
    public function getUser(): User
    {
        if ($this->user === null) {
            throw new AuthException("Identification requise.");
        }

        return $this->user;
    }

    /**
     * @throws PropelException
     */
    public function hasRightForPublisher(Publisher $publisher): bool
    {
        if ($this->user) {
            return $this->user->hasRightForPublisher($publisher);
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function hasPublisherRight(): bool
    {
        if ($this->user) {
            return $this->user->hasPublisherRight();
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

        if (!$option) {
            return null;
        }

        return $option->getValue();
    }

    /**
     * @throws PropelException
     */
    public function setOption(string $key, string $value)
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
        if (!$this->isAuthentified()) {
            return CartQuery::create()->findOneByUid($this->token);
        }

        return CartQuery::create()
            ->filterBySite($this->getCurrentSite()->getSite())
            ->filterByUser($this->user)
            ->findOne();
    }

    private function injectCurrentSite(CurrentSite $currentSite)
    {
        $this->currentSite = $currentSite;
    }

    /**
     * @throws Exception
     */
    public function getCurrentSite(): CurrentSite
    {
        if ($this->currentSite === null) {
            throw new Exception(
                "CurrentSite dependency was not injected in the CurrentUserService. Use the buildFromRequestAndConfig static method to build CurrentUser"
            );
        }

        return $this->currentSite;
    }
}
