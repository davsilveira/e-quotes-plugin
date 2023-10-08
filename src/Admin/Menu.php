<?php
/**
 * Admin menus.
 *
 * All admin menus must be added here.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes
 * @subpackage Emplement\eQuotes\Admin
 */

namespace Emplement\eQuotes\Admin;

class Menu {

	/**
	 * Main menu position.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public static int $menu_position = 12;

	/**
	 * Initialize all hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_menu', [$this, 'register_admin_menus'] );
	}

	/**
	 * Register all admin menus.
	 *
	 * @since 1.0.0
	 */
	public function register_admin_menus() {

		add_menu_page(
			esc_html__( 'Enhanced Quotes', 'e-quotes' ),
			esc_html__( 'Enhanced Quotes', 'e-quotes' ),
			'manage_options',
			'e-quotes',
			'',
			'dashicons-editor-table',
			self::$menu_position
		);

		add_submenu_page(
			'e-quotes',
			'Products',
			'Products',
			'edit_posts', // TODO: Use our own capabilities.
			'edit.php?post_type=eq_product'
		);

		add_submenu_page(
			'e-quotes',
			'Settings',
			'Settings',
			'edit_posts', // TODO: Use our own capabilities.
			'e-quotes-settings-page',
			[ Settings\Page::class, 'render_page' ]
		);
	}
}
