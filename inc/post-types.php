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
function id_basica_register_post_types() {
	// Application post type
	$labels = array(
		'name'               => _x( 'Applications', 'Post type general name', 'id-basica' ),
		'singular_name'      => _x( 'Application', 'Post type singular name', 'id-basica' ),
		'menu_name'          => _x( 'Applications', 'Admin Menu text', 'id-basica' ),
		'name_admin_bar'     => _x( 'Application', 'Add New on Toolbar', 'id-basica' ),
		'add_new'            => __( 'Add New', 'id-basica' ),
		'add_new_item'       => __( 'Add New Application', 'id-basica' ),
		'new_item'           => __( 'New Application', 'id-basica' ),
		'edit_item'          => __( 'Edit Application', 'id-basica' ),
		'view_item'          => __( 'View Application', 'id-basica' ),
		'all_items'          => __( 'All Applications', 'id-basica' ),
		'search_items'       => __( 'Search Applications', 'id-basica' ),
		'not_found'          => __( 'No applications found.', 'id-basica' ),
		'not_found_in_trash' => __( 'No applications found in Trash.', 'id-basica' ),
		'archives'           => _x( 'Application archives', 'The post type archive label used in nav menus', 'id-basica' ),
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
add_action( 'init', 'id_basica_register_post_types' );

/**
 * Register custom taxonomy for Application post type
 */
function id_basica_register_application_taxonomy() {
    $labels = array(
        'name'              => _x( 'Application Types', 'taxonomy general name', 'id-basica' ),
        'singular_name'     => _x( 'Application Type', 'taxonomy singular name', 'id-basica' ),
        'search_items'      => __( 'Search Application Types', 'id-basica' ),
        'all_items'         => __( 'All Application Types', 'id-basica' ),
        'parent_item'       => __( 'Parent Application Type', 'id-basica' ),
        'parent_item_colon' => __( 'Parent Application Type:', 'id-basica' ),
        'edit_item'         => __( 'Edit Application Type', 'id-basica' ),
        'update_item'       => __( 'Update Application Type', 'id-basica' ),
        'add_new_item'      => __( 'Add New Application Type', 'id-basica' ),
        'new_item_name'     => __( 'New Application Type Name', 'id-basica' ),
        'menu_name'         => __( 'Application Types', 'id-basica' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'application-type' ),
        'show_in_rest'      => true, // Enable REST API support
    );

    register_taxonomy( 'application_type', array( 'application' ), $args );
}
add_action( 'init', 'id_basica_register_application_taxonomy' );
