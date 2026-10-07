<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{
    /**
     * @throws PropelException
     */
    public function searchAction(
        CurrentUser        $currentUser,
        QueryParamsService $queryParams,
    ): JsonResponse
    {
        $currentUser->authAdmin();

        $queryParams->parse(["term" => ["type" => "string", "default" => ""]]);

        $customerRepository = new CustomerRepository();
        $customers = $customerRepository->search($queryParams->get("term"));

        $results = array_map(fn($customer) => [
            "label" => sprintf(
                "%s, %s (%s)",
                $customer->getLastName(),
                $customer->getFirstName(),
                $customer->getEmail(),
            ),
            "value" => $customer->getId(),
        ], $customers);

        return new JsonResponse(["results" => $results]);
    }
}
