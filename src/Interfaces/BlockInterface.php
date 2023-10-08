<?php
/**
 * Interface BlockInterface
 *
 * Defines the methods that a block class should implement.
 *
 * @package Emplement\eQuotes\Interfaces
 * @since 1.0.0
 */
namespace Emplement\eQuotes\Interfaces;

interface BlockInterface {

	/**
	 * Get the name of the block.
	 *
	 * @since 1.0.0
	 *
	 * @return string The block's name.
	 */
	public function name() : string;

	/**
	 * Get the block's settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array The block's settings.
	 */
	public function settings() : array;

	/**
	 * Initialize the block.
	 *
	 * @since 1.0.0
	 */
	public function init();

	/**
	 * Register the block.
	 *
	 * @since 1.0.0
	 */
	public function register_block();

	/**
	 * Register admin scripts and styles for the block.
	 *
	 * @since 1.0.0
	 */
	public function register_admin_script();
}
