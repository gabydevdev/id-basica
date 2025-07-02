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
 * Register custom post types for the theme.
 *
 * Registers the 'Application' custom post type for handling
 * user applications within the dashboard system.
 *
 * @since 1.0.0
 */
function id_basica_register_post_types() {
	// Application post type
	$labels = array(
		'name'               => _x( 'Applications', 'Post type general name', ID_BASICA_DOMAIN ),
		'singular_name'      => _x( 'Application', 'Post type singular name', ID_BASICA_DOMAIN ),
		'menu_name'          => _x( 'Applications', 'Admin Menu text', ID_BASICA_DOMAIN ),
		'name_admin_bar'     => _x( 'Application', 'Add New on Toolbar', ID_BASICA_DOMAIN ),
		'add_new'            => __( 'Add New', ID_BASICA_DOMAIN ),
		'add_new_item'       => __( 'Add New Application', ID_BASICA_DOMAIN ),
		'new_item'           => __( 'New Application', ID_BASICA_DOMAIN ),
		'edit_item'          => __( 'Edit Application', ID_BASICA_DOMAIN ),
		'view_item'          => __( 'View Application', ID_BASICA_DOMAIN ),
		'all_items'          => __( 'All Applications', ID_BASICA_DOMAIN ),
		'search_items'       => __( 'Search Applications', ID_BASICA_DOMAIN ),
		'not_found'          => __( 'No applications found.', ID_BASICA_DOMAIN ),
		'not_found_in_trash' => __( 'No applications found in Trash.', ID_BASICA_DOMAIN ),
		'archives'           => _x( 'Application archives', 'The post type archive label used in nav menus', ID_BASICA_DOMAIN ),
	);

	$args = array(
		'labels'          => $labels,
		'public'          => true,
		'show_ui'         => true,
		'menu_position'   => 20,
		'menu_icon'       => 'dashicons-clipboard',
		'capability_type' => 'post',
		'hierarchical'    => false,
		'supports'        => array( 'title', 'author', 'custom-fields' ),
		'has_archive'     => true,
		'rewrite'         => array( 'slug' => 'application' ),
		'query_var'       => true,
		'show_in_rest'    => false,
	);

	register_post_type( 'application', $args );
}
add_action( 'init', 'id_basica_register_post_types' );

/**
 * Register custom taxonomy for Application post type.
 *
 * Creates the 'Application Types' taxonomy to categorize
 * different types of applications within the system.
 *
 * @since 1.0.0
 */
function id_basica_register_application_taxonomy() {
	$labels = array(
		'name'              => _x( 'Application Types', 'taxonomy general name', ID_BASICA_DOMAIN ),
		'singular_name'     => _x( 'Application Type', 'taxonomy singular name', ID_BASICA_DOMAIN ),
		'search_items'      => __( 'Search Application Types', ID_BASICA_DOMAIN ),
		'all_items'         => __( 'All Application Types', ID_BASICA_DOMAIN ),
		'parent_item'       => __( 'Parent Application Type', ID_BASICA_DOMAIN ),
		'parent_item_colon' => __( 'Parent Application Type:', ID_BASICA_DOMAIN ),
		'edit_item'         => __( 'Edit Application Type', ID_BASICA_DOMAIN ),
		'update_item'       => __( 'Update Application Type', ID_BASICA_DOMAIN ),
		'add_new_item'      => __( 'Add New Application Type', ID_BASICA_DOMAIN ),
		'new_item_name'     => __( 'New Application Type Name', ID_BASICA_DOMAIN ),
		'menu_name'         => __( 'Application Types', ID_BASICA_DOMAIN ),
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
