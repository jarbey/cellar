<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HumidityLimitRepository")
 */
class HumidityLimit extends AbstractSensorLimit
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
	 * @ORM\OneToMany(targetEntity="App\Entity\Sensor", mappedBy="humidity_limit")
	 */
	private $sensors;

	/**
	 * HumidityLimit constructor.
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
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return HumidityLimit
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
	 * @return HumidityLimit
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
	 * @return HumidityLimit
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
	 * @return HumidityLimit
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
	 * @return HumidityLimit
	 */
	public function setSensors($sensors) {
		$this->sensors = $sensors;

		return $this;
	}

}
