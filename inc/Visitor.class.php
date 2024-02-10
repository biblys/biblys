<?php

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

class Visitor
{
    private bool $logged = false;
    private mixed $visitor_uid;
    private ?User $user = null;
    private ?array $alerts = null;
    private ?array $purchases = null;

    public function __construct(Request $request)
    {
        if (isset($_COOKIE['visitor_uid'])) {
            $this->visitor_uid = $_COOKIE['visitor_uid'];
        } else {
            $this->visitor_uid = md5(uniqid('', true));
            setcookie('visitor_uid', $this->visitor_uid, 0, '/');
            $_COOKIE['visitor_uid'] = $this->visitor_uid;
        }

        $userUidCookie = $request->cookies->get("user_uid");
        if ($userUidCookie) {
            $this->_setUserFromToken($userUidCookie);
        }
    }

    public function has($field): bool
    {
        $value = $this->get($field);

        return (bool) $value;
    }

    public function get($field)
    {
        if (!$this->isLogged()) {
            return null;
        }

        $fieldWords = explode('_', $field);
        $capitalizedFieldWords = array_map('ucfirst', $fieldWords);
        $pascalCaseFieldName = join('', $capitalizedFieldWords);
        $methodName = "get$pascalCaseFieldName";

        if ($methodName === "getIsAdmin") {
            trigger_deprecation(
                "biblys/biblys",
                "2.75.0",
                "Visitor->isAdmin is deprecated. Use CurrentUser->isAdmin instead."
            );
            return false;
        }

        if (method_exists($this->user, $methodName)) {
            return $this->user->$methodName();
        }

        throw new ArgumentCountError("Method $methodName does not exist on User");
    }

    /**
     * Is the visitor authentificated ?
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->logged;
    }

    /**
     * @throws Exception
     */
    public function isPublisher(): bool
    {
        $right = $this->getCurrentRight();
        if ($right->has('publisher_id')) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function isBookshop(): bool
    {
        $right = $this->getCurrentRight();
        if ($right->has('bookshop_id')) {
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function isLibrary(): bool
    {
        $right = $this->getCurrentRight();
        if ($right->has('library_id')) {
            return true;
        }
        return false;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function isAdmin(): bool
    {
        $request = Request::createFromGlobals();
        $config = Config::load();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        return $currentUser->isAdmin();
    }

    /**
     * @throws Exception
     */
    public function getCurrentRight(): Right
    {
        $rm = new RightManager();
        if ($right = $rm->get(['user_id' => $this->get('id')])) {
            return $right;
        }

        return new Right([]);
    }

    private function _setUserFromToken(string $token): void
    {
        $sm = new SessionManager();
        $session = $sm->get(['session_token' => $token]);
        if (!$session) {
            return;
        }

        if (($session->get('expires') < date('Y-m-d H:i:s'))) {
            return;
        }

        $user = UserQuery::create()->findPk($session->get('user_id'));
        if (!$user) {
            return;
        }

        $this->logged = true;
        $this->user = $user;
    }
}
