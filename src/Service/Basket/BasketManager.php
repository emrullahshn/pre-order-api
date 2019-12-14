<?php


namespace App\Service\Basket;


use App\Repository\ProductRepository;
use App\Schema\Basket;
use App\Schema\BasketItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasketManager implements BasketManagerInterface
{
    public const SESSION_NAME = 'basketItems';

    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;

    /**
     * BasketManager constructor.
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     */
    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     * @return Basket
     */
    public function addItem(Request $request): Basket
    {
        $requestContent = $this->checkParameters($request);

        $productId = $requestContent['productId'];
        $quantity = $requestContent['quantity'];

        $this->checkProductIsExist($productId);
        $basket = $this->addItemToSession($productId, $quantity);

        return $basket;
    }

    /**
     * @param int $productId
     * @param int $quantity
     * @return Basket
     */
    private function addItemToSession(int $productId, int $quantity): Basket
    {
        $basket = new Basket($this->session->get(self::SESSION_NAME));

        $basketItem = (new BasketItem())
            ->setProductId($productId)
            ->setQuantity($quantity);

        $basket->addItem($basketItem);
        $this->session->set(self::SESSION_NAME, $basket->getItems());

        return $basket;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function checkParameters(Request $request)
    {
        $requestContent = json_decode($request->getContent(), true);

        $resolver = new OptionsResolver();

        $resolver
            ->setRequired(['productId', 'quantity'])
            ->setAllowedTypes('productId', 'int')
            ->setAllowedTypes('quantity', 'int');

        $resolver->resolve($requestContent);

        return $requestContent;
    }

    /**
     * @param int $productId
     */
    private function checkProductIsExist(int $productId): void
    {
        $product = $this->productRepository->find($productId);

        if ($product === null) {
            throw new \RuntimeException('Product is not found!');
        }
    }
}
