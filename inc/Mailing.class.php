<?php

class Mailing extends Entity
{
    protected $prefix = 'mailing';
    public $trackChange = false;

    /**
     * Returns true if email address is currently subscribed
     */
    public function isSubscribed()
    {
        if ($this->get('checked') && !$this->get('block')) {
            return true;
        }
        return false;
    }

    /**
     * Returns true if email has been subscribed but is not any more
     */
    public function hasUnsubscribed()
    {
        if ($this->get('block')) {
            return true;
        }
        return false;
    }
}

class MailingManager extends EntityManager
{
    protected $prefix = 'mailing',
			  $table = 'mailing',
			  $object = 'Mailing';

    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }

        return parent::create($defaults);
    }

    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        if (!isset($where['site_id'])) {
            $where['mailing`.`site_id'] = $this->site['site_id'];
        }

        return parent::getAll($where, $options, $withJoins);
    }

    /**
     * Add an email address to the newsletter
     * @param $email String  the email address to add
     * @param $force Boolean if true, email will be readded even if unsubscribed
     * @throws Exception
     */
    public function addSubscriber($email, $force = false)
    {
        // Check if email address is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse ".htmlentities($email)." n'est pas valide.");
        }

        // Check if a subscription already exists
        $mailing = $this->get(["mailing_email" => $email]);
        if ($mailing) {

            // Check if address has unsubscribed in the past
            if ($mailing->hasUnsubscribed() && !$force) {
                throw new Exception("L'adresse ".htmlentities($email)." a été désabonnée de la newsletter et ne peut être réinscrite.");
            }

            // Check if address is currently subscribed
            if ($mailing->isSubscribed()) {
                throw new Exception("L'adresse ".htmlentities($email)." est déjà inscrite à la newsletter.");
            }

            // Update subscription
            $mailing->set('mailing_block', 0)
                ->set('mailing_checked', 1);
            $this->update($mailing);

        } else {
            $mailing = $this->create([
                "mailing_email" => $email,
                "mailing_block" => 0,
                "mailing_checked" => 1
            ]);
        }

        return true;
    }

    public function removeSubscriber($email)
    {
        // Check if email address is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse ".htmlentities($email)." n'est pas valide.");
        }

        // Get subscription for this address
        $mailing = $this->get(["mailing_email" => $email]);
        if (!$mailing) {
            throw new Exception("L'adresse ".htmlentities($email)." n'est pas inscrite à la newsletter.");
        }

        $mailing->set('mailing_block', 1);
        $this->update($mailing);

        return true;
    }

    public function hasSubscriptionFor($email)
    {
        $mailing = $this->get(["mailing_email" => $email]);
        if ($mailing && $mailing->isSubscribed()) {
            return true;
        }
        return false;
    }

    public function countSubscribers()
    {
        $subs = $this->getAll(["mailing_checked" => 1, "mailing_block" => 0]);
        return count($subs);
    }
}
