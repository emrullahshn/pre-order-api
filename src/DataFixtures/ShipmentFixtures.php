<?php

namespace App\DataFixtures;

use App\Entity\Shipment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ShipmentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getShipmentsData() as [$name, $price]) {
            $shipment = new Shipment();
            $shipment->setName($name);
            $shipment->setPrice($price);

            $manager->persist($shipment);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getShipmentsData(): array
    {
        return [
            [
                'Yurtiçi Kargo',
                12
            ],
            [
                'DHL',
                18
            ],
            [
                'PTT',
                8
            ]
        ];
    }
}
