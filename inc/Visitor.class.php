<?php

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\Request;

class Visitor extends AxysAccount
{
    private bool $logged = false;
    private mixed $visitor_uid;
    private ?AxysAccount $user = null;

    private ?bool $isAdmin = null;

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

        parent::__construct([]);
    }

    public function has($field): bool
    {
        if (isset($this->user)) {
            return $this->user->has($field);
        }

        return false;
    }

    public function get($field)
    {
        if (isset($this->user)) {
            return $this->user->get($field);
        }

        return false;
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
     * Get cart from visitor or user
     *  TODO : What if user logs after filling his cart as visitor ? Carts should be merged
     */
    public function getCart($create = null): ?Cart
    {
        if (isset($this->cart)) {
            return $this->cart;
        } else {
            $cm = new CartManager();

            // If visitor has a cart
            if ($cart = $cm->get(array('cart_uid' => $this->visitor_uid))) {
                if ($this->isLogged() && !$cart->has('axys_account_id')) {
                    $cart->set('axys_account_id', $this->get('id'));
                }
                $this->cart = $cart;
                return $cart;
            }

            // Else if logged user has a cart
            elseif ($this->isLogged() && $cart = parent::getCart()) {
                $this->cart = $cart;
                return $cart;
            }

            // Else, if specified, create a new cart
            elseif ($create == 'create') {
                $defaults = array(
                    'cart_uid' => $this->visitor_uid,
                    'cart_ip' => $_SERVER["REMOTE_ADDR"],
                    'cart_type' => 'web'
                );
                if ($this->isLogged()) {
                    $defaults['axys_account_id'] = $this->get('id');
                }
                /** @var Cart $cart */
                $cart = $cm->create($defaults);
                $this->cart = $cart;
                return $cart;
            }
        }

        return null;
    }

    // Get wishes from parent class only if logged in
    public function getWishes(): array
    {
        if ($this->isLogged()) {
            return parent::getWishes();
        } else {
            return array();
        }
    }

    /**
     * Allow the visitor to choose from his different rights
     * @throws Exception
     */
    public function setCurrentRight(Right $right): bool
    {
        $rm = new RightManager();

        // Reset all current rights
        $rights = $this->getRights();
        foreach ($rights as $r) {
            if ($r->has('right_current')) {
                $r->set('right_current', 0);
                $rm->update($r);
            }
        }

        // Set current right
        if ($right->get('axys_account_id') === $this->get('id')) {
            $right->set('right_current', 1);
            $rm->update($right);
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function getCurrentRight(): Right
    {
        $rm = new RightManager();
        if ($right = $rm->get(['axys_account_id' => $this->get('id')])) {
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

        $um = new AxysAccountManager();
        /** @var AxysAccount|false $user */
        $user = $um->getById($session->get('axys_account_id'));
        if (!$user) {
            return;
        }

        $this->logged = true;
        $this->user = $user;
        $this->set('user_uid', $token);
    }
}
