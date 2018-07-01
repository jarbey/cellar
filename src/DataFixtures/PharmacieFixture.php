<?php

namespace App\DataFixtures;
use App\Entity\Db;
use App\Entity\HumidityLimit;
use App\Entity\Sensor;
use App\Entity\TemperatureLimit;
use App\Service\RrdManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 21/01/2018
 * Time: 18:12
 */
class PharmacieFixture extends Fixture implements OrderedFixtureInterface
{

	/** @var RrdManager */
	private $rrd_manager;

	/**
	 * SensorFixture constructor.
	 * @param RrdManager $rrd_manager
	 */
	public function __construct(RrdManager $rrd_manager) {
		$this->rrd_manager = $rrd_manager;
	}

	public function load(ObjectManager $manager)
	{
		/** @var TemperatureLimit $fridge_temperature_limit */
		$fridge_temperature_limit = $this->getReference('fridge_temperature_limit');
		/** @var HumidityLimit $fridge_humidity_limit */
		$fridge_humidity_limit = $this->getReference('fridge_humidity_limit');

		$sensor1 = new Sensor();
		$sensor1->setName('Frigo');
		$sensor1->setType('22');
		$sensor1->setGpio(5);
		$sensor1->setTemperatureLimit($fridge_temperature_limit);
		$sensor1->setHumidityLimit($fridge_humidity_limit);

		$sensor2 = new Sensor();
		$sensor2->setName('Frigo');
		$sensor2->setType('22');
		$sensor2->setGpio(6);
		$sensor2->setTemperatureLimit($fridge_temperature_limit);
		$sensor2->setHumidityLimit($fridge_humidity_limit);

		$db = new Db('pharmacie-fridge', [$sensor1, $sensor2]);
		$db->setId(2);
		$manager->persist($db);
		$manager->flush();

		// Create RRD database
		$this->rrd_manager->createArchive($db);
	}

	public function getOrder()
	{
		return 3;
	}
}