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


class Site extends Entity
{
    protected $prefix = 'site',
        $options = null;

    public function getOpt($key)
    {
        $om = new OptionManager();
        
        // Load and cache all site options
        if ($this->options === null) {
            $this->options = $om->getAll([
                'site_id' => $this->get('id')
            ]);
        }

        // Get options for key
        $options = array_values(array_filter($this->options, function($option) use($key) {
            return $option->get('key') === $key;
        }));

        // If option for key does not exist, return false 
        if (count($options) === 0) {
            return false;
        }

        // Else return option value
        $option = $options[0];
        return $option->get('value');
    }

    public function setOpt($key, $value)
    {
        $om = new OptionManager();

        $option = $om->get(['site_id' => $this->get('id'), 'option_key' => $key]);

        // If option already exists, update it
        if ($option) {
            $option->set('option_value', $value);
            $om->update($option);
            return $this;
        }

        // Else, create a new one
        $option = $om->create(['site_id' => $this->get('id'), 'option_key' => $key, 'option_value' => $value]);

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

    public function
    allowsPublisherWithId($publisherId): bool
    {
        $publisherFilter = $this->getOpt('publisher_filter');
        if (!$publisherFilter) {
            return true;
        }

        $allowedPublisherIds = explode(",", $publisherFilter);
        if (in_array($publisherId, $allowedPublisherIds)) {
            return true;
        }

        return false;
    }

}

class SiteManager extends EntityManager
{
    protected $prefix = 'site',
			  $table = 'sites',
			  $object = 'Site';

}
