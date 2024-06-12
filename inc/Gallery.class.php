<?php

class Gallery extends Entity
{
    protected $prefix = 'gallery';
    public $trackChange = false;

    public function getMedias()
    {
        $mfm = new MediaFileManager();
        return $mfm->getAll(['media_dir' => $this->get('media_dir')]);
    }
}

class GalleryManager extends EntityManager
{
    protected $prefix = 'gallery',
        $table = 'galleries',
        $object = 'Gallery';

    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }
        return parent::create($defaults);
    }

    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        $where['galleries`.`site_id'] = $this->site['site_id'];

        return parent::getAll($where, $options);
    }
}

