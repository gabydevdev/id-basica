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

// Define template variables
$title = 'Solicitud con Vo. Bo. de Capital Humano';
$body = 'La solicitud de movimiento de personal ha recibido el Vo. Bo. de Capital Humano y ahora debe ser recibida por el Coordinador Fiscal.';
$stage_indicator = 'ETAPA 4 - Requiere Recepción del Coordinador Fiscal';

// Additional details specific to this stage
$additional_details = array(
	'<strong>Estado:</strong> Con Vo. Bo. de Capital Humano ✅',
	'<strong>Siguiente paso:</strong> Recepción del Coordinador Fiscal',
	'<strong>Responsable:</strong> Vianey Bahena Ramírez',
);

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
