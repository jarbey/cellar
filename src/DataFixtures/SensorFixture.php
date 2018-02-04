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
class SensorFixture extends Fixture implements OrderedFixtureInterface
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
		/** @var TemperatureLimit $cellar_temperature_limit */
		$cellar_temperature_limit = $this->getReference('cellar_temperature_limit');
		/** @var HumidityLimit $cellar_humidity_limit */
		$cellar_humidity_limit = $this->getReference('cellar_humidity_limit');

		/** @var TemperatureLimit $outside_temperature_limit */
		$outside_temperature_limit = $this->getReference('outside_temperature_limit');
		/** @var HumidityLimit $outside_humidity_limit */
		$outside_humidity_limit = $this->getReference('outside_humidity_limit');

		$sensor1 = new Sensor();
		$sensor1->setType('22');
		$sensor1->setGpio(5);
		$sensor1->setTemperatureLimit($cellar_temperature_limit);
		$sensor1->setHumidityLimit($cellar_humidity_limit);

		$sensor2 = new Sensor();
		$sensor2->setType('11');
		$sensor2->setGpio(6);
		$sensor2->setTemperatureLimit($outside_temperature_limit);
		$sensor2->setHumidityLimit($outside_humidity_limit);

		$sensor3 = new Sensor();
		$sensor3->setType('22');
		$sensor3->setGpio(12);
		$sensor3->setTemperatureLimit($cellar_temperature_limit);
		$sensor3->setHumidityLimit($cellar_humidity_limit);

		$sensor4 = new Sensor();
		$sensor4->setType('22');
		$sensor4->setGpio(13);
		$sensor4->setTemperatureLimit($cellar_temperature_limit);
		$sensor4->setHumidityLimit($cellar_humidity_limit);

		$db = new Db('home', [$sensor1, $sensor2, $sensor3, $sensor4]);
		$manager->persist($db);
		$manager->flush();

		// Create RRD database
		$this->rrd_manager->createArchive($db);

		$this->addReference('db', $db);
		$this->addReference('sensor1', $sensor1);
		$this->addReference('sensor2', $sensor2);
		$this->addReference('sensor3', $sensor3);
		$this->addReference('sensor4', $sensor4);
	}

	public function getOrder()
	{
		return 2;
	}
}