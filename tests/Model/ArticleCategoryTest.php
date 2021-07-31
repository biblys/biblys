<?php

namespace Model;

use Biblys\Test\Factory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ArticleCategoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testAddingSiteIdonSave()
    {
        // given
        $user = new ArticleCategory();
        $user->setName("Science Fiction");

        // when
        $saved = $user->save();

        // then
        $this->assertEquals(
            1,
            $user->getSiteId(),
            "it should have added current site id"
        );
    }
}