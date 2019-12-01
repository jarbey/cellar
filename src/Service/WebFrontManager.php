<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\SensorDataGroup;
use JMS\Serializer\SerializationContext;
use Psr\Log\LoggerInterface;
use JMS\Serializer\SerializerInterface;

class WebFrontManager extends AbstractManager {

	/** @var SerializerInterface */
	private $serializer;

	/** @var string */
	private $websocket_host;

	/**
	 * WebFrontManager constructor.
	 * @param LoggerInterface $logger
	 * @param SerializerInterface $serializer
	 */
	public function __construct(LoggerInterface $logger, SerializerInterface $serializer, $websocket_host) {
		parent::__construct($logger);
		$this->serializer = $serializer;

		$this->websocket_host = $websocket_host;
	}

	/**
	 * @param SensorDataGroup $sensor_data
	 */
	public function sendData(SensorDataGroup $sensor_data) {
		\Ratchet\Client\connect('wss://' . $this->websocket_host)->then(function(\Ratchet\Client\WebSocket $conn) use ($sensor_data) {
			$conn->send($this->serializer->serialize($sensor_data, 'json', SerializationContext::create()->setGroups(['updateSensorData'])));
			$conn->close();
		}, function (\Exception $e) {
			echo "Could not connect: {$e->getMessage()}\n";
		});
	}
}