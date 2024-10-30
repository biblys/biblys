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

    /**
     * @throws Exception
     */
    public function __construct(Config $config)
    {
        $currentSite = CurrentSite::buildFromConfig($config);

        $this->defaultSender = new Address($currentSite->getSite()->getContact(), $currentSite->getTitle());
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
     * @param array $from ["email" => "name"]
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
        $loggerService = new LoggerService();
        $loggerService->log(
            logger: "mails",
            level: "INFO",
            message: "Sent mail $subject to $to through $this->method",
        );

        return true;
    }

    /**
     * @throws InvalidEmailAddressException
     */
    public function validateEmail($email): void
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
