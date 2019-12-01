<?php
namespace App\Command\Stock;

use App\Command\AbstractCommand;
use App\Service\CavusviniferaManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCavusViniferaCommand extends AbstractCommand {


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
            ->setName('cellar:stock:import:cavusvinifera')
            ->setDescription('Import stock from cavus vinifera')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Start import...</info>');
        $nb = $this->cavusvinifera_manager->import();
        $output->writeln('<info>Done ' . $nb . ' bottles imported !</info>');
    }
}
