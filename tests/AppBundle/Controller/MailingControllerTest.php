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
use Biblys\Service\MailingList\Contact;
use Biblys\Service\MailingList\Exception\InvalidConfigurationException;
use Biblys\Service\MailingList\MailingListInterface;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\TemplateService;
use Biblys\Test\RequestFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @throws PropelException
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
        $config = new Config();
        $mailingList = $this->createMock(MailingListInterface::class);
        $mailingList->expects($this->once())
            ->method("addContact")
            ->with("valid-email@example.org", true);
        $mailingListService = $this->createMock(MailingListService::class);
        $mailingListService->expects($this->once())->method("isConfigured")->willReturn(true);
        $mailingListService->expects($this->once())
            ->method("getMailingList")
            ->with()
            ->willReturn($mailingList);
        $flashBag = Mockery::mock(FlashBagInterface::class);
        $flashBag->shouldReceive("add")->with(
            "success",
            "Votre inscription avec l'adresse valid-email@example.org a bien été prise en compte."
        );
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->andReturn($flashBag);

        // when
        $response = $controller->subscribeAction(
            $request,
            $urlGenerator,
            $config,
            $mailingListService,
            $session,
        );

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode()
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testUnsubscribeAction()
    {
        // given
        $controller = new MailingController();
        $request = new Request();
        $request->setMethod("POST");
        $request->request->set("email", "valid-email@example.org");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/success");
        $mailingList = $this->createMock(MailingListInterface::class);
        $mailingList->expects($this->once())
            ->method("removeContact")
            ->with("valid-email@example.org");
        $mailingListService = $this->createMock(MailingListService::class);
        $mailingListService->expects($this->once())->method("isConfigured")->willReturn(true);
        $mailingListService->expects($this->once())
            ->method("getMailingList")
            ->with()
            ->willReturn($mailingList);
        $flashBag = Mockery::mock(FlashBagInterface::class);
        $flashBag->shouldReceive("add")->with(
            "success",
            "Votre désinscription avec l'adresse valid-email@example.org a bien été prise en compte."
        );
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->andReturn($flashBag);

        // when
        $response = $controller->unsubscribeAction(
            $request,
            $urlGenerator,
            $mailingListService,
            $session,
        );

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode()
        );
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigurationException
     */
    public function testContacts()
    {
        // given
        $controller = new MailingController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $mailingList = $this->createMock(MailingListInterface::class);
        $mailingList->expects($this->exactly(1))->method("getContacts")->willReturn([
            new Contact("edmond@furax.fr", "2023-01-25"),
            new Contact("malvina@carjanou.fr", "2023-01-31"),
        ]);
        $mailingList->expects($this->once())->method("getContactCount")->willReturn(2);
        $mailingListService = $this->createMock(MailingListService::class);
        $mailingListService->expects($this->once())->method("isConfigured")->willReturn(true);
        $mailingListService->expects($this->once())
            ->method("getMailingList")
            ->with()
            ->willReturn($mailingList);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->once()
            ->with("AppBundle:Mailing:contacts.html.twig", Mockery::andAnyOtherArgs())
            ->andReturn(new Response("edmond@furax.fr"));

        // when
        $response = $controller->contacts(
            $currentUser,
            $mailingListService,
            $request,
            $templateService,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("edmond@furax.fr", $response->getContent());
    }
}
