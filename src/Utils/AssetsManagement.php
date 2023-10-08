<?php
/**
 * Assets Management class.
 *
 * This file centers global resources used within the admin pages.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Utils
 */

namespace Emplement\eQuotes\Utils;

use Emplement\eQuotes\Traits\PluginHelper;

class AssetsManagement {

	// Load trait that allows us to retrieve path, url and version.
	use PluginHelper;

	/**
	 * Generates internal asset URLs for JavaScript and CSS files.
	 *
	 * This method constructs URLs for assets (JavaScript and CSS files) used internally
	 * within the plugin, based on the provided file name and subdirectory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name The name of the asset file.
	 * @param string $sub_dir   The subdirectory within the plugin's assets directory.
	 *
	 * @return string The generated asset URL.
	 */
	private function create_asset_url( string $file_name, string $sub_dir ) : string {
		return sprintf(
			'%s/assets/%s/%s',
			$this->plugin_url(),
			$sub_dir,
			$file_name
		);
	}


	/**
	 * Get the URL for JavaScript assets within the plugin.
	 *
	 * This method constructs the URL for JavaScript assets used within the plugin
	 * based on the provided file name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name The name of the JavaScript file.
	 *
	 * @return string The generated JavaScript asset URL.
	 */
	public function js_url( string $file_name ) : string {
		return $this->create_asset_url( $file_name, 'js' );
	}

	/**
	 * Get the URL for CSS assets within the plugin.
	 *
	 * This method constructs the URL for CSS assets used within the plugin
	 * based on the provided file name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name The name of the CSS file.
	 *
	 * @return string The generated CSS asset URL.
	 */
	public function css_url( string $file_name ) : string {
		return $this->create_asset_url( $file_name, 'css' );
	}

	/**
	 * Registers the JavaScript namespace script and initializes settings.
	 *
	 * This method registers the 'e-quotes' JavaScript script and initializes its settings.
	 * It encodes plugin settings as JSON and adds them as inline script data.
	 *
	 * @since 1.0.0
	 */
	public function register_js_namespace() {

		$this->register_script( 'e-quotes', $this->js_url( 'e-quotes.js' ) );

		$plugin_settings = wp_json_encode(
			[
				'currency' => get_option( 'e_quotes_currency', 'USD' ),
			]
		);

		wp_add_inline_script(
			'e-quotes',
			'eQuotes.attach("pluginSettings", ' . $plugin_settings . ')'
		);
	}

	/**
	 * Register a JavaScript file using wp_register_script().
	 *
	 * This method provides a simplified interface to register a JavaScript file with WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle       Unique handle for the script.
	 * @param string $src          URL to the JavaScript file.
	 * @param array  $dependencies (Optional) An array of script handles on which this script depends.
	 * @param string $version      (Optional) The version number for the script (default is the plugin's version).
	 * @param bool   $in_footer    (Optional) Whether to enqueue the script in the footer (default is true).
	 * @return void
	 */
	public function register_script(
		string $handle,
		string $src,
		array $dependencies = [],
		string $version = '',
		bool $in_footer = true
	) {

		wp_register_script(
			$handle,
			$src,
			$dependencies,
			empty($version) ? $this->plugin_version() : $version,
			$in_footer
		);
	}

	/**
	 * Enqueues a registered script for inclusion on a WordPress page.
	 *
	 * This method enqueues a script that has been previously registered using `wp_register_script()`
	 * to include it on a WordPress page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle       Unique handle for the script.
	 * @param string $src          (Optional) URL to the script file. Leave empty to use the registered URL.
	 * @param array  $dependencies (Optional) An array of script handles on which this script depends.
	 * @param mixed  $version      (Optional) The version number for the script. Pass `false` to use the registered version.
	 * @param bool   $in_footer    (Optional) Whether to enqueue the script in the footer (default is true).
	 */
	public function enqueue_script(
		string $handle,
		string $src = '',
		array $dependencies = [],
		$version = false,
		bool $in_footer = true
	) {
		wp_enqueue_script(
			$handle,
			$src,
			$dependencies,
			$version,
			$in_footer
		);
	}

	/**
	 * Registers a stylesheet for use in WordPress.
	 *
	 * This method registers a stylesheet to be enqueued later using `wp_enqueue_style()`. It provides a way to
	 * define the handle, source URL, dependencies, version, and media type for the stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle       Unique handle for the stylesheet.
	 * @param string $src          URL to the stylesheet file.
	 * @param array  $dependencies (Optional) An array of stylesheet handles on which this stylesheet depends.
	 * @param string $version      (Optional) The version number for the stylesheet (default is the plugin's version).
	 * @param string $media        (Optional) The media type for which this stylesheet is designed (default is 'all').
	 */
	public function register_style(
		string $handle,
		string $src,
		array $dependencies = [],
		string $version = '',
		string $media = 'all'
	) {
		wp_register_style(
			$handle,
			$src,
			$dependencies,
			empty( $version ) ? $this->plugin_version() : $version,
			$media
		);
	}

	/**
	 * Enqueues a registered stylesheet for inclusion on a WordPress page.
	 *
	 * This method enqueues a stylesheet that has been previously registered using `wp_register_style()`
	 * to include it on a WordPress page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle       Unique handle for the stylesheet.
	 * @param string $src          (Optional) URL to the stylesheet file. Leave empty to use the registered URL.
	 * @param array  $dependencies (Optional) An array of stylesheet handles on which this stylesheet depends.
	 * @param mixed  $version      (Optional) The version number for the stylesheet. Pass `false` to use the registered version.
	 * @param string $media        (Optional) The media type for which this stylesheet is designed (default is 'all').
	 */
	public function enqueue_style(
		string $handle,
		string $src = '',
		array $dependencies = [],
		$version = false,
		string $media = 'all'
	) {
		wp_enqueue_style(
			$handle,
			$src,
			$dependencies,
			$version,
			$media
		);
	}
}

