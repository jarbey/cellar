<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 21/01/2018
 * Time: 18:49
 */

namespace App\Entity;

use App\Entity\Display\DisplayColor;

abstract class AbstractSensorLimit implements SensorLimitInterface {

	/**
	 * @param $value
	 * @return DisplayColor
	 */
	public function getColor($value) {
		if (($value > $this->getHighAlertValue()) || ($value < $this->getLowAlertValue())) {
			return DisplayColor::red();
		} else if (($value > $this->getHighWarningValue()) || ($value < $this->getLowWarningValue())) {
			return DisplayColor::orange();
		}
		// Default
		return DisplayColor::green();
	}

	function __toString() {
		return $this->getLowAlertValue() . ' < ' . $this->getLowWarningValue() . ' < X < ' . $this->getHighWarningValue() . ' < ' . $this->getHighAlertValue();
	}


}