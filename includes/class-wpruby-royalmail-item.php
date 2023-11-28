<?php
namespace WPRubyRoyalMail\Includes;

use WPRubyRoyalMail\Build\DVDoug\BoxPacker\Item;

class WPRuby_RoyalMail_Item implements Item
{

	private $description;
	private $width;
	private $length;
	private $depth;
	private $weight;
	private $volume;
	private $keep_flat;
	private $price;

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($price)
	{
		$this->price = $price;
		return $this;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getWidth() {
		return $this->width;
	}

	public function getLength() {
		return $this->length;
	}

	public function getDepth() {
		return $this->depth;
	}

	public function getWeight() {
		return $this->weight;
	}

	public function getVolume() {
		return $this->volume;
	}

	public function getKeepFlat() {
		return $this->keep_flat;
	}

	/**
	 * @param string $description
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setDescription( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @param int $width
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setWidth( $width ) {
		$this->width = $width;

		return $this;
	}

	/**
	 * @param int $length
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setLength( $length ) {
		$this->length = $length;

		return $this;
	}

	/**
	 * @param int $depth
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setDepth( $depth ) {
		$this->depth = $depth;

		return $this;
	}

	/**
	 * @param int $weight
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setWeight( $weight ) {
		$this->weight = $weight;

		return $this;
	}

	/**
	 * @param int $volume
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setVolume( $volume ) {
		$this->volume = $volume;

		return $this;
	}

	/**
	 * @param boolean $keep_flat
	 *
	 * @return WPRuby_RoyalMail_Item
	 */
	public function setKeepFlat( $keep_flat ) {
		$this->keep_flat = $keep_flat;

		return $this;
	}


}
