<?php


namespace Biblys\Service;


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

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return CurrentUser
     * @throws PropelException
     */
    public static function buildFromRequest(Request $request): CurrentUser
    {
        $userUid = $request->cookies->get("user_uid");

        if ($userUid === null) {
            return new CurrentUser(null);
        }

        $session = SessionQuery::create()
            ->filterByToken($userUid)
            ->findOne();
        if (!$session) {
            return new CurrentUser(null);
        }

        if (($session->getExpiresAt() < date('Y-m-d H:i:s'))) {
            return new CurrentUser(null);
        }

        $user = $session->getUser();
        if (!$user) {
            return new CurrentUser(null);
        }

        return new CurrentUser($user);
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

    public function getUser(): User
    {
        return $this->user;
    }
}