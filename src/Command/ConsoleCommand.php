<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 24/12/2017
 * Time: 13:33
 */

namespace App\Command;

use App\Service\DisplayManager;
use App\Service\SensorDataManager;
use App\Service\SensorManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConsoleCommand extends AbstractCommand {


	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger) {
		parent::__construct($logger);
	}


	protected function configure() {
		$this
			->setName('cellar:console')
			->setDescription('Console')
			->setHelp('Console to manage actions (stock...)')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		try {
			$helper = $this->getHelper('question');

			$question = new Question('Action :');
			$question->setValidator(function ($value) {
				if (trim($value) == '') {
					throw new \Exception('Cannot be empty...');
				}

				return $value;
			});
			$question->setHidden(true);
			$question->setMaxAttempts(20);

			$password = $helper->ask($input, $output, $question);
			echo '-';

		} catch (\Exception $e) {
			$this->getLogger()->warning('Error during get console input : {error}', [ 'error' => $e->getMessage() ]);
		}

	}


}