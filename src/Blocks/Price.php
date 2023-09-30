<?php

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\eQuotes;

class Price {

	public function __construct() {

		add_action( 'init', [$this, 'register_block'] );
		add_action( 'admin_enqueue_scripts', [$this, 'register_block_script'] );

	}

	public function register_block_script() {

		wp_enqueue_script(
			'equotes-price',
			eQuotes::url() . '/blocks/price/build/index.js',
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
			eQuotes::path() . '/blocks/price/src/block.json',
			array(
				'render_callback' => [ $this, 'render' ],
			)
		);


	}


	public function render() {

		return 'Preço';

	}

}
