<?php
/**
 * Admin page
 *
 * @since 0.1.0
 * @package  Emplement\eQuotes
 * @subpackage Admin
 */

declare(strict_types=1);

namespace Emplement\eQuotes\Admin\Settings;

use Emplement\eQuotes\eQuotes;

/**
 * Options Page Class
 *
 * Adds custom routes
 */
class SettingsPage {

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'register_admin_menus' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );
	}

	/**
	 * Register admin menus.
	 *
	 * @return void
	 */
	public function register_admin_menus(): void {
		add_menu_page(
			__( 'Quotations', 'e-quotes' ),
			__( 'Quotations', 'e-quotes' ),
			'manage_options',
			'e-quotes-settings-page',
			[ $this, 'render_page' ],
			'dashicons-editor-table',
			81
		);
	}

	/**
	 * Register admin scripts.
	 *
	 * @return void
	 */
	public function register_admin_scripts(): void {
		// Prevent loading assets on other admin screens.
		if ( function_exists( 'get_current_screen' ) ) {
			$admin_screen = get_current_screen();
			if ( 'toplevel_page_e-quotes-settings-page' !== $admin_screen->id ) {
				return;
			}
		}

		wp_enqueue_style(
			'e-quotes-settings-page',
			eQuotes::url() . '/modules/settings-page/build/index.css',
			[ 'wp-components' ],
			eQuotes::VERSION,
		);

		wp_enqueue_media();

		wp_enqueue_script(
			'e-quotes-settings-page',
			eQuotes::url() . '/modules/settings-page/build/index.js',
			[
				'wp-element',
				'wp-components',
				'wp-api',
				'wp-notices',
				'wp-data',
				'wp-media-utils',
			],
			eQuotes::VERSION,
			true
		);


		wp_add_inline_script(
			'e-quotes-settings-page',
			'const eQuotesVars = ' . wp_json_encode(
				[
					'variable' => 'value',
				],
			),
			'before'
		);
	}

	/**
	 * Display admin page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		?>
		<div id="e-quotes-settings-page"></div>
		<?php
	}
}
