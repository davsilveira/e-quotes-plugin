<?php
/**
 * Settings register class
 *
 * @since 0.1.0
 * @package  Emplement\eQuotes
 * @subpackage Admin
 */

declare(strict_types=1);

namespace Emplement\eQuotes\Admin\Settings;

use Emplement\eQuotes\eQuotes;

/**
 * RegisterSettings Class
 *
 * Register settings to WP API.
 */
class RegisterSettings {

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register settings fields.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		register_setting(
			'e-quotes-settings',
			'e-quotes-corporate-name',
			[
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);
		register_setting(
			'e-quotes-settings',
			'e-quotes-fantasy-name',
			[
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);
	}
}
