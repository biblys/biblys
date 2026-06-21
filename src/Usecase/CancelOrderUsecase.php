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

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Mailer;
use DateTime;
use Model\OrderQuery;
use Order;
use OrderManager;
use Propel\Runtime\Exception\PropelException;
use Repository\PaymentRepository;
use StockManager;

class CancelOrderUsecase
{
    public function __construct(private readonly PaymentRepository $paymentRepository)
    {
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function execute(int $orderId): void
    {
        $order = OrderQuery::create()->findPk($orderId);

        $executedPayments = $this->paymentRepository->findExecutedByOrder($order);
        $totalPaid = array_reduce(
            $executedPayments,
            fn($carry, $payment) => $carry + $payment->getAmount(),
            0
        );
        if ($totalPaid > 0) {
            throw new BusinessRuleException(
                "Cette commande ne peut pas être annulée car elle a été payée (total : " .
                currency($totalPaid / 100) . "). Effectuez d'abord un remboursement."
            );
        }

        $om = new OrderManager();
        $legacyOrder = $om->getById($orderId);

        $sm = new StockManager();
        $stocks = $sm->getAll(['order_id' => $legacyOrder->get('id')]);

        $removedArticles = array_map(fn($stock) => $stock->get('article')->get('title'), $stocks);

        foreach ($stocks as $stock) {
            $om->removeStock($legacyOrder, $stock);
        }
        $om->updateFromStock($legacyOrder);

        $this->_sendCancellationMail($legacyOrder, $removedArticles);

        $order->setCancelDate(new DateTime());
        $order->save();
    }

    private function _sendCancellationMail(Order $legacyOrder, array $removedArticles): void
    {
        if ($legacyOrder->get('type') !== 'web') {
            return;
        }

        $removedCount = count($removedArticles);

        $subject = 'Commande n° ' . $legacyOrder->get('id') . ' annulée';
        $siteDomain = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true)->get('domain');
        $message = '
            <html>
                <head>
                    <title>' . $subject . '</title>
                </head>
                <body>
                    <p>Bonjour,</p>

                    <p>La commande n° ' . $legacyOrder->get('id') . ' a été annulée.</p>

                    <p>
                        Pour mémoire, cette commande concernait le' . s($removedCount) . ' article' . s($removedCount) . ' suivant' . s($removedCount) . '&nbsp;:
                    </p>
                    <ul><li>' . implode('</li><li>', $removedArticles) . '</li></ul>

                    <p>
                        A très bientôt !
                    </p>

                    <p><a href="">http://' . $siteDomain . '/</a></p>
                </body>
            </html>
        ';

        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig(ignoreDeprecation: true));
        $mailer->send($legacyOrder->get('email'), $subject, $message);
    }
}
