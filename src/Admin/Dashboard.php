<?php

namespace Emplement\eQuotes\Admin;

use \Emplement\eQuotes\Utils\AssetsManagement;

class Dashboard {

	private AssetsManagement $assets_management;

	public function __construct( AssetsManagement $assets_management ) {

		$this->assets_management = $assets_management;

		$this->hooks();
	}

	private function hooks() {

		add_action( 'admin_enqueue_scripts', [$this, 'load_assets'] );
	}

	public function load_assets() {

		$this->assets_management->prepare_scripts();
		$this->assets_management->enqueue_script( 'e-quotes' );
	}
}
