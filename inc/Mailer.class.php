<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


/**
 * SwiftMailer wrapper
 */
class Mailer
{
    private $transport;
    private $mailer;
    private $from;
    private $method;

    public function __construct()
    {
        global $site, $config;

        // Default from: site contact address
        $this->from = [$site->get('site_contact') => $site->get('site_title')];

        // Default mailer: php mail() function
        $this->method = 'sendmail';
        $this->transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');

        // If a SMTP config is defined
        $smtp = $config->get('smtp');
        if ($smtp) {
            $this->method = 'smtp';
            $this->transport = new \Swift_SmtpTransport(
                $smtp['host'] ?? 'localhost',
                $smtp['port'] ?? 25,
                $smtp['encryption'] ?? null
            );
            $this->transport->setUsername($smtp['user']);
            $this->transport->setPassword($smtp['pass']);
        }

        // Create mailer with defined transport
        $this->mailer = new \Swift_Mailer($this->transport);
    }

    /**
     * Send an email using Mailer
     *
     * @param  [String] $to      Destination email
     * @param  [String] $subject Subject line
     * @param  [String] $body    HTML body
     * @param  [Array]  $from    From array (['email' => 'name'])
     * @param  [Array]  $options ['reply-to']
     * @return [bool]          true if mail was sent
     */
    public function send($to, $subject, $body, array $from = [], array $options = [], array $headers = [])
    {
        if (getenv("PHP_ENV") === "test") {
            return;
        }

        $log = new Logger('mails');
        $log->pushHandler(new StreamHandler(BIBLYS_PATH.'/logs/mails.log', Logger::INFO));

        // Default from address
        if (empty($from)) {
            $from = $this->from;
        }

        // Create message
        $message = new \Swift_Message();
        $message->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body, 'text/html');

        // Reply-to
        if (isset($options["reply-to"])) {
            $message->setReplyTo($options["reply-to"]);
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

        // Send mail
        $sent = $this->mailer->send($message, $errors);

        // Log
        $log->info(
            'Sent mail "'.$subject.'" to "'.$to.'" through '.$this->method
        );

        if ($errors) {
            trigger_error('Error: '.implode($errors));
        }

        return true;
    }
}
