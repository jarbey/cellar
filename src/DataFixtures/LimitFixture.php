<?php
namespace App\DataFixtures;

use App\Entity\HumidityLimit;
use App\Entity\TemperatureLimit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 21/01/2018
 * Time: 18:12
 */
class LimitFixture extends Fixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$cellar_temperature_limit = new TemperatureLimit('cellar', 10, 14);
		$manager->persist($cellar_temperature_limit);

		$cellar_humidity_limit = new HumidityLimit('cellar', 50, 80);
		$manager->persist($cellar_humidity_limit);

		$outside_temperature_limit = new TemperatureLimit('outside', 12, 18);
		$manager->persist($outside_temperature_limit);

		$outside_humidity_limit = new HumidityLimit('outside', 35, 70);
		$manager->persist($outside_humidity_limit);

		$manager->flush();

		$this->addReference('cellar_temperature_limit', $cellar_temperature_limit);
		$this->addReference('cellar_humidity_limit', $cellar_humidity_limit);

		$this->addReference('outside_temperature_limit', $outside_temperature_limit);
		$this->addReference('outside_humidity_limit', $outside_humidity_limit);
	}

	public function getOrder()
	{
		return 1;
	}
}