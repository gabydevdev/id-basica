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

// Define template variables - only author receives this notification
$title = 'Su solicitud de Movimiento de Personal está COMPLETADA';
$body = 'Su solicitud de movimiento de personal ha sido completada exitosamente. El Coordinador Fiscal ha recibido la solicitud y el proceso ha finalizado. Todas las firmas y autorizaciones requeridas han sido obtenidas.';
$stage_indicator = '🎉 PROCESO COMPLETADO - Todas las firmas obtenidas';

// Additional details for completed process
$additional_details = array(
	'<strong>Estado:</strong> <span style="color: #28a745;">🎉 Proceso completado exitosamente</span>',
	'<strong>Firmas obtenidas:</strong> <span style="color: #28a745;">✅ Jefe Inmediato, ✅ Director de Administración, ✅ Capital Humano, ✅ Coordinador Fiscal</span>',
	'<strong>Progreso:</strong> <span style="color: #28a745;">🎯 4 de 4 firmas completadas</span>',
	'<strong>Fecha de finalización:</strong> <span style="color: #6b6d6f;">' . date( 'd/m/Y H:i' ) . '</span>',
);

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
