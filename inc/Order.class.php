<?php

use Biblys\Service\Log;
use Biblys\Service\Mailer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use Payplug\Exception\HttpException;

class Order extends Entity
{
    protected $prefix = 'order';
    protected $stock = null;
    protected $campaign = null;
    protected $rewards = [];
    protected $copies;

    public function __construct($data)
    {
        global $_SQL, $_SITE;

        /* JOINS */

        // Customer (OneToMany)
        $cm = new CustomerManager();
        if (isset($data['customer_id'])) {
            $data['customer'] = $cm->get(array('customer_id' => $data['customer_id']));
        }

        // User (OneToMany)
        $um = new UserManager();
        if (isset($data['user_id'])) {
            $data['user'] = $um->get(array('user_id' => $data['user_id']));
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
     * @return boolean
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
     * @return boolean
     */
    public function isShipped()
    {

        // If there's no shipping date, it's not shipped
        if (!$this->get('shipping_date')) {
            return false;
        }

        return true;
    }

    //** PAYPAL **//

    /**
     * Creates a Paypal payment link for this order using Paypal PHP SDK
     * @return String the link
     */
    public function createPaypalPaymentLink()
    {
        global $site, $config, $urlgenerator;

        $sm = new StockManager();

        // Get ApiContext
        $apiContext = $this->getPaypalApiContext();

        // Set Payment method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Add each copy to paypal item list
        $copies = $sm->getAll(["order_id" => $this->get("id")]);
        $item_list = new ItemList();
        $subtotal = 0;
        $tax = 0;
        foreach ($copies as $copy) {
            $article = $copy->get('article');
            $item = new Item();
            $item->setName($article->get('title'))
                ->setCurrency("EUR")
                ->setQuantity(1)
                ->setSku($article->get('id'))
                ->setPrice(price($copy->get('selling_price_ht')));
            $item_list->addItem($item);
            $subtotal += price($copy->get('selling_price_ht'));
            $tax += price($copy->get('selling_price_tva'));
        }

        // Set order details
        $details = new Details();
        $details->setTax($tax)
            ->setShipping(price($this->get('shipping')))
            ->setSubtotal($subtotal);

        // Set order amount
        $amount = new Amount();
        $amount->setCurrency("EUR")
            ->setTotal(price($this->get('amount') + $this->get('shipping')))
            ->setDetails($details);

        // Create transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription("Paiement de la commande n° ".$this->get("id"))
            ->setInvoiceNumber($this->get("id"));

        // Redirect urls
        $protocol = 'http';
        $https = $config->get('https');
        if ($https) {
            $protocol = 'https';
        }
        $returnUrl = $protocol.'://'.$site->get('domain').$urlgenerator->generate('order_paypal_process', ['url' => $this->get('url')]);
        $cancelUrl = $protocol.'://'.$site->get('domain').'/payment/'.$this->get('url');
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($apiContext);
        } catch (PayPal\Exception\PayPalConnectionException $pce) {
            throw new Exception("Une erreur est survenue en tentant de contacter Paypal (".$pce->getMessage()."), merci de réessayer plus tard ou avec un autre mode de paiement.");
        } catch (Exception $ex) {
            throw new Exception("Une erreur est survenue en tentant de contacter Paypal, merci de réessayer plus tard ou avec un autre mode de paiement.");
        }

        return $payment->getApprovalLink();
    }

    public function executePaypalPayment($paymentId, $payerId)
    {
        // Get ApiContext
        $apiContext = $this->getPaypalApiContext();

        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $payment->execute($execution, $apiContext);

            try {
                $payment = Payment::get($paymentId, $apiContext);
            } catch (Exception $ex) {
                throw new Exception("There was an error while fetching the payment after execution: ".$ex->getMessage());
            }
        } catch (PayPal\Exception\PayPalConnectionException $e) {
            throw new Exception('PayPal error '.$e->getCode().': '.$e->getData());
        } catch (Exception $ex) {
            throw new Exception("There was an error while executing the payment: ".$ex->getMessage());
        }

        return $payment;
    }

    /**
     * Helpers to get paypal api context using credentials in config.yml
     * Returns an ApiContext object
     */
    private function getPaypalApiContext()
    {
        global $config;

        $paypal_config = $config->get("paypal");
        if (!$paypal_config) {
            throw new Exception("Paypal is not properly configured.");
        }

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $paypal_config["client_id"],         // ClientID
                $paypal_config["client_secret"]      // ClientSecret
            )
        );

        $apiContext->setConfig(["mode" => "live"]);

        return $apiContext;
    }

    /**
     * Create Payplug payment using PHP SDK
     * @return {string} the link
     */
    public function createPayplugPayment()
    {
        global $config, $urlgenerator, $request;

        $payplug = $config->get('payplug');
        if (!$payplug) {
            throw new Exception("Payplug is not configured.");
        }

        if (!isset($payplug["secret"]) || empty($payplug["secret"])) {
            throw new Exception("Missing Payplug secret key.");
        }

        Payplug\Payplug::init(
            [
                'secretKey' => $payplug["secret"],
                'apiVersion' => '2019-08-06',
            ]
        );

        $total_amount = $this->get("amount") + $this->get("shipping");

        $ipn_protocol = 'https';
        if (isset($payplug['ipn_protocol'])) {
            $ipn_protocol = $payplug['ipn_protocol'];
        }

        $ipn_host = $request->getHost();
        if (isset($payplug['ipn_host'])) {
            $ipn_host = $payplug['ipn_host'];
        }

        $notification_url = $ipn_protocol.'://'.$ipn_host.$urlgenerator->generate('order_payplug_notification', ['url' => $this->get('url')]);

        // Gather customer info
        $billing = [
            "first_name" => $this->get("firstname"),
            "last_name" => $this->get("lastname"),
            "email" => $this->get("email"),
            "address1" => $this->get("address1"),
            "postcode" => $this->get("postalcode"),
            "city" => $this->get("city"),
            "country" => $this->get("country")->get("code"),
        ];

        $shipping = $billing;
        $shipping["delivery_type"] = "BILLING";

        try {
            $response = Payplug\Payment::create([
                'amount'            => $total_amount,
                'currency'          => 'EUR',
                'billing'           => $billing,
                'shipping'          => $shipping,
                'hosted_payment'    => [
                    'return_url'        => 'http://'.$_SERVER["HTTP_HOST"].'/order/'.$this->get('url').'?payed=1',
                    'cancel_url'        => 'http://'.$_SERVER["HTTP_HOST"].'/payment/'.$this->get('url')
                ],
                'notification_url'  => $notification_url,
                'metadata'          => [
                    'order_id'          => $this->get('id')
                ]
            ]);
        } catch (HttpException $exception) {
            Log::payplug(
                "ERROR",
                "An error occurred while creating a Payment for order ".$this->get('id'),
                [$exception->getHttpResponse()]
            );
            throw $exception;
        }

        $pm = new PaymentManager();
        $payment = $pm->create([
            "order_id" => $this->get('id'),
            "payment_mode" => "payplug",
            "payment_amount" => $total_amount,
            "payment_provider_id" => $response->id,
            "payment_url" => $response->hosted_payment->payment_url
        ]);

        return $payment;
    }

    public function createStripePayment()
    {
        global $config;

        $stripe = $config->get('stripe');
        if (!$stripe) {
            throw new Exception("Stripe is not configured.");
        }

        if (!isset($stripe["public_key"]) || empty($stripe["public_key"])) {
            throw new Exception("Missing Stripe public key.");
        }

        if (!isset($stripe["secret_key"]) || empty($stripe["secret_key"])) {
            throw new Exception("Missing Stripe secret key.");
        }

        if (!isset($stripe["endpoint_secret"]) || empty($stripe["endpoint_secret"])) {
            throw new Exception("Missing Stripe endpoint secret.");
        }

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        // Add each copy to Stripe line items
        $copies = $this->getCopies();
        $lineItems = array_map(function($copy) {
            return [
                "quantity" => 1,
                "amount" => $copy->get('selling_price'),
                "currency" => "EUR",
                "name" => $copy->getArticle()->get('title'),
            ];
        }, $copies);
        $amountToPay = array_reduce($lineItems, function($total, $current) {
            return $total + $current["amount"];
        }, 0);

        // Add shipping cost as a line item
        $shippingCost = $this->get('shipping');
        if ($shippingCost && $shippingCost !== 0) {
            $lineItems[] = [
                "quantity" => 1,
                "amount" => $shippingCost,
                "currency" => "EUR",
                "name" => "Frais de port"
            ];
            $amountToPay += $shippingCost;
        }

        if ($amountToPay !== (int) $this->get('amount_tobepaid')) {
            throw new Exception("Stripe's amount to pay ($amountToPay) does not match order's amount to be paid (".$this->get('amount_tobepaid').").");
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://'.$_SERVER["HTTP_HOST"].'/order/'.$this->get('url').'?payed=1',
            'cancel_url' => 'http://'.$_SERVER["HTTP_HOST"].'/payment/'.$this->get('url'),
            'customer_email' => $this->get('email'),
        ]);

        $pm = new PaymentManager();
        return $pm->create([
            "order_id" => $this->get('id'),
            "payment_mode" => "stripe",
            "payment_amount" => $amountToPay,
            "payment_provider_id" => $session["id"]
        ]);
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
     * @param Fee $fee the fee
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
     * Parse saved Google Analytics cookie
     * @return [Object] A cookie object
     */
    public function getAnalyticsCookie()
    {
        if (!$this->has('utmz')) {
            return false;
        }

        return Jflight\GACookie\GACookie::parseString('utmz', $this->get('utmz'));
    }

    /**
     * Add utm params to order from cookies
     * @param $cookies: cookies array from Request
     */
    public function setUtmParams($cookies)
    {
        $utmCampaignCookie = $cookies->get('utm_campaign');
        if ($utmCampaignCookie) {
            $this->set('order_utm_campaign', $utmCampaignCookie);
        }

        $utmSourceCookie = $cookies->get('utm_source');
        if ($utmSourceCookie) {
            $this->set('order_utm_source', $utmSourceCookie);
        }

        $utmMediumCookie = $cookies->get('utm_medium');
        if ($utmMediumCookie) {
            $this->set('order_utm_medium', $utmMediumCookie);
        }

        return $this;
    }

    /**
     * Convert utmz property to utm_ properties
     */
    public function convertUtmz()
    {
        $cookie = $this->getAnalyticsCookie();

        if ($cookie) {
            $this->set('order_utm_medium', $cookie->medium);
            $this->set('order_utm_campaign', $cookie->campaign);
            $this->set('order_utm_source', $cookie->source);
        }
        $this->set('order_utmz', null);

        return $this;
    }

    /**
     * Delete alerts for articles in a order
     */
    public function deleteRelatedAlerts()
    {
        global $_V;

        // Ignore if user is not logged in
        if (!$_V->isLogged()) {
            return;
        }

        $alm = new AlertManager();

        $copies = $this->getCopies();
        foreach ($copies as $copy) {
            // Get alert for this user and article
            $alert = $alm->get(
                [
                    "user_id" => $_V->get("id"),
                    "article_id" => $copy->get("article_id")
                ]
            );

            // If it exists, delete it
            if ($alert) {
                $alm->delete($alert);
            }
        }
    }

    public function getCountryName(): string
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
        global $site;

        $req = [];
        $params = ['site_id' => $site->get('id')];

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
        global $site;

        $queries = ["site_id = ".$site->get('id')];
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
     * @return type
     */
    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }

        if (!isset($defaults['cart_uid'])) {
            $url = md5(uniqid('', true));
            $defaults['order_url'] = substr($url, 0, 16);
        }

        try {
            return parent::create($defaults);
        } catch (Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    /**
     * Ajouter les exemplaires d'un panier à la commande
     * @param object $order
     * @return object La nouvelle commande
     */
    public function hydrateFromCart(Order $order, Cart $cart)
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
        $this->updateFromStock($order);

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
     * Remove a copy from an order
     * @param Order $order the order to remove from
     * @param Stock $stock the copy to remove
     * @return boolean
     * @throws Exception
     */
    public function removeStock(Order $order, Stock $stock)
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
                $rm = new CFRewardManager($reward);
                $rm->updateQuantity($reward);
            }

            return true;
        } else {
            throw new Exception("L'exemplaire n'est pas dans la commande.");
        }
    }

    /**
     * Recalcule les montants de la commande en fonction des exemplaires
     * @param object $order L'objet de la commande
     */
    public function updateFromStock(Order $order)
    {
        $sm = new StockManager();

        $order_amount = 0;

        $stock = $sm->getAll(array('order_id' => $order->get('id')));

        foreach ($stock as $s) {
            $order_amount += $s->get('selling_price');
        }

        $order->set('order_amount', $order_amount);
        $order = $this->update($order);

        return $order;
    }

    /**
     * Add payment to order and flag it as executed
     *
     * @param {Order} $order : the order
     * @param {Payment} $payment : a Payment object or the mode as a String
     * @param {Int} $amount : if Payment is not provided
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

        // Substract paid amount to remaining amount to be paid
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

        // If order is paid entirely, mark as payed
        if ($remaining == 0) {
            $this->markAsPayed($order);
        }
    }

    /**
     * Mark Order as payed (and send ebooks)
     * @param object $order The order to mark as payed
     * @param string $mode Payment mode
     */
    public function markAsPayed(Order $order)
    {
        global $site;

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
        $subject = $site->get('tag').' | Commande n° '.$order->get('id').' payée';
        $message = '
            <p>Bonjour '.$order->get('firstname').' !</p>

            <p>Votre paiement pour la commande n° '.$order->get('id').' a bien été reçu.</p>

            <p>
                Commande n° '.$order->get('id').'<br>
                Montant : '.currency($order->get('amount') / 100).'<br>
                Mode de règlement : '.$order->get('payment_mode').'<br>
            </p>

            <p>
                <a href="http://'.$this->site->get('domain').'/order/'.$order->get('url').'">Suivi de la commande</a></a>
            </p>

            '.$order_downloadable.'

            <p>
                Merci pour votre confiance.
            </p>

            <p><a href="http://'.$this->site->get('domain').'/">http://'.$this->site["site_domain"].'/</a></p>
        ';

        $mailer->send($order->get('email'), $subject, $message);

        // Physical types
        $types = Biblys\Article\Type::getAllPhysicalTypes();
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
        $user = $order->get('user');
        if ($order->has('user') && !empty($ebooks)) {
            $um = new UserManager();
            $um->addToLibrary($user, array(), $ebooks);
        }

        // If no physical products, mark order as shipped without warning the user
        if (!count($physical)) {
            $order->set('order_shipping_date', date('Y-m-d H:i:s'));
        }

        // Persist
        $this->update($order);
    }

    /**
     * Mark the order as shipped and warn customer by mail
     * @param  Order  $order           the order to mark
     * @param  String $trackingNumber a tracking number
     */
    public function markAsShipped(Order $order, $trackingNumber = null)
    {
        global $site;

        $mailer = $this->getMailer();

        // Save shipping date
        $order->set('order_shipping_date', date('Y-m-d H:i:s'));

        if ($trackingNumber) {
            $order->set('order_track_number', $trackingNumber);
        }

        $message = null;

        // In-shop pickup
        if ($order->get('shipping_mode') == "magasin") {
            $subjectSuffix = 'disponible en magasin';
            $message .= '
                <p>Votre commande est disponible en magasin.</p>
                <p>Retrouvez les coordonnées et horaires d\'ouverture du magasin sur <a href="http://'.$site->get('domain').'">http://'.$site->get('domain').'/</a></p>
            ';
        }

        // Shipping
        else {
            $subjectSuffix = $site->getOpt('shipped_mail_subject');
            if (!$subjectSuffix) {
                $subjectSuffix = 'expédiée';
            }

            $shippedMessage = $site->getOpt('shipped_mail_message');
            if (!$shippedMessage) {
                $shippedMessage = 'Votre commande a été expédiée.';
            }

            $message .= '<p>'.$shippedMessage.'</p>';
            if ($trackingNumber) {
                $message .= '
                    <p>N° de suivi : '.$trackingNumber.'</p>
                    <p>
                        Vous pouvez suivre l\'envoi de votre colis sur le site de La Poste :<br>
                        http://www.coliposte.net/particulier/suivi_particulier.jsp?colispart='.$trackingNumber.'
                    </p>
                ';
            }
        }

        $subject = $site->get('tag')." | Commande n°".$order->get('id')." ".$subjectSuffix;
        $content = '
            <p>Bonjour '.$order->get('firstname').' !</p>

            '.$message.'

            <p>
                <a href="http://'.$site->get('domain').'/order/'.$order->get('url').'">Suivi de la commande</a></a>
            </p>

            <p>
                Merci pour votre confiance.
            </p>

            <p><a href=""http://'.$site->get('domain').'/>'.$site->get('title').'</a></p>
        ';
        $mailer->send($order->get('email'), $subject, $content);

        // Persist
        $this->update($order, "Marked as shipped");
    }

    public function followUp(Order $order)
    {
        global $site;

        $mailer = $this->getMailer();

        // Save followup date
        $order->set('order_followup_date', date('Y-m-d H:i:s'));

        // Send mail
        $subject = $site->get('tag')." | Commande n°".$order->get('id')." : relance";
        $content = '
            <p>Bonjour '.$order->get('firstname').',</p>

            <p>
                Vous avez passé une commande chez <a href="http://'.$site->get('domain').'/">'.$site->get('title').'</a> le '._date($order->get('created'), 'd/m/Y').' qui n\'a pas encore été payée.<br>
                Peut-être avez vous rencontré une difficulté lors du paiement ? N\'hésitez pas à <a href="http://'.$site->get('domain').'/contact/">nous contacter</a> pour obtenir de l\'aide.
            </p>

            <p>
                Vous pouvez voir le détail de votre commande et la régler ci-dessous :<br />
                <a href="http://'.$site->get('domain').'/order/'.$order->get('url').'">Suivi de la commande</a></a>
            </p>

            <p>
                Si vous n\'êtes plus intéressé par ces articles, ignorez simplement ce message.<br />
                Faute de réponse de votre part, votre commande sera annulée automatiquement d\'ici 5 jours.
            </p>

            <p><a href="http://'.$site->get('domain').'/">'.$site->get('title').'</a></p>
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
        $subject = $this->site['site_tag'] . ' | Commande n° ' . $order->get('id') . ' annulée';
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
        $mailer = new Mailer();
        $mailer->send($order->get('email'), $subject, $message);
    }
}
