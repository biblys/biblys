<?php

namespace Model;

use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ArticleCategoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testAddingSlugOnSave()
    {
        // given
        $articleCategory = new ArticleCategory();
        $articleCategory->setSiteId(1);
        $articleCategory->setName("Fruits & légumes");

        // when
        $articleCategory->save();

        // then
        $this->assertEquals(
            "fruits-legumes",
            $articleCategory->getSlug(),
            "it should have added slug"
        );
    }
}