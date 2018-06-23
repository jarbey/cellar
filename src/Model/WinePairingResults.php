<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 10/05/2018
 * Time: 20:00
 */

namespace App\Model;


use App\Entity\WineBottle;

class WinePairingResults {
    /** @var string */
    private $food;

    /** @var WinePairingResult[] */
    private $wines_best;

    /** @var WinePairingResult[] */
    private $wines_good;

    /**
     * WinePairingResult constructor.
     */
    public function __construct() {
        $this->wines_best = [];
        $this->wines_good = [];
    }

    /**
     * @return string
     */
    public function getFood() {
        return $this->food;
    }

    /**
     * @param string $food
     * @return self
     */
    public function setFood($food) {
        $this->food = $food;
        return $this;
    }

    /**
     * @return WinePairingResult[]
     */
    public function getWinesBest() {
        return $this->wines_best;
    }

    /**
     * @param WineBottle[] $wines_best
     * @return self
     */
    public function setWinesBest($wines_best) {
        $this->wines_best = $wines_best;
        return $this;
    }

    /**
     * @return WinePairingResult[]
     */
    public function getWinesGood() {
        return $this->wines_good;
    }

    /**
     * @param WineBottle[] $wines_good
     * @return self
     */
    public function setWinesGood($wines_good) {
        $this->wines_good = $wines_good;
        return $this;
    }

    /**
     * @param WinePairingResult $wine
     */
    public function addWineBest(WinePairingResult $wine) {
        $this->wines_best[] = $wine;
    }

    /**
     * @param WinePairingResult $wine
     */
    public function addWineGood(WinePairingResult $wine) {
        $this->wines_good[] = $wine;
    }
}