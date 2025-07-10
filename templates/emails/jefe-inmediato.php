<?php
/**
 * Email template for jefe inmediato signature notification
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
	$title = 'Su solicitud ha sido firmada por el Jefe Inmediato';
	$body = 'Su solicitud de movimiento de personal ha sido firmada por el Jefe Inmediato. La solicitud ahora está en proceso de autorización por el Director de Administración.';
	$stage_indicator = '🟡 PROGRESO - Firmada por Jefe Inmediato, esperando autorización';
	
	// Additional details for author
	$additional_details = array(
		'<strong>Estado:</strong> <span style="color: #28a745;">✅ Firmada por Jefe Inmediato</span>',
		'<strong>Siguiente paso:</strong> En espera de autorización del Director de Administración',
		'<strong>Progreso:</strong> <span style="color: #28a745;">1 de 4 firmas completadas</span>',
	);
} else {
	// Message for director de administracion
	$title = 'Solicitud de Movimiento de Personal requiere su autorización';
	$body = 'La solicitud de movimiento de personal creada por ' . esc_html( $author->display_name ) . ' ha sido firmada por el Jefe Inmediato y ahora requiere su autorización como Director de Administración. Por favor, revise la solicitud y autorice si está conforme.';
	$stage_indicator = '🔴 ACCIÓN REQUERIDA - Autorice como Director de Administración';
	
	// Additional details for director
	$additional_details = array(
		'<strong>Estado:</strong> <span style="color: #28a745;">✅ Firmada por Jefe Inmediato</span>',
		'<strong>Acción requerida:</strong> <span style="color: #d22;">Autorizar como Director de Administración</span>',
		'<strong>Progreso:</strong> <span style="color: #28a745;">1 de 4 firmas completadas</span>',
	);
}

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
