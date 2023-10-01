<?php

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\eQuotes;

class FinalSettings {

	public function __construct() {

		add_action( 'init', [$this, 'register_block'] );
		add_action( 'wp_enqueue_scripts', [$this, 'register_front_script'] );
		add_action( 'admin_enqueue_scripts', [$this, 'register_admin_script'] );
	}

	public function register_front_script() {

		wp_enqueue_style(
			'equotes-final-settings-front',
			eQuotes::url() . '/blocks/price/build/style-main.css',
			[],
			eQuotes::VERSION,
		);
	}

	public function register_admin_script() {

		wp_enqueue_style(
			'equotes-final-settings-admin',
			eQuotes::url() . '/blocks/price/build/main.css',
			[],
			eQuotes::VERSION,
		);

		wp_enqueue_script(
			'equotes-final-settings',
			eQuotes::url() . '/blocks/final-settings/build/index.js',
			[
				'wp-block-editor',
				'wp-blocks',
				'wp-components',
				'wp-element',
				'wp-i18n',
			],
			eQuotes::VERSION,
			true
		);
	}

	public function register_block() {

		register_block_type_from_metadata(
			eQuotes::path() . '/blocks/final-settings/src/block.json',
			array(
				'render_callback' => [ $this, 'render' ],
			)
		);
	}


	public function render( array $attributes ) : string {

		return 'Final Settings';
	}
}
