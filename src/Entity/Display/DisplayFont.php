<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 27/12/2017
 * Time: 22:21
 */

namespace App\Entity\Display;


class DisplayFont implements \JsonSerializable {

	/** @var integer */
	private $size;

	function jsonSerialize() {
		return (object)[
			'size' => $this->getSize(),
		];
	}

	/**
	 * DisplayFont constructor.
	 * @param int $size
	 */
	public function __construct($size) {
		$this->size = $size;
	}

	/**
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param int $size
	 * @return DisplayFont
	 */
	public function setSize($size) {
		$this->size = $size;

		return $this;
	}

}