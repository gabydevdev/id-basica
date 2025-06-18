<?php
/**
 * Custom Post Types
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom post types
 */
function idbasica_register_post_types() {
	// Application post type
	$labels = array(
		'name'               => _x( 'Applications', 'Post type general name', 'idbasica' ),
		'singular_name'      => _x( 'Application', 'Post type singular name', 'idbasica' ),
		'menu_name'          => _x( 'Applications', 'Admin Menu text', 'idbasica' ),
		'name_admin_bar'     => _x( 'Application', 'Add New on Toolbar', 'idbasica' ),
		'add_new'            => __( 'Add New', 'idbasica' ),
		'add_new_item'       => __( 'Add New Application', 'idbasica' ),
		'new_item'           => __( 'New Application', 'idbasica' ),
		'edit_item'          => __( 'Edit Application', 'idbasica' ),
		'view_item'          => __( 'View Application', 'idbasica' ),
		'all_items'          => __( 'All Applications', 'idbasica' ),
		'search_items'       => __( 'Search Applications', 'idbasica' ),
		'not_found'          => __( 'No applications found.', 'idbasica' ),
		'not_found_in_trash' => __( 'No applications found in Trash.', 'idbasica' ),
		'archives'           => _x( 'Application archives', 'The post type archive label used in nav menus', 'idbasica' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'application' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-clipboard',
		'supports'           => array( 'title', 'author', 'custom-fields' ),
		'show_in_rest'       => false,
	);

	register_post_type( 'application', $args );
}
add_action( 'init', 'idbasica_register_post_types' );
