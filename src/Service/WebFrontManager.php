<?php

namespace App\Service;

use App\Entity\SensorDataGroup;
use App\Server\WebSocketClient;
use JMS\Serializer\SerializationContext;
use Psr\Log\LoggerInterface;
use JMS\Serializer\SerializerInterface;

class WebFrontManager extends AbstractManager {

	/** @var SerializerInterface */
	private $serializer;

    /** @var WebSocketClient */
    private $websocket_client;

	/**
	 * WebFrontManager constructor.
	 * @param LoggerInterface $logger
	 * @param SerializerInterface $serializer
     * @param string $websocket_host
	 */
	public function __construct(LoggerInterface $logger, SerializerInterface $serializer, $websocket_host) {
		parent::__construct($logger);
		$this->serializer = $serializer;

        $this->websocket_client = new WebSocketClient([
            'host' => $websocket_host,
            'port' => 80,
            'path' => ''
        ]);
	}

	/**
	 * @param SensorDataGroup $sensor_data
     * @return bool|string
	 */
	public function sendData(SensorDataGroup $sensor_data) {
        $this->websocket_client->send(
            $this->serializer->serialize($sensor_data, 'json', SerializationContext::create()->setGroups(['updateSensorData']))
        );
	}
}