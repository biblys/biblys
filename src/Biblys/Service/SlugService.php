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


namespace Biblys\Service;

use Model\BookCollection;
use Model\Publisher;

/**
 * @deprecated
 */
class SlugService
{
    /**
     * @deprecated
     */
    public function slugify(string $input): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.74.0",
            "Biblys\Service\SlugService is deprecated, use Biblys\Service\Slug\SlugService instead",
        );

        $slugService = new Slug\SlugService();
        return $slugService->slugify($input);
    }
}