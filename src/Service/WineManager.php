<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

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

class WineManager extends AbstractManager {

    /** @var BottleSizeRepository */
    private $bottle_size_repository;

	/** @var WineColorRepository */
	private $wine_color_repository;

	/** @var WineAreaRepository */
	private $wine_area_repository;

	/** @var WineBottleRepository */
	private $wine_bottle_repository;

	/** @var WineStockRepository */
	private $wine_stock_repository;

	/**
	 * WineManager constructor.
	 * @param LoggerInterface $logger
     * @param BottleSizeRepository $bottle_size_repository
	 * @param WineColorRepository $wine_color_repository
	 * @param WineAreaRepository $wine_area_repository
	 * @param WineBottleRepository $wine_bottle_repository
	 * @param WineStockRepository $wine_stock_repository
	 */
	public function __construct(LoggerInterface $logger, BottleSizeRepository $bottle_size_repository, WineColorRepository $wine_color_repository, WineAreaRepository $wine_area_repository, WineBottleRepository $wine_bottle_repository, WineStockRepository $wine_stock_repository) {
		parent::__construct($logger);
        $this->bottle_size_repository = $bottle_size_repository;
		$this->wine_color_repository = $wine_color_repository;
		$this->wine_area_repository = $wine_area_repository;
		$this->wine_bottle_repository = $wine_bottle_repository;
		$this->wine_stock_repository = $wine_stock_repository;
	}

    /**
     * @param array $lines
     */
    public function importCSV($lines) {
        $data = [];
        $first_line = [];
        $is_first = true;
        $nb_cols = 0;
        foreach ($lines as $line) {
            if ($is_first) {
                $is_first = false;
                $first_line = str_getcsv($line, "\t");
                $nb_cols = count($first_line);
            } else {
                $line_data = [];
                $raw_line_data = str_getcsv($line, "\t");
                if (count($raw_line_data) == $nb_cols) {
                    foreach ($raw_line_data as $key => $value) {
                        $line_data[$first_line[$key]] = $value;
                    }
                    $data[] = $line_data;
                }
            }
        }

        // Create
        foreach ($data as $stock) {
            list($volume, $unit) = explode(' ', $stock['volume']);
            $volume = floatval(str_replace(',', '.', $volume));
            if ($unit == 'l') {
                $volume *= 100;
            }

            // Bottle
            $bottle_size = $this->getBottleSize($volume);

            // Color
            $wine_color = $this->getWineColor($stock['label']);
            if (!$wine_color) {
                $wine_color = $this->wine_color_repository->create($stock['label']);
            }

            // Area
            $wine_area = $this->getWineArea($stock['country_label'], $stock['subregion_label'], $stock['area_label']);
            if (!$wine_area) {
                $wine_area = $this->wine_area_repository->create($stock['country_label'], $stock['subregion_label'], $stock['area_label']);
            }

            // Wine bottle
            $wine_bottle = $this->getWineBottle($stock['nomCru'], $wine_area, $wine_color, $bottle_size, $stock['millesime']);
            if (!$wine_bottle) {
                $wine_bottle = $this->wine_bottle_repository->create($stock['nomCru'], $wine_area, $wine_color, $bottle_size, $stock['millesime'], $stock['garde_min'], $stock['garde_max'], $stock['garde_optimum']);
            }

            // Stock
            $this->wine_stock_repository->create($wine_bottle,
                new \DateTime($stock['date_achat']), $stock['prix'],
                $stock['quantite_courante'], $stock['quantite_achat'],
                $stock['comment'], $stock['lieu_achat'], $stock['canal_vente']
            );
        }
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
     * @return WineBottleRepository
     */
    public function getWineBottleRepository() {
        return $this->wine_bottle_repository;
    }


}