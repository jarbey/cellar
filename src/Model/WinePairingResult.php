<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 10/05/2018
 * Time: 20:00
 */

namespace App\Model;


use App\Entity\WineArea;
use App\Entity\WineBottle;
use App\Entity\WineColor;

class WinePairingResult {
    /** @var WineColor */
    private $wine_color;

    /** @var WineArea */
    private $wine_area;

    /** @var WineBottle */
    private $bottles;

    /**
     * @return WineColor
     */
    public function getWineColor() {
        return $this->wine_color;
    }

    /**
     * @param WineColor $wine_color
     * @return WinePairingResult
     */
    public function setWineColor($wine_color) {
        $this->wine_color = $wine_color;
        return $this;
    }

    /**
     * @return WineArea
     */
    public function getWineArea() {
        return $this->wine_area;
    }

    /**
     * @param WineArea $wine_area
     * @return WinePairingResult
     */
    public function setWineArea($wine_area) {
        $this->wine_area = $wine_area;
        return $this;
    }

    /**
     * @return WineBottle
     */
    public function getBottles() {
        return $this->bottles;
    }

    /**
     * @param WineBottle $bottles
     * @return WinePairingResult
     */
    public function setBottles($bottles) {
        $this->bottles = $bottles;
        return $this;
    }
}