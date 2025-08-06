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


namespace ApiBundle\Controller;

use Framework\Controller;
use Model\PublisherQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublisherController extends Controller
{
    public function getAction(int $id): JsonResponse
    {
        $publisher = PublisherQuery::create()->findPk($id);
        if (!$publisher) {
            throw new NotFoundHttpException("Publisher with ID $id not found.");
        }

        return new JsonResponse([
            "id" => $publisher->getId(),
            "name" => $publisher->getName(),
        ]);
    }
}