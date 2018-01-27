<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 24/12/2017
 * Time: 13:33
 */

namespace App\Command;

use App\Service\DisplayManager;
use App\Service\SensorDataManager;
use App\Service\SensorManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDataCommand extends AbstractCommand {

	/** @var SensorDataManager */
	private $sensor_data_manager;

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 * @param SensorDataManager $sensor_data_manager
	 */
	public function __construct(LoggerInterface $logger, SensorDataManager $sensor_data_manager) {
		parent::__construct($logger);
		$this->sensor_data_manager = $sensor_data_manager;
	}


	protected function configure() {
		$this
			->setName('cellar:data:send')
			->setDescription('Send data to server')
			->setHelp('Send buffered sensor data to server')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		while (true) {
			try {
				$this->getLogger()->info('Execute send buffered data');

				$nb = $this->sensor_data_manager->serverSend();
				$this->getLogger()->info('Data sent : {nb}', [ 'nb' => $nb ]);
			} catch (\Exception $e) {
				$this->getLogger()->warning('Error during sending server data : {error}', [ 'error' => $e->getMessage() ]);
			}

			sleep(5);
		}

	}


}