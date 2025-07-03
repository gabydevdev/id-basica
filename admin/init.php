<?php
/**
 * WordPress Admin area modifications and customizations.
 *
 * This file contains all admin-specific modifications including
 * custom styles, scripts, dashboard widgets, and admin interface
 * customizations for the ID Basica theme.
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'admin_email_check_interval', '__return_false' );

/**
 * Enqueue custom admin styles and scripts.
 *
 * Loads the theme's custom admin stylesheet and JavaScript files
 * for WordPress admin area customizations.
 *
 * @since 1.0.0
 */
function id_basica_admin_styles_and_scripts() {
	wp_enqueue_style(
		'id-basica-admin-styles',
		ID_BASICA_URI . '/admin/assets/css/admin.css',
		array(),
		ID_BASICA_VERSION
	);

	wp_enqueue_script(
		'id-basica-admin-scripts',
		ID_BASICA_URI . '/admin/assets/js/admin.js',
		array( 'jquery' ),
		ID_BASICA_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'id_basica_admin_styles_and_scripts' );

/**
 * Customize admin footer text.
 *
 * Replaces the default WordPress admin footer text with
 * custom branding for the ID Basica theme.
 *
 * @since 1.0.0
 * @return string Custom footer text with links.
 */
function id_basica_admin_footer_text() {
	return 'Powered by <a href="https://wordpress.org">WordPress</a> | <a href="https://github.com/gabydevdev/id-basica">ID Basica</a>';
}
add_filter( 'admin_footer_text', 'id_basica_admin_footer_text' );

/**
 * Add custom dashboard widget for theme information.
 *
 * Registers a custom dashboard widget that displays information
 * about the ID Basica theme and its features.
 *
 * @since 1.0.0
 */
function id_basica_add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'id_basica_dashboard_widget',
		'ID Basica Theme Information',
		'id_basica_dashboard_widget_callback'
	);
}
add_action( 'wp_dashboard_setup', 'id_basica_add_dashboard_widgets' );

/**
 * Custom dashboard widget callback
 */
function id_basica_dashboard_widget_callback() {
	echo '<p><strong>Welcome to ID Basica!</strong></p>';
	echo '<ul>';
	echo '<li><a href="https://wordpress.org/documentation/">WordPress Documentation</a></li>';
	echo '<li><a href="' . admin_url( 'customize.php' ) . '">Customize Your Site</a></li>';
	echo '<li><a href="' . admin_url( 'edit.php?post_type=page' ) . '">Manage Pages</a></li>';
	echo '</ul>';
}

/**
 * Remove all comment-related functionality
 */
function id_basica_remove_comments() {
	// Remove comments menu
	add_action(
		'admin_menu',
		function () {
			remove_menu_page( 'edit-comments.php' );
		}
	);

	// Remove comments from post and page
	add_action(
		'init',
		function () {
			// Remove comment support from posts and pages
			remove_post_type_support( 'post', 'comments' );
			remove_post_type_support( 'page', 'comments' );
		},
		100
	);

	// Remove comments from admin bar
	add_action(
		'wp_before_admin_bar_render',
		function () {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'comments' );
		}
	);

	// Disable comments and pings
	add_filter( 'comments_open', '__return_false' );
	add_filter( 'pings_open', '__return_false' );

	// Hide existing comments
	add_filter( 'comments_array', '__return_empty_array' );

	// Remove comment-related metaboxes
	add_action(
		'admin_init',
		function () {
			// Remove comments metabox from dashboard
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

			// Remove comments metabox from post types
			foreach ( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_meta_box( 'commentsdiv', $post_type, 'normal' );
					remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
				}
			}
		}
	);

	// Redirect any request to the comments page
	add_action(
		'admin_init',
		function () {
			global $pagenow;

			if ( $pagenow === 'edit-comments.php' || $pagenow === 'comment.php' || $pagenow === 'options-discussion.php' ) {
				wp_redirect( admin_url() );
				exit;
			}
		}
	);

	// Remove comments column from posts and pages list
	add_filter(
		'manage_posts_columns',
		function ( $columns ) {
			unset( $columns['comments'] );
			return $columns;
		}
	);

	add_filter(
		'manage_pages_columns',
		function ( $columns ) {
			unset( $columns['comments'] );
			return $columns;
		}
	);
}
add_action( 'after_setup_theme', 'id_basica_remove_comments' );

/**
 * Remove comment-related widgets
 */
function id_basica_remove_comment_widgets() {
	// Unregister default WordPress widgets
	add_action(
		'widgets_init',
		function () {
			unregister_widget( 'WP_Widget_Recent_Comments' );
		},
		20
	);
}
add_action( 'after_setup_theme', 'id_basica_remove_comment_widgets' );

/**
 * Temporarily hide posts from WordPress dashboard
 *
 * This function removes the 'Posts' menu item and related functionality from the WordPress admin.
 * To restore posts, simply comment out or remove this function.
 */
function id_basica_hide_posts() {
	// Remove 'Posts' menu from admin menu
	add_action(
		'admin_menu',
		function () {
			remove_menu_page( 'edit.php' );
		}
	);

	// Remove 'New Post' from admin bar
	add_action(
		'wp_before_admin_bar_render',
		function () {
			global $wp_admin_bar;
			$wp_admin_bar->remove_node( 'new-post' );
		}
	);

	// Remove 'Posts' from Dashboard 'At a Glance' widget
	add_filter(
		'dashboard_glance_items',
		function ( $items ) {
			if ( isset( $items['post'] ) ) {
				unset( $items['post'] );
			}
			return $items;
		}
	);

	// Redirect if someone tries to access posts directly
	add_action(
		'admin_init',
		function () {
			global $pagenow;

			// Check if we're on the posts listing or editing page
			if ( $pagenow == 'edit.php' && empty( $_GET['post_type'] ) ) {
				wp_redirect( admin_url( 'index.php' ) );
				exit;
			}

			// Check if we're on the new post page
			if ( $pagenow == 'post-new.php' && empty( $_GET['post_type'] ) ) {
				wp_redirect( admin_url( 'index.php' ) );
				exit;
			}
		}
	);

	// Remove 'Posts' from admin dashboard widgets
	add_action(
		'wp_dashboard_setup',
		function () {
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		}
	);

	// Remove 'Latest Posts' feed from dashboard
	add_action(
		'wp_dashboard_setup',
		function () {
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		}
	);

	// Remove 'Post' from 'New' admin bar menu
	add_action(
		'admin_bar_menu',
		function ( $wp_admin_bar ) {
			$wp_admin_bar->remove_node( 'new-post' );
		},
		999
	);
}
add_action( 'after_setup_theme', 'id_basica_hide_posts' );

/**
 * Disable Gutenberg editor for selected pages and post types
 */
function id_basica_disable_gutenberg( $can_edit, $post_type ) {
	// Specify post types where Gutenberg should be disabled
	$disabled_post_types = array( 'application', 'page' ); // Replace with your post types

	// Disable Gutenberg for specific post types
	if ( in_array( $post_type, $disabled_post_types, true ) ) {
		return false;
	}

	// Disable Gutenberg for specific pages by ID
	// if ( isset( $_GET['post'] ) ) {
	// 	$disabled_pages = array( 42, 123 ); // Replace with your page IDs
	// 	if ( in_array( (int) $_GET['post'], $disabled_pages, true ) ) {
	// 		return false;
	// 	}
	// }

	return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'id_basica_disable_gutenberg', 10, 2 );

/**
 * Enable classic widgets dashboard
 */
function id_basica_enable_classic_widgets() {
	add_filter( 'use_widgets_block_editor', '__return_false' );
}
add_action( 'after_setup_theme', 'id_basica_enable_classic_widgets' );

/**
 * Add custom columns (ID, Slug, Template) to the Pages list in the admin area
 */
function id_basica_add_custom_columns( $columns ) {
	$new_columns = array();

	foreach ( $columns as $key => $value ) {
		if ( 'title' === $key ) {
			$new_columns['title']    = $value;
			$new_columns['slug']     = 'Slug';
			$new_columns['template'] = 'Template';
		} elseif ( 'author' === $key ) {
			$new_columns['author'] = $value;
		} elseif ( 'date' === $key ) {
			$new_columns['id']   = 'ID';
			$new_columns['date'] = $value;
		} else {
			$new_columns[ $key ] = $value;
		}
	}

	return $new_columns;
}
add_filter( 'manage_pages_columns', 'id_basica_add_custom_columns' );

/**
 * Populate custom columns (ID, Slug, Template) with data
 */
function id_basica_populate_custom_columns( $column, $post_id ) {
	if ( 'id' === $column ) {
		echo $post_id;
	}
	if ( 'slug' === $column ) {
		$post = get_post( $post_id );
		echo $post->post_name;
	}
	if ( 'template' === $column ) {
		$template = get_page_template_slug( $post_id );
		echo $template ? $template : 'Default';
	}
}
add_action( 'manage_pages_custom_column', 'id_basica_populate_custom_columns', 10, 2 );

/**
 * Add custom styles for columns (ID, Slug, Template)
 */
function id_basica_custom_column_styles() {
	echo '<style>
        .column-slug { width: 15%; }
        .column-id { width: 5%; }
        .column-template { width: 15%; }
    </style>';
}
add_action( 'admin_head', 'id_basica_custom_column_styles' );

require_once ID_BASICA_DIR . '/admin/login.php';
require_once ID_BASICA_DIR . '/admin/users.php';
