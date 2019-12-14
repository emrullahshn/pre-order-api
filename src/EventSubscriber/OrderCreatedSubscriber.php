<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedEvent;
use App\Library\OrderHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * OrderCreatedSubscriber constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderCreatedEvent::NAME => [
                ['calculateTotal'],
                ['clearSession']
            ],
        ];
    }

    /**
     * @param OrderCreatedEvent $event
     */
    public function calculateTotal(OrderCreatedEvent $event): void
    {
        $order = $event->getOrder();

        $order
            ->setItemsTotal(OrderHelper::calculateItemsTotal($order))
            ->setItemsPriceTotal(OrderHelper::calculateItemsPriceTotal($order))
            ->setPriceTotal(OrderHelper::calculatePriceTotal($order))
            ;
    }

    /**
     * @param OrderCreatedEvent $event
     */
    public function clearSession(OrderCreatedEvent $event): void
    {
        $this->session->clear();
    }
}
