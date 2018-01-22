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
use App\Repository\SensorDataRepository;
use App\Repository\SensorRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SensorDataManager extends AbstractManager {

	/** @var SensorDataRepository */
	private $sensor_data_repository;

	/**
	 * SensorManager constructor.
	 * @param LoggerInterface $logger
	 * @param SensorDataRepository $sensor_data_repository
	 */
	public function __construct(LoggerInterface $logger, SensorDataRepository $sensor_data_repository) {
		parent::__construct($logger);
		$this->sensor_data_repository = $sensor_data_repository;
	}

	/**
	 * @param SensorData[] $data
	 */
	public function bufferData($data = []) {
		$this->sensor_data_repository->save($data);
	}

	public function serverSend() {
		$nb_sent = 0;

		/** @var SensorData[] $sensor_data_list */
		$sensor_data_list = $this->sensor_data_repository->findBy([], [ 'date' => 'ASC' ]);

		// Group sensor data by date
		$grouped_sensor_data = [];
		foreach ($sensor_data_list as $sensor_data) {
			if (!array_key_exists($sensor_data->getDate(), $grouped_sensor_data)) {
				$grouped_sensor_data[$sensor_data->getDate()] = [];
			}

			$grouped_sensor_data[$sensor_data->getDate()][] = $sensor_data;
		}

		// Send by date
		foreach ($grouped_sensor_data as $date => $data_list) {
			$payload_parts = [];
			/** @var SensorData $sensor_data */
			foreach ($data_list as $sensor_data) {
				$payload_parts[] = join(',', [$sensor_data->getSensor()->getId(), $sensor_data->getTemperature(), $sensor_data->getHumidity()]);
			}

			$payload = join(';', $payload_parts);

			// Server call
			if ($this->updateDataServer($payload)) {
				$this->sensor_data_repository->remove($data_list);
				$nb_sent += count($data_list);
			}
		}

		return $nb_sent;
	}

	private function updateDataServer($payload) {
		$this->getLogger()->debug("Update server data : {payload}", [ 'payload' => $payload ]);

		return true;
	}

}