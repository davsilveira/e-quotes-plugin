<?php

namespace Emplement\eQuotes\Traits;

trait PluginHelper {

	private string $plugin_path = '';

	private string $plugin_url = '';

	private string $plugin_version = '';

	public function plugin_path() : string {

		if ( '' !== $this->plugin_path ) {
			return $this->plugin_path;
		}

		$this->plugin_path = rtrim( plugin_dir_path( EQUOTES_PLUGIN_FILE ), '/' );

		return $this->plugin_path;
	}

	public function plugin_url() : string {

		if ( '' !== $this->plugin_url ) {
			return $this->plugin_url;
		}

		$this->plugin_url = plugins_url( '', EQUOTES_PLUGIN_FILE );

		return $this->plugin_url;
	}

	public function plugin_version() : string {

		if ( '' !== $this->plugin_version ) {
			return $this->plugin_version;
		}

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugin_data = get_plugin_data( EQUOTES_PLUGIN_FILE );

		$this->plugin_version = $plugin_data['Version'] ?? '';

		return $this->plugin_version;
	}
}
