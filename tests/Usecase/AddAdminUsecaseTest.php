<?php

namespace Usecase;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Mailer;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\RightQuery;
use Model\UserQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;

class AddAdminUsecaseTest extends TestCase
{
    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateActionForExistingUser(): void
    {
        // given
        $site = ModelFactory::createSite();

        ModelFactory::createAdminUser(site: $site, email: "already-admin@example.org");
        $user = ModelFactory::createUser(site: $site, email: "new-admin@example.org");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->with(
            "success",
            "Un accès administrateur a été ajouté pour le compte new-admin@example.org."
        );
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->twice();
        $templateService = Helpers::getTemplateService();

        $usecase = new AddAdminUsecase($currentSite, $flashMessages, $urlGenerator, $templateService, $mailer);

        // when
        $usecase->execute("new-admin@example.org");

        // then
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateActionForNewUser(): void
    {
        // given
        $site = ModelFactory::createSite();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->twice();
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->twice();
        $templateService = Helpers::getTemplateService();

        $usecase = new AddAdminUsecase($currentSite, $flashMessages, $urlGenerator, $templateService, $mailer);

        // when
        $usecase->execute("new-user@example.org");

        // then
        $user = UserQuery::create()->findOneByEmail("new-user@example.org");
        $this->assertNotNull($user);
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateActionFailsIfAdminAlreadyExists(): void
    {
        // given
        $site = ModelFactory::createSite();

        $user = ModelFactory::createAdminUser(site: $site, email: "already-admin@example.org");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("https://example.org");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->with(
            "error",
            "L'utilisateur already-admin@example.org a déjà un accès administrateur."
        );
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->twice();
        $templateService = Helpers::getTemplateService();

        $usecase = new AddAdminUsecase($currentSite, $flashMessages, $urlGenerator, $templateService, $mailer);

        // when
        $exception = Helpers::runAndCatchException(fn() => $usecase->execute("already-admin@example.org"));

        // then
        $this->assertInstanceOf(BusinessRuleException::class, $exception);
        $this->assertEquals("L'utilisateur already-admin@example.org a déjà un accès administrateur.", $exception->getMessage());
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }
}
