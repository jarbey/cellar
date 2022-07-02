<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 24/12/2017
 * Time: 13:33
 */

namespace App\Command;

use App\Repository\DbRepository;
use App\Service\RrdManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateArchiveCommand extends AbstractCommand {

    /** @var RrdManager */
    private $rrd_manager;

    /** @var DbRepository */
    private $dbRepository;

	/**
	 * InformationUpdateCommand constructor.
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger, RrdManager $rrd_manager, DbRepository $dbRepository) {
		parent::__construct($logger);
        $this->rrd_manager = $rrd_manager;
        $this->dbRepository = $dbRepository;
	}


	protected function configure() {
		$this
			->setName('cellar:create_archive')
			->setDescription('Console')
			->setHelp('Create rrdtool archive')
            ->addArgument('db_id', InputArgument::REQUIRED, 'DB id')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		try {
			$db_id = $input->getArgument('db_id');

            $db = $this->dbRepository->find($db_id);
            if ($db) {
                $this->rrd_manager->createArchive($db);
            }

		} catch (\Exception $e) {
			$this->getLogger()->warning('Error during get console input : {error}', [ 'error' => $e->getMessage() ]);
		}

	}


}