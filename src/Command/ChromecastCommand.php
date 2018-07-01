<?php
namespace App\Command;

use App\Service\ChromecastManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChromecastCommand extends AbstractCommand {

    /** @var ChromecastManager */
    private $chromecastManager;

    /**
     * ChromecastCommand constructor.
     * @param LoggerInterface $logger
     * @param ChromecastManager $chromecastManager
     */
    public function __construct(LoggerInterface $logger, ChromecastManager $chromecastManager) {
        parent::__construct($logger);
        $this->chromecastManager = $chromecastManager;
    }

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cellar:chromecast')
            ->setDescription('Launch chromecast')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Start Chromecast</info>');
        $this->chromecastManager->DMP->play('https://cellar.arbey.fr/1');
    }
}
