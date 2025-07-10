<?php
/**
 * Email template for capital humano approval notification
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
	$title = 'Su solicitud tiene Vo. Bo. de Capital Humano';
	$body = 'Su solicitud de movimiento de personal ha recibido el Vo. Bo. de Capital Humano. La solicitud ahora está en la etapa final esperando ser recibida por el Coordinador Fiscal.';
	$stage_indicator = '🟡 PROGRESO - Con Vo. Bo. de Capital Humano, esperando Coordinador Fiscal';
	
	// Additional details for author
	$additional_details = array(
		'<strong>Estado:</strong> <span style="color: #28a745;">✅ Con Vo. Bo. de Capital Humano</span>',
		'<strong>Siguiente paso:</strong> En espera de recepción del Coordinador Fiscal',
		'<strong>Progreso:</strong> <span style="color: #28a745;">3 de 4 firmas completadas</span>',
	);
} else {
	// Message for coordinador fiscal
	$title = 'Solicitud de Movimiento de Personal requiere su recepción final';
	$body = 'La solicitud de movimiento de personal creada por ' . esc_html( $author->display_name ) . ' ha sido firmada por el Jefe Inmediato, autorizada por el Director de Administración y recibió el Vo. Bo. de Capital Humano. Ahora requiere su recepción final como Coordinador Fiscal. Por favor, revise la solicitud y finalice el proceso.';
	$stage_indicator = '🔴 ACCIÓN REQUERIDA - Recibir y finalizar como Coordinador Fiscal';
	
	// Additional details for coordinador fiscal
	$additional_details = array(
		'<strong>Estado:</strong> <span style="color: #28a745;">✅ Con Vo. Bo. de Capital Humano</span>',
		'<strong>Acción requerida:</strong> <span style="color: #d22;">Recibir y finalizar como Coordinador Fiscal</span>',
		'<strong>Progreso:</strong> <span style="color: #ffc107;">⚡ 3 de 4 firmas completadas - ÚLTIMA ETAPA</span>',
	);
}

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
