<?php

namespace Emplement\eQuotes\Utils;

use Emplement\eQuotes\Traits\PluginHelper;

class AssetsManagement {

	use PluginHelper;

	private function create_asset_url( string $file_name = '', string $sub_dir ) : string {
		return sprintf(
			'%s/assets/%s/%s',
			$this->plugin_url(),
			$sub_dir,
			$file_name
		);
	}

	public function js_url( string $file_name = '' ) : string {
		return $this->create_asset_url( $file_name, 'js' );
	}

	public function css_url( string $file_name = '' ) : string {
		return $this->create_asset_url( $file_name, 'css' );
	}

	public function prepare_scripts() {

		$this->register_script( 'e-quotes', $this->js_url( 'e-quotes.js' ) );

		$plugin_settings = wp_json_encode(
			[
				'currency' => get_option( 'e_quotes_currency', 'USD' ),
			]
		);

		wp_add_inline_script(
			'e-quotes',
			'eQuotes.attach( "pluginSettings", ' . $plugin_settings . ' )'
		);
	}

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
			empty( $version ) ? $this->plugin_version() : $version,
			$in_footer
		);
	}

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
}

