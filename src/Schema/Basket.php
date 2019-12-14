<?php
namespace App\Schema;

class Basket
{
    /**
     * @var BasketItem[]|array $items
     */
    private $items;

    /**
     * Basket constructor.
     * @param array|null $basketItems
     */
    public function __construct(?array $basketItems)
    {
        $this->items = $basketItems ?? [];
    }

    /**
     * @return BasketItem[]|array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param BasketItem[]|array $items
     * @return self
     */
    public function setItems($items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param BasketItem $basketItem
     * @return self
     */
    public function addItem(BasketItem $basketItem): self
    {
        $this->items[$basketItem->getProductId()] = $basketItem;

        return $this;
    }
}
