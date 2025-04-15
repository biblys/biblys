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

use Symfony\Component\HttpFoundation\Request;

class QueryParamsService extends ParamsService
{
    public function __construct(private readonly Request $request)
    {
        $rawParams = $this->request->query->all();

        $parametersToIgnore = ["fbclid", "ttclid", "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];

        foreach ($parametersToIgnore as $param) {
            if (isset($rawParams[$param])) {
                unset($rawParams[$param]);
            }
        }

        parent::__construct($rawParams);
    }
}