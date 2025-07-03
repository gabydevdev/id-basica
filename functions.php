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
define( 'ID_BASICA_DOMAIN', 'id-basica' );
define( 'ID_BASICA_VERSION', '1.0.0' );
define( 'ID_BASICA_DIR', get_template_directory() );
define( 'ID_BASICA_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since 1.0.0
 */
function id_basica_theme_setup() {
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
			'dashboard-menu' => esc_html__( 'Dashboard Menu', ID_BASICA_DOMAIN ),
			'user-menu'      => esc_html__( 'User Menu', ID_BASICA_DOMAIN ),
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
add_action( 'after_setup_theme', 'id_basica_theme_setup' );

/**
 * Custom feed links without comments feed.
 *
 * Generates RSS feed link for posts only, excluding comments feed.
 * This is used as a replacement for default wp_head feed links.
 *
 * @since 1.0.0
 */
function id_basica_custom_feed_links() {
	// Only add the posts feed, skip comments feed
	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . ' &raquo; Feed" href="' . esc_url( get_feed_link() ) . "\" />\n";
}

// Load theme helpers and ACF initialization
require_once ID_BASICA_DIR . '/inc/dev-helpers.php';
require_once ID_BASICA_DIR . '/inc/helpers.php';
require_once ID_BASICA_DIR . '/acf/init.php';
require_once ID_BASICA_DIR . '/acf/helpers.php';
require_once ID_BASICA_DIR . '/admin/init.php';

/**
 * Enqueue styles for the theme.
 *
 * @since 1.0.0
 */
function id_basica_styles() {
	// $main_css_asset_file = include ID_BASICA_DIR . '/build/css/main.asset.php';

	// Enqueue main stylesheet
	wp_enqueue_style(
		'id-basica-style',
		ID_BASICA_URI . '/build/css/main.css',
		array(),
		ID_BASICA_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'id_basica_styles', 9998 );

/**
 * Enqueue scripts for the theme.
 *
 * @since 1.0.0
 */
function id_basica_scripts() {
	// $main_js_asset_file = include ID_BASICA_DIR . '/build/js/main.asset.php';

	// Enqueue main script
	wp_enqueue_script(
		'id-basica-script',
		ID_BASICA_URI . '/build/js/main.js',
		array(),
		ID_BASICA_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'id_basica_scripts', 9998 );

// Include custom post types
require_once ID_BASICA_DIR . '/inc/post-types.php';

/**
 * Register widget areas.
 *
 * Registers sidebar widget areas for the theme dashboard.
 *
 * @since 1.0.0
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function id_basica_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Dashboard Sidebar', ID_BASICA_DOMAIN ),
			'id'            => 'dashboard-sidebar',
			'description'   => esc_html__( 'Add widgets here to appear in the sidebar.', ID_BASICA_DOMAIN ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget__header"><h3>',
			'after_title'   => '</h3></div><div class="widget__content">',
		)
	);
}
add_action( 'widgets_init', 'id_basica_widgets_init' );

/**
 * Check if current user should have dashboard access.
 *
 * Determines if the current user should be able to access
 * the dashboard interface. Currently checks for logged-in status.
 *
 * @since 1.0.0
 * @return bool True if user should have dashboard access, false otherwise.
 */
function id_basica_is_dashboard_user() {
	// By default, only check if user is logged in
	// You can add more specific role checks here if needed
	return is_user_logged_in();
}
