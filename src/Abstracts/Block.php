<?php
/**
 * Abstract Block Class.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Abstracts
 */

namespace Emplement\eQuotes\Abstracts;

use Emplement\eQuotes\Interfaces\BlockInterface;
use Emplement\eQuotes\Traits\PluginHelper;
use Emplement\eQuotes\Utils\AssetsManagement;

abstract class Block implements BlockInterface {

	// Load trait that allows us to retrieve path, url and version.
	use PluginHelper;

	/**
	 * Asset management service.
	 *
	 * @since 1.0.0
	 *
	 * @var AssetsManagement
	 */
	private AssetsManagement $assets_management;

	/**
	 * Default block dependencies.
	 *
	 * @since 1.0.0
	 *
	 * @var array|string[]
	 */
	private array $block_dependencies = [
		'e-quotes',
		'wp-block-editor',
		'wp-blocks',
		'wp-components',
		'wp-element',
		'wp-i18n',
	];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param AssetsManagement $assets_management
	 */
	public function __construct( AssetsManagement $assets_management ) {
		$this->assets_management = $assets_management;
	}

	/**
	 * Unique name for this block.
	 *
	 * A block name can only contain lowercase alphanumeric characters and dashes, and must begin with a letter.
	 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function name() : string;

	/**
	 * An array of settings for this block.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function settings() : array {
		return array();
	}

	/**
	 * Whether the block is restricted to our pages or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_restricted() : bool {
		return true;
	}

	/**
	 * Initialize all hooks to register the block.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', [$this, 'register_block'] );
		add_action( 'wp_enqueue_scripts', [$this, 'register_front_script'] );
		add_action( 'admin_enqueue_scripts', [$this, 'register_admin_script'] );
	}

	/**
	 * Create the path or URL for a block's asset file.
	 *
	 * This method generates the path or URL for an asset file associated with a block.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name  The name of the asset file.
	 * @param bool   $return_url Whether to return a URL (true) or a file path (false).
	 *
	 * @return string The asset's path or URL.
	 */
	protected function create_block_path_or_url( string $file_name, bool $return_url = false ): string {

		return sprintf(
			'%s/blocks/%s/build/%s',
			$return_url ? $this->plugin_url() : $this->plugin_path(),
			$this->name(),
			$file_name
		);
	}

	/**
	 * Register the block type.
	 *
	 * This method registers a block type using the block metadata and settings.
	 *
	 * @since 1.0.0
	 */
	public function register_block() {

		register_block_type_from_metadata(
			sprintf(
				'%s/blocks/%s/src/block.json',
				$this->plugin_path(),
				$this->name(),
			),
			$this->settings()
		);
	}

	/**
	 * Register the front-end stylesheet for the block.
	 *
	 * This method registers the front-end stylesheet for the block if it exists.
	 *
	 * @since 1.0.0
	 */
	public function register_front_script() {

		$style_name = 'style-main.css';

		if ( ! file_exists( $this->create_block_path_or_url( $style_name ) ) ) {
			return; // No styles available for this block.
		}

		$this->assets_management->enqueue_style(
			"e-quotes-{$this->name}}-front",
			$this->create_block_path_or_url( $style_name, true )
		);
	}

	/**
	 * Register the admin scripts and styles for the block.
	 *
	 * This method registers the admin scripts and styles for the block based on specific conditions.
	 *
	 * @since 1.0.0
	 */
	public function register_admin_script() {

		$admin_screen = get_current_screen();

		if ( empty( filter_input( INPUT_GET, 'post' ) ) ) {
			return;  // Only load in our admin screens.
		}

		if (
			$this->is_restricted() &&
			strpos( $admin_screen->id, \Emplement\eQuotes\Commons\PostTypes::$product_post_type_name ) === false
		) {
			return;  // If is restricted, load only on products post type edit screen.
		}

		$style_name = 'main.css';

		if ( file_exists( $this->create_block_path_or_url( $style_name ) ) ) {

			$this->assets_management->enqueue_style(
				"e-quotes-{$this->name()}-admin",
				$this->create_block_path_or_url( $style_name, true )
			);
		}

		$this->assets_management->enqueue_script(
			"e-quotes-{$this->name()}",
			$this->create_block_path_or_url( 'index.js', true ),
			$this->block_dependencies
		);
	}

	/**
	 * Ensure the class name for the element is always present.
	 *
	 * This method guarantees that even if the user specifies another classes in the admin block editor,
	 * the final class name attribute will have the expected class in the first parameter.
	 *
	 * @since 1.0.0
	 */
	protected function ensure_class_name( string $class_name, array $block_attributes ) : array {

		if ( ! isset( $block_attributes['className'] ) ) {
			return (array) $class_name; // The block does not have classes, just return the name.
		}

		return array_unique(
			// Filter any empty strings from the final array.
			array_filter(
			// Ensure the specified class is always present.
				array_merge( [ $class_name, $block_attributes['className'] ] )
			)
		);
	}
}
