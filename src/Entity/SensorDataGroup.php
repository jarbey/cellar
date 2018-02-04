<?php

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;

class SensorDataGroup {

	/**
	 * @var \DateTime
	 * @Type("DateTime")
	 * @Groups({"updateSensorData"})
	 */
	private $date;

	/**
	 * @var SensorData[]
	 * @Type("array<App\Entity\SensorData>")
	 * @Groups({"updateSensorData"})
	 */
	private $sensor_data;

	/**
	 * SensorDataGroup constructor.
	 * @param \DateTime $date
	 * @param SensorData[] $sensor_data
	 */
	public function __construct(\DateTime $date = null, array $sensor_data = []) {
		$this->date = $date;
		$this->sensor_data = $sensor_data;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 * @return SensorDataGroup
	 */
	public function setDate(\DateTime $date) {
		$this->date = $date;

		return $this;
	}

	/**
	 * @return SensorData[]
	 */
	public function getSensorData() {
		return $this->sensor_data;
	}

	/**
	 * @param SensorData[] $sensor_data
	 * @return SensorDataGroup
	 */
	public function setSensorData($sensor_data) {
		$this->sensor_data = $sensor_data;

		return $this;
	}
}
