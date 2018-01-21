<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 26/12/2017
 * Time: 20:52
 */

namespace App\Service;


use App\Entity\Display;
use App\Entity\DisplayColor;
use App\Entity\DisplayFont;
use App\Entity\DisplayPosition;
use App\Entity\SensorData;
use Psr\Log\LoggerInterface;

class DisplayManager extends AbstractManager {
	const FONT_SIZE_DATE = 18;
	const FONT_SIZE_DATA = 56;
	const FONT_MARGIN_DATA = 4;
	const OFFSET_DATA = (self::FONT_SIZE_DATA + self::FONT_MARGIN_DATA) / 2;

	private $_socket;

	private $screen_orientation;

	/**
	 * DisplayManager constructor.
	 * @param LoggerInterface $logger
	 * @param integer $screen_orientation
	 */
	public function __construct(LoggerInterface $logger, $screen_orientation) {
		parent::__construct($logger);
		$this->screen_orientation = $screen_orientation;
	}

	private function init() {
		$this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		// Create a TCP/IP socket.
		if ($this->_socket === false) {
			throw new \Exception("Error: socket_create() failed: reason: " . socket_strerror(socket_last_error()));
		}
		// Connect to the server running on the 'bot
		$result = socket_connect($this->_socket, '127.0.0.1', '1111');
		if ($result === false) {
			throw new \Exception("Error: socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->_socket)));
		}
	}

	/**
	 * @param Display[] $display_list
	 * @return string
	 */
	public function sendDisplay($display_list = array()) {
		if (!$this->_socket) {
			$this->init();
		}

		$json = json_encode((object)['display' => $display_list, 'screen_rotation' => $this->screen_orientation]);

		$this->getLogger()->debug('Send to display with json => {json}', ['json' => $json]);

		socket_write($this->_socket, $json);
		return socket_read($this->_socket, 1);
	}

	/**
	 * @param SensorData[] $datas
	 */
	public function displaySensorData($datas = []) {
		$display_data = [new Display(date('d/m/Y H:i:s'), new DisplayFont(self::FONT_SIZE_DATE), new DisplayPosition(300, 0), DisplayColor::white())];

		$y_offset = (count($datas) - 1) * self::OFFSET_DATA * -1;
		/** @var SensorData $data */
		foreach ($datas as $data) {
			$this->getLogger()->debug('Gpio {gpio} : T {temperature} ; H {humidity} ; Tlim {t_limit} ; Hlim {h_limit}', [
				'gpio' => $data->getSensor()->getGpio(),
				'temperature' => $data->getTemperature(),
				'humidity' => $data->getHumidity(),
				't_limit' => $data->getSensor()->getTemperatureLimit(),
				'h_limit' => $data->getSensor()->getHumidityLimit(),
			]);

			$display_data[] = new Display($data->getTemperature() . "°C", new DisplayFont(self::FONT_SIZE_DATA), new DisplayPosition(30, 130 + $y_offset), $data->getSensor()->getTemperatureLimit()->getColor($data->getTemperature()));
			$display_data[] = new Display($data->getHumidity() . " %", new DisplayFont(self::FONT_SIZE_DATA), new DisplayPosition(260, 130 + $y_offset), $data->getSensor()->getHumidityLimit()->getColor($data->getTemperature()));

			$y_offset += self::FONT_SIZE_DATA + self::FONT_MARGIN_DATA;
		}

		$this->sendDisplay($display_data);
	}

	function __destruct() {
		if ($this->_socket) {
			socket_close($this->_socket);
		}
	}

}