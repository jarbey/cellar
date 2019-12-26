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
use App\Entity\SensorDataGroup;
use App\Repository\SensorRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SensorManager extends AbstractManager {

	/** @var Process */
	private $process;

	/** @var string */
	private $sensor_script;

	/** @var SensorRepository */
	private $sensor_repository;

    /** @var string */
    private $db_id;

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
     * @param $db_id
     * @return Sensor[]
     */
	public function getSensors($db_id) {
	    return $this->sensor_repository->getAllDbSensors($db_id);
    }

    /**
     * @param Sensor[] $sensors
     * @return SensorDataGroup
     */
	public function executeSensor($sensors) {
		$this->getLogger()->debug('ENTER executeSensor for ' . count($sensors) . ' GPIOs');

		// EXECUTE COMMAND
        if (!$this->process) {
            // CONSTRUCT COMMAND ARGS
            $cmd_args = [$this->sensor_script];
            foreach ($sensors as $sensor) {
                $cmd_args[] = $sensor->getType() . ',' . $sensor->getGpio();
            }
            $this->process = new Process($cmd_args);
        }

		$this->getLogger()->debug('executeSensor GPIO ' . $this->process->getCommandLine());
		$this->process->run();

		// executes after the command finishes
		if (!$this->process->isSuccessful()) {
			throw new ProcessFailedException($this->process);
		}

		$results = trim($this->process->getOutput());
		$this->getLogger()->debug('Results : ' . $results);

		// PARSE RESULTS
		$sensor_data = [];
		$i = 0;
		$date = time();
		foreach (explode("\n", $results) as $result) {
			list($temperature, $humidity) = explode(';', $result);
			$sensor_data[] = new SensorData($date, $sensors[$i], $temperature, $humidity);

			$i++;
		}

		$this->process->clearOutput();
		$this->process->clearErrorOutput();

		return new SensorDataGroup(new \DateTime('@' . $date), $sensor_data);
	}
}