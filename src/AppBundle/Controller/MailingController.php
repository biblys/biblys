<?php

namespace AppBundle\Controller;

use Biblys\Exception\CaptchaValidationException;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use MailingManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailingController extends Controller
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function subscribeAction(Request $request, UrlGenerator $urlGenerator)
    {
        global $config;

        $mm = new MailingManager();
        $subscribers = $mm->countSubscribers();

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
                        $errorCodes = join($check->getErrorCodes(), ", ");
                        throw new CaptchaValidationException(
                            "Vous n'avez pas correctement complété le test anti-spam ($errorCodes)."
                        );
                    }
                }

                $result = $mm->addSubscriber($email, true);
            } catch (CaptchaValidationException $e) {
                $error = $e->getMessage();
            }

            if (isset($result)) {
                $successUrl = $urlGenerator->generate("mailing_subscribe", ["success" => 1]);
                return new RedirectResponse($successUrl);
            }
        }

        $getEmail = $request->query->get("email", '');
        $fieldValue = $request->request->get('email', $getEmail);
        $success = $request->query->get('success', false);
        return $this->render('AppBundle:Mailing:subscribe.html.twig', [
            'subscribers' => $subscribers,
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
     */
    public function unsubscribeAction(Request $request, UrlGenerator $urlGenerator)
    {
        $mm = new MailingManager();
        $request->attributes->set("page_title", "Désinscription de la newsletter");
        $error = null;

        if ($request->getMethod() == "POST") {
            $email = $request->request->get('email', false);
            try {
                $result = $mm->removeSubscriber($email);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (isset($result)) {
                $successUrl = $urlGenerator->generate("mailing_subscribe", ["success" => 1]);
                return new RedirectResponse($successUrl);
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
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function contactsAction(Request $request): Response
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Liste de contacts");

        $mm = new MailingManager();
        $emails = $mm->getAll([],[
            "order" => "mailing_created",
            "sort" => "desc"
        ]);

        $subscribed = [];
        $export = [];
        foreach ($emails as $email) {
            if ($email->isSubscribed()) {
                $subscribed[] = $email;
                $export[] = [$email->get('email')];
            }
        }

        return $this->render('AppBundle:Mailing:contacts.html.twig', [
            "subscribed" => $subscribed,
            "export" => json_encode($export)
        ]);
    }
}
