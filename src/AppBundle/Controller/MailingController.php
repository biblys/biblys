<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use \ReCaptcha\ReCaptcha as ReCaptcha;


class MailingController extends Controller
{

    public function subscribeAction(Request $request)
    {
        global $config;

        $mm = $this->entityManager("Mailing");
        $subscribers = $mm->countSubscribers();

        $this->setPageTitle("Inscription à la newsletter");

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
                        throw new \Exception("Vous n'avez pas correctement complété le test anti-spam.");
                    }
                }

                $result = $mm->addSubscriber($email, true);
            } catch (\Exception $e) {
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

    public function unsubscribeAction(Request $request)
    {
        $mm = $this->entityManager("Mailing");

        $this->setPageTitle("Désinscription de la newsletter");

        $error = null;

        if ($request->getMethod() == "POST") {
            $email = $request->request->get('email', false);
            try {
                $result = $mm->removeSubscriber($email);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (isset($result)) {
                return $this->redirect($this->generateUrl('mailing_unsubscribe', ['success' => 1]));
            }
        }

        $email = $request->query->get('email', null);
        $success = $request->query->get('success', false);
        return $this->render('AppBundle:Mailing:unsubscribe.html.twig', [
            'email' => $email,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function contactsAction()
    {
        $this->auth("admin");
        $this->setPageTitle("Liste de contacts");

        $mm = $this->entityManager("Mailing");
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
