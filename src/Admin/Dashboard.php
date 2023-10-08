<?php
/**
 * Admin dashboard main file.
 *
 * This file centers global resources used within the admin pages.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Admin\Settings
 */

namespace Emplement\eQuotes\Admin;

use \Emplement\eQuotes\Utils\AssetsManagement;

class Dashboard {

	/**
	 * Asset management service.
	 *
	 * @since 1.0.0
	 *
	 * @var AssetsManagement
	 */
	private AssetsManagement $assets_management;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param AssetsManagement $assets_management
	 */
	public function __construct( AssetsManagement $assets_management ) {
		$this->assets_management = $assets_management;
	}

	/**
	 * Initialize all hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', [$this, 'load_assets'] );
	}

	/**
	 * Load styles and scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_assets() {

		// Register the JS namespace and settings object.
		$this->assets_management->register_js_namespace();

		// Load our namespace.
		$this->assets_management->enqueue_script( 'e-quotes' );
	}
}
