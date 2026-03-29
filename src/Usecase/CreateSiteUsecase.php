<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace Usecase;

use Biblys\Service\Slug\SlugService;
use Model\Site;
use Model\SiteQuery;
use Propel\Runtime\Exception\PropelException;

class CreateSiteUsecase
{
    public function __construct() {}

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function execute(string $siteName, string $baseUrl, string $contactEmail): Site
    {
        $existingSite = SiteQuery::create()->findOne();
        if ($existingSite !== null) {
            throw new BusinessRuleException("Un site est déjà configuré : {$existingSite->getTitle()}");
        }

        $slugService = new SlugService();
        $slug = $slugService->slugify($siteName);

        $site = new Site();
        $site->setName($slug);
        $site->setTitle($siteName);
        $site->setDomain($baseUrl);
        $site->setContact($contactEmail);
        $site->save();

        return $site;
    }
}