<?php
namespace App\Command;

use App\Service\WinePairingManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WinePairingCommand extends AbstractCommand {

    /** @var WinePairingManager  */
    private $winepairing_manager;

    /**
     * WinePairingCommand constructor.
     * @param LoggerInterface $logger
     * @param WinePairingManager $winepairing_manager
     */
    public function __construct(LoggerInterface $logger, WinePairingManager $winepairing_manager) {
        parent::__construct($logger);
        $this->winepairing_manager = $winepairing_manager;
    }

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cellar:winepairing')
            ->setDescription('WinePairing command')
            ->addArgument('search', InputArgument::REQUIRED, 'Meal')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Start...</info>');

        //$this->searchplatsnetvins('magrets de canard');
        //$this->searchplatsnetvins('Boeuf Bourguignon');
        //$this->searchplatsnetvins('Côte de boeuf aux cèpes');

        $output->write($this->winepairing_manager->getWinePairing($input->getArgument('search')));
    }
}
