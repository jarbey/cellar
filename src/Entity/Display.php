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

	/** @var DisplayFont */
	private $font;

	/** @var DisplayPosition */
	private $position;

	/** @var DisplayColor */
	private $color;

	function jsonSerialize() {
		return (object)[
			'text' => $this->getText(),
			'font' => $this->getFont(),
			'position' => $this->getPosition(),
			'color' => $this->getColor()
		];
	}


	/**
	 * DisplayText constructor.
	 * @param string $text
	 * @param DisplayFont $font
	 * @param DisplayPosition $position
	 * @param DisplayColor $color
	 */
	public function __construct($text, DisplayFont $font, DisplayPosition $position, DisplayColor $color) {
		$this->text = $text;
		$this->font = $font;
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

	/**
	 * @return DisplayFont
	 */
	public function getFont() {
		return $this->font;
	}

	/**
	 * @param DisplayFont $font
	 * @return Display
	 */
	public function setFont($font) {
		$this->font = $font;

		return $this;
	}

}