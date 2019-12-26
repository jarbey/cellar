<?php
namespace App\Command\Temperature;

use App\Command\AbstractCommand;
use App\Service\SensorDataManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDataCommand extends AbstractCommand {

	/** @var SensorDataManager */
	private $sensor_data_manager;

    /** @var int */
    private $db_id;

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 * @param SensorDataManager $sensor_data_manager
	 */
	public function __construct(LoggerInterface $logger, SensorDataManager $sensor_data_manager, $db_id) {
		parent::__construct($logger);
		$this->sensor_data_manager = $sensor_data_manager;
		$this->db_id = $db_id;
	}


	protected function configure() {
		$this
			->setName('cellar:temperature:send')
			->setDescription('Send data to server')
			->setHelp('Send buffered sensor data to server')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
	    while (true) {
            try {
                $this->getLogger()->info('Execute send buffered data');

                $nb = $this->sensor_data_manager->serverSend($this->db_id);
                $this->getLogger()->info('Data sent : {nb}', [ 'nb' => $nb ]);
            } catch (\Exception $e) {
                $this->getLogger()->warning('Error during sending server data : {error}', [ 'error' => $e->getMessage() ]);
            }

            sleep(10);
        }
	}


}