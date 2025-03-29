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

use Biblys\Exception\CaptchaValidationException;
use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\MailingList\Exception\InvalidConfigurationException;
use Biblys\Service\MailingList\Exception\MailingListServiceException;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\Pagination;
use Biblys\Service\TemplateService;
use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
        Session $session,
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
        if ($recaptcha_config && isset($recaptcha_config['secret']) && isset($recaptcha_config['sitekey'])) {
            $recaptcha = new Recaptcha($recaptcha_config['secret']);
            $recaptcha_sitekey = $recaptcha_config["sitekey"];
        }

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

                $session->getFlashBag()->add(
                    "success",
                    "Votre inscription avec l'adresse $email a bien été prise en compte."
                );
                return new RedirectResponse($urlGenerator->generate("mailing_subscribe"));
            } catch (CaptchaValidationException|MailingListServiceException $exception) {
                $session->getFlashBag()->add("error", $exception->getMessage());
                return new RedirectResponse($urlGenerator->generate("mailing_subscribe"));
            }
        }

        $getEmail = $request->query->get("email", '');
        $fieldValue = $request->request->get('email', $getEmail);
        return $this->render('AppBundle:Mailing:subscribe.html.twig', [
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
        Session $session,
    ): RedirectResponse|Response
    {
        $request->attributes->set("page_title", "Désinscription de la newsletter");

        if (!$mailingListService->isConfigured()) {
            throw new NotFoundHttpException("Aucun service de gestion de liste de contacts n'est configuré.");
        }

        if ($request->getMethod() === "POST") {
            $email = $request->request->get('email', false);
            try {
                $mailingList = $mailingListService->getMailingList();
                $mailingList->removeContact($email);

                $session->getFlashBag()->add(
                    "success",
                    "Votre désinscription avec l'adresse $email a bien été prise en compte."
                );
                return new RedirectResponse($urlGenerator->generate("mailing_unsubscribe"));
            } catch (Exception $exception) {
                $session->getFlashBag()->add("error", $exception->getMessage());
                return new RedirectResponse($urlGenerator->generate("mailing_unsubscribe"));
            }
        }

        $email = $request->query->get('email');
        return $this->render('AppBundle:Mailing:unsubscribe.html.twig', [
            'email' => $email,
        ]);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigurationException
     * @throws Exception
     */
    public function contacts(
        CurrentUser $currentUser,
        MailingListService $mailingListService,
        Request $request,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        if (!$mailingListService->isConfigured()) {
            throw new NotFoundHttpException("Aucun service de gestion de liste de contacts n'est configuré.");
        }

        $currentPage = $request->query->get("p", 0);
        $contactsPerPage = 1000;

        $list = $mailingListService->getMailingList();
        $contactCount = $list->getContactCount();
        $pagination = new Pagination($currentPage, $contactCount, $contactsPerPage);
        $contacts = $list->getContacts($pagination->getOffset(), $pagination->getLimit());
        $exportableContacts = array_map(function($contact) {
            return [$contact->getEmail()];
        }, $contacts);

        return $templateService->renderResponse('AppBundle:Mailing:contacts.html.twig', [
            "source" => $list->getSource(),
            "link" => $list->getLink(),
            "total" => $contactCount,
            "contacts" => $contacts,
            "export" => json_encode($exportableContacts),
            "pagination" => $pagination,
        ], isPrivate: true);
    }
}
