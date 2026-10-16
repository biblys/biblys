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

namespace AppBundle\Controller\Legacy;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

require_once __DIR__ . "/../../../setUp.php";

class ListTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS["LEGACY_REQUEST"]);
    }

    public function testPageSelectorKeepsCurrentQueryParams()
    {
        // given
        ModelFactory::createSiteOption("articles_per_page", "1");
        for ($i = 1; $i <= 11; $i++) {
            ModelFactory::createArticle(
                title: "Article $i",
                ean: "978123456789$i",
                url: "author/article-$i",
            );
        }
        $query = ["o" => "article_title_alphabetic", "p" => "3", "s" => "1", "_FORMAT" => "html"];
        $GLOBALS["LEGACY_REQUEST"] = new Request(query: $query);

        $_REQ = "";

        // when
        $content = require __DIR__ . "/../../../../controllers/common/php/_list.php";

        // then
        $this->assertStringContainsString(
            '<input type="hidden" name="o" value="article_title_alphabetic">',
            $content,
            "it should keep the sort order in the page selector form"
        );
        $this->assertStringNotContainsString(
            '<input type="hidden" name="p"',
            $content,
            "it should not duplicate the page parameter"
        );
        $this->assertStringNotContainsString(
            '<input type="hidden" name="_FORMAT"',
            $content,
            "it should not keep the format parameter"
        );
    }

    public function testListKeepsSearchQueryInSortLinks()
    {
        // given
        $GLOBALS["LEGACY_REQUEST"] = new Request(query: ["q" => "Fleuve Noir"]);
        $_REQ = null;
        $terms = null;

        // when
        $content = require __DIR__ . "/../../../../controllers/common/php/_list.php";

        // then
        $this->assertStringContainsString(
            "?q=Fleuve+Noir&amp;o=article_title_alphabetic&amp;d=0",
            $content,
            "it should keep the search query in sort links"
        );
    }

    #[DataProvider("paramsThatMustBeStringsProvider")]
    public function testListRejectsParamGivenAsArray(string $paramName)
    {
        // given
        $query = [$paramName => ["../?", "../%3f"]];
        $GLOBALS["LEGACY_REQUEST"] = new Request(query: $query);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter '$paramName' must be a string");

        // when
        require __DIR__ . "/../../../../controllers/common/php/_list.php";
    }

    public static function paramsThatMustBeStringsProvider(): array
    {
        return [["q"], ["o"], ["d"], ["p"]];
    }
}
