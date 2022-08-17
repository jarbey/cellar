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
     * @var int
     * @Groups({"updateSensorData"})
     */
    private $db_id;

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

    /**
     * @return int
     */
    public function getDbId()
    {
        return $this->db_id;
    }

    /**
     * @param int $db_id
     * @return SensorDataGroup
     */
    public function setDbId(int $db_id)
    {
        $this->db_id = $db_id;
        return $this;
    }
}
