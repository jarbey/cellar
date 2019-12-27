<?php
namespace App\Command\Temperature;

use App\Command\AbstractBackgroundCommand;
use App\Command\AbstractCommand;
use App\Service\SensorDataManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDataCommand extends AbstractBackgroundCommand {

	/** @var SensorDataManager */
	private $sensor_data_manager;

    /** @var int */
    private $db_id;

    //=============

    /** @var bool */
    private $wait_interval = 10;

    /** @var string */
    protected $debug_memory_filename = '/home/pi/cellar/debug_memory_send.log';

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 * @param SensorDataManager $sensor_data_manager
	 */
	public function __construct(LoggerInterface $logger, SensorDataManager $sensor_data_manager, $db_id, $wait_interval, $debug_memory) {
		parent::__construct($logger);
		$this->sensor_data_manager = $sensor_data_manager;
		$this->db_id = $db_id;
        $this->wait_interval = $wait_interval;
        $this->debug_memory = $debug_memory;
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
                $this->loop_iteration++;

                $this->getLogger()->info('Execute send buffered data');

                $nb = $this->sensor_data_manager->serverSend($this->db_id);
                $this->getLogger()->info('Data sent : {nb}', [ 'nb' => $nb ]);

                // Memory management
                $this->debug_memory_usage();
            } catch (\Exception $e) {
                $this->getLogger()->warning('Error during sending server data : {error}', [ 'error' => $e->getMessage() ]);
            }

            // Memory management
            try {
                if (($this->loop_iteration % $this->loop_memory_flush) == 0) {
                    $this->manage_memory();
                }
            } catch (\Exception $e) {
                $this->getLogger()->warning('Memory - {error}', [ 'error' => $e->getMessage() . "\n" . $e->getTraceAsString() ]);
                break;
            }

            sleep(10);
        }
	}


    protected function flush_memory() {
        $this->sensor_data_manager->clear();
    }

}