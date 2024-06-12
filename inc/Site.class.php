<?php

class Site extends Entity
{
    protected $prefix = 'site',
        $options = null;

    public function getOpt($key)
    {
        $om = new OptionManager();

        $option = $om->get(['site_id' => $this->get('id'), 'option_key' => $key]);

        if ($option) {
            return $option->get('value');
        }

        return false;
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
