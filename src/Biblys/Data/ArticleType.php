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


namespace Biblys\Data;

use Biblys\Service\Slug\SlugService;

class ArticleType
{
    private int $_id;
    private string $_name;
    private string $_slug;
    private string $_tax;
    private bool $_isDownloadable = false;
    private bool $_isPhysical = false;
    private bool $_isService = false;

    public const BOOK = 1;
    public const EBOOK = 2;
    public const CD = 3;
    public const DVD = 4;
    public const GAME = 5;
    public const GOODIES = 6;
    public const DROUILLE = 7;

    public const BUNDLE = 8;
    public const COMICS = 9;
    public const SUBSCRIPTION = 10;
    public const EAUDIOBOOK = 11;
    public const CODE_CARD = 12;
    public const PERIODICAL = 13;
    public const ROLEPLAY = 14;
    public const CARDS = 15;




    public function setId($id): void
    {
        $this->_id = $id;
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function setName($name): void
    {
        $this->_name = $name;
        $slugService = new SlugService();
        $slug = $slugService->slugify($name);
        $this->setSlug($slug);
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function setSlug($slug): void
    {
        $this->_slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->_slug;
    }

    public function setTax($tax): void
    {
        $this->_tax = $tax;
    }

    public function getTax(): string
    {
        return $this->_tax;
    }

    public function setDownloadable($isDownloadable): void
    {
        $this->_isDownloadable = $isDownloadable;
    }

    public function isDownloadable(): bool
    {
        return $this->_isDownloadable;
    }

    public function setPhysical($isPhysical): void
    {
        $this->_isPhysical = $isPhysical;
    }

    public function isPhysical(): bool
    {
        return $this->_isPhysical;
    }

    public function isService(): bool
    {
        return $this->_isService;
    }

    public function setIsService(bool $isService): void
    {
        $this->_isService = $isService;
    }

    /**
     * Return all types
     * @return ArticleType[] an array containing all Types
     */
    public static function getAll(): array
    {
        $types = [];

        $livre = new ArticleType();
        $livre->setId(self::BOOK);
        $livre->setName('Livre papier');
        $livre->setTax('BOOK');
        $livre->setPhysical(true);
        $livre->setDownloadable(false);
        $types[] = $livre;

        $type = new ArticleType();
        $type->setId(self::EBOOK);
        $type->setName('Livre numérique');
        $type->setTax('EBOOK');
        $type->setPhysical(false);
        $type->setDownloadable(true);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::CD);
        $type->setName('CD');
        $type->setTax('CD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::DVD);
        $type->setName('DVD');
        $type->setTax('DVD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::GAME);
        $type->setName('Jeu');
        $type->setTax('STANDARD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::GOODIES);
        $type->setName('Goodies');
        $type->setTax('STANDARD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::DROUILLE);
        $type->setName('Drouille');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::BUNDLE);
        $type->setName('Lot');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::COMICS);
        $type->setName('BD');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::SUBSCRIPTION);
        $type->setName('Abonnement');
        $type->setTax('STANDARD');
        $type->setPhysical(false);
        $type->setDownloadable(false);
        $type->setIsService(true);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::EAUDIOBOOK);
        $type->setName('Livre audio');
        $type->setTax('EAUDIOBOOK');
        $type->setPhysical(false);
        $type->setDownloadable(true);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::CODE_CARD);
        $type->setName('Carte à code');
        $type->setTax('EBOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::PERIODICAL);
        $type->setName('Périodique');
        $type->setTax('PERIODICAL');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::ROLEPLAY);
        $type->setName('Jeu de rôle');
        $type->setTax('BOOK');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        $type = new ArticleType();
        $type->setId(self::CARDS);
        $type->setName('Carterie');
        $type->setTax('STANDARD');
        $type->setPhysical(true);
        $type->setDownloadable(false);
        $types[] = $type;

        return $types;
    }

    /**
     * Get only physical types
     * @return ArticleType[] a filtered array of Types
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
     * @return array a filtered array of Types
     */
    public static function getAllDownloadableTypes(): array
    {
        $types = self::getAll();

        return array_filter($types, function($type) {
            if ($type->isDownloadable()) {
                return true;
            }
        });
    }

    public static function getById($id): bool|ArticleType
    {
        $types = self::getAll();

        foreach ($types as $type) {
            if ($type->getId() == $id) {
                return $type;
            }
        }

        return false;
    }

    public static function getBySlug($slug): bool|ArticleType
    {
        $types = self::getAll();

        foreach ($types as $type) {
            if ($type->getSlug() === $slug) {
                return $type;
            }
        }

        return false;
    }

    public static function getOptions($selected_id = 0): array
    {
        $types = self::getAll();

        return array_map(function($type) use($selected_id) {
            return '<option value="'.$type->getId().'"'.($type->getId() == $selected_id ? ' selected' : null).'>'.$type->getName().'</option>';
        }, $types);
    }
}
