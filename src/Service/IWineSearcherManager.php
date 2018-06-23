<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 08/05/2018
 * Time: 12:44
 */

namespace App\Service;

use App\Entity\WineBottle;

interface IWineSearcherManager {

    /**
     * @param WineBottle $wine_bottle
     * @return float|null
     */
    public function getPrice(WineBottle $wine_bottle);

    /**
     * @param WineBottle $wine_bottle
     * @return int|null
     */
    public function getGrade(WineBottle $wine_bottle);

    /**
     * @param WineBottle $wine_bottle
     * @return null
     */
    public function getInfos(WineBottle $wine_bottle);

}