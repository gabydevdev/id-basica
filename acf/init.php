<?php
/**
 * Advanced Custom Fields (ACF) configuration and initialization.
 *
 * Handles ACF Pro plugin dependency checking, field group loading,
 * and theme-specific ACF configurations. Ensures ACF Pro is available
 * and properly configured for the theme's requirements.
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check ACF Pro plugin status
$acf_pro_plugin_path = 'advanced-custom-fields-pro/acf.php';
$acf_pro_installed   = file_exists( WP_PLUGIN_DIR . '/' . $acf_pro_plugin_path );
$acf_pro_active      = is_plugin_active( $acf_pro_plugin_path );

if ( $acf_pro_installed && ! $acf_pro_active ) {
	// ACF Pro is installed but not active - show activation notice
	add_action(
		'admin_notices',
		function () {
			?>
		<div class="notice notice-error is-dismissible">
			<p><strong>ID Basica Theme:</strong> Advanced Custom Fields Pro is installed but not active. Please <a href="<?php echo admin_url( 'plugins.php' ); ?>">activate it</a> for full theme functionality.</p>
		</div>
			<?php
		}
	);
} elseif ( ! $acf_pro_active && ! defined( 'MY_ACF_PATH' ) ) {
	// ACF Pro not active (either not installed or deactivated) - use bundled version
	$bundled_acf_path = ID_BASICA_DIR . '/acf/advanced-custom-fields-pro/';
	$bundled_acf_file = $bundled_acf_path . 'acf.php';

	if ( file_exists( $bundled_acf_file ) ) {
		// Define path and URL to the bundled ACF plugin.
		define( 'MY_ACF_PATH', $bundled_acf_path );
		define( 'MY_ACF_URL', ID_BASICA_URI . '/acf/advanced-custom-fields-pro/' );

		// Include the ACF plugin.
		include_once $bundled_acf_file;

		// Customize the URL setting to fix incorrect asset URLs.
		function id_basica_acf_settings_url( $url ) {
			return MY_ACF_URL;
		}
		add_filter( 'acf/settings/url', 'id_basica_acf_settings_url' );

		// Show notice that bundled version is being used
		add_action(
			'admin_notices',
			function () {
				?>
			<div class="notice notice-info is-dismissible">
				<p><strong>ID Basica Theme:</strong> Using bundled ACF Pro. Consider installing the plugin version for easier updates.</p>
			</div>
				<?php
			}
		);
	} else {
		// Neither plugin nor bundled version available
		add_action(
			'admin_notices',
			function () {
				?>
			<div class="notice notice-error">
				<p><strong>ID Basica Theme:</strong> Advanced Custom Fields Pro is required but not found. Please install the plugin or contact theme support.</p>
			</div>
				<?php
			}
		);
	}
}

require_once ID_BASICA_DIR . '/acf/options.php';
require_once ID_BASICA_DIR . '/acf/group-fields.php';

/**
 * Define the path where ACF field groups will be saved as JSON files.
 *
 * @param string $path The default save path.
 * @return string The custom save path.
 */
function id_basica_acf_json_save_point( $path ) {
	$save_path = ID_BASICA_DIR . '/acf-json';
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
	$paths[] = ID_BASICA_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'id_basica_acf_json_load_point' );

function id_basica_acfe_modules() {
	// Disable Enhanced UI
	acfe_update_setting( 'modules/ui', false );

	if ( ID_BASICA\DEV\is_dev() ) {
		acfe_update_setting( 'dev', true ); // enable developer mode
	}
}
add_action( 'acfe/init', 'id_basica_acfe_modules' );

// Optionally hide ACF menu in production
if ( ! WP_DEBUG && defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'production' ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
}

// Check if the ACF free plugin is activated
if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
	// Free plugin activated, show notice
	add_action(
		'admin_notices',
		function () {
			?>
		<div class="notice notice-warning is-dismissible">
			<p><strong>ID Basica Theme:</strong> The free ACF plugin has been deactivated because this theme includes ACF Pro. Your field groups and data remain intact.</p>
		</div>
			<?php
		},
		99
	);

	// Deactivate the ACF free plugin if it is active
	try {
		deactivate_plugins( 'advanced-custom-fields/acf.php' );
	} catch ( Exception $e ) {
		// Log error if deactivation fails
		error_log( 'Failed to deactivate ACF plugin: ' . $e->getMessage() );
	}
}
