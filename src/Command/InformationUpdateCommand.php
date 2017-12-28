<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 24/12/2017
 * Time: 13:33
 */

namespace App\Command;


use App\Entity\Display;
use App\Entity\DisplayColor;
use App\Entity\DisplayFont;
use App\Entity\DisplayPosition;
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
			$datas = $this->sensor_manager->executeSensor();

			foreach ($datas as $data) {
				$this->getLogger()->info('Gpio {gpio} : T {temperature} ; H {humidity}', [
					'gpio' => $data->getGpio(),
					'temperature' => $data->getTemperature(),
					'humidity' => $data->getHumidity(),
				]);
				$this->display_manager->sendDisplay([
					new Display(date('d/m/Y H:i:s'), new DisplayFont(18), new DisplayPosition(280, 0), DisplayColor::white()),

					new Display($data->getTemperature() . "°C", new DisplayFont(56), new DisplayPosition(30, 100), DisplayColor::white()),
					new Display($data->getTemperature() . "°C", new DisplayFont(56), new DisplayPosition(30, 100), DisplayColor::white()),
					new Display($data->getHumidity() . " %", new DisplayFont(56), new DisplayPosition(260, 100), DisplayColor::red())
				]);
			}

			sleep(1);
		}

	}


}