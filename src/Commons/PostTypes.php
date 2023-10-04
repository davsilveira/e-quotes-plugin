<?php

namespace Emplement\eQuotes\Commons;

class PostTypes {

	public function init() {
		add_action( 'init', [ $this, 'register_custom_post_type'] );
	}

	public function register_custom_post_type() {
		$labels = array(
			'name'               => 'Meu Post Type',
			'singular_name'      => 'Meu Post Type Singular',
			'menu_name'          => 'Meu Plugin',
			'add_new'            => 'Adicionar Novo',
			'add_new_item'       => 'Adicionar Novo Meu Post Type',
			'edit_item'          => 'Editar Meu Post Type',
			'new_item'           => 'Novo Meu Post Type',
			'view_item'          => 'Ver Meu Post Type',
			'search_items'       => 'Procurar Meu Post Type',
			'not_found'          => 'Nenhum Meu Post Type encontrado',
			'not_found_in_trash' => 'Nenhum Meu Post Type encontrado na lixeira',
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'menu_position'       => 5, // Posição no menu
			'menu_icon'           => 'dashicons-admin-post', // Ícone no menu
			'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		);

		register_post_type( 'meu_post_type', $args );
	}
}
