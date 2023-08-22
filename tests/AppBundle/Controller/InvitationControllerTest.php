<?php

namespace AppBundle\Controller;

use Biblys\Service\TemplateService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class InvitationControllerTest extends TestCase
{

    public function testNewAction()
    {
        // given
        $controller = new InvitationController();
        $templateService = $this->createMock(TemplateService::class);
        $templateService
            ->expects($this->once())
            ->method("render")
            ->with("AppBundle:Invitation:new.html.twig")
            ->willReturn(new Response("Créer une invitation de téléchargement"));

        // when
        $response = $controller->newAction($templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            "Créer une invitation de téléchargement",
            $response->getContent()
        );
    }
}
