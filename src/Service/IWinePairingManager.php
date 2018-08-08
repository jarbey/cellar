<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 08/05/2018
 * Time: 12:44
 */

namespace App\Service;

use App\Model\WinePairingResults;

interface IWinePairingManager {

    /**
     * @param string $food
     * @return WinePairingResults|null
     */
    public function getWineBottlesPair($food);

}