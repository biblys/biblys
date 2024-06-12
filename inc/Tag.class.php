<?php

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
