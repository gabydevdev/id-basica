<?php
/**
 * Email template for new application creation
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define template variables
$title = 'Nueva Solicitud de Movimiento de Personal';
$body = 'Se ha creado una nueva solicitud de movimiento de personal que requiere su atención.';
$stage_indicator = 'NUEVA SOLICITUD - Requiere Firma de Jefe Inmediato';

// Additional details specific to creation stage
$additional_details = array();
$additional_details[] = '<strong>Estado:</strong> Pendiente de firma del Jefe Inmediato';
$additional_details[] = '<strong>Siguiente paso:</strong> Firma del Jefe Inmediato';

// Add movement details if available
if ( ! empty( $tipo_movimiento ) ) {
	$additional_details[] = '<strong>Tipo de movimiento:</strong> ' . esc_html( $tipo_movimiento );
}

if ( ! empty( $puesto_actual ) && ! empty( $puesto_nuevo ) ) {
	$additional_details[] = '<strong>Cambio de puesto:</strong> ' . esc_html( $puesto_actual ) . ' → ' . esc_html( $puesto_nuevo );
}

if ( ! empty( $departamento_actual ) && ! empty( $departamento_nuevo ) ) {
	$additional_details[] = '<strong>Cambio de departamento:</strong> ' . esc_html( $departamento_actual ) . ' → ' . esc_html( $departamento_nuevo );
}

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
