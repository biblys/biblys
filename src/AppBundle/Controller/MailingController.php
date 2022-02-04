<?php

namespace AppBundle\Controller;

use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use MailingManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha as ReCaptcha;
use Symfony\Component\HttpFoundation\Response;
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
    public function subscribeAction(Request $request)
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
                        throw new Exception("Vous n'avez pas correctement complété le test anti-spam.");
                    }
                }

                $result = $mm->addSubscriber($email, true);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (isset($result)) {
                return $this->redirect($this->generateUrl('mailing_subscribe', ['success' => 1]));
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
    public function unsubscribeAction(Request $request)
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
                return $this->redirect($this->generateUrl('mailing_unsubscribe', ['success' => 1]));
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
