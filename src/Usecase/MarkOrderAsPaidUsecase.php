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
use Biblys\Exception\UnreachableExternalServiceException;
use Biblys\Service\LoggerService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Model\Map\UserTableMap;
use Model\Order;
use NumberFormatter;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MarkOrderAsPaidUsecase
{

    public function __construct(
        private UrlGeneratorInterface          $urlGenerator,
        private TemplateService                $templateService,
        private Mailer                         $mailer,
        private AddArticleToUserLibraryUsecase $addArticleToUserLibraryUsecase,
        private LoggerService                  $loggerService = new LoggerService(),
    )
    {
    }

    /**
     * @param Order $order
     * @param int $payedAmountInCents
     * @param string $paymentMode
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws UnreachableExternalServiceException
     */
    public function execute(Order $order, int $payedAmountInCents, string $paymentMode): void
    {
        $con = Propel::getWriteConnection(UserTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $order->setPaymentDate(new DateTime());
            $order->save();

            // The payment is valid whether or not the confirmation email can be sent
            try {
                $this->sendPaymentConfirmationEmail($order, $payedAmountInCents, $paymentMode);
            } catch (UnreachableExternalServiceException|TransportExceptionInterface $exception) {
                $this->loggerService->log(
                    logger: "errors",
                    level: "ERROR",
                    message: "Order {$order->getId()} was marked as paid but its payment confirmation email could not be sent: " . $exception->getMessage(),
                );
            }

            if ($order->containsDownloadableArticles()) {

                $downloadableItems = array_values(array_filter(
                    $order->getStockItems()->getArrayCopy(),
                    fn($stockItem) => $stockItem->getArticle()->getType()->isDownloadable()
                ));

                $this->addArticleToUserLibraryUsecase->execute(
                    user: $order->getUser(),
                    items: $downloadableItems,
                    sendEmail: true,
                );
            }

            if (!$order->containsPhysicalArticles()) {
                $order->setShippingDate(new DateTime());
                $order->save();
            }

            $con->commit();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    private function sendPaymentConfirmationEmail(Order $order, int $payedAmountInCents, string $paymentMode): void
    {
        if (!$order->getEmail()) {
            return;
        }

        $orderUrl = $this->urlGenerator->generate("legacy_order", ["url" => $order->getSlug()]);

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::CURRENCY);
        $formattedPayedAmount = $formatter->formatCurrency($payedAmountInCents / 100, 'EUR');

        $libraryUrl = "";
        if ($order->containsDownloadableArticles()) {
            $libraryUrl = $this->urlGenerator->generate("user_library");
        }

        $mailSubject = "Paiement pour votre commande bien reçu";
        $mailBody = $this->templateService->render(
            "AppBundle:Order:order-payed-email.html.twig",
            [
                "subject" => $mailSubject,
                "order_id" => $order->getId(),
                "payed_amount" => $formattedPayedAmount, true,
                "payment_mode" => $paymentMode,
                "has_downloadable" => $order->containsDownloadableArticles(),
                "library_url" => $libraryUrl,
                "order_url" => $orderUrl,
            ]
        );

        $this->mailer->send(
            $order->getEmail(),
            $mailSubject,
            $mailBody,
        );
    }
}
