<?php


namespace App\Schema;


use Swagger\Annotations as SWG;

class BasketItem
{
    /**
     * @var int $productId
     *
     * @SWG\Property(type="integer")
     */
    private $productId;

    /**
     * @var int $quantity
     *
     * @SWG\Property(type="integer")
     */
    private $quantity;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return self
     */
    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return self
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
