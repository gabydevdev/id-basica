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

// Define template variables
$title = 'Solicitud Firmada por Jefe Inmediato';
$body = 'La solicitud de movimiento de personal ha sido firmada por el Jefe Inmediato y requiere autorización del Director de Administración.';
$stage_indicator = 'ETAPA 2 - Requiere Autorización del Director de Administración';

// Additional details specific to this stage
$additional_details = array(
	'<strong>Estado:</strong> Firmada por Jefe Inmediato ✅',
	'<strong>Siguiente paso:</strong> Autorización del Director de Administración',
	'<strong>Responsable:</strong> Ana Beatriz Juncal Tapia',
);

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
