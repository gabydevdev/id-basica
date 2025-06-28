<?php
/**
 * ACF field group for Movimiento de personal (Personal Movement)
 *
 * @package ID_Basica
 */

use function ID_BASICA\DEV\console_log;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if ACF is active
if ( ! class_exists( 'ACF' ) ) {
	return;
}

/**
 * Enqueue frontend styles and scripts for ACF forms.
 *
 * This function loads custom CSS and JavaScript files for ACF fields
 * on the frontend only (not in admin area).
 *
 * @since 1.0.0
 */
function id_basica_acf_frontend_styles_and_scripts() {
	if ( ! is_admin() ) {
		$acf_fields_js_asset_file  = include ID_BASICA_DIR . '/build/js/acf-fields.asset.php';
		$acf_fields_css_asset_file = include ID_BASICA_DIR . '/build/css/acf-fields.asset.php';

		wp_enqueue_script(
			'id-basica-acf-fields',
			ID_BASICA_URI . '/build/js/acf-fields.js',
			array( 'jquery', 'acf-input' ),
			$acf_fields_js_asset_file['version'],
			true
		);

		wp_enqueue_style(
			'id-basica-acf-fields',
			ID_BASICA_URI . '/build/css/acf-fields.css',
			array(),
			$acf_fields_css_asset_file['version']
		);
	}
}
add_action( 'acf/input/admin_enqueue_scripts', 'id_basica_acf_frontend_styles_and_scripts', 9999 );

/**
 * Modify the `fecha_de_formato` field on field load.
 *
 * - Set the field as disabled.
 * - Set the current date in in d/m/Y format as value, if no value is already set.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_load_field_fecha_de_formato( $field ) {
	$field['disabled'] = true;

	// Set default value to current date if not already set.
	if ( empty( $field['value'] ) ) {
		$field['value'] = date( 'd/m/Y' );
	}

	return $field;
}
add_filter( 'acf/load_field/name=fecha_de_formato', 'id_basica_load_field_fecha_de_formato' );

/**
 * Modify the `nombre_de_empleado` field on field load.
 *
 * - Set the field as disabled.
 * - Set the current user's display name as value, if no value is already set.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_load_field_nombre_de_empleado( $field ) {
	$field['disabled'] = true;

	// Get current user.
	$current_user = wp_get_current_user();

	if ( empty( $field['value'] ) && $current_user->exists() ) {
		$field['value'] = sanitize_text_field( $current_user->display_name );
	}

	return $field;
}
add_filter( 'acf/load_field/name=nombre_de_empleado', 'id_basica_load_field_nombre_de_empleado' );

/**
 * Modify the `user_id` field on field load.
 *
 * Set the current user's ID as value, if no value is already set.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_load_field_user_id( $field ) {
	// Set the value to the current user's ID.
	$current_user = wp_get_current_user();

	if ( empty( $field['value'] ) && $current_user->exists() ) {
		$field['value'] = absint( $current_user->ID );
	}

	return $field;
}
add_filter( 'acf/load_field/name=user_id', 'id_basica_load_field_user_id' );

/**
 * Modify the `entry_title` field on field load.
 *
 * Generate a title combining the:
 *  - form name
 *  - user display name
 *  - and current date;
 * set it as value, if no value is already set.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_load_field_entry_title( $field ) {
	if ( empty( $field['value'] ) ) {
		$current_entry = get_post( get_the_ID() );
		$current_user  = wp_get_current_user();
		$current_date  = date( 'Y-m-d' );

		// Only proceed if we have valid data.
		if ( $current_entry && $current_user->exists() ) {
			$field['value'] = sanitize_text_field(
				$current_entry->post_title . ' - ' . $current_user->display_name . ' - ' . $current_date
			);
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=entry_title', 'id_basica_load_field_entry_title' );

/**
 * Populate `puesto` field choices from JSON file.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_populate_puesto_field_choices( $field ) {
	// Reset choices.
	$field['choices'] = array();

	$field['choices'][''] = '';

	// Path to the JSON file.
	$json_file_path = get_template_directory() . '/puesto.json';

	// Check if the file exists.
	if ( file_exists( $json_file_path ) ) {
		// Get the contents of the JSON file.
		$json_data = file_get_contents( $json_file_path );

		// Decode the JSON data into an associative array.
		$puestos = json_decode( $json_data, true );

		// Check if decoding was successful.
		if ( is_array( $puestos ) ) {
			// Populate the choices with the JSON data.
			foreach ( $puestos as $key => $value ) {
				$field['choices'][ sanitize_key( $key ) ] = sanitize_text_field( $value );
			}
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=puesto', 'id_basica_populate_puesto_field_choices' );

/**
 * Populate `departamento` field choices from JSON file.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_populate_departamento_field_choices( $field ) {
	// Reset choices.
	$field['choices'] = array();

	$field['choices'][''] = '';

	// Path to the JSON file.
	$json_file_path = get_template_directory() . '/departamento.json';

	// Check if the file exists.
	if ( file_exists( $json_file_path ) ) {
		// Get the contents of the JSON file.
		$json_data = file_get_contents( $json_file_path );

		// Decode the JSON data into an associative array.
		$departamentos = json_decode( $json_data, true );

		// Check if decoding was successful.
		if ( is_array( $departamentos ) ) {
			// Populate the choices with the JSON data.
			foreach ( $departamentos as $key => $value ) {
				$field['choices'][ sanitize_key( $key ) ] = sanitize_text_field( $value );
			}
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=departamento', 'id_basica_populate_departamento_field_choices' );

/**
 * Populate `jefe_inmediato` field choices.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_populate_jefe_inmediato_field_choices( $field ) {
	console_log( $field );

	// Reset choices.
	$field['choices'] = array();

	$field['choices'][''] = '';

	// Get all users with the 'jefe_inmediato' role.
	$args = array(
		'role'   => 'jefe_inmediato',
		'fields' => array( 'ID', 'display_name' ),
	);

	$users = get_users( $args );

	// Populate the choices with user data.
	if ( ! empty( $users ) ) {
		foreach ( $users as $user ) {
			$field['choices'][ $user->ID ] = sanitize_text_field( $user->display_name );
		}
	}

	// If no users found, add a default option.
	if ( empty( $field['choices'] ) ) {
		$field['choices']['no_jefe'] = __( 'No Jefe Inmediato Found', 'id-basica' );
	}

	return $field;
}
add_filter( 'acf/load_field/name=jefe_inmediato', 'id_basica_populate_jefe_inmediato_field_choices' );
