<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 27/12/2017
 * Time: 18:07
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\Entity(repositoryClass="App\Repository\SensorDataRepository")
 */
class SensorData {

	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @Groups({"updateSensorData"})
	 */
	private $date;

	/**
	 * @var Sensor
	 *
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="App\Entity\Sensor", inversedBy="sensor_data")
	 * @ORM\JoinColumn(name="sensor", referencedColumnName="id")
	 * @Groups({"updateSensorData"})
	 */
	private $sensor;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 * @Groups({"updateSensorData"})
	 */
	private $temperature;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 * @Groups({"updateSensorData"})
	 */
	private $humidity;

	/**
	 * SensorData constructor.
	 * @param int $date
	 * @param Sensor $sensor
	 * @param float $temperature
	 * @param float $humidity
	 */
	public function __construct($date, Sensor $sensor, $temperature, $humidity) {
		$this->date = $date;
		$this->sensor = $sensor;
		$this->temperature = $temperature;
		$this->humidity = $humidity;
	}

	/**
	 * @return int
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param int $date
	 * @return SensorData
	 */
	public function setDate($date) {
		$this->date = $date;

		return $this;
	}

	/**
	 * @return Sensor
	 */
	public function getSensor() {
		return $this->sensor;
	}

	/**
	 * @param Sensor $sensor
	 * @return SensorData
	 */
	public function setSensor($sensor) {
		$this->sensor = $sensor;

		return $this;
	}


	/**
	 * @return float
	 */
	public function getTemperature() {
		return $this->temperature;
	}

	/**
	 * @param float $temperature
	 * @return SensorData
	 */
	public function setTemperature($temperature) {
		$this->temperature = $temperature;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHumidity() {
		return $this->humidity;
	}

	/**
	 * @param float $humidity
	 * @return SensorData
	 */
	public function setHumidity($humidity) {
		$this->humidity = $humidity;

		return $this;
	}

}