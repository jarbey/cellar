<?php
namespace App\Command;

use App\Service\IDealWineManager;
use App\Service\WineManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CavusViniferaLoadCommand extends AbstractCommand {

    /** @var IDealWineManager */
    private $idealwine_manager;

    /**
     * CavusViniferaLoadCommand constructor.
     * @param LoggerInterface $logger
     * @param IDealWineManager $idealwine_manager
     */
    public function __construct(LoggerInterface $logger, IDealWineManager $idealwine_manager) {
        parent::__construct($logger);
        $this->idealwine_manager = $idealwine_manager;
    }

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cavusvinifera:load')
            ->setDescription('Load data from cavusvinifera')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Start Cavus Vinifera load</info>');

        $this->idealwine_manager->connect();
    }
}
