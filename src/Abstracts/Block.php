<?php

namespace Emplement\eQuotes\Abstracts;

abstract class Block {

	use \Emplement\eQuotes\Traits\Utils;

	private array $block_dependencies = [
		'wp-block-editor',
		'wp-blocks',
		'wp-components',
		'wp-element',
		'wp-i18n',
	];

	protected string $name;

	protected array $settings = [];

	public function init() {

		add_action( 'init', [$this, 'register_block'] );
		add_action( 'wp_enqueue_scripts', [$this, 'register_front_script'] );
		add_action( 'admin_enqueue_scripts', [$this, 'register_admin_script'] );
	}

	private function build_path_or_url( string $file_name, bool $return_url = false ) : string {

		return sprintf(
			'%s/blocks/%s/build/%s',
			$return_url ? $this->plugin_url() : $this->plugin_path(),
			$this->name,
			$file_name
		);
	}

	public function register_block() {

		register_block_type_from_metadata(
			sprintf(
				'%s/blocks/%s/src/block.json',
				$this->plugin_path(),
				$this->name,
			),
			$this->settings
		);
	}

	public function register_front_script() {

		$style_name = 'style-main.css';

		if ( ! file_exists( $this->build_path_or_url( $style_name ) ) ) {
			return; // No styles available for this block.
		}

		wp_enqueue_style(
			"e-quotes-{$this->name}}-front",
			$this->build_path_or_url( $style_name, true ),
			[],
			$this->plugin_version()
		);
	}

	public function register_admin_script() {

		$style_name = 'main.css';

		if ( file_exists( $this->build_path_or_url( $style_name ) ) ) {

			wp_enqueue_style(
				"e-quotes-{$this->name}-admin",
				$this->build_path_or_url( $style_name, true ),
				[],
				$this->plugin_version()
			);
		}

		wp_enqueue_script(
			"e-quotes-{$this->name}",
			$this->build_path_or_url( 'index.js', true ),
			$this->block_dependencies,
			$this->plugin_version(),
			true
		);

		wp_add_inline_script(
			'e-quotes-price',
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
}