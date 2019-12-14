<?php

namespace App\Controller\Api;

use App\Service\Order\OrderManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route(path="order/", name="order_")
 *
 * Class OrderController
 * @package App\Controller
 */
class OrderController extends AbstractFOSRestController
{
    /**
     * @var OrderManagerInterface $orderManager
     */
    private $orderManager;

    /**
     * OrderController constructor.
     * @param OrderManagerInterface $orderManager
     */
    public function __construct(OrderManagerInterface $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @Rest\Post(path="create", name="create")
     *
     * @SWG\Tag(name="Order")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Create order",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="firstName", type="string", example="Emrullah"),
     *         @SWG\Property(property="lastName", type="string", example="Şahin"),
     *         @SWG\Property(property="email", type="string", example="sn.emrullah@gmail.com"),
     *         @SWG\Property(property="phoneNumber", type="string", example="5437948425"),
     *         @SWG\Property(property="paymentId", type="int", example="1"),
     *         @SWG\Property(property="shipmentId", type="int", example="1"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Order created!",
     *     @SWG\Schema(@SWG\Property(property="status", type="boolean"))
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
    public function createAction(Request $request): ?Response
    {
        try {
            $this->orderManager->create($request);
            $view = $this->view(['status' => true], 200);
            return $this->handleView($view);
        } catch (Exception $exception) {
            return $this->handleView($this->view([
                'status' => false,
                'message' => $exception->getMessage()
            ]));
        }
    }

    /**
     * @Rest\Post(path="approve", name="approve")
     *
     * @SWG\Tag(name="Order")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Approve order",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="orderId", type="int", example="1"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Order approved!",
     *     @SWG\Schema(@SWG\Property(property="status", type="boolean"))
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
     * @return Response
     */
    public function approveAction(Request $request): ?Response
    {
        try {
            $this->orderManager->approve($request);
            $view = $this->view(['status' => true], 200);
            return $this->handleView($view);
        } catch (Exception $exception) {
            return $this->handleView($this->view([
                'status' => false,
                'message' => $exception->getMessage()
            ]));
        }
    }
}
