<?php
namespace App\Command;

use App\Service\CavusviniferaManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends AbstractCommand {


    /** @var CavusviniferaManager */
    private $cavusvinifera_manager;

    /**
     * CavusViniferaLoadCommand constructor.
     * @param LoggerInterface $logger
     * @param CavusviniferaManager $cavusvinifera_manager
     */
    public function __construct(LoggerInterface $logger, CavusviniferaManager $cavusvinifera_manager) {
        parent::__construct($logger);
        $this->cavusvinifera_manager = $cavusvinifera_manager;
    }

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('test')
            ->setDescription('test command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Start...</info>');

        $this->cavusvinifera_manager->import();
    }
}
