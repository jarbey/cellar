<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 27/12/2017
 * Time: 22:21
 */

namespace App\Entity\Display;


class DisplayColor implements \JsonSerializable {
	/** @var integer */
	private $red;

	/** @var integer */
	private $green;

	/** @var integer */
	private $blue;

	function jsonSerialize() {
		return (object)[
			'r' => $this->getRed(),
			'g' => $this->getGreen(),
			'b' => $this->getBlue(),
		];
	}

	/**
	 * DisplayColor constructor.
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 */
	public function __construct($red, $green, $blue) {
		$this->red = $red;
		$this->green = $green;
		$this->blue = $blue;
	}

	/**
	 * @return int
	 */
	public function getRed() {
		return $this->red;
	}

	/**
	 * @param int $red
	 * @return DisplayColor
	 */
	public function setRed($red) {
		$this->red = $red;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getGreen() {
		return $this->green;
	}

	/**
	 * @param int $green
	 * @return DisplayColor
	 */
	public function setGreen($green) {
		$this->green = $green;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getBlue() {
		return $this->blue;
	}

	/**
	 * @param int $blue
	 * @return DisplayColor
	 */
	public function setBlue($blue) {
		$this->blue = $blue;

		return $this;
	}


	/**
	 * @return DisplayColor
	 */
	public static function red() {
		return new DisplayColor(0xFF, 0x00, 0x00);
	}

	/**
	 * @return DisplayColor
	 */
	public static function orange() {
		return new DisplayColor(0xFF, 0x7D, 0x00);
	}

	/**
	 * @return DisplayColor
	 */
	public static function green() {
		return new DisplayColor(0x35, 0x96, 0x2B);
	}

	/**
	 * @return DisplayColor
	 */
	public static function blue() {
		return new DisplayColor(0x00, 0x00, 0xFF);
	}

	/**
	 * @return DisplayColor
	 */
	public static function white() {
		return new DisplayColor(0xFF, 0xFF, 0xFF);
	}

}