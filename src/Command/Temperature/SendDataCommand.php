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

    //=============

    /** @var bool */
    private $wait_interval = 10;

    /** @var bool */
    private $debug_memory = 0;

    /** @var int */
    private $loop_iteration = 0;

    /** @var int */
    private $loop_memory_flush = 10;

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
                if (($this->loop_iteration % $this->loop_memory_flush) == 0) {
                    $this->flush_memory();
                }
                $this->debug_memory_usage();
            } catch (\Exception $e) {
                $this->getLogger()->warning('Error during sending server data : {error}', [ 'error' => $e->getMessage() ]);
            }

            sleep(10);
        }
	}


    /**
     * Detach all entities, then fetch sensors and force garbage collecting
     *
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    private function flush_memory() {
        $this->sensor_data_manager->clear();

        gc_enable();
        gc_collect_cycles();
    }

    private function debug_memory_usage() {
        $mem_usage = memory_get_usage();
        file_put_contents('/home/pi/cellar/debug_memory_send.log', 'Memory usage after iteration ' . $this->loop_iteration . ': ' . round($mem_usage / 1024) . 'KB' . "\n", FILE_APPEND);
    }

}