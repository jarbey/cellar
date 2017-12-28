<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;


use App\Entity\SensorData;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SensorManager extends AbstractManager {

	/** @var string */
	private $sensor_script;

	/** @var integer[] */
	private $gpios;

	/**
	 * SensorManager constructor.
	 * @param $sensor_script
	 */
	public function __construct(LoggerInterface $logger, $sensor_script, $gpios) {
		parent::__construct($logger);
		$this->sensor_script = $sensor_script;
		$this->gpios = explode(',', $gpios);
	}

	/**
	 * @return SensorData[]
	 */
	public function executeSensor() {
		$this->getLogger()->debug('ENTER executeSensor for ' . count($this->gpios) . ' GPIOs');

		$datas = [];
		foreach ($this->gpios as $gpio) {
			$this->getLogger()->debug('executeSensor GPIO ' . $gpio);
			$process = new Process($this->sensor_script . ' 2302 ' . $gpio);
			$process->run();

			// executes after the command finishes
			if (!$process->isSuccessful()) {
				throw new ProcessFailedException($process);
			}

			$output = trim($process->getOutput());
			$this->getLogger()->debug('Ouput : ' . $output);
			list($temperature, $humidity) = explode(';', $output);

			$datas[] = new SensorData(date('U'), $gpio, $temperature, $humidity);
		}

		return $datas;
	}
}