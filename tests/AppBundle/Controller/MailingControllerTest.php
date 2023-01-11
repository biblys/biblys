<?php

namespace AppBundle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class MailingControllerTest extends TestCase
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testSubscribeAction()
    {
        // given
        $controller = new MailingController();
        $request = new Request();
        $request->setMethod("POST");
        $request->request->set("email", "valid-email@example.org");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/success");

        // when
        $response = $controller->subscribeAction($request, $urlGenerator);

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode()
        );
    }
}
