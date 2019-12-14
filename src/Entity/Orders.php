<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Orders
{
    public const STATUS_INIT = 0;
    public const STATUS_AUTO_REJECT = -1;
    public const STATUS_APPROVED = 1;

    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Payment")
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shipment", inversedBy="orders")
     */
    private $shipment;

    /**
     * @ORM\Column(type="integer")
     */
    private $itemsTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $itemsPriceTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $priceTotal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="_order")
     */
    private $orderItems;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $status = 0;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Payment|null
     */
    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment|null $payment
     * @return $this
     */
    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Shipment|null
     */
    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    /**
     * @param Shipment|null $shipment
     * @return $this
     */
    public function setShipment(?Shipment $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemsTotal(): ?int
    {
        return $this->itemsTotal;
    }

    /**
     * @param int $itemsTotal
     * @return $this
     */
    public function setItemsTotal(int $itemsTotal): self
    {
        $this->itemsTotal = $itemsTotal;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getItemsPriceTotal(): ?float
    {
        return $this->itemsPriceTotal;
    }

    /**
     * @param float $itemsPriceTotal
     * @return $this
     */
    public function setItemsPriceTotal(float $itemsPriceTotal): self
    {
        $this->itemsPriceTotal = $itemsPriceTotal;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPriceTotal(): ?float
    {
        return $this->priceTotal;
    }

    /**
     * @param float $priceTotal
     * @return $this
     */
    public function setPriceTotal(float $priceTotal): self
    {
        $this->priceTotal = $priceTotal;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }

        return $this;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
