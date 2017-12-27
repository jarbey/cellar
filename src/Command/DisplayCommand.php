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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
		$bundle = $helper->ask($input, $output, $question);

		$this->display_manager->sendDisplay([$bundle, $bundle]);
	}


}