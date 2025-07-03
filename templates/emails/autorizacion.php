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

// Define template variables
$title = 'Solicitud Autorizada por Director de Administración';
$body = 'La solicitud de movimiento de personal ha sido autorizada por el Director de Administración y requiere el Vo. Bo. de Capital Humano.';
$stage_indicator = 'ETAPA 3 - Requiere Vo. Bo. de Capital Humano';

// Additional details specific to this stage
$additional_details = array(
	'<strong>Estado:</strong> Autorizada por Director de Administración ✅',
	'<strong>Siguiente paso:</strong> Vo. Bo. de Capital Humano',
	'<strong>Responsable:</strong> Departamento de Capital Humano',
);

// Include the base template
include get_template_directory() . '/templates/emails/base-template.php';
