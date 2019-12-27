<?php
namespace App\Command\Temperature;

use App\Command\AbstractBackgroundCommand;
use App\Command\AbstractCommand;
use App\Entity\Sensor;
use App\Service\DisplayManager;
use App\Service\SensorDataManager;
use App\Service\SensorManager;
use App\Service\WebFrontManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InformationUpdateCommand extends AbstractBackgroundCommand {

	/** @var SensorManager */
	private $sensor_manager;

	/** @var SensorDataManager */
	private $sensor_data_manager;

	/** @var DisplayManager */
	private $display_manager;

	/** @var WebFrontManager */
	private $web_front_manager;

	//=============

    /** @var int */
    private $db_id;

    /** @var Sensor[] */
    private $sensors;

    //=============

    /** @var bool */
    private $wait_interval = 10;

    /** @var string */
    protected $debug_memory_filename = '/home/pi/cellar/debug_memory_get.log';

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 * @param SensorManager $sensor_manager
	 * @param SensorDataManager $sensor_data_manager
	 * @param DisplayManager $display_manager
	 * @param WebFrontManager $web_front_manager
     * @param int $db_id
     * @param bool $wait_interval
     * @param bool $debug_memory
	 */
	public function __construct(LoggerInterface $logger, SensorManager $sensor_manager, SensorDataManager $sensor_data_manager, DisplayManager $display_manager, WebFrontManager $web_front_manager, $db_id, $wait_interval, $debug_memory) {
		parent::__construct($logger);
		$this->sensor_manager = $sensor_manager;
		$this->sensor_data_manager = $sensor_data_manager;
		$this->display_manager = $display_manager;
		$this->web_front_manager = $web_front_manager;
		$this->db_id = $db_id;
        $this->wait_interval = $wait_interval;
        $this->debug_memory = $debug_memory;
	}


	protected function configure()
	{
		$this
			->setName('cellar:temperature:update')
			->setDescription('Send information to databse')
			->setHelp('This command get current values from sensor and push-it into the database')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
	    // Fetch sensors
	    $this->getSensors();

	    while (true) {
            try {
                $this->loop_iteration++;

                $this->getLogger()->info('Execute info update');

                // Get data
                $sensor_data = $this->sensor_manager->executeSensor($this->sensors);

                // Buffer data
                $this->sensor_data_manager->bufferData($sensor_data);

                // Display data
                $this->display_manager->displaySensorData($sensor_data);

                // Send to front
                $this->web_front_manager->sendData($sensor_data);

                // Clear data from memory
                $sensor_data = null;
                $this->debug_memory_usage();
            } catch (\Exception $e) {
                $this->getLogger()->warning('Error during info update : {error}', [ 'error' => $e->getMessage() . "\n" . $e->getTraceAsString() ]);
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

            sleep($this->wait_interval);
        }

	}

	private function getSensors() {
        $this->sensors = null;
	    $this->sensors = $this->sensor_manager->getSensors($this->db_id);
    }


	protected function flush_memory() {
        $this->sensor_data_manager->clear();
        $this->getSensors();
    }
}