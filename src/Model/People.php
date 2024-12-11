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


namespace Model;

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Biblys\Service\Slug\SlugService;
use Model\Base\People as BasePeople;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'people' table.
 *
 * Intervenants
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class People extends BasePeople
{
    /**
     * @deprecated People->getName is deprecated, use People->getFullName instead.
     */
    public function getName(): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "3.1.1",
            "People->getName is deprecated, use People->getFullName instead."
        );

        return $this->getFullName();
    }

    /**
     * @deprecated People->getAlpha is deprecated, use People->getAlphabeticalName instead.
     */
    public function getAlpha(): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "3.1.1",
            "People->getAlpha is deprecated, use People->getAlphabeticalName instead."
        );

        return $this->getFullName();
    }

    public function getFullName(): string
    {
        return trim($this->getFirstName()." ".$this->getLastName());
    }

    public function getAlphabeticalName(): string
    {
        return trim($this->getLastName()." ".$this->getFirstName());
    }

    /**
     * @throws PropelException
     */
    public function getArticles(): Collection
    {
        return ArticleQuery::create()
            ->joinWithRole()
            ->useRoleQuery()
                ->filterByPeople($this)
            ->endUse()
            ->find();
    }

    /**
     * @throws InvalidEntityException
     * @throws EntityAlreadyExistsException
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
        if ($this->getLastName() === null) {
            throw new InvalidEntityException("Le contributeur doit avoir un nom.");
        }

        if ($this->getSite() && filter_var($this->getSite(), FILTER_VALIDATE_URL) === false) {
            throw new InvalidEntityException("L'adresse du site web doit être une URL valide.");
        }

        $slugService = new SlugService();
        $this->setUrl($slugService->slugify($this->getFullName()));

        /** Writing deprecated columns for backward compatibility */
        $this->setName($this->getFullName());
        $this->setAlpha($this->getAlphabeticalName());

        $peopleQuery = PeopleQuery::create()->filterByUrl($this->getUrl());
        if (!$this->isNew()) {
            $peopleQuery->filterById($this->getId(), Criteria::NOT_EQUAL);
        }
        $otherContributorWithTheSameName = $peopleQuery->exists();
        if ($otherContributorWithTheSameName) {
            throw new EntityAlreadyExistsException(
                "Il existe déjà un contributeur avec le nom {$this->getFullName()}."
            );
        }

        return true;
    }
}
