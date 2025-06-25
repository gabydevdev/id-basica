<?php
/**
 * Development helper functions
 *
 * @package ID_Basica
 */

namespace ID_BASICA\DEV;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the current environment is a development environment.
 *
 * @return bool True if the current environment is a development environment.
 */
function is_dev() {
	$current_domain = parse_url( site_url(), PHP_URL_HOST );
	$dev_domains    = array( 'local', 'localhost', 'dev', '127.0.0.1' );

	// Check if the current domain matches any of the development domains
	if ( in_array( $current_domain, $dev_domains, true ) ) {
		return true;
	}

	// Check if WP_ENVIRONMENT_TYPE is set to a development-related value
	$dev_env_types = array( 'local', 'staging', 'development' );
	if ( defined( 'WP_ENVIRONMENT_TYPE' ) && in_array( WP_ENVIRONMENT_TYPE, $dev_env_types, true ) ) {
		return true;
	}

	return false;
}

/**
 * Prints the formatted debug styles.
 */
function debug_styles() {
	static $first_time = true;

	if ( ! $first_time ) {
		return;
	}

	$styles = '<style type="text/css">
			div.nanato-print-r {
				margin: 10px auto;
				padding: 0;
				max-width: 100%;
				width: calc(100% - (30px * 2));
				max-height: 500px;
				border-radius: 3px;
				background: #23282d;
				border: 1px solid #F5F5F5;
				position: relative;
				z-index: 999;
				overflow-y: scroll;
			}
			div.nanato-print-r pre {
				display: block;
				margin: 0;
				padding: 5px;
				color: #78FF5B;
				background: #23282d;
				text-shadow: 1px 1px 0 #000;
				font-family: Consolas, monospace;
				font-size: 12px;
				line-height: 16px;
				text-align: left;
			}
			div.nanato-print-r-group {
				margin: 10px auto;
				padding: 1px;
				border-radius: 5px;
				background: #f1f1f1;
				position: relative;
				z-index: 11110;
			}
			div.nanato-print-r-group div.nanato-print-r {
				margin: 9px;
				border-width: 0;
			}
		</style>';

	echo $styles;
	$first_time = false;
}

/**
 * Prints a value or a group of values in a formatted way.
 *
 * @param mixed ...$args The value or values to be printed.
 */
function print_log( ...$args ) {
	if ( ! is_dev() || empty( $args ) ) {
		return;
	}

	debug_styles();

	if ( count( $args ) === 1 ) {
		echo '<div class="nanato-print-r"><pre>';
		echo htmlspecialchars( print_r( $args[0], true ), ENT_QUOTES, 'UTF-8' );
		echo '</pre></div>';
	} else {
		echo '<div class="nanato-print-r-group">';
		foreach ( $args as $param ) {
			print_log( $param );
		}
		echo '</div>';
	}
}

/**
 * Alias for print_log
 *
 * @see print_log()
 */
function debug( ...$args ) {
	print_log( ...$args );
}

/**
 * Log data to the browser console.
 *
 * @param mixed $data The data to log.
 */
function console_log( $data ) {
	if ( ! is_dev() || empty( $data ) ) {
		return;
	}

	echo '<script>';
	echo 'console.log(' . wp_json_encode( $data ) . ');';
	echo '</script>';
}
