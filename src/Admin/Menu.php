<?php

namespace Emplement\eQuotes\Admin;

class Menu {

	public function init() {
		add_action( 'admin_menu', [$this, 'add_custom_post_type_menu'] );
	}

	public function add_custom_post_type_menu() {
		add_submenu_page(
			'edit.php?post_type=meu_post_type', // Slug do post type
			'Meu Post Type',                    // Título da página
			'Meu Post Type',                    // Título do menu
			'edit_posts',                       // Capacidade requerida
			'edit.php?post_type=meu_post_type'  // URL da página
		);
	}
}
