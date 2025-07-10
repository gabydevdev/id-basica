<?php
/**
 * Email template for director de administracion authorization notification
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
	$title = 'Su solicitud ha sido autorizada por el Director de Administración';
	$body = 'Su solicitud de movimiento de personal ha sido autorizada por el Director de Administración. La solicitud ahora está en proceso de obtener el Vo. Bo. de Capital Humano.';
	$stage_indicator = '🟡 PROGRESO - Autorizada por Director, esperando Vo. Bo. de Capital Humano';
	
	// Additional details for author
	$additional_details = array(
		'<strong>Estado:</strong> <span style="color: #28a745;">✅ Autorizada por Director de Administración</span>',
		'<strong>Siguiente paso:</strong> En espera de Vo. Bo. de Capital Humano',
		'<strong>Progreso:</strong> <span style="color: #28a745;">2 de 4 firmas completadas</span>',
	);
} else {
	// Message for capital humano
	$title = 'Solicitud de Movimiento de Personal requiere su Vo. Bo.';
	$body = 'La solicitud de movimiento de personal creada por ' . esc_html( $author->display_name ) . ' ha sido firmada por el Jefe Inmediato y autorizada por el Director de Administración. Ahora requiere su Vo. Bo. como Capital Humano. Por favor, revise la solicitud y dé su visto bueno si está conforme.';
	$stage_indicator = '🔴 ACCIÓN REQUERIDA - Dé su Vo. Bo. como Capital Humano';
	
	// Additional details for capital humano
	$additional_details = array(
		'<strong>Estado:</strong> <span style="color: #28a745;">✅ Autorizada por Director de Administración</span>',
		'<strong>Acción requerida:</strong> <span style="color: #d22;">Dar Vo. Bo. como Capital Humano</span>',
		'<strong>Progreso:</strong> <span style="color: #28a745;">2 de 4 firmas completadas</span>',
	);
}

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
