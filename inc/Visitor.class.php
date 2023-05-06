<?php

use Symfony\Component\HttpFoundation\Request;

class Visitor extends User
{
    /** @var bool */
    private $logged = false;

    /** @var mixed|string */
    private $visitor_uid = null;

    /** @var User */
    private $user = null;

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

    public function has($field)
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

    /*
        * Is the visitor currently an admin ?
        * @param type $id
        * @return boolean
        */
    public function isAdmin()
    {
        
        $right = $this->getCurrentRight();
        if ($right->get('site_id') == getLegacyCurrentSite()['site_id']) {
            return true;
        }
        return false;
    }

    public function isPublisher()
    {
        $right = $this->getCurrentRight();
        if ($right->has('publisher_id')) {
            return true;
        }

        return false;
    }

    public function isPublisherWithId($id)
    {
        $right = $this->getCurrentRight();
        if ($right->get('publisher_id') === $id) {
            return true;
        }

        return false;
    }

    /**
     * Is the visitor currently a bookshop ?
     * @param int $id
     * @return bool
     */
    public function isBookshop($id = null)
    {
        $right = $this->getCurrentRight();
        if ($right->has('bookshop_id')) {
            return true;
        }
        return false;
    }

    /**
     * Is the visitor currently a library ?
     * @param int $id
     * @return boolean
     */
    public function isLibrary($id = null)
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
                // If user is logged, add user_id
                if ($this->isLogged() && !$cart->has('user_id')) {
                    $cart->set('user_id', $this->get('id'));
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
                    $defaults['user_id'] = $this->get('id');
                }
                $cart = $cm->create($defaults);
                $this->cart = $cart;
                return $cart;
            }
        }

        return null;
    }

    // Get wishes from parent class only if logged in
    public function getWishes()
    {
        if ($this->isLogged()) {
            return parent::getWishes();
        } else {
            return array();
        }
    }

    /**
     * Allow the visitor to choose from his different rights
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
        if ($right->get('user_id') === $this->get('id')) {
            $right->set('right_current', 1);
            $rm->update($right);
            return true;
        }

        return false;
    }

    public function getCurrentRight(): Right
    {
        

        $rm = new RightManager();
        $rights = $this->getRights();

        // Find current right
        foreach ($rights as $right) {
            if ($right->has('right_current')) {
                return $right;
            }
        }

        // If no right & user is admin, return admin right
        if ($right = $rm->get(['user_id' => $this->get('id'), 'site_id' => getLegacyCurrentSite()['site_id']])) {
            return $right;
        }

        // Else if other right available, set that one
        if ($right = $rm->get(['user_id' => $this->get('id')])) {
            return $right;
        }

        // If no right at all, return empty right
        return new Right([]);
    }

    /**
     * User->getPurchases override: only get purchases if user is logged
     * @return array An array of purchases, empty if user is not logged in
     */
    public function getPurchases()
    {
        if ($this->isLogged()) {
            return parent::getPurchases();
        }

        return [];
    }

    /**
     * @param string $token
     * @return bool
     */
    private function _setUserFromToken(string $token): bool
    {
        $sm = new SessionManager();
        $session = $sm->get(['session_token' => $token]);
        if (!$session) {
            return false;
        }

        if (($session->get('expires') < date('Y-m-d H:i:s'))) {
            return false;
        }

        $um = new UserManager();
        $user = $um->getById($session->get('user_id'));
        if (!$user) {
            return false;
        }

        $this->logged = true;
        $this->user = $user;
        $this->set('user_uid', $token);
        return true;
    }
}
