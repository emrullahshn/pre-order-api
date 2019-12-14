<?php /** @noinspection ALL */

namespace App\Service\Order;

use App\Entity\OrderItem;
use App\Entity\Orders;
use App\Event\OrderCreatedEvent;
use App\Message\OrderApprovedMessage;
use App\Message\OrderAutoRejectMessage;
use App\Repository\OrdersRepository;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use App\Repository\ShipmentRepository;
use App\Schema\BasketItem;
use App\Service\Basket\BasketManager;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderManager implements OrderManagerInterface
{
    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;

    /**
     * @var ShipmentRepository $shipmentRepository
     */
    private $shipmentRepository;

    /**
     * @var PaymentRepository $paymentRepository
     */
    private $paymentRepository;

    /**
     * @var OrdersRepository $orderRepository
     */
    private $orderRepository;

    /**
     * @var MessageBusInterface $messageBus
     */
    private $messageBus;

    /**
     * OrderManager constructor.
     * @param SessionInterface $session
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param ProductRepository $productRepository
     * @param ShipmentRepository $shipmentRepository
     * @param PaymentRepository $paymentRepository
     * @param OrdersRepository $orderRepository
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        ProductRepository $productRepository,
        ShipmentRepository $shipmentRepository,
        PaymentRepository $paymentRepository,
        OrdersRepository $orderRepository,
        MessageBusInterface $messageBus
    )
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->productRepository = $productRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->paymentRepository = $paymentRepository;
        $this->orderRepository = $orderRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @param Request $request
     */
    public function create(Request $request): void
    {
        // Check Parameters is valid
        $requestContent = $this->checkCreateParameters($request);
        // Create order and add order items
        $order = $this->createOrder($requestContent);
        // Dispatch order created event. This event doing  calculate total and session clear
        $this->eventDispatcher->dispatch(new OrderCreatedEvent($order), OrderCreatedEvent::NAME);
        $this->entityManager->flush();

        // Send message to queue . Oluşturulan mesaj queue'ya 24 saat sonra düşecek ve approve edilmemiş
        // ise AUTO_REJECT statusa güncelliyecek.
        $this->messageBus->dispatch(new OrderAutoRejectMessage($order->getId()), [new DelayStamp(86400000)]);
    }

    /**
     * @param Request $request
     */
    public function approve(Request $request): void
    {
        // Check parameters is valid
        $requestContent = $this->checkApproveParameters($request);
        // Update order status to approve. İf order not exist throw exception
        $order = $this->approveOrder($requestContent);
        // Dispatch order approved event and send notifications
        $this->messageBus->dispatch(new OrderApprovedMessage($order->getId()));
        $this->entityManager->flush();
    }

    /**
     * @param $requestContent
     * @return Orders
     */
    private function createOrder($requestContent): Orders
    {
        $basketItems = $this->session->get(BasketManager::SESSION_NAME);
        if ($basketItems === null) {
            throw new RuntimeException('Basket is empty!');
        }

        $shipment = $this->shipmentRepository->find($requestContent['shipmentId']);
        $payment = $this->paymentRepository->find($requestContent['paymentId']);
        $order = (new Orders())
            ->setFirstName($requestContent['firstName'])
            ->setLastName($requestContent['lastName'])
            ->setEmail($requestContent['email'])
            ->setPhoneNumber($requestContent['phoneNumber'])
            ->setShipment($shipment)
            ->setPayment($payment);
        $this->entityManager->persist($order);
        /**
         * @var BasketItem $basketItem
         */
        foreach ($basketItems as $basketItem) {
            $product = $this->productRepository->find($basketItem->getProductId());
            $orderItem = (new OrderItem())
                ->setOrder($order)
                ->setProduct($product)
                ->setQuantity($basketItem->getQuantity());

            $order->addOrderItem($orderItem);
            $this->entityManager->persist($orderItem);
        }

        return $order;
    }

    /**
     * @param $requestContent
     * @return Orders
     */
    private function approveOrder($requestContent): Orders
    {
        $orderId = $requestContent['orderId'];
        $order = $this->orderRepository->find($orderId);
        if ($order === null) {
            throw  new RuntimeException('Order is not found!');
        }
        $order->setStatus(Orders::STATUS_APPROVED);

        return $order;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function checkCreateParameters(Request $request)
    {
        $requestContent = json_decode($request->getContent(), true);
        $resolver = (new OptionsResolver())
            ->setRequired([
                'firstName',
                'lastName',
                'email',
                'phoneNumber',
                'paymentId',
                'shipmentId'
            ])
            ->setAllowedTypes('firstName', 'string')
            ->setAllowedTypes('lastName', 'string')
            ->setAllowedTypes('email', 'string')
            ->setAllowedTypes('phoneNumber', 'string')
            ->setAllowedTypes('paymentId', 'int')
            ->setAllowedTypes('shipmentId', 'int');
        $resolver->resolve($requestContent);

        return $requestContent;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function checkApproveParameters(Request $request)
    {
        $requestContent = json_decode($request->getContent(), true);
        $resolver = (new OptionsResolver())
            ->setRequired('orderId')
            ->setAllowedTypes('orderId', 'int');
        $resolver->resolve($requestContent);

        return $requestContent;
    }
}
