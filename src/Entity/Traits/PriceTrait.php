<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait PriceTrait
{
    /**
     * @var float $price
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
