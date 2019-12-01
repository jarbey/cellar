<?php
namespace App\Service;

use App\Entity\BottleSize;
use App\Entity\WineArea;
use App\Entity\WineColor;
use App\Repository\BottleSizeRepository;
use App\Repository\WineAreaRepository;
use App\Repository\WineBottleRepository;
use App\Repository\WineColorRepository;
use App\Repository\WineStockRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WinePairingManager extends AbstractManager {

    /** @var PlatsnetvinsManager */
    private $platsnetvins_manager;

	/**
	 * WineManager constructor.
	 * @param LoggerInterface $logger
     * @param PlatsnetvinsManager $platsnetvins_manager
	 */
	public function __construct(LoggerInterface $logger, PlatsnetvinsManager $platsnetvins_manager) {
		parent::__construct($logger);
        $this->platsnetvins_manager = $platsnetvins_manager;
	}


    public function getWinePairing($search) {
        $result = $this->platsnetvins_manager->getWineBottlesPair($search);
        $raw_result = $result->getFood() . "\n";
        $raw_result .= 'BEST' . "\n";
        foreach ($result->getWinesBest() as $wine_best) {
            $raw_result .= '-> ' . $wine_best->getWineColor()->getName() . ' : ' .
                $wine_best->getWineArea()->getAreaName() . ' - ' .
                $wine_best->getWineArea()->getRegion() . ' => ' .
                count($wine_best->getWineArea()->getBottles()) . "\n";

            foreach ($wine_best->getWineArea()->getBottles() as $bottle) {
                if ($bottle->getStocks()) {
                    foreach ($bottle->getStocks() as $stock) {
                        if ($stock->getQuantityCurrent() > 0) {
                            $raw_result .= '  => ' . $stock->getQuantityCurrent() . 'x ' . $bottle->getVintage() . ' - ' . $bottle->getName() . ' : (' . $bottle->getBottleSize()->getCapacity() . ' cl)' . "\n";
                        }
                    }
                }
            }

        }
        $raw_result .= 'GOOD' . "\n";
        foreach ($result->getWinesGood() as $wine_best) {
            $raw_result .= '-> ' . $wine_best->getWineColor()->getName() . ' : ' .
                $wine_best->getWineArea()->getAreaName() . ' - ' .
                $wine_best->getWineArea()->getRegion() . ' => ' .
                count($wine_best->getWineArea()->getBottles()) . "\n";

            foreach ($wine_best->getWineArea()->getBottles() as $bottle) {
                if ($bottle->getStocks()) {
                    foreach ($bottle->getStocks() as $stock) {
                        if ($stock->getQuantityCurrent() > 0) {
                            $raw_result .= '  => ' . $stock->getQuantityCurrent() . 'x ' . $bottle->getVintage() . ' - ' . $bottle->getName() . ' : (' . $bottle->getBottleSize()->getCapacity() . ' cl)' . "\n";
                        }
                    }
                }
            }
        }
        $raw_result .= 'OTHERS' . "\n";
        foreach ($result->getWines() as $wine_best) {
            $raw_result .= '-> ' . $wine_best->getWineColor()->getName() . ' : ' .
                $wine_best->getWineArea()->getAreaName() . ' - ' .
                $wine_best->getWineArea()->getRegion() . ' => ' .
                count($wine_best->getWineArea()->getBottles()) . "\n";

            foreach ($wine_best->getWineArea()->getBottles() as $bottle) {
                if ($bottle->getStocks()) {
                    foreach ($bottle->getStocks() as $stock) {
                        if ($stock->getQuantityCurrent() > 0) {
                            $raw_result .= '  => ' . $stock->getQuantityCurrent() . 'x ' . $bottle->getVintage() . ' - ' . $bottle->getName() . ' : (' . $bottle->getBottleSize()->getCapacity() . ' cl)' . "\n";
                        }
                    }
                }
            }
        }

        return $raw_result;
    }

    /**
     * @param $capacity
     * @return BottleSize|null
     */
    public function getBottleSize($capacity) {
        return $this->bottle_size_repository->getBottleSizeByCapacity($capacity);
    }

    /**
     * @param $name
     * @return WineColor|null
     */
    public function getWineColor($name) {
        return $this->wine_color_repository->getWineColorByName($name);
    }

    /**
     * @param $country
     * @param $region
     * @param $area
     * @return WineArea|null
     */
    public function getWineArea($country, $region, $area) {
        return $this->wine_area_repository->getWineArea($country, $region, $area);
    }

    /**
     * @param $name
     * @param WineArea $wine_area
     * @param WineColor $wine_color
     * @param BottleSize $bottle_size
     * @param $vintage
     * @return \App\Entity\WineBottle|null
     */
    public function getWineBottle($name, WineArea $wine_area, WineColor $wine_color, BottleSize $bottle_size, $vintage) {
        return $this->wine_bottle_repository->getWineBottle($name, $wine_area, $wine_color, $bottle_size, $vintage);
    }

    /**
     * @return WineColorRepository
     */
    public function getWineColorRepository() {
        return $this->wine_color_repository;
    }

    /**
     * @return WineBottleRepository
     */
    public function getWineBottleRepository() {
        return $this->wine_bottle_repository;
    }


}