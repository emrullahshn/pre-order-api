<?php

namespace App\Tests\OrderHelper;

use App\Entity\OrderItem;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Shipment;
use App\Library\OrderHelper;
use PHPUnit\Framework\TestCase;

class OrderHelperTest extends TestCase
{
    public function testOrderHelperClass()
    {
        $itemsTotal = OrderHelper::calculateItemsTotal($this->getMockOrder());
        $itemsPriceTotal = OrderHelper::calculateItemsPriceTotal($this->getMockOrder());
        $priceTotal = OrderHelper::calculatePriceTotal($this->getMockOrder());


        $this->assertEquals(5, $itemsTotal);
        $this->assertEquals(80, $itemsPriceTotal);
        $this->assertEquals(90, $priceTotal);
    }

    /**
     * @return Orders
     */
    private function getMockOrder(): Orders
    {
        return (new Orders())
            ->setEmail('test@test.com')
            ->setPhoneNumber('5437948425')
            ->setFirstName('Test')
            ->setLastName('Tester')
            ->setPayment($this->getMockPayment())
            ->setShipment($this->getMockShipment())
            ->addOrderItem($this->getMockOrderItem())
            ->addOrderItem($this->getMockOrderItem2())
            ;
    }

    /**
     * @return Payment
     */
    private function getMockPayment(): Payment
    {
        return (new Payment())
            ->setName('Test Payment')
            ->setPrice(5)
            ;
    }

    /**
     * @return Shipment
     */
    private function getMockShipment(): Shipment
    {
        return (new Shipment())
            ->setName('Test Shipment')
            ->setPrice(5)
            ;
    }

    /**
     * @return OrderItem
     */
    private function getMockOrderItem(): OrderItem
    {
        return (new OrderItem())
            ->setProduct($this->getMockProduct())
            ->setQuantity(3)
            ;
    }

    /**
     * @return OrderItem
     */
    private function getMockOrderItem2(): OrderItem
    {
        return (new OrderItem())
            ->setProduct($this->getMockProduct2())
            ->setQuantity(2)
            ;
    }

    /**
     * @return Product
     */
    private function getMockProduct(): Product
    {
        return (new Product())
            ->setName('Test Product 1')
            ->setPrice(10)
            ;
    }

    /**
     * @return Product
     */
    private function getMockProduct2(): Product
    {
        return (new Product())
            ->setName('Test Product 2')
            ->setPrice(25)
            ;
    }
}
