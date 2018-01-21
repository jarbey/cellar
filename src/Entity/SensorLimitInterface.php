<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 21/01/2018
 * Time: 18:49
 */

namespace App\Entity;


interface SensorLimitInterface {

	/**
	 * @return float
	 */
	public function getLowValue();

	/**
	 * @return float
	 */
	public function getHighValue();
}