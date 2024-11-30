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
use Model\Base\BookCollection as BaseBookCollection;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'collections' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class BookCollection extends BaseBookCollection
{
    /**
     * @throws PropelException
     * @throws InvalidEntityException
     * @throws EntityAlreadyExistsException
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
        if (!$this->getName()) {
            throw new InvalidEntityException("La collection doit avoir un nom.");
        }

        $publisher = $this->getPublisher();
        if (!$publisher) {
            throw new InvalidEntityException("La collection doit être associée à un éditeur.");
        }

        $this->setPublisherName($publisher->getName());

        $slugService = new SlugService();
        $slug = $slugService->createForBookCollection($this->getName(), $publisher->getName());
        $this->setUrl($slug);

        $otherCollectionWithTheSameName = BookCollectionQuery::create()
            ->filterByPublisherId($publisher->getId())
            ->filterByName($this->getName())
            ->filterById($this->getId(), Criteria::NOT_EQUAL)
            ->findOne();
        if ($otherCollectionWithTheSameName) {
            throw new EntityAlreadyExistsException(
                sprintf(
                    "Il existe déjà une collection avec le nom « %s » (n° %s) chez l'éditeur %s (slug: %s).",
                    $otherCollectionWithTheSameName->getName(),
                    $otherCollectionWithTheSameName->getId(),
                    $publisher->getName(),
                    $otherCollectionWithTheSameName->getUrl(),
                )
            );
        }

        if ($this->getNoosfereId()) {
            $otherCollectionWithTheSameNoosfereId = BookCollectionQuery::create()
                ->filterByNoosfereId($this->getNoosfereId())
                ->filterById($this->getId(), Criteria::NOT_EQUAL)
                ->findOne();
            if ($otherCollectionWithTheSameNoosfereId) {
                throw new EntityAlreadyExistsException(
                    sprintf(
                        "Il existe déjà une collection avec l'identifiant noosfere %s: Collection n° %s (slug: %s).",
                        $this->getNoosfereId(),
                        $otherCollectionWithTheSameNoosfereId->getId(),
                        $otherCollectionWithTheSameNoosfereId->getUrl(),
                    )
                );
            }
        }

        return parent::preSave($con);
    }
}
