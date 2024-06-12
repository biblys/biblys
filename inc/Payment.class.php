<?php

class Payment extends Entity
{
	protected $prefix = 'payment';
    public $trackChange = false;

    /**
     * Test if payment has been made
     * @return {boolean} true if it has
     */
    public function isExecuted()
    {
        return $this->has('executed');
    }

    /**
     * Test if payment has been made
     * @return {boolean} true if it has
     */
    public function setExecuted($date = null)
    {
        if (!$date) {
            $date = new DateTime();
        }

        if (!$date instanceof DateTime) {
            throw new Exception("Date must be an instance of DateFormat");
        }

        $this->set('payment_executed', $date->format("Y-m-d H:i:s"));
    }
}

class PaymentManager extends EntityManager
{
	protected $prefix = 'payment',
			  $table = 'payments',
			  $object = 'Payment';

    public function create(array $defaults = array())
    {
      if (!isset($defaults['site_id'])) {
          $defaults['site_id'] = $this->site['site_id'];
      }

      return parent::create($defaults);
    }

    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        $where['payments`.`site_id'] = $this->site['site_id'];

        return parent::getAll($where, $options);
    }

}
