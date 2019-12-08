<?php

namespace App\Command\Temperature;

use App\Command\AbstractCommand;
use App\Server\WebSocketComponent;
use Ratchet\Client\WebSocket;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketServerCommand extends AbstractCommand {
	protected function configure() {
		$this
			->setName('cellar:temperature:websocket:server')
			->setDescription('Start websocket server');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
        $this->getLogger()->info('Start WebSocket server...');

		$server = IoServer::factory(
			new HttpServer(new WsServer(new WebSocketComponent($this->getLogger()))),
			8080,
			'127.0.0.1'
		);

		$server->run();

        $this->getLogger()->info('WebSocket server finished !');
	}
}