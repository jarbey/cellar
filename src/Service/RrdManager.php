<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\AbstractSensorLimit;
use App\Entity\Db;
use App\Entity\Sensor;
use App\Entity\SensorDataGroup;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RrdManager extends AbstractManager {

	const TEMPERATURE = 'temperature';
	const HUMIDITY = 'humidity';


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
		$this->rrdtool_bin = (trim($rrdtool_bin) != '') ? $rrdtool_bin : null;
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

		return $this->executeRrdCommand('update ' . $this->getRrdPath($db) . ' -t ' . join(':', $template_parts) . ' ' . $date . ':' . join(':', $data_parts));
	}

	/**
	 * Create rrd archive from Sensor array
	 *
	 * @param Db $db
	 * @return bool
	 */
	public function createArchive(Db $db) {
		$cmd_parts = [];
		$cmd_parts[] = 'create ' . $this->getRrdPath($db) . ' --step \'30\'';
		/** @var Sensor $sensor */
		foreach ($db->getSensors() as $sensor) {
			$cmd_parts[] = '\'DS:' . $sensor->getId() . '_t:GAUGE:60:-20:60\'';
			$cmd_parts[] = '\'DS:' . $sensor->getId() . '_h:GAUGE:60:0:100\'';
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

		if (!file_exists($this->getRrdPath($db))) {
			return $this->executeRrdCommand(join(' ', $cmd_parts));
		}
		return false;
	}

	public function graphArchive(Db $db, Sensor $sensor, $type, \DateTime $start) {
		/** @var AbstractSensorLimit $limit */
		$limit = $sensor->getTemperatureLimit();
		if ($type == self::HUMIDITY) {
			$limit = $sensor->getHumidityLimit();

			$title = 'Humidite';
			$sensor_suffix = 'h';
		} else {
			$title = 'Temperature';
			$sensor_suffix = 't';
		}

		$offset = ($limit->getHighAlertValue() - $limit->getLowAlertValue()) / 4;

		$min_value = max(0, $limit->getLowAlertValue() - $offset);
		$max_value = min($limit->getHighAlertValue() + $offset, 100);

		$options = [
			'--start ' . $start->getTimestamp(),
			'--vertical-label "' . $title . '"',
			'--height 400',
			'--width 800',
			'--lower-limit ' . $min_value,
			'--upper-limit ' . $max_value,
			'--rigid',
			'DEF:a=' . $this->getRrdPath($db) . ':' . $sensor->getId() . '_' . $sensor_suffix . ':AVERAGE'
		];

		$options = array_merge($options, $this->addCDEF(1, -999, $limit->getLowAlertValue()));
		$options = array_merge($options, $this->addCDEF(3, $limit->getLowAlertValue(), $limit->getLowWarningValue()));
		$options = array_merge($options, $this->addCDEF(5, $limit->getLowWarningValue(), $limit->getHighWarningValue()));
		$options = array_merge($options, $this->addCDEF(7, $limit->getHighWarningValue(), $limit->getHighAlertValue()));
		$options = array_merge($options, $this->addCDEF(9, $limit->getHighAlertValue(), 999));

		$options = array_merge($options, array(
			"AREA:cdef1#FF0000FF:\"\"",
			"AREA:cdef2#FF000019:\"\":STACK",
			"AREA:cdef3#FF7D00FF:\"\":STACK",
			"AREA:cdef4#FF7D0033:\"\":STACK",
			"AREA:cdef5#35962BFF:\"\":STACK",
			"AREA:cdef6#00FF0019:\"\":STACK",
			"AREA:cdef7#FF7D00FF:\"\":STACK",
			"AREA:cdef8#FF7D0033:\"\":STACK",
			"AREA:cdef9#FF0000FF:\"\":STACK",
			"AREA:cdef10#FF000019:\"\":STACK",
			"COMMENT:\"        \\n\"",
			"GPRINT:a:LAST:\"      Actuellement\:%6.1lf  \"",
			"GPRINT:a:AVERAGE:\"Moyenne\:%6.1lf  \"",
			"GPRINT:a:MAX:\"Maximum\:%6.1lf\\n\"",
			"LINE2:a#000000FF:\"\"",
		));

		return $this->executeRrdCommand('graph - ' . join(" \\\n", $options));
	}

	private function addCDEF($offset, $low_value, $high_value) {
		$A_DEF = 'a,UN,0,a,IF';

		$options = [];

		$options[] = "CDEF:cdef" . $offset . "=" . $A_DEF . "," . $low_value . "," . ($high_value - 0.00001) . ",LIMIT";
		$options[] = "CDEF:cdef" . ($offset + 1) . "=" . $A_DEF . "," . $high_value . ",LT," . $A_DEF . "," . $low_value . ",LT," . ($high_value - $low_value) . "," . $high_value . "," . $A_DEF . ",-,IF,0,IF";

		return $options;
	}

	/**
	 * @param Db $db
	 * @return string
	 */
	private function getRrdPath(Db $db) {
		return $this->data_folder . $db->getId() . '.rrd';
	}

	/**
	 * @param $cmd
	 * @return bool
	 */
	private function executeRrdCommand($cmd) {
		if ($this->rrdtool_bin != null) {
			return $this->executeCommand($this->rrdtool_bin . ' ' . $cmd);
		}
		return false;
	}

	/**
	 * @param $cmd
	 * @return string
	 */
	private function executeCommand($cmd) {
		$this->process->setCommandLine($cmd);
		$this->process->run();

		// executes after the command finishes
		if (!$this->process->isSuccessful()) {

			$this->getLogger()->debug('Error {error}', ['error' => $this->process->getErrorOutput()]);
			// Check and analyse error
			if (!(
				(strpos($this->process->getErrorOutput(), 'illegal attempt to update using time') !== false) &&
				(strpos($this->process->getErrorOutput(), 'when last update time') !== false) &&
				(strpos($this->process->getErrorOutput(), 'minimum one second step') !== false)
			)) {
				throw new ProcessFailedException($this->process);
			}

		}

		return $this->process->getOutput();
	}
}