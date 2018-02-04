<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 24/12/2017
 * Time: 13:33
 */

namespace App\Command;


use App\Entity\Display\Display;
use App\Entity\Display\DisplayColor;
use App\Entity\Display\DisplayFont;
use App\Entity\Display\DisplayPosition;
use App\Service\DisplayManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DisplayCommand extends AbstractCommand {

	/** @var DisplayManager */
	private $display_manager;

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 * @param DisplayManager $display_manager
	 */
	public function __construct(LoggerInterface $logger, DisplayManager $display_manager) {
		parent::__construct($logger);
		$this->display_manager = $display_manager;
	}


	protected function configure()
	{
		$this
			->setName('cellar:display')
			->setDescription('Send information to screen')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$helper = $this->getHelper('question');
		$question = new Question('Information a afficher');
		$text = $helper->ask($input, $output, $question);

		$this->display_manager->sendDisplay([
			new Display($text, new DisplayFont(36), new DisplayPosition(0, 0), DisplayColor::red()),
			new Display($text, new DisplayFont(36), new DisplayPosition(36, 36), DisplayColor::green()),
			new Display($text, new DisplayFont(36), new DisplayPosition(72, 72), DisplayColor::blue()),
		]);
	}


}