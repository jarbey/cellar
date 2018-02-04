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
	private $low_alert_value;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	private $high_alert_value;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	private $low_warning_value;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	private $high_warning_value;


	/**
	 * @var Sensor[]
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Sensor", mappedBy="humidity_limit")
	 */
	private $sensors;

	/**
	 * HumidityLimit constructor.
	 * @param string $name
	 * @param float $low_alert_value
	 * @param float $high_alert_value
	 * @param float $low_warning_value
	 * @param float $high_warning_value
	 */
	public function __construct($name, $low_alert_value, $high_alert_value, $low_warning_value, $high_warning_value) {
		$this->name = $name;
		$this->low_alert_value = $low_alert_value;
		$this->high_alert_value = $high_alert_value;
		$this->low_warning_value = $low_warning_value;
		$this->high_warning_value = $high_warning_value;
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
	public function getLowAlertValue(): float {
		return $this->low_alert_value;
	}

	/**
	 * @param float $low_alert_value
	 * @return HumidityLimit
	 */
	public function setLowAlertValue(float $low_alert_value): HumidityLimit {
		$this->low_alert_value = $low_alert_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHighAlertValue(): float {
		return $this->high_alert_value;
	}

	/**
	 * @param float $high_alert_value
	 * @return HumidityLimit
	 */
	public function setHighAlertValue(float $high_alert_value): HumidityLimit {
		$this->high_alert_value = $high_alert_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLowWarningValue(): float {
		return $this->low_warning_value;
	}

	/**
	 * @param float $low_warning_value
	 * @return HumidityLimit
	 */
	public function setLowWarningValue(float $low_warning_value): HumidityLimit {
		$this->low_warning_value = $low_warning_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHighWarningValue(): float {
		return $this->high_warning_value;
	}

	/**
	 * @param float $high_warning_value
	 * @return HumidityLimit
	 */
	public function setHighWarningValue(float $high_warning_value): HumidityLimit {
		$this->high_warning_value = $high_warning_value;

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
