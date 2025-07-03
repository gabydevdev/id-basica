<?php

use function ID_BASICA\DEV\console_log;
/**
 * Functions for ACF field groups in ID Basica theme.
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend styles and scripts for ACF forms.
 *
 * This function loads custom CSS and JavaScript files for ACF fields
 * on the frontend only (not in admin area).
 *
 * @since 1.0.0
 */
function id_basica_acf_frontend_scripts() {
	if ( ! is_admin() ) {
		$acf_fields_js_asset_file  = include ID_BASICA_DIR . '/build/js/acf-fields.asset.php';

		wp_enqueue_script(
			'id-basica-acf-fields',
			ID_BASICA_URI . '/build/js/acf-fields.js',
			array( 'jquery', 'acf-input' ),
			ID_BASICA_VERSION,
			true
		);

		wp_localize_script( 'id-basica-acf-fields', 'id_basica_acf_ajax_object', array(
			'current_user_id' => get_current_user_id()
		) );
	}
}
add_action( 'acf/input/admin_enqueue_scripts', 'id_basica_acf_frontend_scripts', 9999 );

function id_basica_populate_field_fecha_de_formato( $field ) {
	// Set default value to current date if not already set.
	if ( empty( $field['value'] ) ) {
		$field['value'] = date( 'd/m/Y' );
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=fecha_de_formato', 'id_basica_populate_field_fecha_de_formato' );

function id_basica_populate_field_nombre_de_empleado( $field ) {
	// Get current user.
	$current_user = wp_get_current_user();

	if ( empty( $field['value'] ) && $current_user && $current_user->exists() ) {
		// Get the user's display name.
		$nombre_de_empleado = $current_user->display_name;

		if ( $nombre_de_empleado ) {
			// Set the value to the user's display name.
			$field['value'] = sanitize_text_field( $nombre_de_empleado );
		}
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=nombre_de_empleado', 'id_basica_populate_field_nombre_de_empleado' );

function id_basica_populate_field_puesto( $field ) {
	// Get current user.
	$current_user = wp_get_current_user();

	// Set default value to current user's puesto if not already set.
	if ( empty( $field['value'] ) && $current_user && $current_user->exists() ) {
		$puesto = get_user_meta( $current_user->ID, 'puesto', true );

		if ( $puesto ) {
			$field['value'] = sanitize_text_field( $puesto );
		}
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=puesto', 'id_basica_populate_field_puesto' );

function id_basica_populate_field_departamento( $field ) {
	// Get current user.
	$current_user = wp_get_current_user();

	// Set default value to current user's department if not already set.
	if ( empty( $field['value'] ) && $current_user && $current_user->exists() ) {
		$departamento = get_user_meta( $current_user->ID, 'departamento', true );

		if ( $departamento ) {
			$field['value'] = sanitize_text_field( $departamento );
		}
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=departamento', 'id_basica_populate_field_departamento' );

function id_basica_populate_field_user_id( $field ) {
	// Get current user.
	$current_user = wp_get_current_user();
	
	// Set default value to current user ID if not already set.
	if ( empty( $field['value'] ) && $current_user && $current_user->exists() ) {
		$field['value'] = absint( $current_user->ID );
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=user_id', 'id_basica_populate_field_user_id' );

function id_basica_populate_field_jefe_inmediato_id( $field ) {
	// Get current user.
	$current_user = wp_get_current_user();

	// Set default value to the jefe inmediato ID from user meta if not already set.
	if ( empty( $field['value'] ) && $current_user && $current_user->exists() ) {
		$jefe_inmediato_id = get_user_meta( $current_user->ID, 'jefe_inmediato', true );

		if ( $jefe_inmediato_id ) {
			$field['value'] = $jefe_inmediato_id;
		}
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=jefe_inmediato_id', 'id_basica_populate_field_jefe_inmediato_id' );

function id_basica_populate_field_entry_title( $field ) {
	// Get current user.
	$current_user = wp_get_current_user();

	if ( empty( $field['value'] ) && $current_user && $current_user->exists() ) {
		$current_date = date( 'd/m/Y' );

		$form_name = 'Solicitud';

		// Generate title: "Form Name - User Name - Date"
		$field['value'] = sanitize_text_field(
			$form_name . ' - ' . $current_user->display_name . ' - ' . $current_date
		);
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=entry_title', 'id_basica_populate_field_entry_title' );

function id_basica_field_firma_de_jefe_inmediato( $field ) {
	$current_user      = wp_get_current_user();
	$jefe_inmediato_id = get_user_meta( $current_user->ID, 'jefe_inmediato', true );

	ID_BASICA\DEV\console_log( get_field( 'jefe_inmediato_id' ) );

	if ( get_field( 'jefe_inmediato_id' ) ) {
		$jefe_inmediato_id = get_field( 'jefe_inmediato_id' );
	}

	$jefe_inmediato_user = get_user_by( 'id', $jefe_inmediato_id );

	if ( $jefe_inmediato_user ) {
		// Set the value to the jefe inmediato's display name.
		$jefe_inmediato_name   = sanitize_text_field( $jefe_inmediato_user->display_name );
		$field['instructions'] = $jefe_inmediato_name . "<br>\r\nJefe Inmediato<br>\r\nSolicita";
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=firma_de_jefe_inmediato', 'id_basica_field_firma_de_jefe_inmediato' );

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

/**
 * Populate `group_field` field choices.
 *
 * @since 1.0.0
 * @param array $field The ACF field array.
 * @return array Modified field array.
 */
function id_basica_populate_group_field_choices( $field ) {
	// Reset choices.
	$field['choices'] = array();

	$field['choices'][''] = '';

	// Get all ACF field groups.
	$groups = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : array();

	// Populate the choices with group data.
	if ( ! empty( $groups ) ) {
		foreach ( $groups as $group ) {
			$field['choices'][ $group['ID'] ] = sanitize_text_field( $group['title'] );
		}
	}

	// If no groups found, add a default option.
	if ( empty( $field['choices'] ) ) {
		$field['choices']['no_group'] = __( 'No Group Found', 'id-basica' );
	}

	return $field;
}
add_filter( 'acf/load_field/name=group_field', 'id_basica_populate_group_field_choices' );
