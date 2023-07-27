<?php

namespace AppBundle\Controller;

use Biblys\Exception\CaptchaValidationException;
use Biblys\Service\Config;
use Biblys\Service\MailingList\Exception\InvalidConfigurationException;
use Biblys\Service\MailingList\Exception\InvalidEmailAddressException;
use Biblys\Service\MailingList\Exception\MailingListServiceException;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\Pagination;
use Exception;
use Framework\Controller;
use Payplug\Exception\NotFoundException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailingController extends Controller
{

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function subscribeAction(
        Request $request,
        UrlGenerator $urlGenerator,
        Config $config,
        MailingListService $mailingListService,
    ): RedirectResponse|Response
    {
        if (!$mailingListService->isConfigured()) {
            throw new NotFoundHttpException("Aucun service de gestion de liste de contacts n'est configuré.");
        }

        $request->attributes->set("page_title", "Inscription à la newsletter");

        // ReCaptcha
        $recaptcha = false;
        $recaptcha_config = $config->get('recaptcha');
        $recaptcha_sitekey = false;
        if ($recaptcha_config && isset($recaptcha_config['secret']) && isset($recaptcha_config['sitekey']) && !auth()) {
            $recaptcha = new Recaptcha($recaptcha_config['secret']);
            $recaptcha_sitekey = $recaptcha_config["sitekey"];
        }

        $error = null;
        if ($request->getMethod() == "POST") {

            $email = $request->request->get('email', false);

            try {
                // Check captcha
                if ($recaptcha) {
                    $answer = $request->request->get('g-recaptcha-response');
                    $check = $recaptcha->verify($answer, $request->getClientIp());

                    if (!$check->isSuccess()) {
                        $errorCodes = join(", ", $check->getErrorCodes());
                        throw new CaptchaValidationException(
                            "Vous n'avez pas correctement complété le test anti-spam ($errorCodes)."
                        );
                    }
                }

                $mailingList = $mailingListService->getMailingList();
                $mailingList->addContact($email, true);
                $successUrl = $urlGenerator->generate("mailing_subscribe", ["success" => 1]);
                return new RedirectResponse($successUrl);
            } catch (CaptchaValidationException|MailingListServiceException $exception) {
                $error = $exception->getMessage();
            }
        }

        $getEmail = $request->query->get("email", '');
        $fieldValue = $request->request->get('email', $getEmail);
        $success = $request->query->get('success', false);
        return $this->render('AppBundle:Mailing:subscribe.html.twig', [
            'error' => $error,
            'success' => $success,
            'recaptcha_key' => $recaptcha_sitekey,
            'field_value' => $fieldValue,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function unsubscribeAction(
        Request $request,
        UrlGenerator $urlGenerator,
        MailingListService $mailingListService,
    ): RedirectResponse|Response
    {
        if (!$mailingListService->isConfigured()) {
            throw new NotFoundHttpException("Aucun service de gestion de liste de contacts n'est configuré.");
        }

        $request->attributes->set("page_title", "Désinscription de la newsletter");
        $error = null;

        if ($request->getMethod() === "POST") {
            $email = $request->request->get('email', false);
            try {
                $mailingList = $mailingListService->getMailingList();
                $mailingList->removeContact($email);
                $successUrl = $urlGenerator->generate("mailing_unsubscribe", ["success" => 1]);
                return new RedirectResponse($successUrl);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $email = $request->query->get('email');
        $success = $request->query->get('success', false);
        return $this->render('AppBundle:Mailing:unsubscribe.html.twig', [
            'email' => $email,
            'error' => $error,
            'success' => $success
        ]);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigurationException
     */
    public function contacts(
        Request $request,
        MailingListService $mailingListService,
    ): Response
    {
        self::authAdmin($request);

        if (!$mailingListService->isConfigured()) {
            throw new NotFoundHttpException("Aucun service de gestion de liste de contacts n'est configuré.");
        }

        $request->attributes->set("page_title", "Liste de contacts");

        $currentPage = $request->query->get("p", 0);
        $contactsPerPage = 1000;

        $list = $mailingListService->getMailingList();
        $contactCount = $list->getContactCount();
        $pagination = new Pagination($currentPage, $contactCount, $contactsPerPage);
        $contacts = $list->getContacts($pagination->getOffset(), $pagination->getLimit());
        $exportableContacts = array_map(function($contact) {
            return [$contact->getEmail()];
        }, $contacts);

        return $this->render('AppBundle:Mailing:contacts.html.twig', [
            "source" => $list->getSource(),
            "link" => $list->getLink(),
            "total" => $contactCount,
            "contacts" => $contacts,
            "export" => json_encode($exportableContacts),
            "pagination" => $pagination,
        ]);
    }
}
