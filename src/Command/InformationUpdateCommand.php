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
use App\Entity\SensorData;
use App\Service\DisplayManager;
use App\Service\SensorManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InformationUpdateCommand extends AbstractCommand {

	const FONT_SIZE_DATE = 18;
	const FONT_SIZE_DATA = 56;
	const FONT_MARGIN_DATA = 4;
	const OFFSET_DATA = (self::FONT_SIZE_DATA + self::FONT_MARGIN_DATA) / 2;


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

			$display_data = [new Display(date('d/m/Y H:i:s'), new DisplayFont(self::FONT_SIZE_DATE), new DisplayPosition(300, 0), DisplayColor::white())];

			$y_offset = (count($datas) - 1) * self::OFFSET_DATA * -1;
			/** @var SensorData $data */
			foreach ($datas as $data) {
				$this->getLogger()->info('Gpio {gpio} : T {temperature} ; H {humidity}', [
					'gpio' => $data->getGpio(),
					'temperature' => $data->getTemperature(),
					'humidity' => $data->getHumidity(),
				]);

				$display_data[] = new Display($data->getTemperature() . "°C", new DisplayFont(self::FONT_SIZE_DATA), new DisplayPosition(30, 130 + $y_offset), DisplayColor::white());
				$display_data[] = new Display($data->getHumidity() . " %", new DisplayFont(self::FONT_SIZE_DATA), new DisplayPosition(260, 130 + $y_offset), DisplayColor::red());

				$y_offset += self::FONT_SIZE_DATA + self::FONT_MARGIN_DATA;
			}

			$this->display_manager->sendDisplay($display_data);

			sleep(5);
		}

	}


}