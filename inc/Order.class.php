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


use Biblys\Data\ArticleType;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Log;
use Biblys\Service\Mailer;
use Model\StockQuery;
use Model\UserQuery;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Payplug\Exception\ConfigurationException;
use Payplug\Exception\ConfigurationNotSetException;
use Payplug\Exception\HttpException;
use Stripe\Exception\ApiErrorException;
use Stripe\Product;
use Stripe\Stripe;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Usecase\AddArticleToUserLibraryUsecase;

class Order extends Entity
{
    protected $prefix = 'order';
    protected $stock = null;
    protected $campaign = null;
    protected $rewards = [];
    protected $copies;

    public function __construct($data)
    {
        global $_SQL;

        /* JOINS */

        // Customer (OneToMany)
        $cm = new CustomerManager();
        if (isset($data['customer_id'])) {
            $data['customer'] = $cm->get(array('customer_id' => $data['customer_id']));
        }

        // Country (OneToMany)
        $com = new CountryManager();
        if (isset($data['country_id'])) {
            $data['country'] = $com->get(array('country_id' => $data['country_id']));
        }

        parent::__construct($data);
    }

    /**
     * Returns order amount + shipping
     * @return {int} the total in cents
     */
    public function getTotal()
    {
        return $this->get('shipping') + $this->get('amount');
    }

    /**
     * Returns true if order is payed
     * @return bool
     */
    public function isPayed()
    {

        // If order has an amount left, it's not payed
        if ($this->get('amount_tobepaid') > 0) {
            return false;
        }

        // If there's no payment date, it's not payed
        if (!$this->get('payment_date')) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if order has been shipped (= has a shipping date)
     * @return bool
     */
    public function isShipped()
    {

        // If there's no shipping date, it's not shipped
        if (!$this->get('shipping_date')) {
            return false;
        }

        return true;
    }

    public function setCampaign($campaign_id)
    {
        if (isset($this->campaign)) {
            return;
        }

        $cfcm = new CFCampaignManager();
        $campaign = $cfcm->getById($campaign_id);
        if ($campaign) {
            $this->campaign = $campaign;
        }
    }

    public function getCampaign()
    {
        if (isset($this->campaign)) {
            return $this->campaign;
        }
        return false;
    }

    /**
     * Get copies in order
     */
    public function getCopies()
    {
        if (!isset($this->copies)) {
            $sm = new StockManager();
            $this->copies = $sm->getAll(['order_id' => $this->get('id')]);
        }

        return $this->copies;
    }

    /**
     * Get copies in order
     */
    public function containsDownloadable()
    {
        $copies = $this->getCopies();

        foreach ($copies as $copy) {
            $article = $copy->get('article');
            if ($article && $article->isDownloadable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update order's shipping fee after creation
     * @param Shipping $fee the fee
     * @throws Exception if order is Payed
     */
    public function setShippingFee(Shipping $fee)
    {
        if ($this->isPayed()) {
            throw new Exception("Impossible de modifier le mode d'expédition d'une commande qui a déjà été payée.");
        }

        if ($this->isShipped()) {
            throw new Exception("Impossible de modifier le mode d'expédition d'une commande qui a déjà été expédiée.");
        }

        $this->set('shipping_id', $fee->get('id'));
        $this->set('order_shipping', $fee->get('fee'));
        $this->set('order_shipping_mode', $fee->get('type'));
        $this->set('order_amount_tobepaid', $this->get('amount') + $fee->get('fee'));
    }

    /**
     * Returns order's items total weight
     * @return Int the sum of all order's items weight
     */
    public function getTotalWeight()
    {
        $sm = new StockManager();
        $items = $sm->getAll(['order_id' => $this->get('id')]);

        $total = 0;
        foreach ($items as $item) {
            $total += $item->get('weight');
        }

        return $total;
    }

    /**
     * Delete alerts for articles in a order
     * @throws Exception
     */
    public function deleteRelatedAlerts(CurrentUser $currentUser)
    {
        if (!$currentUser->isAuthenticated()) {
            return;
        }

        $alm = new AlertManager();

        $copies = $this->getCopies();
        foreach ($copies as $copy) {
            // Get alert for this user and article
            $alert = $alm->get(
                [
                    "user_id" => $currentUser->getUser()->getId(),
                    "article_id" => $copy->get("article_id")
                ]
            );

            // If it exists, delete it
            if ($alert) {
                $alm->delete($alert);
            }
        }
    }

    public function getCountryName(): ?string
    {
        if ($this->has("country_id")) {
            $cm = new CountryManager();
            $country = $cm->getById($this->get("country_id"));
            return $country->get("name");
        }

        return $this->get("country");
    }
}

class OrderManager extends EntityManager
{
    protected $prefix = 'order';
    protected $table = 'orders';
    protected $object = 'Order';

    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        $where['orders`.`site_id'] = $this->site['site_id'];

        return parent::getAll($where, $options);
    }


    public function count(array $where = array())
    {
        $where['orders`.`site_id'] = $this->site['site_id'];

        return parent::count($where);
    }

    public function countAllFromAnalytics($filters)
    {
        return $this->getAllFromAnalytics($filters, ['count' => true]);
    }

    public function getAllFromAnalytics($filters, $options)
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $req = [];
        $params = ['site_id' => $globalSite->get('id')];

        if ($filters['source']) {
            $req[] = "AND `order_utmz` LIKE :source";
            $params['source'] = '%utmcsr='.$filters['source'].'%';
        }
        if ($filters['campaign']) {
            $req[] = "AND `order_utmz` LIKE :campaign";
            $params['campaign'] = '%utmccn='.$filters['campaign'].'%';
        }
        if ($filters['medium']) {
            $req[] = "AND `order_utmz` LIKE :medium";
            $params['medium'] = '%utmcmd='.$filters['medium'].'%';
        }

        if (isset($options['limit']) && isset($options['offset'])) {
            $limit_req = "LIMIT ".$options['limit']." OFFSET ".$options['offset'];
        } elseif (isset($options['count'])) {
            $limit_req = null;
        }

        $sql = $this->db->prepare("SELECT * FROM `orders` WHERE `site_id` = :site_id AND `order_utmz` IS NOT NULL ".join(" ", $req)." AND `order_payment_date` IS NOT NULL AND `order_cancel_date` IS NULL ORDER BY `order_payment_date` DESC ".$limit_req);
        $sql->execute($params);
        $orders = $sql->fetchAll(PDO::FETCH_ASSOC);

        if (isset($options['count'])) {
            return count($orders);
        }

        $orders = array_map(function ($order) {
            return new Order($order);
        }, $orders);

        return $orders;
    }

    /**
     * Search for an order
     */
    public function search($keywords, array $where = [], array $options = [], $withJoins = false)
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $queries = ["site_id = ".$globalSite->get('id')];
        $i = 0;
        $keywords = explode(' ', $keywords);
        foreach ($keywords as $k) {
            $queries[] = "(`order_firstname` LIKE :keyword_".$i." OR
                        `order_lastname` LIKE :keyword_".$i." OR
                        `order_email` LIKE :keyword_".$i." OR
                        `order_id` LIKE :keyword_".$i.")";
            $params['keyword_'.$i] = '%'.$k.'%';
            $i++;
        }
        $query = implode(' AND ', $queries);

        $where = EntityManager::buildSqlQuery($where);
        $query .= " AND ".$where["where"];
        $params = array_merge($params, $where["params"]);

        return $this->getQuery($query, $params, $options, $withJoins);
    }

    /**
     * Create order
     * @param array $defaults
     * @return Order
     * @throws Exception
     */
    public function create(array $defaults = array()): Order
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }

        if (!isset($defaults['cart_uid'])) {
            $url = md5(uniqid('', true));
            $defaults['order_url'] = substr($url, 0, 16);
        }

        try {
            /** @var Order $order */
            $order = parent::create($defaults);
            return $order;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function hydrateFromCart(Order $order, Cart $cart): Order
    {
        $sm = new StockManager();
        $cm = new CartManager();

        // Get cart content
        $stock = $sm->getAll(array('cart_id' => $cart->get('id')));

        // Add each copy to the order
        foreach ($stock as $s) {
            $this->addStock($order, $s);
        }

        // Update cart (now empty) from stock
        $cm->updateFromStock($cart);

        // Update order from copies
        $updatedOrder = $this->updateFromStock($order);

        // Update campaign (if necessary)
        $campaign = $order->getCampaign();
        if ($campaign) {
            $cfcm = new CFCampaignManager();
            $cfcm->updateFromSales($campaign);

            $cfrm = new CFRewardManager();
            $rewards = $cfrm->getAll([
                "campaign_id" => $campaign->get("id"),
                "reward_limited" => 1
            ]);
            foreach ($rewards as $reward) {
                $cfrm->updateQuantity($reward);
            }
        }

        return $updatedOrder;
    }

    /**
     * Ajouter un exemplaire à une commande
     * @param object $order L'objet de la commande
     * @param int $stock L'objet de l'exemplaire
     */

    public function addStock(Order $order, Stock $stock)
    {
        if (!$stock->isAvailable()) {
            throw new Exception('Exemplaire '.$stock->get('id').' indisponible.');
        }

        $sm = new StockManager();
        $stock->set('order', $order);
        $stock->set('stock_selling_date', date('Y-m-d H:i:s'));
        $stock->set('cart_id', null);
        $stock->set('stock_cart_date', null);

        // Tax
        $stock = $sm->calculateTax($stock);

        // Customer
        if ($order->has('customer_id')) {
            $stock->set('customer_id', $order->get('customer_id'));
        }

        // Crowdfunding campaign
        if ($stock->has('campaign_id')) {
            $order->setCampaign($stock->get('campaign_id'));
        }

        $sm->update($stock);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function removeStock(Order $order, Stock $stock): bool
    {
        // If the copy is in the order
        if ($stock->get('order_id') == $order->get('order_id')) {
            $sm = new StockManager();
            $stock->set('order_id', null);
            $stock->set('user_id', null);
            $stock->set('customer_id', null);
            $stock->set('stock_selling_date', null);
            $sm->update($stock);

            // Crowdfunding
            if ($campaign = $stock->get('campaign')) {
                $stock->set('campaign_id', null);
                $stock->set('reward_id', null);
                $cm = new CFCampaignManager();
                $cm->updateFromSales($campaign);
            }

            // Reward
            if ($reward = $stock->get('reward')) {
                $rm = new CFRewardManager();
                $rm->updateQuantity($reward);
            }

            return true;
        } else {
            throw new Exception("L'exemplaire n'est pas dans la commande.");
        }
    }

    /**
     * Recalcule les montants de la commande en fonction des exemplaires
     * @throws Exception
     */
    public function updateFromStock(Order $order): Order
    {
        $sm = new StockManager();

        $order_amount = 0;

        $stock = $sm->getAll(array('order_id' => $order->get('id')));

        foreach ($stock as $s) {
            $order_amount += $s->get('selling_price');
        }

        $order->set('order_amount', $order_amount);

        if (!$order->isPayed()) {
            $order->set("order_amount_tobepaid", $order_amount + $order->get('order_shipping'));
        }

        /** @var Order $order */
        $order = $this->update($order);

        return $order;
    }

    /**
     * Add payment to order and flag it as executed
     *
     * @param {Order} $order : the order
     * @param {Payment} $payment : a Payment object or the mode as a String
     * @param {Int} $amount : if Payment is not provided
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function addPayment(Order $order, $payment, $amount = 0)
    {
        $pm = new PaymentManager();

        if (!$payment instanceof \Payment) {
            $mode = $payment;
            $payment = $pm->create([
                "order_id" => $order->get('id'),
                "payment_mode" => $mode,
                "payment_amount" => $amount
            ]);
        }

        $mode = $payment->get('mode');
        $amount = $payment->get('amount');

        // Flag the payment as executed
        $payment->setExecuted();
        $pm->update($payment);

        // Update current amount for this mode
        $current_amount = $order->get('order_payment_'.$mode);
        $current_amount += $payment->get('amount');
        $order->set('order_payment_'.$mode, $current_amount);

        // Subtract paid amount to remaining amount to be paid
        $remaining = $order->get('order_amount_tobepaid') - $amount;

        // Remaining amount should not be less than 0
        if ($remaining < 0) {
            $remaining = 0;
        }

        // Save remaining amount
        $order->set('order_amount_tobepaid', $remaining);

        // Save payment mode
        $order->set('order_payment_mode', $mode);

        // Persist order
        $this->update($order);

        // If order is paid entirely, mark as paid
        if ($remaining == 0) {
            $config = Config::load();
            $currentSite = CurrentSite::buildFromConfig($config);
            $container = include __DIR__."/../src/container.php";
            $urlGenerator = $container->get("url_generator");
            $this->markAsPayed($currentSite, $urlGenerator, $order);
        }
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function markAsPayed(CurrentSite $currentSite, UrlGenerator $urlGenerator, Order $order): void
    {
        $mailer = $this->getMailer();

        // Save payment date
        $order->set('order_payment_date', date('Y-m-d H:i:s'));

        $order_downloadable = null;
        if ($order->containsDownloadable()) {
            $order_downloadable = '
                <p>
                    Vous pouvez télécharger les articles numériques de votre commande depuis
                    <a href="http://'.$this->site->get('domain').'/pages/log_myebooks">
                        votre bibliothèque numérique
                    </a>.
                </p>
            ';
        }

        // Send mail
        $subject = 'Commande n° '.$order->get('id').' payée';
        $message = '
            <p>Bonjour '.$order->get('firstname').' !</p>

            <p>Votre paiement pour la commande n° '.$order->get('id').' a bien été reçu.</p>

            <p>
                Commande n° '.$order->get('id').'<br>
                Montant : '.currency($order->getTotal() / 100).'<br>
                Mode de règlement : '.$order->get('payment_mode'). '<br>
            </p>

            <p>
                <a href="https://' .$this->site->get('domain').'/order/'.$order->get('url').'">Suivi de la commande</a></a>
            </p>

            '.$order_downloadable.'

            <p>
                Merci pour votre confiance.
            </p>

            <p><a href="http://'.$this->site->get('domain').'/">http://'.$this->site["site_domain"].'/</a></p>
        ';

        $mailer->send($order->get('email'), $subject, $message);

        // Physical types
        $types = ArticleType::getAllPhysicalTypes();
        $physical_types = array_map(function ($type) {
            return $type->getId();
        }, $types);

        // Loop through each copy
        $sm = new StockManager();
        $stocks = $sm->getAll(array('order_id' => $order->get('id')));
        $books = [];
        $ebooks = [];
        $physical = [];

        foreach ($stocks as $stock) {
            $type_id = $stock->get('article')->get('type_id');

            if ($type_id == 2) {
                $ebooks[] = $stock;
            } else {
                $books[] = $stock;
                $stock->set('user_id', $order->get('user_id'));
                $sm->update($stock);
            }

            // If article is physical
            if (in_array($type_id, $physical_types)) {
                $physical[] = $stock;
            }
        }

        // Books & e-books
        if ($order->has('user_id') && !empty($ebooks)) {
            $items = array_map(function (Stock $ebook) {
                return StockQuery::create()->findPk($ebook->get('id'));
            }, $ebooks);

            $user = UserQuery::create()->findPk($order->get('user_id'));
            $usecase = new AddArticleToUserLibraryUsecase($mailer);
            $usecase->execute(
                currentSite: $currentSite,
                urlGenerator: $urlGenerator,
                user: $user,
                items: $items,
                sendEmail: true,
            );
        }

        // If no physical products, mark order as shipped without warning the user
        if (!count($physical)) {
            $order->set('order_shipping_date', date('Y-m-d H:i:s'));
        }

        // Persist
        $this->update($order);
    }

    public function followUp(Order $order)
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $mailer = $this->getMailer();

        // Save followup date
        $order->set('order_followup_date', date('Y-m-d H:i:s'));

        // Send mail
        $subject = "Commande n°".$order->get('id')." : relance";
        $content = '
            <p>Bonjour '.$order->get('firstname').',</p>

            <p>
                Vous avez passé une commande chez <a href="http://'.$globalSite->get('domain').'/">'.$globalSite->get('title').'</a> le '._date($order->get('created'), 'd/m/Y').' qui n\'a pas encore été payée.<br>
                Peut-être avez vous rencontré une difficulté lors du paiement ? N\'hésitez pas à <a href="http://'.$globalSite->get('domain').'/contact/">nous contacter</a> pour obtenir de l\'aide.
            </p>

            <p>
                Vous pouvez voir le détail de votre commande et la régler ci-dessous :<br />
                <a href="http://'.$globalSite->get('domain').'/order/'.$order->get('url').'">Suivi de la commande</a></a>
            </p>

            <p>
                Si vous n\'êtes plus intéressé par ces articles, ignorez simplement ce message.<br />
                Faute de réponse de votre part, votre commande sera annulée automatiquement d\'ici 5 jours.
            </p>

            <p><a href="http://'.$globalSite->get('domain').'/">'.$globalSite->get('title').'</a></p>
        ';
        $mailer->send($order->get('email'), $subject, $content);

        // Persist
        $this->update($order, "Marked as followup");
    }

    /**
     * Set the order customer & if paid, copies customer
     * @param Order    $order
     * @param Customer $customer
     */
    public function setCustomer(Order $order, Customer $customer = null)
    {
        $sm = new StockManager();

        // Set customer
        if ($customer) {
            $order->set('customer', $customer);

            $payed = $order->get('payment_date');
            if ($payed) {
                $copies = $sm->getAll(["order_id" => $order->get('id')]);
                foreach ($copies as $copy) {
                    $copy->set('customer', $customer);
                    $sm->update($copy);
                }
            }
        }

        // Unset customer
        else {
            $order->set('customer_id', null);

            $copies = $sm->getAll(["order_id" => $order->get('id')]);
            foreach ($copies as $copy) {
                $copy->set('customer_id', null);
                $sm->update($copy);
            }
        }

        $this->update($order);
        return $order;
    }

    /**
     * Cancel an order
     * @param Order $order the order entity to cancel
     * @throws Exception
     */
    public function cancel(Order $order)
    {

        // Remove all stock from order
        $sm = new StockManager();
        $stocks = $sm->getAll(array('order_id' => $order->get('id')));
        $removed = array();
        $removedCount = 0;
        foreach ($stocks as $stock) {
            $article = $stock->get('article');
            $removed[] = $article->get('title');
            $removedCount++;
            $this->removeStock($order, $stock);
        }
        $this->updateFromStock($order);

        // Send mail
        if ($order->get("type") === "web") {
            $this->_sendCancellationMail($order, $removedCount, $removed);
        }

        // Set cancelation date
        $order->set('order_cancel_date', date('Y-m-d H:i:s'));

        // Persist order
        $this->update($order);
    }

    /**
     * @param Order $order
     * @param int $removedCount
     * @param array $removed
     * @throws Exception
     */
    private function _sendCancellationMail(Order $order, int $removedCount, array $removed): void
    {
        $subject = 'Commande n° ' . $order->get('id') . ' annulée';
        $message = '
            <html>
                <head>
                    <title>' . $subject . '</title>
                </head>
                <body>
                    <p>Bonjour,</p>

                    <p>La commande n° ' . $order->get('id') . ' a été annulée.</p>

                    <p>
                        Pour mémoire, cette commande concernait le' . s($removedCount) . ' article' . s($removedCount) . ' suivant' . s($removedCount) . '&nbsp;:
                    </p>
                    <ul><li>' . implode('</li><li>', $removed) . '</li></ul>

                    <p>
                        A très bientôt !
                    </p>

                    <p><a href="">http://' . $this->site["site_domain"] . '/</a></p>
                </body>
            </html>
        ';
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());
        $mailer->send($order->get('email'), $subject, $message);
    }
}
