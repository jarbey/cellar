<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 27/12/2017
 * Time: 22:21
 */

namespace App\Entity;


class DisplayPosition implements \JsonSerializable {

	/** @var integer */
	private $x;

	/** @var integer */
	private $y;

	/** @var integer */
	private $angle;

	function jsonSerialize() {
		return (object)[
			'x' => $this->getX(),
			'y' => $this->getY(),
			'angle' => $this->getAngle(),
		];
	}

	/**
	 * DisplayColor constructor.
	 * @param int $x
	 * @param int $y
	 */
	public function __construct($x, $y, $angle = 0) {
		$this->x = $x;
		$this->y = $y;
		$this->angle = $angle;
	}

	/**
	 * @return int
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * @param int $x
	 * @return DisplayPosition
	 */
	public function setX($x) {
		$this->x = $x;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 * @param int $y
	 * @return DisplayPosition
	 */
	public function setY($y) {
		$this->y = $y;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getAngle() {
		return $this->angle;
	}

	/**
	 * @param int $angle
	 * @return DisplayPosition
	 */
	public function setAngle($angle) {
		$this->angle = $angle;

		return $this;
	}

}