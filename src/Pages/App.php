<?php
/**
 * App main file.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Pages
 */

namespace Emplement\eQuotes\Pages;

use \Emplement\eQuotes\Utils\AssetsManagement;

class App {

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
		add_action( 'wp_enqueue_scripts', [$this, 'load_assets'] );
	}

	/**
	 * Load styles and scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_assets() {

		// Load the app styles.
		$this->assets_management->enqueue_style(
			'e-quotes-app',
			$this->assets_management->css_url( 'app.css' )
		);

		// Register the JS namespace and settings object.
		$this->assets_management->register_js_namespace();

		// Load our namespace.
		$this->assets_management->enqueue_script(
			'e-quotes-app',
			$this->assets_management->js_url( 'app.js' ),
			[ 'e-quotes' ]
		);
	}
}
