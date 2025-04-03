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
        return parent::getAll($where, $options);
    }

}
