<?php


namespace App\Event;


use App\Entity\Orders;
use Symfony\Contracts\EventDispatcher\Event;

class OrderCreatedEvent extends Event
{
    public const NAME = 'order.created';

    /**
     * @var Orders $order
     */
    private $order;

    /**
     * OrderCreatedEvent constructor.
     * @param Orders $order
     */
    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    /**
     * @return Orders
     */
    public function getOrder(): Orders
    {
        return $this->order;
    }

    /**
     * @param Orders $order
     */
    public function setOrder(Orders $order): void
    {
        $this->order = $order;
    }
}
