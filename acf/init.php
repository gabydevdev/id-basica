<?php
/**
 * Advanced Custom Fields configuration.
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if ACF PRO is active
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
	// Abort all bundling, ACF PRO plugin takes priority
	return;
}

// Check if another plugin or theme has bundled ACF
if ( defined( 'MY_ACF_PATH' ) ) {
	return;
}

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', IDBASICA_THEME_DIR . '/acf/advanced-custom-fields-pro/' );
define( 'MY_ACF_URL', IDBASICA_THEME_URI . '/acf/advanced-custom-fields-pro/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the URL setting to fix incorrect asset URLs.
function id_basica_acf_settings_url( $url ) {
	return MY_ACF_URL;
}
add_filter( 'acf/settings/url', 'id_basica_acf_settings_url' );

/**
 * Define the path where ACF field groups will be saved as JSON files.
 *
 * @param string $path The default save path.
 * @return string The custom save path.
 */
function id_basica_acf_json_save_point( $path ) {
	$save_path = ID_BASICA_THEME_DIR . '/acf-json';
	return $save_path;
}
add_filter( 'acf/settings/save_json', 'id_basica_acf_json_save_point' );

/**
 * Define where ACF should look for JSON files to import.
 *
 * @param array $paths Array of load paths.
 * @return array Modified array of load paths.
 */
function id_basica_acf_json_load_point( $paths ) {
	$paths[] = ID_BASICA_THEME_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'id_basica_acf_json_load_point' );

// Optionally hide ACF menu in production
if ( ! WP_DEBUG && defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'production' ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
}

// Check if the ACF free plugin is activated
if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
	// Free plugin activated, show notice
	add_action( 'admin_notices', function () {
		?>
		<div class="notice notice-warning is-dismissible">
			<p><strong>ID Basica Theme:</strong> The free ACF plugin has been deactivated because this theme includes ACF Pro. Your field groups and data remain intact.</p>
		</div>
		<?php
	}, 99 );

	// Deactivate the ACF free plugin if it is active
	try {
		deactivate_plugins( 'advanced-custom-fields/acf.php' );
	} catch ( Exception $e ) {
		// Log error if deactivation fails
		error_log( 'Failed to deactivate ACF plugin: ' . $e->getMessage() );
	}
}

require_once IDBASICA_THEME_DIR . '/acf/options.php';
