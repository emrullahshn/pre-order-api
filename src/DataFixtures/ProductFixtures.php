<?php

namespace App\DataFixtures;

use App\Component\Discount\Model\Discount;
use App\Component\Payment\Model\Payment;
use App\Component\Shipment\Model\Shipment;
use App\Entity\Product;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('tr_TR');
        $faker->addProvider(new Commerce($faker));

        for ($i = 0; $i < 10; $i++){
            $product = new Product();
            $product
                ->setName($faker->productName)
                ->setPrice($faker->randomFloat(2, 100,5000))
            ;

            $manager->persist($product);
        }


        $manager->flush();
    }
}
