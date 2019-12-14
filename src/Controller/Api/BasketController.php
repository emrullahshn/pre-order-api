<?php

namespace App\Controller\Api;

use App\Service\Basket\BasketManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Schema\BasketItem;

/**
 * @Rest\Route(path="basket/", name="basket_")
 * Class BasketController
 * @package App\Controller
 */
class BasketController extends AbstractFOSRestController
{
    /**
     * @var BasketManagerInterface $basketManager
     */
    private $basketManager;

    /**
     * BasketController constructor.
     * @param BasketManagerInterface $basketManager
     */
    public function __construct(BasketManagerInterface $basketManager)
    {
        $this->basketManager = $basketManager;
    }

    /**
     * @Rest\Post(path="add-item", name="add_item")
     *
     * @SWG\Tag(name="Basket")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add Item to Basket",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="productId", type="int", example="1", description="Product Id"),
     *         @SWG\Property(property="quantity", type="int", example="1", description="Product quantity")
     *     )
     * )
     *
     * * @SWG\Response(
     *     response="200",
     *     description="Item added to basket!",
     *     @SWG\Schema(
     *          @SWG\Property(property="status", type="boolean"),
     *          @SWG\Property(property="basket",
     *              @SWG\Property(property="items",
     *              @Model(type=BasketItem::class)
     *          )
     *        )
     *      )
     * )
     *
     * @SWG\Response(
     *     response="500",
     *     description="Error!",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="status", type="boolean", example=false),
     *              @SWG\Property(property="message", type="string")
     *          }
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @return Response|null
     */
    public function addItemAction(Request $request): ?Response
    {
        try {
            $basket = $this->basketManager->addItem($request);

            $view = $this->view([
                'status' => true,
                'basket' => $basket
            ], 200);

            return $this->handleView($view);

        } catch (Exception $exception) {
            $view = $this->view([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);

            return $this->handleView($view);
        }
    }
}
