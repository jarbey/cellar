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

	/** @var string[] */
	private $sensor_types;

	/** @var integer[] */
	private $gpios;

	/**
	 * SensorManager constructor.
	 * @param $sensor_script
	 */
	public function __construct(LoggerInterface $logger, $sensor_script, $sensor_types, $gpios) {
		parent::__construct($logger);
		$this->sensor_script = $sensor_script;
		$this->sensor_types = explode(';', $sensor_types);
		$this->gpios = explode(';', $gpios);
	}

	/**
	 * @return SensorData[]
	 */
	public function executeSensor() {
		$this->getLogger()->debug('ENTER executeSensor for ' . count($this->gpios) . ' GPIOs');

		// CONSTRUCT COMMAND ARGS
		$cmd_args = [];
		foreach ($this->gpios as $sensor_index => $gpio) {
			// TODO : Control index ==> Create structure to store sensor type + gpio ...
			$cmd_args[] = $this->sensor_types[$sensor_index] . ',' . $gpio;
		}

		// EXECUTE COMMAND
		$command = $this->sensor_script . ' ' . join(' ', $cmd_args);
		$this->getLogger()->debug('executeSensor GPIO ' . $command);

		$process = new Process($command);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
			throw new ProcessFailedException($process);
		}

		$results = trim($process->getOutput());
		$this->getLogger()->debug('Results : ' . $results);

		// PARSE RESULTS
		$datas = [];
		foreach (explode("\n", $results) as $result) {
			list($temperature, $humidity) = explode(';', $result);
			$datas[] = new SensorData(date('U'), $this->gpios[count($datas)], $temperature, $humidity);
		}

		return $datas;
	}
}