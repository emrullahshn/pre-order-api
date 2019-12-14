<?php

namespace App\DataFixtures;

use App\Entity\Discount;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class DiscountFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('tr_TR');
        $faker->addProvider(new Commerce($faker));

        for ($i = 0; $i < 5; $i++){
            $discount = (new Discount())
            ->setName($faker->promotionCode)
            ->setCode($faker->promotionCode)
            ->setDiscount($faker->randomFloat(2,1,50))
            ;

            $manager->persist($discount);
        }

        $manager->flush();
    }
}
