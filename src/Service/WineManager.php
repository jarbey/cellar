<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;


use App\Entity\Bottle;
use App\Entity\Sensor;
use App\Entity\SensorData;
use App\Entity\SensorDataGroup;
use App\Entity\WineArea;
use App\Entity\WineColor;
use App\Repository\BottleRepository;
use App\Repository\SensorRepository;
use App\Repository\WineAreaRepository;
use App\Repository\WineBottleRepository;
use App\Repository\WineColorRepository;
use App\Repository\WineStockRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WineManager extends AbstractManager {

    /** @var BottleRepository */
    private $bottle_repository;

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
     * @param BottleRepository $bottle_repository
	 * @param WineColorRepository $wine_color_repository
	 * @param WineAreaRepository $wine_area_repository
	 * @param WineBottleRepository $wine_bottle_repository
	 * @param WineStockRepository $wine_stock_repository
	 */
	public function __construct(LoggerInterface $logger, BottleRepository $bottle_repository, WineColorRepository $wine_color_repository, WineAreaRepository $wine_area_repository, WineBottleRepository $wine_bottle_repository, WineStockRepository $wine_stock_repository) {
		parent::__construct($logger);
        $this->bottle_repository = $bottle_repository;
		$this->wine_color_repository = $wine_color_repository;
		$this->wine_area_repository = $wine_area_repository;
		$this->wine_bottle_repository = $wine_bottle_repository;
		$this->wine_stock_repository = $wine_stock_repository;
	}

    /**
     * @param $csvFile
     */
    public function importCSV($csvFile) {
        $data = [];
        $first_line = [];
        $is_first = true;
        foreach ($csvFile as $line) {
            if ($is_first) {
                $is_first = false;
                $first_line = str_getcsv($line, "\t");
            } else {
                $line_data = [];
                foreach (str_getcsv($line, "\t") as $key => $value) {
                    $line_data[$first_line[$key]] = $value;
                }
                $data[] = $line_data;
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
            $bottle = $this->getBottle($volume);

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

        }
    }

    /**
     * @param $capacity
     * @return Bottle|null
     */
    public function getBottle($capacity) {
        return $this->bottle_repository->getBottleByCapacity($capacity);
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


}