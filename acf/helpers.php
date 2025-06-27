<?php

/**
 * Get the ACF field value.
 *
 * @param string   $selector The field name or key.
 * @param int|null $post_id  The post ID to get the field for. Defaults to the current post.
 * @return mixed The value of the ACF field or null if not found.
 */
function id_basica_get_acf_field( $selector, $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		_e( 'ACF plugin is not active or get_field function is not available', ID_BASICA_DOMAIN );
		return null;
	}

	$field = ( $post_id !== null ) ? get_field( $selector, $post_id ) : get_field( $selector );

	return $field;
}

/**
 * Get all ACF fields for a post.
 *
 * @param int|null $post_id The post ID to get the fields for or 'options'. Defaults to the current post.
 * @return array|null The array of ACF fields or null if not found.
 */
function id_basica_get_acf_fields( $post_id = null ) {
	if ( ! function_exists( 'get_fields' ) ) {
		_e( 'ACF plugin is not active or get_fields function is not available', ID_BASICA_DOMAIN );
		return null;
	}

	$fields = ( $post_id !== null ) ? get_fields( $post_id ) : get_fields();

	return $fields;
}

function id_basica_acf_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'field_groups' => '', // IDs separados por coma
		),
		$atts,
		'acf_form'
	);

	$args = array();

	// Verificar si hay un ID en la URL
	$post_id = 'new_post';
	if ( ! empty( $_GET['solicitud_id'] ) ) {
		$maybe_id = absint( $_GET['solicitud_id'] );
		if ( get_post_status( $maybe_id ) ) {
			$post_id = $maybe_id;
		}
	}

	$args['post_id'] = $post_id;

	if ( $post_id === 'new_post' ) {
		$args['new_post'] = array(
			'post_type'   => 'application',
			'post_status' => 'publish',
		);
	}

	if ( ! empty( $atts['field_groups'] ) ) {
		$args['field_groups'] = explode( ',', $atts['field_groups'] );
	} else {
		$args['field_groups'] = false;
	}

	$args['submit_value']          = esc_html__( 'Send', ID_BASICA_DOMAIN );
	$args['label_placement']       = 'top';
	$args['instruction_placement'] = 'field';
	$args['uploader']              = 'basic';
	$args['html_submit_button']    = '<input type="submit" class="acf-button button button-primary button-large" value="%s" />';

	ID_BASICA\DEV\console_log( $args );

	ob_start();

	acf_form( $args );

	return ob_get_clean();
}
add_shortcode( 'acf_form', 'id_basica_acf_form_shortcode' );
