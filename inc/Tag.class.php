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


class Tag extends Entity
{
    protected $prefix = 'tag';
    public $trackChange = false;
}

class TagManager extends EntityManager
{
    protected $prefix = 'tag',
        $table = 'tags',
        $object = 'Tag';


    /**
     * Search a tag by its name event inexact
     * @param  string $tagName the string to search
     * @return Tag             or null if none
     */
    public function search($tagName)
    {
        $slug = makeurl($tagName);

        $tag = $this->get(['tag_url' => $slug]);
        if ($tag) {
            return $tag;
        }

        return null;
    }

    public function preprocess($tag)
    {
        $name = $tag->get('name');

        // Make tag's slug from name
        $slug = makeurl($name);

        $tag->set('tag_url', $slug);

        return $tag;
    }

    public function validate($tag)
    {
        if (!$tag->has('name')) {
            throw new Exception('Le mot-clé doit avoir un nom.');
        }

        // Check that there is not another tag with that name
        $other = $this->get(['tag_url' => $tag->get('url'), 'tag_id' => '!= '.$tag->get('id')]);
        if ($other) {
          throw new Exception('Il existe déjà un mot-clé avec le nom '.$tag->get('name').'.');
        }

          return true;
      }
}
