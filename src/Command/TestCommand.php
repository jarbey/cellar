<?php
namespace App\Command;

use App\Repository\WineBottleRepository;
use App\Service\IDealWineManager;
use App\Service\PlatsnetvinsManager;
use App\Service\WineDeciderManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends AbstractCommand {

    /** @var IDealWineManager */
    private $idealwine_manager;

    /** @var WineDeciderManager */
    private $wine_decider_manager;

    /** @var WineBottleRepository */
    private $wine_bottle_repository;

    /** @var PlatsnetvinsManager */
    private $platsnetvins_manager;

    /**
     * CavusViniferaLoadCommand constructor.
     * @param LoggerInterface $logger
     * @param WineDeciderManager $wine_decider_manager
     */
    public function __construct(LoggerInterface $logger, IDealWineManager $idealwine_manager, WineDeciderManager $wine_decider_manager, PlatsnetvinsManager $platsnetvins_manager, WineBottleRepository $wine_bottle_repository) {
        parent::__construct($logger);
        $this->idealwine_manager = $idealwine_manager;
        $this->wine_decider_manager = $wine_decider_manager;
        $this->wine_bottle_repository = $wine_bottle_repository;
        $this->platsnetvins_manager = $platsnetvins_manager;
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

        $this->searchplatsnetvins('magrets de canard');
        //$this->searchplatsnetvins('Boeuf Bourguignon');
        //$this->searchplatsnetvins('Côte de boeuf aux cèpes');
    }

    private function searchplatsnetvins($food) {
        $result = $this->platsnetvins_manager->getWineBottlesPair($food);
        echo $result->getFood() . "\n";
        echo 'BEST' . "\n";
        foreach ($result->getWinesBest() as $wine_best) {
            echo '-> ' . $wine_best->getWineColor()->getName() . ' : ' .
                $wine_best->getWineArea()->getAreaName() . ' - ' .
                $wine_best->getWineArea()->getRegion() . ' => ' .
                count($wine_best->getWineArea()->getBottles()) . "\n";

            foreach ($wine_best->getWineArea()->getBottles() as $bottle) {
                if ($bottle->getStocks()) {
                    foreach ($bottle->getStocks() as $stock) {
                        if ($stock->getQuantityCurrent() > 0) {
                            echo '  => ' . $stock->getQuantityCurrent() . 'x ' . $bottle->getVintage() . ' - ' . $bottle->getName() . ' : (' . $bottle->getBottleSize()->getCapacity() . ' cl)' . "\n";
                        }
                    }
                }
            }

        }
        echo 'GOOD' . "\n";
        foreach ($result->getWinesGood() as $wine_best) {
            echo '-> ' . $wine_best->getWineColor()->getName() . ' : ' .
                $wine_best->getWineArea()->getAreaName() . ' - ' .
                $wine_best->getWineArea()->getRegion() . ' => ' .
                count($wine_best->getWineArea()->getBottles()) . "\n";

            foreach ($wine_best->getWineArea()->getBottles() as $bottle) {
                if ($bottle->getStocks()) {
                    foreach ($bottle->getStocks() as $stock) {
                        if ($stock->getQuantityCurrent() > 0) {
                            echo '  => ' . $stock->getQuantityCurrent() . 'x ' . $bottle->getVintage() . ' - ' . $bottle->getName() . ' : (' . $bottle->getBottleSize()->getCapacity() . ' cl)' . "\n";
                        }
                    }
                }
            }
        }
    }

    private function searchIdealwine() {
        for ($i = 87; $i < 93; $i++) {
            $wine_bottle = $this->wine_bottle_repository->find($i);
            echo $wine_bottle->getVintage() . ' -> ' . $this->idealwine_manager->getGrade($wine_bottle) . ' : ' . $this->idealwine_manager->getPrice($wine_bottle) . " €\n";
        }
    }
}
