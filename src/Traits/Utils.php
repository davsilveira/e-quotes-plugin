<?php

namespace Emplement\eQuotes\Traits;

trait Utils {

	public function plugin_path() : string {
		return rtrim( plugin_dir_path( EQUOTES_PLUGIN_FILE ), '/' );
	}

	public function plugin_url() : string {
		return plugins_url( '', EQUOTES_PLUGIN_FILE );
	}

	public function plugin_version() : string {

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugin_data = get_plugin_data( EQUOTES_PLUGIN_FILE );

		if ( ! isset( $plugin_data['Version'] ) ) {
			throw new Exception( 'Plugin version cannot be found in plugin headers' );
		}

		return $plugin_data['Version'] ?? '';
	}
}
