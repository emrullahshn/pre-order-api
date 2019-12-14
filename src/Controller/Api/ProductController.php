<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;

/**
 * @Rest\Route(path="product/", name="product_")
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Rest\Get(path="get-all", name="get_all")
     *
     *
     * @SWG\Tag(name="Product")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Product list successfully fetched!",
     *     @SWG\Schema(
     *          type="array",
     *          @Model(type=Product::class)
     *      )
     * )
     *
     * @Security(name="Bearer")
     *
     */
    public function getAll(): Response
    {
        $products = $this->productRepository->findAll();

        $view = $this->view($products, 200);
        return $this->handleView($view);
    }
}
