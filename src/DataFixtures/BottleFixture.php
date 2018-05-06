<?php

namespace App\DataFixtures;

use App\Entity\Bottle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BottleFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bottle_37 = new Bottle('demi-bouteille', 37.5);
        $manager->persist($bottle_37);

        $bottle_50 = new Bottle('?', 50);
        $manager->persist($bottle_50);

        $bottle_62 = new Bottle('clavelin', 62);
        $manager->persist($bottle_62);

        $bottle_75 = new Bottle('bouteille', 75);
        $manager->persist($bottle_75);

        $bottle_150 = new Bottle('magnum', 150);
        $manager->persist($bottle_150);

        $bottle_600 = new Bottle('imperiale', 600);
        $manager->persist($bottle_600);

        $manager->flush();

        $this->addReference('bottle_37_5cl', $bottle_37);
        $this->addReference('bottle_50cl', $bottle_50);
        $this->addReference('bottle_62cl', $bottle_62);
        $this->addReference('bottle_75cl', $bottle_75);
        $this->addReference('bottle_150cl', $bottle_150);
        $this->addReference('bottle_600cl', $bottle_600);
    }

    public function getOrder() {
        return 10;
    }
}
