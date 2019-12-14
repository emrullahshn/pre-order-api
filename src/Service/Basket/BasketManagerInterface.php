<?php


namespace App\Service\Basket;


use App\Schema\Basket;
use Symfony\Component\HttpFoundation\Request;

interface BasketManagerInterface
{
    public function addItem(Request $requestContent): Basket;
}
