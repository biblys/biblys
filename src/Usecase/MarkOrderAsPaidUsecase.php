<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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


namespace Usecase;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\InvalidSiteIdException;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Model\Map\UserTableMap;
use Model\Order;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class MarkOrderAsPaidUsecase
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private TemplateService $templateService,
        private Mailer $mailer
    )
    {
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws InvalidSiteIdException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(Order $order, int $payedAmountInCents, string $paymentMode): void
    {
        $con = Propel::getWriteConnection(UserTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $order->setPaymentDate(new DateTime());
            $order->save();

            $orderUrl = $this->urlGenerator->generate("legacy_order", ["url" => $order->getSlug()]);

            $formatter = new \NumberFormatter('fr_FR', \NumberFormatter::CURRENCY);
            $formattedPayedAmount = $formatter->formatCurrency($payedAmountInCents / 100, 'EUR');

            $mailSubject = "Paiement pour votre commande bien reçu";
            $mailBody = $this->templateService->render(
                "AppBundle:Order:order-payed-email.html.twig",
                [
                    "subject" => $mailSubject,
                    "order_id" => $order->getId(),
                    "payed_amount" => $formattedPayedAmount, true,
                    "payment_mode" => $paymentMode,
                    "has_downloadable" => false, // $order->containsDownloadableArticles(),
                    "library_url" => "",
                    "order_url" => $orderUrl,
                ]
            );

            $this->mailer->send(
                $order->getEmail(),
                $mailSubject,
                $mailBody,
            );

            $con->commit();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }
     }
}