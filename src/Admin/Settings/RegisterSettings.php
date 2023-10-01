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
		/* ---- General Settings ---- */

		// Corporate name.
		register_setting(
			'e_quotes_settings',
			'e_quotes_corporate_name',
			[
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);

		// Fantasy name.
		register_setting(
			'e_quotes_settings',
			'e_quotes_fantasy_name',
			[
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);

		// Main logo.
		register_setting(
			'e_quotes_settings',
			'e_quotes_main_logo',
			[
				'default'      => 0,
				'show_in_rest' => true,
				'type'         => 'integer',
			]
		);

		// Primary color.
		register_setting(
			'e_quotes_settings',
			'e_quotes_primary_color',
			[
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);

		// Secondary color.
		register_setting(
			'e_quotes_settings',
			'e_quotes_secondary_color',
			[
				'default'      => '',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);

		// Currency.
		register_setting(
			'e_quotes_settings',
			'e_quotes_currency',
			[
				'default'      => 'USD',
				'show_in_rest' => true,
				'type'         => 'string',
			]
		);
	}
}
