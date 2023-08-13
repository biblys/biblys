<?php

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;

class InvalidCredentialsException extends Exception
{
}

class AxysUser extends Entity
{
    protected $prefix = 'user';
    protected $cart = null;
    protected $alerts = null;
    protected $wishes = null;
    protected $purchases = null;
    protected $options = null;
    public $trackChange = false;

    public function getOpt($key)
    {
        global $_SITE;

        $om = new OptionManager();

        $option = $om->get(['site_id' => $_SITE->get('id'), 'option_key' => $key, 'axys_user_id' => $this->get('id')]);

        if ($option) {
            return $option->get('value');
        }

        return false;
    }

    public function setOpt($key, $value)
    {
        global $_SITE;

        $om = new OptionManager();

        $option = $om->get(['site_id' => $_SITE->get('id'), 'option_key' => $key, 'axys_user_id' => $this->get('id')]);

        // If option already exists, update it
        if ($option) {
            $option->set('option_value', $value);
            $option->set('axys_user_id', $this->get('id'));
            $om->update($option);

            return $this;
        }

        // Else, create a new one
        $option = $om->create(['site_id' => $_SITE->get('id'), 'axys_user_id' => $this->get('id'), 'option_key' => $key, 'option_value' => $value]);

        return $this;
    }

    /**
     * Get the customer corresponding to this user or create one.
     *
     * @param bool $create Create customer if there isn't one
     */
    public function getCustomer($create = false)
    {
        $cm = new CustomerManager();

        // Get customer if if already exists
        if ($customer = $cm->get(['axys_user_id' => $this->get('id')])) {
            return $customer;
        } elseif ($create) {
            $customer = $cm->create();
            $customer->set('axys_user_id', $this->get('id'))
                ->set('customer_first_name', $this->get('first_name'))
                ->set('customer_last_name', $this->get('last_name'))
                ->set('customer_email', $this->get('email'));
            $cm->update($customer);

            return $customer;
        } else {
            return false;
        }
    }

    /**
     * Get user's username.
     *
     * @return string
     */
    public function getUserName()
    {
        if ($this->has('screen_name')) {
            return $this->get('screen_name');
        }

        if ($this->has('last_name')) {
            return trim($this->get('first_name') . ' ' . $this->get('last_name'));
        }

        if ($this->has('email')) {
            return $this->get('email');
        }

        if ($this->has('id')) {
            return 'User #' . $this->get('id');
        }

        return 'Inconnu';
    }

    /**
     * Get user cart.
     *
     * @return Cart
     */
    public function getCart()
    {
        if (isset($this->cart)) {
            return $this->cart;
        }

        $cm = new CartManager();
        if ($cart = $cm->get(['carts`.`user_id' => $this->get('id')])) {
            return $cart;
        }

        return false;
    }

    /**
     * Check if User has article in cart.
     *
     * @param $type
     * @param $id
     *
     * @return bool
     */
    public function hasInCart($type, $id): bool
    {
        $cart = $this->getCart();
        if (!$cart) {
            return false;
        }

        if ($type === "stock") {
            $sm = new StockManager();
            $stock = $sm->getById($id);
            return $cart->containsStock($stock);
        }

        if ($type === "article") {
            $am = new ArticleManager();
            $article = $am->getById($id);
            return $cart->containsArticle($article);
        }

        throw new InvalidArgumentException("Unknown type $type");
    }

    /**
     * Check if User has article in his library (shortcut for hasPurchased).
     *
     * @param Article $article
     *
     * @return bool
     */
    public function hasInLibrary($article)
    {
        return $this->hasPurchased($article);
    }

    /* ALERTS */

    /**
     * Get all user alerts.
     *
     * @return array user alerts
     */
    public function getAlerts()
    {
        if (!is_array($this->alerts)) {
            $am = new AlertManager();
            $this->alerts = $am->getAll(['axys_user_id' => $this->get('id')]);
        }

        return $this->alerts;
    }

    /**
     * Check if user has alert.
     *
     * @param int $article_id
     *
     * @return bool
     */
    public function hasAlert($article_id)
    {
        $alerts = $this->getAlerts();
        foreach ($alerts as $a) {
            if ($article_id == $a['article_id']) {
                return true;
            }
        }

        return false;
    }

    /* WISHES */

    // Get all user wishes
    public function getWishes()
    {
        if (is_array($this->wishes)) {
            return $this->wishes;
        } else {
            $wm = new WishManager();

            return $wm->getAll(['axys_user_id' => $this->get('id')]);
        }
    }

    // Is this article in the visitor's wishlist ?
    public function hasAWish($article_id)
    {
        $wishes = $this->getWishes();
        foreach ($wishes as $w) {
            if ($article_id == $w['article_id']) {
                return true;
            }
        }

        return false;
    }

    /* PURCHASES */

    // Get all user purchases
    public function getPurchases()
    {
        if (is_array($this->purchases)) {
            return $this->purchases;
        } else {
            $sm = new StockManager();

            return $sm->getAll(['axys_user_id' => $this->get('id')], [], false);
        }
    }

    // Is this article in the visitor's wishlist ?
    public function hasPurchased($article)
    {
        $purchases = $this->getPurchases();
        foreach ($purchases as $p) {
            if ($article->get('id') == $p['article_id']) {
                return true;
            }
        }

        return false;
    }

    /* RIGHTS */

    /**
     * Is the user root ?
     *
     * @return bool
     */
    public function isRoot()
    {
        return $this->get('user_id') == 1;
    }

    /**
     * Is the user an admin ?
     *
     * @return bool
     */
    public function isAdmin()
    {
        
        if ($this->isRoot()) {
            return true;
        } else {
            return $this->hasRight('site', LegacyCodeHelper::getLegacyCurrentSite()['id']);
        }
    }

    public function getRights()
    {
        $config = Config::load();
        $currentSiteService = CurrentSite::buildFromConfig($config);

        $rm = new RightManager();
        $rights = $rm->getAll(['axys_user_id' => $this->get('id')], [], false);

        // Keep only admin rights for current site
        foreach ($rights as $key => $right) {
            if ($right->has('site_id') && $right->get('site_id') != $currentSiteService->getId()) {
                unset($rights[$key]);
            }
        }

        return $rights;
    }

    public function hasRight(string $type, int $id): bool
    {
        $rights = $this->getRights();
        foreach ($rights as $r) {
            if ($r->get($type . '_id') === $id) {
                return true;
            }
        }

        return false;
    }

    public function giveRight($type, $id)
    {
        $rm = new RightManager();
        $right = $rm->create();
        $right->set('axys_user_id', $this->get('id'))->set($type . '_id', $id);
        $rm->update($right);
    }

    public function removeRight($type, $id)
    {
        $rm = new RightManager();
        $right = $rm->get([
            'axys_user_id' => $this->get('id'),
            $type . '_id' => $id,
        ]);

        if ($right) {
            $rm->delete($right);
        }
    }

    /**
     * Get the user's current wishlist (or create one).
     *
     * @param bool $create Create wishlist if there isn't one
     */
    public function getWishlist($create = false)
    {
        $wm = new WishlistManager();

        $wishlist = $wm->get(['axys_user_id' => $this->get('id'), 'wishlist_current' => 1]);

        if (!$wishlist && $create) {
            $wishlist = $wm->create(['axys_user_id' => $this->get('id')]);
        }

        return $wishlist;
    }
}

class AxysUserManager extends EntityManager
{
    protected $prefix = 'user';
    protected $object = 'AxysUser';
    protected $select = '*,
                    `id` AS `user_id`,
                    `Email` AS `user_email`,
                    `user_key` AS `user_uid`,
                    `user_civilite` AS `user_title`,
                    `user_prenom` AS `user_first_name`,
                    `user_nom` AS `user_last_name`,
                    `user_adresse1` AS `user_address1`,
                    `user_adresse2` AS `user_address2`,
                    `user_codepostal` AS `user_postal_code`,
                    `user_ville` AS `user_city`,
                    `user_pays` AS `user_country`,
                    `user_telephone` AS `user_phone`
                    ';

    public function __construct()
    {
        parent::__construct();

        $this->table = "axys_users";
    }

    public function getQuery($query, $params, $options = [], $withJoins = true)
    {
        // Old db scheme
        $query = str_replace('`user_id`', '`id`', $query);
        $query = str_replace('`user_email`', '`Email`', $query);
        $query = str_replace('`user_uid`', '`user_key`', $query);

        return parent::getQuery($query, $params, $options);
    }

    public function create(array $defaults = [], $text = null)
    {
        // Check if there is already a user with that e-mail address
        if ($this->get(['user_email' => $defaults['user_email']])) {
            throw new Exception('Cette adresse e-mail est déjà utilisée !');
        }

        // Generate a new password if necessary
        if (isset($defaults['user_new_password'])) {
            $user_password = $defaults['user_new_password'];
        } else {
            $user_password = null;
            for ($i = 0; $i < 8; ++$i) {
                $user_password .= substr('ABCDEFGHJKMNPQRSTUVWXYZabcdefghijkmnopqrstuvxyz23456789', rand(0, 31), 1);
            }
        }
        unset($defaults['user_new_password']);

        // Crypt the password
        $defaults['user_password'] = password_hash($user_password, PASSWORD_DEFAULT);

        // Override defaults because of old db scheme
        $defaults['Email'] = $defaults['user_email'];
        unset($defaults['user_email']);

        // Creating the entity
        $user = parent::create($defaults);
        $user->set('user_just_created', true);
        $user->set('user_new_password', $user_password);

        // Send mail
        if (empty($text)) {
            $text = '<p>Bienvenue sur Axys !</p>';
        }
        $message = $text . '
<p>
    Voici vos informations de connexion :<br />
    Adresse e-mail : ' . $user->get('user_email') . '<br />
    Mot de passe : ' . $user_password . '
</p>

<p>Grâce à votre compte, vous pourrez désormais vous identifier en un clic sur tous les sites du réseau Axys sans avoir à créer à chaque fois un nouveau compte. Retrouvez la liste sites du réseau sur <a href="https://axys.me"Z>axys.me</a>.</a></p>

<p>A très bientôt sur les sites du réseau Axys !</p>
';
        $this->mail($user, $this->site['site_tag'] . ' | Votre compte Axys', $message);

        // Return user
        return $user;
    }

    /**
     * Send a mail to the user.
     *
     * @param AxysUser   $user    The mail's recipient
     * @param string $subject The mail's subject
     * @param string $message The mail's body
     * @param array  $headers The mail's header
     */
    public function mail(AxysUser $user, $subject, $message, $headers = null)
    {
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>' . $subject . '</title>
    </head>
    <body>
        ' . $message . '
    </body>
</html>
            ';
        $mailer = new Biblys\Service\Mailer();
        $mailer->send($user->get('email'), $subject, $message);
    }

    /**
     * Add ebooks to user's library.
     *
     * @param object $user        The user
     * @param array  $articles    An array of Article objects to add
     * @param array  $stock       An array of Stock objects to add (if they already exists)
     * @param bool   $predownload Can the user download the files before article publication date?
     * @param array  $options     Additionnal options
     */
    public function addToLibrary(AxysUser $user, array $articles = [], array $stocks = [], $predownload = false, $options = [])
    {
        global $_SITE;

        $added = [];
        $errors = [];
        $sm = new StockManager();

        if (!isset($options['send_email'])) {
            $options['send_email'] = true;
        }

        if (!empty($articles)) {
            foreach ($articles as $article) {
                // Check if article is owned by current site
                $downloadablePublishers = explode(',', $_SITE->getOpt('downloadable_publishers'));
                if (
                    $_SITE->get('publisher_id') !== $article->get('publisher_id') &&
                    !in_array($article->get('publisher_id'), $downloadablePublishers)
                ) {
                    throw new Exception('Ce site n\'est pas autorisé à distribué cet article.');
                }

                // Check if article is a downloadable
                if ($article->get('type_id') == 2 || $article->get('type_id') == 11) {
                    // Check if article is already in library
                    if ($sm->getAll(['article_id' => $article->get('id'), 'axys_user_id' => $user->get('id')])) {
                        $errors[] = 'Article ' . $article->get('title') . ' is already in user\'s library.';
                    } else {
                        // Create a new free copy
                        $stock = $sm->create(['site_id' => $this->site['site_id']])
                            ->set('article_id', $article->get('id'))
                            ->set('site_id', $this->site['site_id'])
                            ->set('stock_selling_price', 0);

                        $sm->update($stock);

                        $new_stock = $sm->getById($stock->get('id'));
                        $stocks[] = $new_stock;
                    }
                } else {
                    $errors[] = 'Article #' . $article->get('id') . ' is not downloadable.';
                }
            }
        }

        // Add existing stocks to library
        if (!empty($stocks)) {
            foreach ($stocks as $stock) {
                $article = $stock->get('article');

                // Check if article is a downloadable
                if ($article->get('type_id') == 2 || $article->get('type_id') == 11) {
                    // Check if copy is already in library
                    if ($stock->has('axys_user_id')) {
                        $errors[] = 'Stock #' . $stock->get('id') . ' is already in user\'s library.';
                    }

                    // Else add it
                    else {
                        $stock->set('axys_user_id', $user->get('id'))
                            ->set('stock_selling_date', date('Y-m-d H:i:s'));
                        if ($predownload) {
                            $stock->set('stock_allow_predownload', 1);
                        }

                        $sm->update($stock);
                        $added[] = $article->get('title');
                    }
                } else {
                    $errors[] = 'Article #' . $article->get('id') . ' is not downloadable.';
                }
            }
        }

        // Send mail
        if (!empty($added) && $options['send_email']) {
            $newuser = null;
            if ($user->get('user_just_created')) {
                $newuser = '
                        <p>
                            Connectez-vous en utilisant vos identifiants Axys :<br />
                            Adresse e-mail : ' . $user->get('user_email') . '<br />
                            Mot de passe : ' . $user->get('user_new_password') . '
                        </p>

                    ';
            }

            $headers = null;
            $headers .= 'From: ' . $this->site['site_title'] . ' <' . $this->site['site_contact'] . '>' . "\r\n";
            $subject = $this->site['site_tag'] . ' | De nouveaux livres numériques disponibles dans votre bibliothèque.';
            $message = '
                    <p>Bonjour,</p>
                    <p>Les livres numériques suivants ont été ajoutés à <a href="http://' . $this->site['site_domain'] . '/pages/log_myebooks">votre bibliothèque numérique</a> :</p>
                    <ul><li>' . implode('</li><li>', $added) . '</li></ul>
                    <p>Vous pouvez les télécharger à volonté depuis notre site, dans tous les formats disponibles. Vous pourrez également profiter gratuitement des mises à jour de ces fichiers si de nouvelles versions sont proposées.</p>
                    <p>Vous trouverez également dans votre bibliothèque numérique de l\'aide pour télécharger et lire ces fichiers. En cas de difficulté, n\'hésitez pas à nous solliciter en répondant à ce message.</p>
                    <p><a href="http://' . $this->site['site_domain'] . '/pages/log_myebooks"><strong>Accéder à votre bibliothèque numérique</strong></a></p>
                    ' . $newuser . '
                    <p>NB : Ces fichiers vous sont volontairement proposés sans dispositif de gestion des droits numériques (DRM ou GDN). Nous vous invitons à les transmettre à vos proches si vous souhaitez les leur faire découvrir, comme vous le feriez avec un livre papier, mais nous vous prions de ne pas les diffuser plus largement, par respect pour l\'auteur et l\'éditeur.</p>
                ';
            $mailer = new Mailer();
            $mailer->send($user->get('Email'), $subject, $message);
        }
    }
}
