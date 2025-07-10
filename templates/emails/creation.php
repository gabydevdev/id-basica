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

// Define template variables based on recipient type
if ( $recipient_type === 'author' ) {
	// Message for the form author
	$title = 'Su solicitud de Movimiento de Personal ha sido creada';
	$body = 'Su solicitud de movimiento de personal ha sido creada exitosamente y está en proceso de aprobación. Puede verificar el estado de su solicitud en cualquier momento utilizando el enlace a continuación.';
	$stage_indicator = '🟡 SOLICITUD CREADA - En espera de aprobación del Jefe Inmediato';
} else {
	// Message for jefe inmediato
	$title = 'Nueva solicitud de Movimiento de Personal requiere su firma';
	$body = 'Se ha creado una nueva solicitud de movimiento de personal por ' . esc_html( $author->display_name ) . ' que requiere su firma como Jefe Inmediato. Por favor, revise la solicitud y firme si está conforme.';
	$stage_indicator = '🔴 ACCIÓN REQUERIDA - Firme como Jefe Inmediato';
}

// Additional details specific to creation stage
$additional_details = array();
if ( $recipient_type === 'author' ) {
	$additional_details[] = '<strong>Estado:</strong> <span style="color: #ffc107;">⏳ Pendiente de firma del Jefe Inmediato</span>';
	$additional_details[] = '<strong>Siguiente paso:</strong> Esperando firma del Jefe Inmediato';
	$additional_details[] = '<strong>Progreso:</strong> <span style="color: #d22;">0 de 4 firmas completadas</span>';
} else {
	$additional_details[] = '<strong>Estado:</strong> <span style="color: #ffc107;">⏳ Pendiente de su firma</span>';
	$additional_details[] = '<strong>Acción requerida:</strong> <span style="color: #d22;">Firmar como Jefe Inmediato</span>';
	$additional_details[] = '<strong>Prioridad:</strong> <span style="color: #dc3545;">🔴 Alta - Primera firma requerida</span>';
}

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
