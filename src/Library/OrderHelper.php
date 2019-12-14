<?php


namespace App\Library;


use App\Entity\OrderItem;
use App\Entity\Orders;

class OrderHelper
{

    public static function calculateItemsTotal(Orders $order)
    {
        return array_sum($order->getOrderItems()->map(static function (OrderItem $orderItem) {
            return $orderItem->getQuantity();
        })->toArray());
    }

    public static function calculateItemsPriceTotal(Orders $order)
    {
        $total = 0;
        $orderItems = $order->getOrderItems();

        /**
         * @var OrderItem $orderItem
         */
        foreach ($orderItems as $orderItem) {
            $total += $orderItem->getProduct()->getPrice() * $orderItem->getQuantity();
        }

        return $total;
    }

    public static function calculatePriceTotal(Orders $order)
    {
        $priceTotal = self::calculateItemsPriceTotal($order);
        $priceTotal += $order->getShipment()->getPrice();
        $priceTotal += $order->getPayment()->getPrice();

        return $priceTotal;
    }
}
