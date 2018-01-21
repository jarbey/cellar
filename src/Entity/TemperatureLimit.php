<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TemperatureLimitRepository")
 */
class TemperatureLimit extends AbstractSensorLimit
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
	private $name;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	private $low_value;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	private $high_value;


	/**
	 * @var Sensor[]
	 *
	 * @ORM\ManyToMany(targetEntity="App\Entity\Sensor", mappedBy="temperature_limit")
	 */
	private $sensors;

	/**
	 * TemperatureLimit constructor.
	 * @param string $name
	 * @param float $low_value
	 * @param float $high_value
	 */
	public function __construct($name, $low_value, $high_value) {
		$this->name = $name;
		$this->low_value = $low_value;
		$this->high_value = $high_value;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return TemperatureLimit
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return TemperatureLimit
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLowValue() {
		return $this->low_value;
	}

	/**
	 * @param float $low_value
	 * @return TemperatureLimit
	 */
	public function setLowValue($low_value) {
		$this->low_value = $low_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHighValue() {
		return $this->high_value;
	}

	/**
	 * @param float $high_value
	 * @return TemperatureLimit
	 */
	public function setHighValue($high_value) {
		$this->high_value = $high_value;

		return $this;
	}

	/**
	 * @return Sensor[]
	 */
	public function getSensors() {
		return $this->sensors;
	}

	/**
	 * @param Sensor[] $sensors
	 * @return TemperatureLimit
	 */
	public function setSensors($sensors) {
		$this->sensors = $sensors;

		return $this;
	}
}
