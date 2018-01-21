<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;


use App\Entity\Sensor;
use App\Entity\SensorData;
use App\Repository\SensorRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SensorManager extends AbstractManager {

	/** @var string */
	private $sensor_script;

	/** @var SensorRepository */
	private $sensor_repository;

	/**
	 * SensorManager constructor.
	 * @param LoggerInterface $logger
	 * @param $sensor_script
	 * @param SensorRepository $sensor_repository
	 */
	public function __construct(LoggerInterface $logger, $sensor_script, SensorRepository $sensor_repository) {
		parent::__construct($logger);
		$this->sensor_script = $sensor_script;
		$this->sensor_repository = $sensor_repository;
	}

	/**
	 * @return SensorData[]
	 */
	public function executeSensor() {
		/** @var Sensor[] $sensors */
		$sensors = $this->sensor_repository->findAll();

		$this->getLogger()->debug('ENTER executeSensor for ' . count($sensors) . ' GPIOs');

		// CONSTRUCT COMMAND ARGS
		$cmd_args = [];
		foreach ($sensors as $sensor) {
			$cmd_args[] = $sensor->getType() . ',' . $sensor->getGpio();
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
		$sensor_data = [];
		$i = 0;
		foreach (explode("\n", $results) as $result) {
			list($temperature, $humidity) = explode(';', $result);
			$sensor_data[] = new SensorData(date('U'), $sensors[$i], $temperature, $humidity);

			$i++;
		}

		return $sensor_data;
	}
}