<?php
/**
 * WordPress Admin Modifications
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom admin styles
 */
function idbasica_admin_styles() {
	wp_enqueue_style(
		'idbasica-admin-styles',
		IDBASICA_THEME_URI . '/inc/admin/assets/css/admin.css',
		array(),
		IDBASICA_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'idbasica_admin_styles' );

/**
 * Custom admin scripts
 */
function idbasica_admin_scripts() {
	wp_enqueue_script(
		'idbasica-admin-scripts',
		IDBASICA_THEME_URI . '/inc/admin/assets/js/admin.js',
		array( 'jquery' ),
		IDBASICA_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'idbasica_admin_scripts' );

/**
 * Customize admin footer text
 */
function idbasica_admin_footer_text() {
	echo 'Powered by <a href="https://wordpress.org">WordPress</a> | <a href="https://github.com/gabydevdev/idbasica">ID Basica</a>';
}
add_filter( 'admin_footer_text', 'idbasica_admin_footer_text' );

/**
 * Add custom dashboard widget
 */
function idbasica_add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'idbasica_dashboard_widget',
		'ID Basica Theme Information',
		'idbasica_dashboard_widget_callback'
	);
}
add_action( 'wp_dashboard_setup', 'idbasica_add_dashboard_widgets' );

/**
 * Custom dashboard widget callback
 */
function idbasica_dashboard_widget_callback() {
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
function idbasica_remove_comments() {
	// Remove comments menu
	add_action( 'admin_menu', function() {
		remove_menu_page( 'edit-comments.php' );
	});

	// Remove comments from post and page
	add_action( 'init', function() {
		// Remove comment support from posts and pages
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	}, 100);

	// Remove comments from admin bar
	add_action( 'wp_before_admin_bar_render', function() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	});

	// Disable comments and pings
	add_filter( 'comments_open', '__return_false' );
	add_filter( 'pings_open', '__return_false' );

	// Hide existing comments
	add_filter( 'comments_array', '__return_empty_array' );

	// Remove comment-related metaboxes
	add_action( 'admin_init', function() {
		// Remove comments metabox from dashboard
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

		// Remove comments metabox from post types
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_meta_box( 'commentsdiv', $post_type, 'normal' );
				remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
			}
		}
	});

	// Redirect any request to the comments page
	add_action( 'admin_init', function() {
		global $pagenow;

		if ( $pagenow === 'edit-comments.php' || $pagenow === 'comment.php' || $pagenow === 'options-discussion.php' ) {
			wp_redirect( admin_url() );
			exit;
		}
	});

	// Remove comments column from posts and pages list
	add_filter( 'manage_posts_columns', function( $columns ) {
		unset( $columns['comments'] );
		return $columns;
	});

	add_filter( 'manage_pages_columns', function( $columns ) {
		unset( $columns['comments'] );
		return $columns;
	});
}
add_action( 'after_setup_theme', 'idbasica_remove_comments' );

/**
 * Remove comment-related widgets
 */
function idbasica_remove_comment_widgets() {
	// Unregister default WordPress widgets
	add_action( 'widgets_init', function() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}, 20 );
}
add_action( 'after_setup_theme', 'idbasica_remove_comment_widgets' );

/**
 * Temporarily hide posts from WordPress dashboard
 *
 * This function removes the 'Posts' menu item and related functionality from the WordPress admin.
 * To restore posts, simply comment out or remove this function.
 */
function idbasica_hide_posts() {
    // Remove 'Posts' menu from admin menu
    add_action( 'admin_menu', function() {
        remove_menu_page( 'edit.php' );
    });

    // Remove 'New Post' from admin bar
    add_action( 'wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_node( 'new-post' );
    });

    // Remove 'Posts' from Dashboard 'At a Glance' widget
    add_filter( 'dashboard_glance_items', function( $items ) {
        if ( isset( $items['post'] ) ) {
            unset( $items['post'] );
        }
        return $items;
    });

    // Redirect if someone tries to access posts directly
    add_action( 'admin_init', function() {
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
    });

    // Remove 'Posts' from admin dashboard widgets
    add_action( 'wp_dashboard_setup', function() {
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    });

    // Remove 'Latest Posts' feed from dashboard
    add_action( 'wp_dashboard_setup', function() {
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    });

    // Remove 'Post' from 'New' admin bar menu
    add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'new-post' );
    }, 999);
}
add_action( 'after_setup_theme', 'idbasica_hide_posts' );

