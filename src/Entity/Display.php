<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 27/12/2017
 * Time: 22:21
 */

namespace App\Entity;


class Display implements \JsonSerializable {

	/** @var string */
	private $text;

	/** @var DisplayPosition */
	private $position;

	/** @var DisplayColor */
	private $color;

	function jsonSerialize() {
		return (object)[
			'text' => $this->getText(),
			'position' => $this->getPosition(),
			'color' => $this->getColor()
		];
	}


	/**
	 * DisplayText constructor.
	 * @param string $text
	 * @param DisplayPosition $position
	 * @param DisplayColor $color
	 */
	public function __construct($text, DisplayPosition $position, DisplayColor $color) {
		$this->text = $text;
		$this->position = $position;
		$this->color = $color;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @param string $text
	 * @return Display
	 */
	public function setText($text) {
		$this->text = $text;

		return $this;
	}

	/**
	 * @return DisplayPosition
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param DisplayPosition $position
	 * @return Display
	 */
	public function setPosition($position) {
		$this->position = $position;

		return $this;
	}

	/**
	 * @return DisplayColor
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param DisplayColor $color
	 * @return Display
	 */
	public function setColor($color) {
		$this->color = $color;

		return $this;
	}

}