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
	 * @ORM\ManyToMany(targetEntity="App\Entity\Sensor", mappedBy="temperature_limit")
	 */
	private $sensors;

	/**
	 * TemperatureLimit constructor.
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
	public function getLowAlertValue() {
		return $this->low_alert_value;
	}

	/**
	 * @param float $low_alert_value
	 * @return TemperatureLimit
	 */
	public function setLowAlertValue($low_alert_value) {
		$this->low_alert_value = $low_alert_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHighAlertValue() {
		return $this->high_alert_value;
	}

	/**
	 * @param float $high_alert_value
	 * @return TemperatureLimit
	 */
	public function setHighAlertValue($high_alert_value) {
		$this->high_alert_value = $high_alert_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLowWarningValue() {
		return $this->low_warning_value;
	}

	/**
	 * @param float $low_warning_value
	 * @return TemperatureLimit
	 */
	public function setLowWarningValue($low_warning_value) {
		$this->low_warning_value = $low_warning_value;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHighWarningValue() {
		return $this->high_warning_value;
	}

	/**
	 * @param float $high_warning_value
	 * @return TemperatureLimit
	 */
	public function setHighWarningValue($high_warning_value) {
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
	 * @return TemperatureLimit
	 */
	public function setSensors($sensors) {
		$this->sensors = $sensors;

		return $this;
	}
}
