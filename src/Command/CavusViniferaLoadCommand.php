<?php
namespace App\Command;

use App\Entity\Bottle;
use App\Service\ChromecastManager;
use App\Service\WineManager;
use App\Utils\StringUtils;
use Psr\Log\LoggerInterface;
use Stringy\StaticStringy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CavusViniferaLoadCommand extends AbstractCommand {

    /** @var WineManager */
    private $wine_manager;

    /**
     * CavusViniferaLoadCommand constructor.
     * @param LoggerInterface $logger
     * @param WineManager $wine_manager
     */
    public function __construct(LoggerInterface $logger, WineManager $wine_manager) {
        parent::__construct($logger);
        $this->wine_manager = $wine_manager;
    }

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cavusvinifera:load')
            ->setDescription('Load data from cavusvinifera')
            ->addArgument('file', InputArgument::REQUIRED, 'File to load')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Start Cavus Vinifera load</info>');

        // CSV Parsing
        $this->wine_manager->importCSV(file($input->getArgument('file')));
    }
}
