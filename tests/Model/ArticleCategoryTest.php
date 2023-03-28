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
        $user = new ArticleCategory();
        $user->setName("Fruits & légumes");

        // when
        $user->save();

        // then
        $this->assertEquals(
            "fruits-legumes",
            $user->getSlug(),
            "it should have added slug"
        );
    }
}