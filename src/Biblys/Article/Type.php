<?php

namespace Biblys\Article;

class Type
{
    private $_id,
        $_name,
        $_slug,
        $_tax,
        $_isDownloadable = false,
        $_isPhysical = false;

    public function __construct()
    {
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
        $this->setSlug(makeurl($name));
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setSlug($slug)
    {
        $this->_slug = $slug;
    }

    public function getSlug()
    {
        return $this->_slug;
    }

    public function setTax($tax)
    {
        $this->_tax = $tax;
    }

    public function getTax()
    {
        return $this->_tax;
    }

    public function setDownloadable($isDownloadable)
    {
        $this->_isDownloadable = $isDownloadable;
    }

    public function isDownloadable()
    {
        return $this->_isDownloadable;
    }

    public function setPhysical($isPhysical)
    {
        $this->_isPhysical = $isPhysical;
    }

    public function isPhysical()
    {
        return $this->_isPhysical;
    }

    /**
     * Return all types
     * @return Type[] an array containing all Types
     */
    public static function getAll(): array
    {
        $types = [];

        $livre = new Type();
        $livre->setId(1);
        $livre->setName('Livre papier');
        $livre->setTax('BOOK');
        $livre->setPhysical(true);
        $livre->setDownloadable(false);
        $types[] = $livre;

        $type = new Type();
        $type->setId(2);
        $type->setName('Livre numérique');
        $type->setTax('EBOOK');
        $type->setPhysical(false);
        $type->setDownloadable(true);
        $types[] = $type;

        $type = new Type();
        $type->setId(3);
        $type->setName('CD');
        $type->setTax('CD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(4);
        $type->setName('DVD');
        $type->setTax('DVD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(5);
        $type->setName('Jeu');
        $type->setTax('STANDARD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(6);
        $type->setName('Goodies');
        $type->setTax('STANDARD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(7);
        $type->setName('Drouille');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(8);
        $type->setName('Lot');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(9);
        $type->setName('BD');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(10);
        $type->setName('Abonnement');
        $type->setTax('STANDARD');
        $type->setPhysical(false);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(11);
        $type->setName('Livre audio');
        $type->setTax('EAUDIOBOOK');
        $type->setPhysical(false);
        $type->setDownloadable(true);
        $types[] = $type;

        $type = new Type();
        $type->setId(12);
        $type->setName('Carte à code');
        $type->setTax('EBOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(13);
        $type->setName('Périodique');
        $type->setTax('PERIODICAL');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(14);
        $type->setName('Jeu de rôle');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new Type();
        $type->setId(15);
        $type->setName('Carterie');
        $type->setTax('STANDARD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        return $types;
    }

    /**
     * Get only physical types
     * @return Type[] a filtered array of Types
     */
    public static function getAllPhysicalTypes(): array
    {
        $types = self::getAll();

        return array_filter($types, function($type) {
            if ($type->isPhysical()) {
                return true;
            }
        });
    }

    /**
     * Get only physical types
     * @return [array] a filtered array of Types
     */
    public static function getAllDownloadableTypes()
    {
        $types = self::getAll();

        return array_filter($types, function($type) {
            if ($type->isDownloadable()) {
                return true;
            }
        });
    }

    public static function getById($id)
    {
        $types = self::getAll();

        foreach ($types as $type) {
            if ($type->getId() == $id) {
                return $type;
            }
        }

        return false;
    }

    public static function getBySlug($slug)
    {
        $types = self::getAll();

        foreach ($types as $type) {
            if ($type->getSlug() === $slug) {
                return $type;
            }
        }

        return false;
    }

    public static function getOptions($selected_id = 0)
    {
        $types = self::getAll();

        return array_map(function($type) use($selected_id) {
            return '<option value="'.$type->getId().'"'.($type->getId() == $selected_id ? ' selected' : null).'>'.$type->getName().'</option>';
        }, $types);
    }
}
