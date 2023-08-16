<?php

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
        $this->assertEquals(1024, strlen($article->get("article_keywords")));
    }
}
