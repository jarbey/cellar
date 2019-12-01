<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 26/12/2017
 * Time: 20:52
 */

namespace App\Service;

use App\Entity\Display\Display;
use App\Entity\Display\DisplayColor;
use App\Entity\Display\DisplayFont;
use App\Entity\Display\DisplayPosition;
use App\Entity\SensorData;
use App\Entity\SensorDataGroup;
use Psr\Log\LoggerInterface;

class DisplayManager extends AbstractManager {
	const FONT_SIZE_DATE = 18;
	const FONT_SIZE_DATA = 56;
	const FONT_MARGIN_DATA = 4;
	const OFFSET_DATA = (self::FONT_SIZE_DATA + self::FONT_MARGIN_DATA) / 2;

	/** @var resource */
	private $_socket;

	/** @var bool */
	private $screen_enable;

	/** @var int */
	private $screen_orientation;

	/**
	 * DisplayManager constructor.
	 * @param LoggerInterface $logger
	 * @param boolean $screen
	 * @param integer $screen_orientation
	 */
	public function __construct(LoggerInterface $logger, $screen, $screen_orientation) {
		parent::__construct($logger);
		$this->screen_enable = !!$screen;
		$this->screen_orientation = $screen_orientation;
	}

	/**
	 * Initialize communication with screen
	 *
	 * @throws \Exception
	 */
	private function init() {
		if ($this->screen_enable) {
			// Create a TCP/IP socket
			$this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if ($this->_socket === false) {
				throw new \Exception("Error: socket_create() failed: reason: " . socket_strerror(socket_last_error()));
			}

			// Set timeout
			socket_set_option($this->_socket, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 2, "usec" => 0]);

			// Connect to the server
			$result = socket_connect($this->_socket, '127.0.0.1', '1111');
			if ($result === false) {
				throw new \Exception("Error: socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->_socket)));
			}
		}
	}

	/**
	 * @param Display[] $display_list
	 * @return string
	 */
	public function sendDisplay($display_list = []) {
		if ($this->screen_enable) {
			if (!$this->_socket) {
				$this->init();
			}

			// Create object
			$json = json_encode((object)['display' => $display_list, 'screen_rotation' => $this->screen_orientation]);
			$this->getLogger()->debug('Send to display with json => {json}', ['json' => $json]);

			// Send to display server and read result
			socket_write($this->_socket, $json);
			return socket_read($this->_socket, 1);
		}
		return null;
	}

	/**
	 * @param SensorDataGroup $sensor_data
	 */
	public function displaySensorData(SensorDataGroup $sensor_data) {
		// Date top-right
		$display_data = [new Display(date('d/m/Y H:i:s'), new DisplayFont(self::FONT_SIZE_DATE), new DisplayPosition(300, 0), DisplayColor::white())];

		// Data for each SensorData
		$y_offset = (count($sensor_data->getSensorData()) - 1) * self::OFFSET_DATA * -1;
		/** @var SensorData $data */
		foreach ($sensor_data->getSensorData() as $data) {
			$this->getLogger()->debug('Gpio {gpio} : T {temperature} ; H {humidity} ; Tlim {t_limit} ; Hlim {h_limit}', [
				'gpio' => $data->getSensor()->getGpio(),
				'temperature' => $data->getTemperature(),
				'humidity' => $data->getHumidity(),
				't_limit' => $data->getSensor()->getTemperatureLimit()->__toString(),
				'h_limit' => $data->getSensor()->getHumidityLimit()->__toString(),
			]);

			$display_data[] = new Display($data->getTemperature() . "°C", new DisplayFont(self::FONT_SIZE_DATA), new DisplayPosition(30, 130 + $y_offset), $data->getSensor()->getTemperatureLimit()->getColor($data->getTemperature()));
			$display_data[] = new Display($data->getHumidity() . " %", new DisplayFont(self::FONT_SIZE_DATA), new DisplayPosition(260, 130 + $y_offset), $data->getSensor()->getHumidityLimit()->getColor($data->getHumidity()));

			$y_offset += self::FONT_SIZE_DATA + self::FONT_MARGIN_DATA;
		}

		// Send to display
		$this->sendDisplay($display_data);
	}

	function __destruct() {
		if ($this->_socket) {
			socket_close($this->_socket);
		}
	}

}