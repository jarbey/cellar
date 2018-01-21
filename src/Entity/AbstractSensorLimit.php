<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 21/01/2018
 * Time: 18:49
 */

namespace App\Entity;


abstract class AbstractSensorLimit implements SensorLimitInterface {

	/**
	 * @param $value
	 * @return DisplayColor
	 */
	public function getColor($value) {
		if (($value > $this->getHighValue()) || ($value < $this->getLowValue())) {
			return DisplayColor::red();
		}

		return DisplayColor::white();
	}
}