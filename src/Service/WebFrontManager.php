<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\SensorDataGroup;
use App\Server\WebSocketClient;
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
     * @return bool|string
	 */
	public function sendData(SensorDataGroup $sensor_data) {
        $ws = new WebSocketClient([
            'host' => $this->websocket_host,
            'port' => 80,
            'path' => ''
        ]);
        $result = $ws->send(
            $this->serializer->serialize($sensor_data, 'json', SerializationContext::create()->setGroups(['updateSensorData']))
        );
        $ws->close();

        return $result;
	}
}