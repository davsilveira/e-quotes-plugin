<?php
/**
 * Custom Post Type registration file.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Commons
 */

namespace Emplement\eQuotes\Commons;

class PostTypes {

	/**
	 * The product slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static string $product_post_type_name = 'eq_product';

	/**
	 * Initialize all hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_custom_post_type'] );
	}

	/**
	 * Register the custom post types.
	 *
	 * @since 1.0.0
	 */
	public function register_custom_post_type() {

		$args = [
			'labels'        => [
				'name'               => esc_html__( 'Products', 'e-quotes' ),
				'singular_name'      => esc_html__( 'Product', 'e-quotes' ),
				'menu_name'          => esc_html__( 'Enhanced Products', 'e-quotes' ),
				'add_new'            => esc_html__( 'Add new', 'e-quotes' ),
				'add_new_item'       => esc_html__( 'Add new product', 'e-quotes' ),
				'edit_item'          => esc_html__( 'Edit product', 'e-quotes' ),
				'new_item'           => esc_html__( 'New product', 'e-quotes' ),
				'view_item'          => esc_html__( 'View product', 'e-quotes' ),
				'search_items'       => esc_html__( 'Search products', 'e-quotes' ),
				'not_found'          => esc_html__( 'No products can be found', 'e-quotes' ),
				'not_found_in_trash' => esc_html__( 'No products can be found in trash', 'e-quotes' ),
			],
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => \Emplement\eQuotes\Admin\Menu::$menu_position,
			'menu_icon'     => 'dashicons-admin-post',
			'supports'      => [ 'title', 'editor', 'thumbnail' ],
			'show_in_rest'  => true,
		];

		register_post_type( self::$product_post_type_name, $args );
	}
}
