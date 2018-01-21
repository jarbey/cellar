<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 24/12/2017
 * Time: 13:33
 */

namespace App\Command;

use App\Service\DisplayManager;
use App\Service\SensorManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InformationUpdateCommand extends AbstractCommand {

	/** @var SensorManager */
	private $sensor_manager;

	/** @var DisplayManager */
	private $display_manager;

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 * @param SensorManager $sensor_manager
	 * @param DisplayManager $display_manager
	 */
	public function __construct(LoggerInterface $logger, SensorManager $sensor_manager, DisplayManager $display_manager) {
		parent::__construct($logger);
		$this->sensor_manager = $sensor_manager;
		$this->display_manager = $display_manager;
	}


	protected function configure()
	{
		$this
			->setName('cellar:information:update')
			->setDescription('Send information to databse')
			->setHelp('This command get current values from sensor and push-it into the database')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		while (true) {
			try {
				$this->getLogger()->info('Execute info update');

				$data = $this->sensor_manager->executeSensor();
				$this->getLogger()->info('Data : {data}', [ 'data' => $data ]);

				$this->display_manager->displaySensorData($data);
			} catch (\Exception $e) {
				$this->getLogger()->warning('Error during info update : {error}', [ 'error' => $e->getTraceAsString() ]);
			}

			sleep(5);
		}

	}


}