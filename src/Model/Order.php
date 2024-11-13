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


namespace Model;

use Model\Base\Order as BaseOrder;

/**
 * Skeleton subclass for representing a row from the 'orders' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Order extends BaseOrder
{
    public function getTrackingLink(): string
    {
        $trackingNumber = $this->getTrackNumber();

        if (!$trackingNumber) {
            return "";
        }

        if ($this->getShippingMode() === "suivi") {
            return "https://www.laposte.fr/outils/suivre-vos-envois?code=$trackingNumber";
        }

        if ($this->getShippingMode() === "mondial-relay") {
            return sprintf(
                "https://www.mondialrelay.fr/suivi-de-colis?numeroExpedition=%s&codePostal=%s",
                $trackingNumber,
                $this->getPostalcode()
            );
        }

        return "";
    }
}
