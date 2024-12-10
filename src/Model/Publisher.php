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
use Biblys\Service\StringService;
use Model\Base\Publisher as BasePublisher;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'publishers' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Publisher extends BasePublisher
{
    /**
     * @throws InvalidEntityException
     * @throws EntityAlreadyExistsException
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
        if ($this->getName() === null) {
            throw new InvalidEntityException("L'éditeur doit avoir un nom.");
        }

        $name = new StringService($this->getName());

        $this->setNameAlphabetic($name->alphabetize()->get());
        $this->setUrl((new SlugService())->slugify($this->getName()));

        $publisherQuery = PublisherQuery::create()
            ->filterByUrl($this->getUrl());

        if (!$this->isNew()) {
            $publisherQuery->filterById($this->getId(), Criteria::NOT_EQUAL);
        }

        $publisherExists = $publisherQuery->exists();
        if ($publisherExists) {
            throw new EntityAlreadyExistsException(
                "Il existe déjà un éditeur avec le nom " . $this->getName() . "."
            );
        }

        return true;
    }
}
