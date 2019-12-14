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
use Doctrine\ORM\ORMException;
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
     *
     */
    public function clear() {
        $this->wine_stock_repository->truncate();
        $this->wine_bottle_repository->truncate();
        $this->wine_area_repository->truncate();
        $this->wine_color_repository->truncate();
    }

    /**
     * @param $volume
     * @param $label
     * @param $country_label
     * @param $subregion_label
     * @param $area_label
     * @param $nomCru
     * @param $millesime
     * @param $garde_min
     * @param $garde_max
     * @param $garde_optimum
     * @param $date_achat
     * @param $prix
     * @param $quantite_courante
     * @param $quantite_achat
     * @param $comment
     * @param $lieu_achat
     * @param $canal_vente
     * @return \App\Entity\WineStock
     */
    public function import($volume, $label, $country_label, $subregion_label, $area_label, $nomCru, $millesime, $garde_min, $garde_max, $garde_optimum, $date_achat, $prix,
       $quantite_courante, $quantite_achat, $comment, $lieu_achat, $canal_vente) {
        // Bottle
        $bottle_size = $this->getBottleSize($volume);
        if (!$bottle_size) throw new \Exception('Cannot found bottle size for : ' . $volume);

        // Color
        $wine_color = $this->getWineColor($label);
        if (!$wine_color) {
            $wine_color = $this->wine_color_repository->create($label);
        }

        // Area
        $wine_area = $this->getWineArea($country_label, $subregion_label, $area_label);
        if (!$wine_area) {
            $wine_area = $this->wine_area_repository->create($country_label, $subregion_label, $area_label);
        }

        // Wine bottle
        $wine_bottle = $this->getWineBottle($nomCru, $wine_area, $wine_color, $bottle_size, $millesime);
        if (!$wine_bottle) {
            $wine_bottle = $this->wine_bottle_repository->create($nomCru, $wine_area, $wine_color, $bottle_size, $millesime, $garde_min, $garde_max, $garde_optimum);
        }

        // Stock
        return $this->wine_stock_repository->create($wine_bottle,
            new \DateTime($date_achat), $prix,
            $quantite_courante, $quantite_achat,
            $comment, $lieu_achat, $canal_vente
        );
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

    /**
     * @return BottleSizeRepository
     */
    public function getBottleSizeRepository() {
        return $this->bottle_size_repository;
    }

}