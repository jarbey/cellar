<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SensorRepository")
 */
class Sensor
{
    /**
	 * @var integer
	 *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $type;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="smallint")
	 */
	private $gpio;

	/**
	 * @var TemperatureLimit
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\TemperatureLimit", inversedBy="sensors")
	 * @ORM\JoinColumn(name="temperature_limit_id", referencedColumnName="id")
	 */
	private $temperature_limit;

	/**
	 * @var HumidityLimit
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\HumidityLimit", inversedBy="sensors")
	 * @ORM\JoinColumn(name="humidity_limit_id", referencedColumnName="id")
	 */
	private $humidity_limit;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Sensor
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return Sensor
	 */
	public function setType($type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getGpio() {
		return $this->gpio;
	}

	/**
	 * @param int $gpio
	 * @return Sensor
	 */
	public function setGpio($gpio) {
		$this->gpio = $gpio;

		return $this;
	}

	/**
	 * @return TemperatureLimit
	 */
	public function getTemperatureLimit() {
		return $this->temperature_limit;
	}

	/**
	 * @param TemperatureLimit $temperature_limit
	 * @return Sensor
	 */
	public function setTemperatureLimit($temperature_limit) {
		$this->temperature_limit = $temperature_limit;

		return $this;
	}

	/**
	 * @return HumidityLimit
	 */
	public function getHumidityLimit() {
		return $this->humidity_limit;
	}

	/**
	 * @param HumidityLimit $humidity_limit
	 * @return Sensor
	 */
	public function setHumidityLimit($humidity_limit) {
		$this->humidity_limit = $humidity_limit;

		return $this;
	}

}
