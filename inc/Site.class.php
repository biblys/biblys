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


use Model\Option;
use Model\OptionQuery;

class Site extends Entity
{
    protected $prefix = 'site',
        $options = null;

    public function getOpt($key)
    {
        // Load and cache all site options
        if ($this->options === null) {
            $this->options = OptionQuery::create()->find()->getArrayCopy();
        }

        foreach ($this->options as $option) {
            if ($option->getKey() === $key) {
                return $option->getValue();
            }
        }

        return false;
    }

    public function setOpt($key, $value)
    {
        $option = OptionQuery::create()->filterByKey($key)->findOne();

        // If option already exists, update it
        if ($option) {
            $option->setValue($value);
            $option->save();
            return $this;
        }

        // Else, create a new one
        $option = new Option();
        $option->setKey($key);
        $option->setValue($value);
        $option->save();

        // Reset cached options
        $this->options = null;

        return $this;
    }

    public function getNameForCheckPayment()
    {
        $name = $this->getOpt('name_for_check_payment');
        if ($name) {
            return $name;
        }

        return $this->get('title');
    }
}

class SiteManager extends EntityManager
{
    protected $prefix = 'site',
			  $table = 'sites',
			  $object = 'Site';

}
