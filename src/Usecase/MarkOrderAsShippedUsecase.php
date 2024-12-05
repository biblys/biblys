<?php

namespace Usecase;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use DateTime;
use Model\Order;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MarkOrderAsShippedUsecase
{
    public function __construct(
        private readonly CurrentSite $currentSite,
        private readonly TemplateService $templateService,
        private readonly Mailer $mailer,
    )
    {

    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(Order $order, ?string $trackingNumber): void
    {
        $order->setShippingDate(new DateTime());
        $order->setTrackNumber($trackingNumber);
        $order->save();

        $mailSubjectSuffix = $this->currentSite->getOption("shipped_mail_subject") ?? "a été expédiée !";
        $mailSubject = "Votre commande $mailSubjectSuffix";
        $mailMessage = $this->currentSite->getOption("shipped_mail_message") ?? "Votre commande n°{$order->getId()} a été expédiée.";
        $isPickup = $order->getShippingFee()->getType() === "magasin";
        if ($isPickup) {
            $mailSubject = "Votre commande est disponible en magasin !";
            $mailMessage = "Votre commande n°{$order->getId()} est disponible en magasin.";
        }

        $orderUrl = "https://{$this->currentSite->getSite()->getDomain()}/order/{$order->getSlug()}";

        $body = $this->templateService->render(
            "AppBundle:Order:order-shipped-email.html.twig",
            [
                "subject" => $mailSubject,
                "message" => $mailMessage,
                "tracking_link" => $order->getTrackingLink(),
                "display_pickup_notice" => $isPickup,
                "order_url" => $orderUrl,
            ]
        );

        $this->mailer->send(
            to: $order->getEmail(),
            subject: $mailSubject,
            body: $body,
        );
    }
}