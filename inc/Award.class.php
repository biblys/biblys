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


class Award extends Entity
{
    protected $prefix = 'award';
    public $trackChange = false;

    public function __construct($data, $withJoins = true)
    {
        /* JOINS */

        if ($withJoins) {

            // Article (OneToMany)
            $am = new ArticleManager();
            if (isset($data['article_id'])) {
                $data['article'] = $am->getById($data['article_id']);
            }
        }

        parent::__construct($data);
    }
}

class AwardManager extends EntityManager
{
    protected $prefix = 'award',
        $table = 'awards',
        $object = 'Award';

    public function validate($award)
    {
        if (!$award->has('name')) {
            throw new Exception('Le prix littéraire doit avoir un nom.');
        }

        return true;
    }
}
