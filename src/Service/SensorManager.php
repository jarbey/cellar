<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;


use App\Entity\SensorData;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SensorManager {

	/** @var string */
	private $sensor_script;

	/** @var integer[] */
	private $gpios;

	/**
	 * SensorManager constructor.
	 * @param $sensor_script
	 */
	public function __construct($sensor_script, $gpios) {
		$this->sensor_script = $sensor_script;
		$this->gpios = explode(',', $gpios);
	}

	/**
	 * @return SensorData[]
	 */
	public function executeSensor() {
		$datas = [];

		foreach ($this->gpios as $gpio) {
			$process = new Process($this->sensor_script . ' 22 ' . $gpio);
			$process->run();

			// executes after the command finishes
			if (!$process->isSuccessful()) {
				throw new ProcessFailedException($process);
			}

			list($temperature, $humidity) = $process->getOutput();

			$datas[] = new SensorData(date('U'), $temperature, $humidity);
		}

		return $datas;
	}
}