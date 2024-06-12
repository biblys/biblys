<?php

    class Cycle extends Entity
	{
		protected $prefix = 'cycle';
    }
    
    class CycleManager extends EntityManager
	{
		protected $prefix = 'cycle',
				  $table = 'cycles',
				  $object = 'Cycle';
        
        public function preprocess($cycle)
        {
            $name = $cycle->get('name');

            // Make cycle's slug from name
            $slug = makeurl($name);

            $cycle->set('cycle_url', $slug);

            return $cycle;
        }

        public function validate($cycle)
        {
            if (!$cycle->has('name')) {
                throw new Exception('Le cycle doit avoir un nom.');
            }

            // Check that there is not another cycle with that name
            $other = $this->get([
                'cycle_url' => $cycle->get('url'), 
                'cycle_id' => '!= '.$cycle->get('id')
            ]);
            if ($other) {
                throw new Exception('Il existe déjà un cycle avec le nom '.$cycle->get('name').'.');
            }

            return true;
        }
	}
