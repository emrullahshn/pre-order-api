<?php

namespace App\DataFixtures;

use App\Entity\Payment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getPaymentsData() as [$name, $price]) {
            $payment = new Payment();
            $payment->setName($name);
            $payment->setPrice($price);

            $manager->persist($payment);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getPaymentsData(): array
    {
        return [
            [
                'Havale',
                0
            ],
            [
                'Kapıda Ödeme',
                5
            ],
            [
                'Paypal',
                1.23
            ]
        ];
    }
}
