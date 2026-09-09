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

use Biblys\Data\ArticleType;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../../setUp.php";

class SpecialOfferEvaluatorTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testEvaluateWithNoConditionsConfigured()
    {
        // given
        $specialOffer = ModelFactory::createSpecialOffer(targetCollection: null, targetQuantity: null);
        $cart = ModelFactory::createCart();

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertNull($evaluation->amount);
        $this->assertNull($evaluation->quantity);
        $this->assertTrue($evaluation->isMet());
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateAmountConditionNotMet()
    {
        // given
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: null, targetQuantity: null, targetAmount: 3000,
        );
        $cart = ModelFactory::createCart();
        ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertFalse($evaluation->amount->isMet);
        $this->assertEquals(3000, $evaluation->amount->target);
        $this->assertEquals(1000, $evaluation->amount->current);
        $this->assertNull($evaluation->quantity);
        $this->assertFalse($evaluation->isMet());
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateAmountConditionMet()
    {
        // given
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: null, targetQuantity: null, targetAmount: 3000,
        );
        $cart = ModelFactory::createCart();
        ModelFactory::createStockItem(cart: $cart, sellingPrice: 3000);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertTrue($evaluation->amount->isMet);
        $this->assertTrue($evaluation->isMet());
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateAmountConditionIgnoresIntangibleArticles()
    {
        // given
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: null, targetQuantity: null, targetAmount: 3000,
        );
        $cart = ModelFactory::createCart();
        ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000);
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart, sellingPrice: 5000);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertEquals(1000, $evaluation->amount->current);
        $this->assertFalse($evaluation->amount->isMet);
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateQuantityConditionNotMet()
    {
        // given
        $targetCollection = ModelFactory::createCollection();
        $freeArticle = ModelFactory::createArticle(collection: $targetCollection);
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: $targetCollection, targetQuantity: 2, freeArticle: $freeArticle,
        );
        $cart = ModelFactory::createCart();
        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(article: $article1, cart: $cart);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertFalse($evaluation->quantity->isMet);
        $this->assertEquals(2, $evaluation->quantity->target);
        $this->assertEquals(1, $evaluation->quantity->current);
        $this->assertNull($evaluation->amount);
        $this->assertFalse($evaluation->isMet());
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateQuantityConditionMet()
    {
        // given
        $targetCollection = ModelFactory::createCollection();
        $freeArticle = ModelFactory::createArticle(collection: $targetCollection);
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: $targetCollection, targetQuantity: 2, freeArticle: $freeArticle,
        );
        $cart = ModelFactory::createCart();
        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(article: $article1, cart: $cart);
        $article2 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(article: $article2, cart: $cart);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertTrue($evaluation->quantity->isMet);
        $this->assertEquals(2, $evaluation->quantity->current);
        $this->assertTrue($evaluation->isMet());
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateQuantityConditionExcludesFreeArticleItselfFromCount()
    {
        // given
        $targetCollection = ModelFactory::createCollection();
        $freeArticle = ModelFactory::createArticle(collection: $targetCollection);
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: $targetCollection, targetQuantity: 1, freeArticle: $freeArticle,
        );
        $cart = ModelFactory::createCart();
        ModelFactory::createStockItem(article: $freeArticle, cart: $cart);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertEquals(0, $evaluation->quantity->current);
        $this->assertFalse($evaluation->quantity->isMet);
    }

    /**
     * @throws PropelException
     */
    public function testEvaluateWithBothConditionsAndOnlyOneMet()
    {
        // given
        $targetCollection = ModelFactory::createCollection();
        $freeArticle = ModelFactory::createArticle(collection: $targetCollection);
        $specialOffer = ModelFactory::createSpecialOffer(
            targetCollection: $targetCollection, targetQuantity: 2, targetAmount: 3000, freeArticle: $freeArticle,
        );
        $cart = ModelFactory::createCart();
        ModelFactory::createStockItem(cart: $cart, sellingPrice: 3000);

        // when
        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);

        // then
        $this->assertTrue($evaluation->amount->isMet);
        $this->assertFalse($evaluation->quantity->isMet);
        $this->assertFalse($evaluation->isMet());
    }
}
