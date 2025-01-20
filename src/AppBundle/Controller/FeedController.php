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


namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Framework\Controller;
use Laminas\Feed\Writer\Feed;
use Model\Post;
use Model\PostQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FeedController extends Controller
{
    /**
     * @throws PropelException
     */
    public function blogAction(
        CurrentSite $currentSite,
        CurrentUrlService $currentUrl,
        UrlGenerator $urlGenerator,
    ): Response
    {
        $posts = PostQuery::create()
            ->filterByStatus(1)
            ->orderByDate(Criteria::DESC)
            ->limit(15)
            ->find();

        $feed = new Feed();
        $feed->setTitle($currentSite->getTitle());
        $feed->setDescription("Les derniers billets du blog");
        $feed->setLink("https://{$currentSite->getSite()->getDomain()}");
        $feed->setFeedLink($currentUrl->getAbsoluteUrl(), "rss");
        $feed->setDateModified($posts->getFirst()->getDate());

        /** @var Post $post */
        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($urlGenerator->generate("post_show", ["slug" => $post->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL));
            $entry->setDateCreated($post->getDate());
            $entry->setContent($post->getContent());
            $feed->addEntry($entry);
        }

        return new Response($feed->export("rss"), 200, ["Content-Type" => "application/rss+xml"]);
    }
}