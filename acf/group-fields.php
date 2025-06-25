<?php
/**
 * ACF field group for Movimiento de personal (Personal Movement)
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if ACF is active
if ( ! class_exists( 'ACF' ) ) {
	return;
}

function id_basica_acf_frontend_styles_and_scripts() {
	if ( ! is_admin() ) {
		$acf_fields_js_asset_file  = include( ID_BASICA_THEME_DIR . '/build/js/acf-fields.asset.php' );
		$acf_fields_css_asset_file = include( ID_BASICA_THEME_DIR . '/build/css/acf-fields.asset.php' );

		wp_enqueue_script(
			'id-basica-acf-fields',
			ID_BASICA_THEME_URI . '/build/js/acf-fields.js',
			array( 'jquery', 'acf-input' ),
			$acf_fields_js_asset_file['version']
		);

		wp_enqueue_style(
			'id-basica-acf-fields',
			ID_BASICA_THEME_URI . '/build/css/acf-fields.css',
			array(),
			$acf_fields_css_asset_file['version']
		);
	}
}
add_action( 'acf/input/admin_enqueue_scripts', 'id_basica_acf_frontend_styles_and_scripts', 9999 );

add_filter( 'acf/prepare_field/name=fecha_de_formato', function ($field) {
	$field['disabled'] = true;

	// Set default value to current date if not already set
	if ( empty( $field['value'] ) ) {
		$field['value'] = date('d/m/Y');
	}

	return $field;
} );

add_filter( 'acf/prepare_field/name=nombre_de_empleado', function ($field) {
	$field['disabled'] = true;

	// get current user
	$current_user   = wp_get_current_user();
	$field['value'] = $current_user->display_name;

	return $field;
} );

add_filter( 'acf/load_field/name=puesto', function ($field) {
	// Reset choices
	$field['choices'] = array();

	// Path to the JSON file
	$json_file_path = get_template_directory() . '/puesto.json';

	// Check if the file exists
	if ( file_exists( $json_file_path ) ) {
		// Get the contents of the JSON file
		$json_data = file_get_contents( $json_file_path );

		// Decode the JSON data into an associative array
		$puestos = json_decode( $json_data, true );

		// Check if decoding was successful
		if ( is_array( $puestos ) ) {
			// Populate the choices with the JSON data
			foreach ( $puestos as $key => $value ) {
				$field['choices'][ $key ] = $value;
			}
		}
	}

	return $field;
} );

add_filter( 'acf/load_field/name=departamento', function ($field) {
	// Reset choices
	$field['choices'] = array();

	// Path to the JSON file
	$json_file_path = get_template_directory() . '/departamento.json';

	// Check if the file exists
	if ( file_exists( $json_file_path ) ) {
		// Get the contents of the JSON file
		$json_data = file_get_contents( $json_file_path );

		// Decode the JSON data into an associative array
		$puestos = json_decode( $json_data, true );

		// Check if decoding was successful
		if ( is_array( $puestos ) ) {
			// Populate the choices with the JSON data
			foreach ( $puestos as $key => $value ) {
				$field['choices'][ $key ] = $value;
			}
		}
	}

	return $field;
} );

add_filter( 'acf/prepare_field/name=user_id', function ($field) {
	// Set the value to the current user's ID
	$current_user = wp_get_current_user();
	$field['value'] = $current_user->ID;

	return $field;
} );

add_filter( 'acf/prepare_field/name=entry_title', function ($field) {
	// Set the value to the current entry's title
	$current_entry = get_post( get_the_ID() );
	$current_user  = wp_get_current_user();
	$current_date  = date( 'Y-m-d' );

	
	$field['value'] = $current_entry->post_title . ' - ' . $current_user->display_name . ' - ' . $current_date;

	return $field;
} );
