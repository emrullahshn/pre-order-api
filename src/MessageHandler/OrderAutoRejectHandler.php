<?php
namespace App\MessageHandler;

use App\Entity\Orders;
use App\Message\OrderAutoRejectMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderAutoRejectHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * OrderAutoRejectHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(OrderAutoRejectMessage $message)
    {
        $orderId = $message->getOrderId();
        $order = $this->entityManager->getRepository(Orders::class)->find($orderId);

        if ($order->getStatus() !== Orders::STATUS_APPROVED) {
            $order->setStatus(Orders::STATUS_AUTO_REJECT);
        }

        $this->entityManager->flush();
    }
}
