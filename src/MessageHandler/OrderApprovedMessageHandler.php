<?php

namespace App\MessageHandler;

use App\Entity\Orders;
use App\Message\OrderApprovedMessage;
use App\Service\Notifications\EmailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderApprovedMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var EmailServiceInterface $emailService
     */
    private $emailService;

    /**
     * OrderApprovedMessageHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param EmailServiceInterface $emailService
     */
    public function __construct(EntityManagerInterface $entityManager, EmailServiceInterface $emailService)
    {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    /**
     * @param OrderApprovedMessage $message
     */
    public function __invoke(OrderApprovedMessage $message)
    {
        $orderId = $message->getOrderId();
        /**
         * @var Orders $order
         */
        $order = $this->entityManager->getRepository(Orders::class)->find($orderId);
        $this->sendMailNotification($order);
        $this->entityManager->flush();
    }

    /**
     * @param Orders $order
     */
    private function sendMailNotification(Orders $order): void
    {
        $this->emailService
            ->setTo($order->getEmail())
            ->setTitle('Siparişiniz onaylandı!')
            ->setMessage('Siparişiniz onaylanmıştır. En kısa sürede teslim edilecektir.');
        $this->emailService->sendEmail();
    }
}
