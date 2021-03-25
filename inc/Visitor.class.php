<?php

use Framework\Exception\AuthException;

class Visitor extends User
{
    private $logged = null;
    private $visitor_uid = null;
    private $user = null;

    public function __construct($request)
    {
        global $_SQL;
        $this->db = $_SQL;

        if (isset($_COOKIE['visitor_uid'])) {
            $this->visitor_uid = $_COOKIE['visitor_uid'];
        } else {
            $this->visitor_uid = md5(uniqid('', true));
            setcookie('visitor_uid', $this->visitor_uid, 0, '/');
            $_COOKIE['visitor_uid'] = $this->visitor_uid;
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
     * @return boolean
     */
    public function isLogged()
    {
        global $request;

        if (isset($this->logged)) {
            return $this->logged;
        } else {
            $um = new UserManager();
            $sm = new SessionManager();

            // If user_uid cookie is set
            // not working on tys without HttpFoundation : $user_uid = $request->cookies->get('user_uid', false);

            if (isset($_COOKIE["user_uid"])) {
                $user_uid = $_COOKIE["user_uid"];
                // If there is a not expired session for this token
                $session = $sm->get(['session_token' => $user_uid]);
                if ($session) {

                    // If session has expired
                    if (($session->get('expires') < date('Y-m-d H:i:s'))) {
                        return false;
                    }

                    // Get user
                    $user = $um->getById($session->get('user_id'));
                    if ($user) {
                        $this->logged = true;
                        $this->user = $user;
                        $this->set('user_uid', $_COOKIE['user_uid']);
                        return true;
                    }
                }
            }
            $this->logged = false;
            return false;
        }
    }

    /*
        * Is the visitor currently an admin ?
        * @param type $id
        * @return boolean
        */
    public function isAdmin()
    {
        global $_SITE;
        $right = $this->getCurrentRight();
        if ($right->get('site_id') == $_SITE['site_id']) {
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
     * @param type $id
     * @return boolean
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
     * @param type $id
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
     * Is the visitor currenty a web cron task launcher
     */
    public function isCron()
    {
        global $request, $config;

        $siteCron = $config->get('cron');
        if (!$siteCron) {
            throw new Exception('Cron is not configured for this site');
        }
        if (!isset($siteCron['key'])) {
            throw new Exception('Key is missing in cron configuration');
        }

        $requestCronKey = $request->headers->get('X-CRON-KEY');
        if (!$requestCronKey) {
            throw new AuthException('Request lacks X-CRON-KEY header');
        }

        if ($requestCronKey !== $siteCron['key']) {
            throw new AuthException('Wrong cron key');
        }
    }

    /**
     * Get cart from visitor or user
     *  TODO : What if user logs after filling his cart as visitor ? Carts should be merged
     */
    public function getCart($create = null)
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
        return false;
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
     * @param type $uid
     */
    public function setCurrentRight($right)
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
        if ($right->get('user_id') == $this->get('id')) {
            $right->set('right_current', 1);
            $rm->update($right);
            return true;
        }

        return false;
    }

    public function getCurrentRight()
    {
        global $_SITE;

        $rm = new RightManager();
        $rights = $this->getRights();

        // Find current right
        foreach ($rights as $right) {
            if ($right->has('right_current')) {
                return $right;
            }
        }

        // If no right & user is admin, set admin right
        if ($right = $rm->get(array('user_id' => $this->get('id'), 'site_id' => $_SITE['site_id']))) {
            if ($this->setCurrentRight($right)) {
                return $right;
            }
        }

        // Else if other right available, set that one
        if ($right = $rm->get(array('user_id' => $this->get('id')))) {
            if ($this->setCurrentRight($right)) {
                return $right;
            }
        }

        // If no right at all, return empty right
        return new Right(array());
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
}
