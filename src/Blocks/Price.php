<?php

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\eQuotes;

class Price {

	public function __construct() {

		add_action( 'init', [$this, 'register_block'] );
		add_action( 'admin_enqueue_scripts', [$this, 'register_block_script'] );
	}

	public function register_block_script() {

		wp_enqueue_style(
			'equotes-price',
			eQuotes::url() . '/blocks/price/build/main.css',
			[],
			eQuotes::VERSION,
		);

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

		wp_add_inline_script(
			'equotes-price',
			'const EQUOTES = ' . wp_json_encode(
				[
					'settings' => [
						'currency' => get_option( 'e_quotes_currency', 'USD' ),
					]
				]
			),
			'before'
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


	public function render( array $attributes ) : string {

		// Ensure unique values.
		$class_name = array_unique(
			// Filter any empty strings from the final array.
			array_filter(
				// Ensure our class is always present.
				array_merge( ['equotes-price-component', $attributes['className']] )
			)
		);

		return sprintf(
			'<div class="%s"><span class="e-quotes-currency-sign">%s</span>%s</div>',
			esc_attr( implode( ' ', $class_name ) ),
			esc_html( get_option( 'e_quotes_currency', 'USD' ) === 'USD' ? '$' : 'R$' ),
			esc_html( $attributes['price'] ?? 0 )
		);
	}
}
