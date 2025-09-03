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


namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class StatsControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function testSalesByArticleAction(): void
    {
        // given
        $article = ModelFactory::createArticle(title: "Sold article");
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime());
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime());
        $controller = new StatsController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->salesByArticleAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Sold article", $response->getContent());
        $this->assertStringContainsString("24,68", $response->getContent());
    }


    /**
     * @throws Exception
     */
    public function testMatomo()
    {
        // given
        $controller = new StatsController();
        $config = new Config([
            "matomo" => [
                "domain" => "example.org",
                "login" => "login",
                "md5pass" => "password",
            ]
        ]);

        // when
        $response = $controller->matomo($config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "https://example.org/index.php?module=Login&action=logme&login=login&password=password",
            $response->getTargetUrl(),
        );
    }

    /**
     * @throws Exception
     */
    public function testUmami()
    {
        // given
        $controller = new StatsController();
        $config = new Config(["umami" => ["share_url" => "https://example.org/umami"]]);

        // when
        $response = $controller->umami($config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://example.org/umami", $response->getTargetUrl());
    }
}
