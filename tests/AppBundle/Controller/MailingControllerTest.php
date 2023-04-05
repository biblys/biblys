<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\MailingList\Contact;
use Biblys\Service\MailingList\MailingListInterface;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Test\RequestFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
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
        $mailingListService->expects($this->once())
            ->method("getMailingList")
            ->with()
            ->willReturn($mailingList);

        // when
        $response = $controller->subscribeAction($request, $urlGenerator, $config, $mailingListService);

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
        $mailingListService->expects($this->once())
            ->method("getMailingList")
            ->with()
            ->willReturn($mailingList);

        // when
        $response = $controller->unsubscribeAction(
            $request,
            $urlGenerator,
            $mailingListService
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
    public function testContacts()
    {
        // given
        $controller = new MailingController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $mailingList = $this->createMock(MailingListInterface::class);
        $mailingList->expects($this->once())->method("getContacts")->willReturn([
            new Contact("edmond@furax.fr", "2023-01-25"),
            new Contact("malvina@carjanou.fr", "2023-01-31"),
        ]);
        $mailingList->expects($this->once())->method("getContactCount")->willReturn(2);
        $mailingListService = $this->createMock(MailingListService::class);
        $mailingListService->expects($this->once())
            ->method("getMailingList")
            ->with()
            ->willReturn($mailingList);

        // when
        $response = $controller->contacts($request, $mailingListService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("2 contacts", $response->getContent());
        $this->assertStringContainsString("edmond@furax.fr", $response->getContent());
        $this->assertStringContainsString("2023-01-25", $response->getContent());
        $this->assertStringContainsString("malvina@carjanou.fr", $response->getContent());
        $this->assertStringContainsString("2023-01-31", $response->getContent());
        $this->assertStringContainsString(
            "[[&quot;edmond@furax.fr&quot;],[&quot;malvina@carjanou.fr&quot;]]",
            $response->getContent(),
            "prepares export data"
        );
    }
}
