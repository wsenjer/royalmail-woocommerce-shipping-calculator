<?php


use WPRuby\RoyalMailLite\DVDoug\BoxPacker\Box;

class WPRuby_RoyalMail_Box implements Box
{
	private $reference;
	private $outer_width;
	private $outer_length;
	private $outer_depth;
	private $empty_weight;
	private $inner_width;
	private $inner_length;
	private $inner_depth;
	private $max_weight;

	public function getReference() {
		return $this->reference;
	}

	public function getOuterWidth() {
		return $this->outer_width;
	}

	public function getOuterLength() {
		return $this->outer_length;
	}

	public function getOuterDepth() {
		return $this->outer_depth;
	}

	public function getEmptyWeight() {
		return $this->empty_weight;
	}

	public function getInnerWidth() {
		return $this->inner_width;
	}

	public function getInnerLength() {
		return $this->inner_length;
	}

	public function getInnerDepth() {
		return $this->inner_depth;
	}

	public function getInnerVolume() {
		return $this->getInnerLength() * $this->getInnerWidth() * $this->getInnerDepth();
	}

	public function getMaxWeight() {
		return $this->max_weight;
	}

	/**
	 * @param mixed $reference
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setReference( $reference ) {
		$this->reference = $reference;

		return $this;
	}

	/**
	 * @param mixed $outer_width
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setOuterWidth( $outer_width ) {
		$this->outer_width = $outer_width;

		return $this;
	}

	/**
	 * @param mixed $outer_length
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setOuterLength( $outer_length ) {
		$this->outer_length = $outer_length;

		return $this;
	}

	/**
	 * @param mixed $outer_depth
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setOuterDepth( $outer_depth ) {
		$this->outer_depth = $outer_depth;

		return $this;
	}

	/**
	 * @param mixed $empty_weight
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setEmptyWeight( $empty_weight ) {
		$this->empty_weight = $empty_weight;

		return $this;
	}

	/**
	 * @param mixed $inner_width
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setInnerWidth( $inner_width ) {
		$this->inner_width = $inner_width;

		return $this;
	}

	/**
	 * @param mixed $inner_length
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setInnerLength( $inner_length ) {
		$this->inner_length = $inner_length;

		return $this;
	}

	/**
	 * @param mixed $inner_depth
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setInnerDepth( $inner_depth ) {
		$this->inner_depth = $inner_depth;

		return $this;
	}

	/**
	 * @param mixed $max_weight
	 *
	 * @return WPRuby_RoyalMail_Box
	 */
	public function setMaxWeight( $max_weight ) {
		$this->max_weight = $max_weight;

		return $this;
	}

}
