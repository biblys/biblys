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

use Biblys\Exception\GleephAPIException;
use Biblys\Gleeph\GleephAPI;
use Model\Article;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;

class GleephService
{
    private GleephAPI $api;
    private CurrentSite $currentSiteService;
    private LoggerService $loggerService;

    public function __construct(
        GleephAPI $api,
        CurrentSite $currentSiteService,
        LoggerService $loggerService
    )
    {
        $this->api = $api;
        $this->currentSiteService = $currentSiteService;
        $this->loggerService = $loggerService;
    }

    /**
     * @return Article[]
     * @throws ClientExceptionInterface
     * @throws PropelException
     */
    public function getSimilarArticlesByEan(string $ean, int $numberOfSuggestions = 3): array
    {
        try {
            $eans = $this->api->getSimilarBooksByEan($ean, $numberOfSuggestions);
        } catch (GleephAPIException $exception) {
            $this->loggerService->log(
                logger: "gleeph",
                level: "error",
                message: "Call to Gleeph API failed",
                context: [$exception->getMessage()]
            );
            return [];
        }

        $similarArticles = ArticleQuery::create()
            ->filterForCurrentSite($this->currentSiteService)
            ->findByEan($eans)
            ->getData();

        $similarArticlesCount = count($similarArticles);
        $this->loggerService->log(
            logger: "gleeph",
            level: "info",
            message: "Found $similarArticlesCount similar article(s) for EAN $ean",
            context: $eans,
        );

        return $similarArticles;
    }
}