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


use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;

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

        /**
         * @param Entity $cycle
         * @return bool
         * @throws EntityAlreadyExistsException
         * @throws InvalidEntityException
         */
        public function validate($cycle)
        {
            if (!$cycle->has('name')) {
                throw new InvalidEntityException('Le cycle doit avoir un nom.');
            }

            // Check that there is not another cycle with that name
            $other = $this->get([
                'cycle_url' => $cycle->get('url'), 
                'cycle_id' => '!= '.$cycle->get('id')
            ]);
            if ($other) {
                throw new EntityAlreadyExistsException(
                    'Il existe déjà un cycle avec le nom '.$cycle->get('name').'.'
                );
            }

            return true;
        }
	}
