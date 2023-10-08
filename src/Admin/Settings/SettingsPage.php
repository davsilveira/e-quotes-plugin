<?php
/**
 * Admin page.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Admin\Settings
 */

declare( strict_types=1 );

namespace Emplement\eQuotes\Admin\Settings;

use Emplement\eQuotes\Traits\PluginHelper;
use Emplement\eQuotes\Utils\AssetsManagement;

/**
 * Settings Page Class.
 *
 * @since 1.0.0
 */
class SettingsPage {

	// Load trait that allows us to retrieve path, url and version.
	use PluginHelper;

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
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );
	}

	/**
	 * Register scripts and styles to render the Settings Page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_admin_scripts(): void {

		$admin_screen = get_current_screen();

		if ( strpos( $admin_screen->id, 'page_e-quotes-settings-page' ) === false ) {
			return; // Not the settings screen.
		}

		// Store the module path.
		$path = sprintf( '%s/modules/settings-page', $this->plugin_url() );

		// Enqueue module styles.
		$this->assets_management->enqueue_style(
			'e-quotes-settings-page',
			sprintf( '%s/build/index.css', $path ),
			[ 'wp-components' ]
		);

		// Enqueue WP media scripts necessary for media upload handling.
		wp_enqueue_media();

		// Enqueue the main module script.
		$this->assets_management->enqueue_script(
			'e-quotes-settings-page',
			sprintf( '%s/build/index.js', $path ),
			[
				'wp-element',
				'wp-components',
				'wp-api',
				'wp-notices',
				'wp-data',
				'wp-media-utils',
			]
		);
	}

	/**
	 * Render the container HTML.
	 *
	 * The Settings page will be loaded as a React Application.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_page(): void {
		?>
		<div id="e-quotes-settings-page"></div>
		<?php
	}
}
