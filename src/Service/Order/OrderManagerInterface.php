<?php
namespace App\Service\Order;

use Symfony\Component\HttpFoundation\Request;

interface OrderManagerInterface
{
    public function create(Request $request) ;
    public function approve(Request $request) ;
}
