<?php /** @noinspection SpellCheckingInspection */
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


use Biblys\Exception\EntityAlreadyExistsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @throws Exception
 */
return function (Request $request): Response
{
    if ($_POST) {
        if (empty($_POST["collection_publisher_id"])) {
            throw new BadRequestHttpException("Éditeur non défini !");
        } else {
            $collectionName = $request->request->get('collection_name');
            $publisherId = $request->request->get('collection_publisher_id');

            $cm = new CollectionManager();
            try {
                $collection = $cm->create([
                    'collection_name' => $collectionName,
                    'publisher_id' => $publisherId,
                ]);
            } catch (EntityAlreadyExistsException $exception) {
                throw new ConflictHttpException($exception->getMessage());
            }
            $data = array_merge([
                'collection_id' => $collection->get('id'),
            ], $_POST);
            return new JsonResponse($data);
        }
    }

    throw new BadRequestHttpException();
};
