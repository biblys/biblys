<?php

namespace AppBundle\Controller\Legacy;

use Biblys\Data\ArticleType;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";


class ArticleEditTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testLemoninkFieldIsDisplayed()
    {
        // given
        $controller = require __DIR__."/../../../../controllers/common/php/article_edit.php";

        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        $request = new Request();
        $request->query->set("id", $article->getId());

        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(true);
        $currentUser->shouldReceive("getCurrentRight")->andReturn(null);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn("null");
        $currentUser->shouldReceive("authPublisher")->andReturn(true);

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("url");
        $config = new Config(["lemonink" => ["api_key" => "abcd1234"]]);

        // when
        $response = $controller($request, $currentUser, $currentSite, $urlGenerator, $config);

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            "Identifiant LemonInk :",
            $response->getContent(),
            "displays the LemonInk field"
        );
    }
}
