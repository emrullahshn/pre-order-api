<?php
namespace App\Message;

class OrderApprovedMessage
{
    /**
     * @var int $orderId
     */
    private $orderId;

    /**
     * OrderAutoReject constructor.
     * @param int $orderId
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }
}
