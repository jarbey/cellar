<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 27/12/2017
 * Time: 18:07
 */

namespace App\Entity;


class SensorData {

	/** @var integer */
	private $date;

	/** @var integer */
	private $gpio;

	/** @var float */
	private $temperature;

	/** @var float */
	private $humidity;

	/**
	 * SensorData constructor.
	 * @param int $date
	 * @param int $gpio
	 * @param float $temperature
	 * @param float $humidity
	 */
	public function __construct($date, $gpio, $temperature, $humidity) {
		$this->date = $date;
		$this->gpio = $gpio;
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
	 * @return int
	 */
	public function getGpio() {
		return $this->gpio;
	}

	/**
	 * @param int $gpio
	 * @return SensorData
	 */
	public function setGpio($gpio) {
		$this->gpio = $gpio;

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