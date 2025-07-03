<?php
/**
 * Email template for coordinador fiscal completion notification
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define template variables
$title = 'Solicitud de Movimiento de Personal COMPLETADA';
$body = 'La solicitud de movimiento de personal ha sido completada exitosamente. El Coordinador Fiscal ha recibido la solicitud y el proceso ha finalizado.';
$stage_indicator = 'PROCESO COMPLETADO ✅';

// Additional details specific to this stage
$additional_details = array(
	'<strong>Estado:</strong> Proceso completado exitosamente ✅',
	'<strong>Resultado:</strong> Solicitud procesada y archivada',
	'<strong>Fecha de finalización:</strong> ' . date( 'd/m/Y H:i' ),
);

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
