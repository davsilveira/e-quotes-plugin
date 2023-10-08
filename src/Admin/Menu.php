<?php

namespace Emplement\eQuotes\Admin;

use Emplement\eQuotes\Admin\Settings\SettingsPage;

class Menu {

	public function init() {
		add_action( 'admin_menu', [$this, 'register_admin_menus'] );
	}

	public function register_admin_menus() {

		add_menu_page(
			esc_html__( 'Enhanced Quotes', 'e-quotes' ),
			esc_html__( 'Enhanced Quotes', 'e-quotes' ),
			'manage_options',
			'e-quotes',
			'',
			'dashicons-editor-table',
			81
		);

		add_submenu_page(
			'e-quotes',
			'Products',
			'Product',
			'edit_posts',
			'e-quotes-products'
		);

		add_submenu_page(
			'e-quotes',
			'Settings',
			'Settings',
			'edit_posts',
			'e-quotes-settings-page',
			[ SettingsPage::class, 'render_page' ]
		);
	}
}
