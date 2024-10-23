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


use Symfony\Component\HttpFoundation\JsonResponse;

$am = new AwardManager();

if ($request->getMethod() === "POST") {
    $award = $am->create(
        [
            "article_id" => $request->request->get("article_id"),
            "award_name" => $request->request->get("award_name"),
            "award_year" => $request->request->get("award_year"),
            "award_category" => $request->request->get("award_category"),
            "award_note" => $request->request->get("award_note"),
        ]
    );
    
    $awardId = $award->get("id");
    $awardHTML = '
        <li id="award_'.$awardId.'" class="newAward" style="display: none;">
            <span class="fa fa-trash-o pointer deleteAward" 
                data-award_id="'.$awardId.'"></span>
            '.$_POST["award_name"].' '.$_POST["award_year"].' 
            ('.$_POST["award_category"].')
        </li>
    ';
    $response = new JsonResponse(["award" => $awardHTML]);
}

$del = $request->query->get("del");
if ($del) {
    $award = $am->getById($request->query->get("award_id"));
    if ($award) {
        $am->delete($award);
    }

    $response = new JsonResponse(["ok" => 1]);
}

$response->send();
