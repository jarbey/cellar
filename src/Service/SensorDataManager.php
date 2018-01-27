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

	/** @var \GuzzleHttp\Client $client */
	private $client;

	/** @var string */
	private $server_db;

	/**
	 * SensorManager constructor.
	 * @param LoggerInterface $logger
	 * @param SensorDataRepository $sensor_data_repository
	 */
	public function __construct(LoggerInterface $logger, SensorDataRepository $sensor_data_repository, $client, $server_db) {
		parent::__construct($logger);
		$this->sensor_data_repository = $sensor_data_repository;

		/** @var \GuzzleHttp\Client $client */
		$this->client = $client;

		$this->server_db = $server_db;
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

			// Server call
			if ($this->updateDataServer($date, $payload_parts)) {
				$this->sensor_data_repository->remove($data_list);
				$nb_sent += count($data_list);
			}
		}

		return $nb_sent;
	}

	private function updateDataServer($date, $data = []) {
		$this->getLogger()->debug("Update server data : {date} => {data}", [ 'date' => $date, 'data' => $data ]);

		$response = $this->client->get('', [
			'query' => ['db' => $this->server_db, 'date' => $date, 'data' => join(';', $data)]
		]);

		return (($response->getStatusCode() >= 200) && ($response->getStatusCode() < 400));
	}

}