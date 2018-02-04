<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\Db;
use App\Entity\Sensor;
use App\Entity\SensorData;
use App\Entity\SensorDataGroup;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RrdManager extends AbstractManager {
	/** @var Process */
	private $process;

	/** @var string */
	private $rrdtool_bin;

	/** @var string */
	private $data_folder;

	/**
	 * SensorManager constructor.
	 * @param LoggerInterface $logger
	 * @param string $rrdtool_bin
	 * @param string $data_folder
	 */
	public function __construct(LoggerInterface $logger, $rrdtool_bin, $data_folder) {
		parent::__construct($logger);
		$this->rrdtool_bin = $rrdtool_bin;
		$this->data_folder = $data_folder;

		$this->process = new Process('');
	}

	/**
	 * @param Db $db
	 * @param SensorDataGroup $sensor_data_group
	 * @param integer $date
	 * @return bool
	 */
	public function updateArchive(Db $db, SensorDataGroup $sensor_data_group, $date = null) {
		if ($date == null) {
			$date = $sensor_data_group->getDate()->getTimestamp();
		}

		$template_parts = [];
		$data_parts = [];
		foreach ($sensor_data_group->getSensorData() as $data) {
			$template_parts[] = $data->getSensor()->getId() . '_t:' . $data->getSensor()->getId() . '_h';
			$data_parts[] = $data->getTemperature() . ':' . $data->getHumidity();
		}

		return $this->executeCommand($this->rrdtool_bin . ' update ' . $this->data_folder . $db->getName() . '.rrd -t ' . join(':', $template_parts) . ' ' . $date . ':' . join(':', $data_parts));
	}

	/**
	 * Create rrd archive from Sensor array
	 *
	 * @param Db $db
	 * @param Sensor[] $sensors
	 * @return bool
	 */
	public function createArchive(Db $db, $sensors) {
		$cmd_parts = [];
		$cmd_parts[] = $this->rrdtool_bin . ' create ' . $this->data_folder . $db->getName() . '.rrd --step \'30\'';
		/** @var Sensor $sensor */
		foreach ($sensors as $sensor) {
			$cmd_parts[] = 'DS:' . $sensor->getId() . '_t:GAUGE:60:-20:60\'';
			$cmd_parts[] = 'DS:' . $sensor->getId() . '_h:GAUGE:60:0:100\'';
		}
		$cmd_parts[] = '\'RRA:MIN:0.99:1:480\'';
		$cmd_parts[] = '\'RRA:MIN:0.99:10:576\'';
		$cmd_parts[] = '\'RRA:MIN:0.99:120:672\'';
		$cmd_parts[] = '\'RRA:MIN:0.99:480:720\'';
		$cmd_parts[] = '\'RRA:MIN:0.99:2880:730\'';
		$cmd_parts[] = '\'RRA:MIN:0.99:20160:1043\'';
		$cmd_parts[] = '\'RRA:AVERAGE:0.99:1:480\'';
		$cmd_parts[] = '\'RRA:AVERAGE:0.99:10:576\'';
		$cmd_parts[] = '\'RRA:AVERAGE:0.99:120:672\'';
		$cmd_parts[] = '\'RRA:AVERAGE:0.99:480:720\'';
		$cmd_parts[] = '\'RRA:AVERAGE:0.99:2880:730\'';
		$cmd_parts[] = '\'RRA:AVERAGE:0.99:20160:1043\'';
		$cmd_parts[] = '\'RRA:MAX:0.99:1:480\'';
		$cmd_parts[] = '\'RRA:MAX:0.99:10:576\'';
		$cmd_parts[] = '\'RRA:MAX:0.99:120:672\'';
		$cmd_parts[] = '\'RRA:MAX:0.99:480:720\'';
		$cmd_parts[] = '\'RRA:MAX:0.99:2880:730\'';
		$cmd_parts[] = '\'RRA:MAX:0.99:20160:1043\'';

		return $this->executeCommand(join('\\', $cmd_parts));
	}

	/**
	 * @param $cmd
	 * @return bool
	 */
	private function executeCommand($cmd) {
		$this->process->setCommandLine($cmd);
		$this->process->run();

		// executes after the command finishes
		if (!$this->process->isSuccessful()) {
			throw new ProcessFailedException($this->process);
		}

		return true;
	}
}