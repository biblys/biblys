<?php

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class TemplateServiceTest extends TestCase
{

    /**
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function testRenderFromString()
    {
        // given
        $config = new Config();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $request = new Request();
        $templateService = new TemplateService($config, $currentSite, $currentUser, $request);

        // when
        $response = $templateService->renderFromString(
            "Hello <b>{{ name }}</b>!",
            ["name" => "World"]
        );

        // then
        $this->assertEquals(
            "Hello <b>World</b>!",
            $response->getContent(),
        );
    }
}