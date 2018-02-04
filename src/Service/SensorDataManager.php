<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\SensorData;
use App\Entity\SensorDataGroup;
use App\Exception\ServerException;
use App\Repository\SensorDataRepository;
use GuzzleHttp\Exception\RequestException;
use JMS\Serializer\SerializationContext;
use Psr\Log\LoggerInterface;
use JMS\Serializer\SerializerInterface;
use GuzzleHttp\Psr7;

class SensorDataManager extends AbstractManager {

	/** @var SensorDataRepository */
	private $sensor_data_repository;

	/** @var SerializerInterface */
	private $serializer;

	/** @var \GuzzleHttp\Client $client */
	private $client;

	/** @var string */
	private $db_id;

	/**
	 * SensorManager constructor.
	 * @param LoggerInterface $logger
	 * @param SensorDataRepository $sensor_data_repository
	 */
	public function __construct(LoggerInterface $logger, SensorDataRepository $sensor_data_repository, SerializerInterface $serializer, $client, $db_id) {
		parent::__construct($logger);
		$this->sensor_data_repository = $sensor_data_repository;
		$this->serializer = $serializer;

		/** @var \GuzzleHttp\Client $client */
		$this->client = $client;

		$this->db_id = $db_id;
	}

	/**
	 * @param SensorData[] $data
	 */
	public function bufferData($data = []) {
		$this->sensor_data_repository->save($data);
	}

	/**
	 * @return int
	 * @throws ServerException
	 */
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
		foreach ($grouped_sensor_data as $date => $sensor_data_list) {
			$sensor_data_group = new SensorDataGroup(new \DateTime('@' . $date), $sensor_data_list);

			// Server call
			if (!$this->updateDataServer($sensor_data_group)) {
				throw new ServerException();
			}

			// Delete sent data
			//$this->sensor_data_repository->remove($sensor_data_list);
			$nb_sent += count($sensor_data_list);
		}

		return $nb_sent;
	}

	/**
	 * @param SensorDataGroup $sensor_data_group
	 * @return bool
	 */
	private function updateDataServer(SensorDataGroup $sensor_data_group) {
		$this->getLogger()->debug("Update server data for date {date}", [ 'date' => $sensor_data_group->getDate() ]);

		$payload = $this->serializer->serialize($sensor_data_group, 'json', SerializationContext::create()->setGroups(['updateSensorData']));

		$this->getLogger()->debug("With data {data}", [ 'data' => $payload ]);

		try {
			// PUT /{db_id}/{timestamp}
			$response = $this->client->put($this->db_id . '/' . $sensor_data_group->getDate()->getTimestamp(), [
				'body' => $payload
			]);

			$status = (($response->getStatusCode() >= 200) && ($response->getStatusCode() < 400));
			echo '-> ' . $response->getBody();
			if ($status) {

			}

			return $status;
		} catch (RequestException $e) {
			echo Psr7\str($e->getRequest());
			if ($e->hasResponse()) {
				echo Psr7\str($e->getResponse());
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}


	}

}