<?php
/**
 * IDBasica Theme functions and definitions
 *
 * @package IDBasica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants
define( 'ID_BASICA_VERSION', '1.0.0' );
define( 'ID_BASICA_THEME_DIR', get_template_directory() );
define( 'ID_BASICA_THEME_URI', get_template_directory_uri() );

/**
 * Theme setup function
 */
function id_basica_setup() {
	// Add default posts feed links to head (comments feed links are removed)
	// We're using a custom implementation to remove comment feeds
	add_action( 'wp_head', 'id_basica_custom_feed_links', 3 );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 40,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Register navigation menus
	register_nav_menus(
		array(
			'dashboard-menu' => esc_html__( 'Dashboard Menu', 'id-basica' ),
			'user-menu'      => esc_html__( 'User Menu', 'id-basica' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'caption',
			'style',
			'script',
		)
	);

	// Set content width
	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}
}
add_action( 'after_setup_theme', 'id_basica_setup' );

/**
 * Custom feed links without comments
 */
function id_basica_custom_feed_links() {
	// Only add the posts feed, skip comments feed
	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . ' &raquo; Feed" href="' . esc_url( get_feed_link() ) . "\" />\n";
}

// Load theme helpers and ACF initialization
require_once ID_BASICA_THEME_DIR . '/inc/dev-helpers.php';
require_once ID_BASICA_THEME_DIR . '/inc/helpers.php';
require_once ID_BASICA_THEME_DIR . '/acf/init.php';
require_once ID_BASICA_THEME_DIR . '/inc/admin/init.php';

/**
 * Enqueue scripts and styles.
 */
function id_basica_styles() {
	$main_css_asset_file = include( ID_BASICA_THEME_DIR . '/build/css/main.asset.php' );

	// Enqueue Font Awesome for icons
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);

	// Enqueue main stylesheet
	wp_enqueue_style(
		'id-basica-style',
		ID_BASICA_THEME_URI . '/build/css/main.css',
		$main_css_asset_file['dependencies'],
		$main_css_asset_file['version']
	);
}
add_action( 'wp_enqueue_scripts', 'id_basica_styles' );

/**
 * Enqueue scripts and styles.
 */
function id_basica_scripts() {
	$main_js_asset_file = include( ID_BASICA_THEME_DIR . '/build/js/main.asset.php' );

	// Enqueue main script
	wp_enqueue_script(
		'id-basica-script',
		ID_BASICA_THEME_URI . '/build/js/main.js',
		$main_js_asset_file['dependencies'],
		$main_js_asset_file['version']
	);
}
add_action( 'wp_enqueue_scripts', 'id_basica_scripts' );

// Include custom post types
require_once ID_BASICA_THEME_DIR . '/inc/post-types.php';

/**
 * Register widget areas
 */
function id_basica_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Dashboard Sidebar', 'id-basica' ),
			'id'            => 'dashboard-sidebar',
			'description'   => esc_html__( 'Add widgets here to appear in the sidebar.', 'id-basica' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget__header"><h3>',
			'after_title'   => '</h3></div><div class="widget__content">',
		)
	);
}
add_action( 'widgets_init', 'id_basica_widgets_init' );

/**
 * Dashboard functions
 */
function id_basica_is_dashboard_user() {
	// By default, only check if user is logged in
	// You can add more specific role checks here if needed
	return is_user_logged_in();
}

/**
 * Redirect non-admin users to the dashboard
 */
function id_basica_redirect_to_dashboard() {
	// Only apply to non-admin users who are logged in
	if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) && ! is_admin() ) {
		// Don't redirect on the dashboard page itself or on logout
		global $post;

		// Allow certain pages to be accessible
		$allowed_pages = array( 'dashboard', 'logout' );

		// Get current page slug
		$current_slug = $post ? $post->post_name : '';

		// If not on an allowed page and not a POST request
		if ( ! in_array( $current_slug, $allowed_pages, true ) && ! is_404() && 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			// Get dashboard page URL (you need to create this page)
			$dashboard_url = home_url( '/dashboard/' );

			wp_redirect( $dashboard_url );
			exit;
		}
	}
}
add_action( 'template_redirect', 'id_basica_redirect_to_dashboard' );
