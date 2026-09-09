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


namespace Biblys\Service\SpecialOffers;

use Model\Cart;
use Model\SpecialOffer;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

final class SpecialOfferEvaluator
{
    /**
     * @throws PropelException
     */
    public static function evaluate(SpecialOffer $specialOffer, Cart $cart): SpecialOfferEvaluation
    {
        $amount = null;
        $targetAmount = $specialOffer->getTargetAmount();
        if ($targetAmount !== null) {
            $current = $cart->getSubtotal();
            $amount = new SpecialOfferConditionEvaluation(
                isMet: $current >= $targetAmount,
                target: $targetAmount,
                current: $current,
            );
        }

        $quantity = null;
        $targetQuantity = $specialOffer->getTargetQuantity();
        $targetCollection = $specialOffer->getTargetCollection();
        if ($targetQuantity !== null && $targetCollection !== null) {
            $current = StockQuery::create()
                ->filterByCart($cart)
                ->filterByArticle($specialOffer->getFreeArticle(), Criteria::NOT_EQUAL)
                ->useArticleQuery()
                ->filterByCollectionId($targetCollection->getId())
                ->endUse()
                ->count();
            $quantity = new SpecialOfferConditionEvaluation(
                isMet: $current >= $targetQuantity,
                target: $targetQuantity,
                current: $current,
            );
        }

        return new SpecialOfferEvaluation($amount, $quantity);
    }
}
