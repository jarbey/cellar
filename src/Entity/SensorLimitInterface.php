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
	public function getLowAlertValue();

	/**
	 * @return float
	 */
	public function getHighAlertValue();

	/**
	 * @return float
	 */
	public function getLowWarningValue();

	/**
	 * @return float
	 */
	public function getHighWarningValue();
}