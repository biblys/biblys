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


require_once __DIR__."/setUp.php";

use Biblys\Test\EntityFactory;
use PHPUnit\Framework\TestCase;

class ArticleManagerTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testRefreshMetadata()
    {
        // given
        $am = new ArticleManager();
        $article = EntityFactory::createArticle([
            "article_title" => "L'Animalie",
        ]);

        // when
        $article = $am->refreshMetadata($article);

        // then
        $this->assertStringContainsString("L'Animalie", $article->get("article_keywords"));
    }

    /**
     * @throws Exception
     */
    public function testRefreshMetadataWithSearchTermsTooLong()
    {
        // given
        $title = "Un titre de livre vraiment très long";
        $veryLongTitle = str_repeat($title, 100);
        $am = new ArticleManager();
        $article = EntityFactory::createArticle(title: $title);
        $article->set("article_title", $veryLongTitle);

        // when
        $article = $am->refreshMetadata($article);

        // then
        $this->assertEquals(1016, strlen($article->get("article_keywords")));
    }
}
