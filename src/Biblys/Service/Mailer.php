<?php

namespace Biblys\Service;

use Biblys\Exception\InvalidEmailAddressException;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * SwiftMailer wrapper
 */
class Mailer
{
    private TransportInterface $transport;
    private \Symfony\Component\Mailer\Mailer $mailer;
    private Address $defaultSender;
    private string $method = "sendmail";

    public function __construct(Config $config)
    {
        global $_SITE;

        $this->defaultSender = new Address($_SITE->get("site_contact"), $_SITE->get("site_title"));
        $this->transport = new SendmailTransport();

        // If an SMTP config is defined
        $smtp = $config->get("smtp");
        if ($smtp) {
            $this->method = "smtp";
            $smtpDsn = "smtp://{$smtp["user"]}:{$smtp["pass"]}@{$smtp["host"]}:{$smtp["port"]}";
            $this->transport = Transport::fromDsn($smtpDsn);
        }

        // Create mailer with defined transport
        $this->mailer = new \Symfony\Component\Mailer\Mailer($this->transport);
    }

    /**
     * Send an email using Mailer
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param array $from ["email" => "name"]
     * @param array $options
     * @param array $headers
     * @return bool [bool]          true if mail was sent
     * @throws TransportExceptionInterface
     * @throws InvalidEmailAddressException
     */
    public function send(
        string $to,
        string $subject,
        string $body,
        array $from = [],
        array $options = [],
        array $headers = []
    ): bool
    {
        $sender = $this->defaultSender;
        if (count($from) > 0) {
            $senderEmail = array_keys($from)[0];
            $senderName = array_values($from)[0];
            $this->validateEmail($senderEmail);
            $sender = new Address($senderEmail, $senderName);
        }

        $this->validateEmail($to);
        foreach ($from as $email => $name) {
            $this->validateEmail($email);
        }

        // Create message
        $message = new Email();
        $message->from($sender)
            ->to($to)
            ->subject($subject)
            ->html($body, 'text/html');

        // Reply-to
        if (isset($options["reply-to"])) {
            $this->validateEmail($options["reply-to"]);
            $message->replyTo($options["reply-to"]);
        }

        // CC
        if (isset($options["cc"])) {
            foreach ($options["cc"] as $cc) {
                $message->addCc($cc);
            }
        }

        // Set headers
        $mailHeaders = $message->getHeaders();
        foreach ($headers as $key => $val) {
            $mailHeaders->addTextHeader($key, $val);
        }

        if (getenv("PHP_ENV") === "test") {
            return true;
        }

        // Send mail
        $this->mailer->send($message);

        // Log
        Log::mail(
            "INFO",
            "Sent mail \"$subject\" to \"$to\" through " . $this->method
        );

        return true;
    }

    /**
     * @throws InvalidEmailAddressException
     */
    public function validateEmail($email)
    {
        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);
        if ($validator->isValid($email, $multipleValidations) === false) {
            throw new InvalidEmailAddressException("L'adresse $email est invalide.");
        }
    }
}
