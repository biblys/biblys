<?php

namespace Usecase;

use Biblys\Service\CurrentUser;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;

class UpdateStockItemPriceUsecase
{
    private CurrentUser $currentUser;

    public function __construct(CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function execute(Stock $stockItem, int $newPrice): void
    {
        if (!$stockItem->getArticle()->isPriceEditable()) {
            throw new BusinessRuleException("Le prix de cet article n'est pas libre.");
        }

        if ($newPrice < $stockItem->getArticle()->getPrice()) {
            throw new BusinessRuleException("Le prix doit être supérieur ou égal à " . currency($stockItem->getArticle()->getPrice() / 100));
        }

        if (!$this->currentUser->hasStockItemInCart($stockItem)) {
            throw new BusinessRuleException("Cet exemplaire n'est pas dans votre panier.");
        }

        $stockItem->setSellingPrice($newPrice);
        $stockItem->save();

        $amount = 0;
        $count = 0;
        $currentUserCart = $this->currentUser->getCart();
        $cartStockItems = $currentUserCart->getStocks();
        /** @var Stock $cartStockItem */
        foreach ($cartStockItems as $cartStockItem) {
            $amount += $cartStockItem->getSellingPrice();
            $count += 1;
        }
        $currentUserCart->setAmount($amount);
        $currentUserCart->setCount($count);
        $currentUserCart->save();
    }
}