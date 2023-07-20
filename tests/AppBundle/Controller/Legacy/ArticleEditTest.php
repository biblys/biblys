<?php

namespace AppBundle\Controller\Legacy;

use AppBundle\Controller\LegacyController;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";


class ArticleEditTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDisplayingArticleEditPage()
    {
        // given
        $article = ModelFactory::createArticle();

        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->query->set("page", "article_edit");
        $request->query->set("id", $article->getId());

        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = Config::load();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $response = $legacyController->defaultAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator,
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "it should respond with status code 200"
        );
    }
}
