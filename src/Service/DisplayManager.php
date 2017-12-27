<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 26/12/2017
 * Time: 20:52
 */

namespace App\Service;


use App\Entity\Display;
use Psr\Log\LoggerInterface;

class DisplayManager extends AbstractManager {

	private $_socket;

	/**
	 * DisplayManager constructor.
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger) {
		parent::__construct($logger);
	}

	private function init() {
		$this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		// Create a TCP/IP socket.
		if ($this->_socket === false) {
			throw new \Exception("Error: socket_create() failed: reason: " . socket_strerror(socket_last_error()));
		}
		// Connect to the server running on the 'bot
		$result = socket_connect($this->_socket, '172.20.10.7', '1111');
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

		$json = json_encode((object)['display' => $display_list]);

		$this->getLogger()->debug('Send to display with json => {json}', ['json' => $json]);

		socket_write($this->_socket, $json);
		return socket_read($this->_socket, 1);
	}

	function __destruct() {
		if ($this->_socket) {
			socket_close($this->_socket);
		}
	}

}