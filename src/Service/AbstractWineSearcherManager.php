<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\WineBottle;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7;

abstract class AbstractWineSearcherManager extends AbstractManager implements IWineSearcherManager {

    public function getPrice(WineBottle $wine_bottle) {
        list($price, $grade) = $this->getInfos($wine_bottle);
        if ($price) {
            return $price;
        }

        return null;
    }

    public function getGrade(WineBottle $wine_bottle) {
        list($price, $grade) = $this->getInfos($wine_bottle);
        if ($grade) {
            return $grade;
        }

        return null;
    }
}