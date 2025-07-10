<?php
/**
 * ACF (Advanced Custom Fields) custom functions and form handlers.
 *
 * This file contains custom filters and functions for handling
 * ACF forms and field modifications within the theme.
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle email notifications for 'Movimiento de Personal' application forms
 *
 * This function is triggered after ACF saves post data and handles
 * sending email notifications based on the workflow stage of the application.
 *
 * @since 1.0.0
 * @param int $post_id The post ID that was saved
 */
function id_basica_handle_movimiento_personal_notifications( $post_id ) {
	// Debug: Log function entry
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Notification handler called for post ID: {$post_id}" );
	}

	// Only process application posts
	if ( get_post_type( $post_id ) !== 'application' ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Skipping - not an application post (type: " . get_post_type( $post_id ) . ")" );
		}
		return;
	}

	// Check if this is a 'Movimiento de Personal' form
	$form_name = get_field( 'form_name', $post_id );
	if ( $form_name !== 'Movimiento de Personal' ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Skipping - not a Movimiento de Personal form (form_name: {$form_name})" );
		}
		return;
	}

	// Debug: Log that we're processing this form
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Processing Movimiento de Personal form for post ID: {$post_id}" );
	}

	// Get current user (form author)
	$author_id = get_post_field( 'post_author', $post_id );
	$author    = get_userdata( $author_id );
	
	if ( ! $author ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Could not get author for post ID: {$post_id}" );
		}
		return;
	}

	// Debug: Log author info
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Form author - ID: {$author_id}, Email: {$author->user_email}, Name: {$author->display_name}" );
	}

	// Get signature field values
	$firma_jefe_inmediato      = get_field( 'firma_de_jefe_inmediato', $post_id );
	$firma_autorizacion        = get_field( 'firma_de_autorizacion', $post_id );
	$firma_capital_humano      = get_field( 'firma_de_capital_humano', $post_id );
	$firma_coordinador_fiscal  = get_field( 'firma_de_coordinador_fiscal', $post_id );

	// Debug: Log current signature values
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Current signatures - Jefe: " . ( $firma_jefe_inmediato ? 'YES' : 'NO' ) . 
				   ", Autorizacion: " . ( $firma_autorizacion ? 'YES' : 'NO' ) . 
				   ", Capital Humano: " . ( $firma_capital_humano ? 'YES' : 'NO' ) . 
				   ", Coordinador Fiscal: " . ( $firma_coordinador_fiscal ? 'YES' : 'NO' ) );
	}

	// Get stored previous values from last save
	$prev_signatures = get_post_meta( $post_id, '_prev_signatures', true );
	if ( ! is_array( $prev_signatures ) ) {
		$prev_signatures = array(
			'firma_de_jefe_inmediato'     => '',
			'firma_de_autorizacion'       => '',
			'firma_de_capital_humano'     => '',
			'firma_de_coordinador_fiscal' => '',
		);
	}

	// Debug: Log previous signature values
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Previous signatures - Jefe: " . ( $prev_signatures['firma_de_jefe_inmediato'] ? 'YES' : 'NO' ) . 
				   ", Autorizacion: " . ( $prev_signatures['firma_de_autorizacion'] ? 'YES' : 'NO' ) . 
				   ", Capital Humano: " . ( $prev_signatures['firma_de_capital_humano'] ? 'YES' : 'NO' ) . 
				   ", Coordinador Fiscal: " . ( $prev_signatures['firma_de_coordinador_fiscal'] ? 'YES' : 'NO' ) );
	}

	// Check if this is a new post (creation)
	$is_new_post = get_post_meta( $post_id, '_acf_notifications_sent', true ) !== 'initial';

	// Debug: Log new post status
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Is new post? " . ( $is_new_post ? 'YES' : 'NO' ) );
	}

	// Determine what notifications to send based on what changed
	if ( $is_new_post ) {
		// 1) On creation - ALWAYS send creation notification
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sending CREATION notification (new post created)" );
		}
		id_basica_send_creation_notification( $post_id, $author );
		update_post_meta( $post_id, '_acf_notifications_sent', 'initial' );
		
	} elseif ( ! empty( $firma_jefe_inmediato ) && empty( $prev_signatures['firma_de_jefe_inmediato'] ) ) {
		// 2) When firma_de_jefe_inmediato is first added
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sending JEFE INMEDIATO notification (signature added)" );
		}
		id_basica_send_jefe_inmediato_notification( $post_id, $author );
		
	} elseif ( ! empty( $firma_autorizacion ) && empty( $prev_signatures['firma_de_autorizacion'] ) ) {
		// 3) When firma_de_autorizacion is first added
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sending AUTORIZACION notification (signature added)" );
		}
		id_basica_send_autorizacion_notification( $post_id, $author );
		
	} elseif ( ! empty( $firma_capital_humano ) && empty( $prev_signatures['firma_de_capital_humano'] ) ) {
		// 4) When firma_de_capital_humano is first added
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sending CAPITAL HUMANO notification (signature added)" );
		}
		id_basica_send_capital_humano_notification( $post_id, $author );
		
	} elseif ( ! empty( $firma_coordinador_fiscal ) && empty( $prev_signatures['firma_de_coordinador_fiscal'] ) ) {
		// 5) When firma_de_coordinador_fiscal is first added
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sending COORDINADOR FISCAL notification (signature added)" );
		}
		id_basica_send_coordinador_fiscal_notification( $post_id, $author );
	} else {
		// No notification needed
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: No notification needed - no signature changes detected" );
		}
	}

	// Store current signature values for next comparison
	update_post_meta( $post_id, '_prev_signatures', array(
		'firma_de_jefe_inmediato'     => $firma_jefe_inmediato,
		'firma_de_autorizacion'       => $firma_autorizacion,
		'firma_de_capital_humano'     => $firma_capital_humano,
		'firma_de_coordinador_fiscal' => $firma_coordinador_fiscal,
	) );

	// Debug: Log completion
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Notification handler completed for post ID: {$post_id}" );
	}
}
add_action( 'acf/save_post', 'id_basica_handle_movimiento_personal_notifications', 20 );

/**
 * Debug function to log all ACF form saves
 * 
 * This function logs every time ACF saves a post for debugging purposes
 *
 * @since 1.0.0
 * @param int $post_id The post ID that was saved
 */
function id_basica_debug_acf_save_post( $post_id ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$post_type = get_post_type( $post_id );
		$post_title = get_the_title( $post_id );
		$form_name = get_field( 'form_name', $post_id );
		
		error_log( "ID Basica DEBUG: ACF Save Post - ID: {$post_id}, Type: {$post_type}, Title: {$post_title}, Form: {$form_name}" );
		
		// Log all ACF field values for debugging
		if ( $post_type === 'application' && $form_name === 'Movimiento de Personal' ) {
			$fields = get_fields( $post_id );
			if ( $fields ) {
				foreach ( $fields as $field_name => $field_value ) {
					if ( strpos( $field_name, 'firma_' ) === 0 ) {
						$value_display = $field_value ? 'YES' : 'NO';
						error_log( "ID Basica DEBUG: Field {$field_name}: {$value_display}" );
					}
				}
			}
		}
	}
}
add_action( 'acf/save_post', 'id_basica_debug_acf_save_post', 1 ); // Run early to log all saves

/**
 * Send notification email on application creation
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_creation_notification( $post_id, $author ) {
	// Debug: Log function entry
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Creation notification - Post ID: {$post_id}, Author: {$author->user_email}" );
	}

	// Send notification to author
	$author_subject = 'Su solicitud de Movimiento de Personal ha sido creada - ' . get_the_title( $post_id );
	$author_message = id_basica_get_notification_email_template( $post_id, $author, 'creation', 'author' );
	id_basica_send_notification_email( array( $author->user_email ), $author_subject, $author_message );

	// Send notification to jefe inmediato
	$jefe_inmediato_id = get_field( 'jefe_inmediato_id', $post_id );
	if ( $jefe_inmediato_id ) {
		$jefe_inmediato = get_userdata( $jefe_inmediato_id );
		if ( $jefe_inmediato ) {
			$jefe_subject = 'Nueva solicitud de Movimiento de Personal requiere su firma - ' . get_the_title( $post_id );
			$jefe_message = id_basica_get_notification_email_template( $post_id, $author, 'creation', 'jefe_inmediato' );
			id_basica_send_notification_email( array( $jefe_inmediato->user_email ), $jefe_subject, $jefe_message );
			
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Sent creation notification to jefe inmediato: {$jefe_inmediato->user_email}" );
			}
		}
	}

	// Debug: Log completion
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Creation notification completed for author: {$author->user_email}" );
	}
}

/**
 * Send notification when jefe inmediato signs
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_jefe_inmediato_notification( $post_id, $author ) {
	// Debug: Log function entry
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Jefe inmediato notification - Post ID: {$post_id}, Author: {$author->user_email}" );
	}

	// Send notification to author
	$author_subject = 'Su solicitud de Movimiento de Personal ha sido firmada por el Jefe Inmediato - ' . get_the_title( $post_id );
	$author_message = id_basica_get_notification_email_template( $post_id, $author, 'jefe_inmediato', 'author' );
	id_basica_send_notification_email( array( $author->user_email ), $author_subject, $author_message );

	// Send notification to director de administracion
	$director_email = '';
	
	// Primary: Get from user ID field in the form
	$direccion_administracion_id = get_field( 'direccion_de_administracion_id', $post_id );
	if ( $direccion_administracion_id ) {
		$direccion_administracion = get_userdata( $direccion_administracion_id );
		if ( $direccion_administracion ) {
			$director_email = $direccion_administracion->user_email;
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Got director email from form user ID: {$director_email}" );
			}
		}
	}
	
	// Fallback: Get from options if form field is empty
	if ( empty( $director_email ) ) {
		$director_email = get_field( 'director_de_administracion_email', 'option' );
		if ( ! empty( $director_email ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Using fallback - got director email from options: {$director_email}" );
			}
		}
	}
	
	if ( ! empty( $director_email ) ) {
		$director_subject = 'Solicitud de Movimiento de Personal requiere su autorización - ' . get_the_title( $post_id );
		$director_message = id_basica_get_notification_email_template( $post_id, $author, 'jefe_inmediato', 'director_administracion' );
		id_basica_send_notification_email( array( $director_email ), $director_subject, $director_message );
		
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sent notification to director de administracion: {$director_email}" );
		}
	} else {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: WARNING - No director de administracion email found!" );
		}
	}

	// Debug: Log completion
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Jefe inmediato notification completed for author: {$author->user_email}" );
	}
}

/**
 * Send notification when director de administracion signs
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_autorizacion_notification( $post_id, $author ) {
	// Debug: Log function entry
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Autorizacion notification - Post ID: {$post_id}, Author: {$author->user_email}" );
	}

	// Send notification to author
	$author_subject = 'Su solicitud de Movimiento de Personal ha sido autorizada - ' . get_the_title( $post_id );
	$author_message = id_basica_get_notification_email_template( $post_id, $author, 'autorizacion', 'author' );
	id_basica_send_notification_email( array( $author->user_email ), $author_subject, $author_message );

	// Send notification to capital humano
	$capital_humano_email = '';
	
	// Primary: Get from user ID field in the form
	$capital_humano_id = get_field( 'capital_humano_id', $post_id );
	if ( $capital_humano_id ) {
		$capital_humano = get_userdata( $capital_humano_id );
		if ( $capital_humano ) {
			$capital_humano_email = $capital_humano->user_email;
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Got capital humano email from form user ID: {$capital_humano_email}" );
			}
		}
	}
	
	// Fallback: Get from options if form field is empty
	if ( empty( $capital_humano_email ) ) {
		$capital_humano_email = get_field( 'capital_humano_email', 'option' );
		if ( ! empty( $capital_humano_email ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Using fallback - got capital humano email from options: {$capital_humano_email}" );
			}
		}
	}
	
	if ( ! empty( $capital_humano_email ) ) {
		$capital_subject = 'Solicitud de Movimiento de Personal requiere su Vo. Bo. - ' . get_the_title( $post_id );
		$capital_message = id_basica_get_notification_email_template( $post_id, $author, 'autorizacion', 'capital_humano' );
		id_basica_send_notification_email( array( $capital_humano_email ), $capital_subject, $capital_message );
		
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sent notification to capital humano: {$capital_humano_email}" );
		}
	} else {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: WARNING - No capital humano email found!" );
		}
	}

	// Debug: Log completion
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Autorizacion notification completed for author: {$author->user_email}" );
	}
}

/**
 * Send notification when capital humano signs
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_capital_humano_notification( $post_id, $author ) {
	// Debug: Log function entry
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Capital humano notification - Post ID: {$post_id}, Author: {$author->user_email}" );
	}

	// Send notification to author
	$author_subject = 'Su solicitud de Movimiento de Personal tiene Vo. Bo. de Capital Humano - ' . get_the_title( $post_id );
	$author_message = id_basica_get_notification_email_template( $post_id, $author, 'capital_humano', 'author' );
	id_basica_send_notification_email( array( $author->user_email ), $author_subject, $author_message );

	// Send notification to coordinador fiscal
	$coordinador_fiscal_email = '';
	
	// Primary: Get from user ID field in the form
	$coordinador_fiscal_id = get_field( 'coordinador_fiscal_id', $post_id );
	if ( $coordinador_fiscal_id ) {
		$coordinador_fiscal = get_userdata( $coordinador_fiscal_id );
		if ( $coordinador_fiscal ) {
			$coordinador_fiscal_email = $coordinador_fiscal->user_email;
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Got coordinador fiscal email from form user ID: {$coordinador_fiscal_email}" );
			}
		}
	}
	
	// Fallback: Get from options if form field is empty
	if ( empty( $coordinador_fiscal_email ) ) {
		$coordinador_fiscal_email = get_field( 'coordinador_fiscal_email', 'option' );
		if ( ! empty( $coordinador_fiscal_email ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Using fallback - got coordinador fiscal email from options: {$coordinador_fiscal_email}" );
			}
		}
	}
	
	if ( ! empty( $coordinador_fiscal_email ) ) {
		$coordinador_subject = 'Solicitud de Movimiento de Personal requiere su firma final - ' . get_the_title( $post_id );
		$coordinador_message = id_basica_get_notification_email_template( $post_id, $author, 'capital_humano', 'coordinador_fiscal' );
		id_basica_send_notification_email( array( $coordinador_fiscal_email ), $coordinador_subject, $coordinador_message );
		
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sent notification to coordinador fiscal: {$coordinador_fiscal_email}" );
		}
	} else {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: WARNING - No coordinador fiscal email found!" );
		}
	}

	// Debug: Log completion
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Capital humano notification completed for author: {$author->user_email}" );
	}
}

/**
 * Send notification when coordinador fiscal signs (final step)
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_coordinador_fiscal_notification( $post_id, $author ) {
	// Debug: Log function entry
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Coordinador fiscal notification - Post ID: {$post_id}, Author: {$author->user_email}" );
	}

	// Send notification to author only (process is complete)
	$author_subject = 'Su solicitud de Movimiento de Personal está COMPLETADA - ' . get_the_title( $post_id );
	$author_message = id_basica_get_notification_email_template( $post_id, $author, 'coordinador_fiscal', 'author' );
	id_basica_send_notification_email( array( $author->user_email ), $author_subject, $author_message );

	// Debug: Log completion
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Coordinador fiscal notification completed for author: {$author->user_email}" );
	}
}

/**
 * Get template variables for email notifications
 * 
 * Allows filtering of template variables before they are used in email templates
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 * @param string  $stage   The workflow stage
 * @return array Template variables
 */
function id_basica_get_email_template_variables( $post_id, $author, $stage, $recipient_type = 'author' ) {
	$variables = array(
		'post_id'         => $post_id,
		'author'          => $author,
		'stage'           => $stage,
		'recipient_type'  => $recipient_type,
		'application_url' => get_permalink( $post_id ),
		'employee_name'   => get_field( 'nombre_de_empleado', $post_id ) ?: 'No especificado',
		'date_created'    => get_the_date( 'd/m/Y', $post_id ),
		'site_name'       => get_bloginfo( 'name' ),
		'admin_email'     => get_option( 'admin_email' ),
	);

	// Add application-specific details
	$variables['puesto_actual'] = get_field( 'puesto_actual', $post_id );
	$variables['puesto_nuevo'] = get_field( 'puesto_nuevo', $post_id );
	$variables['departamento_actual'] = get_field( 'departamento_actual', $post_id );
	$variables['departamento_nuevo'] = get_field( 'departamento_nuevo', $post_id );
	$variables['tipo_movimiento'] = get_field( 'tipo_de_movimiento', $post_id );

	/**
	 * Filter email template variables
	 *
	 * @since 1.0.0
	 * @param array   $variables     Template variables
	 * @param int     $post_id       The post ID
	 * @param WP_User $author        The form author
	 * @param string  $stage         The workflow stage
	 * @param string  $recipient_type The recipient type (author, jefe_inmediato, etc.)
	 */
	return apply_filters( 'id_basica_email_template_variables', $variables, $post_id, $author, $stage, $recipient_type );
}

/**
 * Generate email template for notifications
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 * @param string  $stage   The workflow stage
 * @return string The email message
 */
function id_basica_get_notification_email_template( $post_id, $author, $stage, $recipient_type = 'author' ) {
	// Debug: Log template generation
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Generating email template - Post ID: {$post_id}, Stage: {$stage}, Recipient: {$recipient_type}" );
	}

	// Get all template variables
	$template_vars = id_basica_get_email_template_variables( $post_id, $author, $stage, $recipient_type );
	
	// Extract variables for use in templates
	extract( $template_vars );

	// Map stages to template files
	$template_map = array(
		'creation'           => 'creation.php',
		'jefe_inmediato'     => 'jefe-inmediato.php',
		'autorizacion'       => 'autorizacion.php',
		'capital_humano'     => 'capital-humano.php',
		'coordinador_fiscal' => 'coordinador-fiscal.php',
	);

	// Get the template file for this stage
	$template_file = isset( $template_map[ $stage ] ) ? $template_map[ $stage ] : $template_map['creation'];
	$template_path = get_template_directory() . '/templates/emails/' . $template_file;

	// Debug: Log template file path
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Template file path: {$template_path}" );
		error_log( "ID Basica DEBUG: Template file exists: " . ( file_exists( $template_path ) ? 'YES' : 'NO' ) );
	}

	// Check if template file exists
	if ( ! file_exists( $template_path ) ) {
		// Fallback to a simple template if file doesn't exist
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Using fallback template for stage: {$stage}" );
		}
		return id_basica_get_fallback_email_template( $post_id, $author, $stage, $recipient_type );
	}

	// Start output buffering to capture the template output
	ob_start();
	
	// Include the template file
	include $template_path;
	
	// Get the template output
	$message = ob_get_clean();

	// Debug: Log template output length
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Template output length: " . strlen( $message ) . " characters" );
	}

	return $message;
}

/**
 * Fallback email template in case template files are missing
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 * @param string  $stage   The workflow stage
 * @return string The email message
 */
function id_basica_get_fallback_email_template( $post_id, $author, $stage, $recipient_type = 'author' ) {
	$application_url = get_permalink( $post_id );
	$employee_name   = get_field( 'nombre_de_empleado', $post_id ) ?: 'No especificado';
	$date_created    = get_the_date( 'd/m/Y', $post_id );

	$stage_messages = array(
		'creation'           => 'Nueva solicitud de movimiento de personal creada',
		'jefe_inmediato'     => 'Solicitud firmada por Jefe Inmediato',
		'autorizacion'       => 'Solicitud autorizada por Director de Administración',
		'capital_humano'     => 'Solicitud con Vo. Bo. de Capital Humano',
		'coordinador_fiscal' => 'Solicitud completada por Coordinador Fiscal',
	);

	$message_title = $stage_messages[ $stage ] ?? $stage_messages['creation'];
	
	// Adjust message based on recipient type
	if ( $recipient_type !== 'author' ) {
		$message_title = "Acción requerida: " . $message_title;
	}

	$message = "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$message_title}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
            line-height: 1.5;
            color: #202021;
            margin: 0;
            padding: 0;
            background-color: #fcfcfc;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #d22 0%, #cd1d1d 100%);
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }
        .email-content { padding: 24px; }
        .header {
            color: #202021;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }
        .details-box {
            background-color: #fcfcfc;
            padding: 20px;
            border-left: 4px solid #d22;
            margin: 24px 0;
            border-radius: 4px;
            border: 1px solid #ececed;
        }
        .details-box h3 {
            margin-top: 0;
            color: #202021;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .details-box ul {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }
        .details-box li {
            margin-bottom: 8px;
            padding: 8px 0;
            border-bottom: 1px solid #ececed;
            color: #434445;
        }
        .details-box li:last-child { border-bottom: none; }
        .cta-button {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button a {
            background: linear-gradient(135deg, #d22 0%, #cd1d1d 100%);
            color: #ffffff;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(221, 34, 34, 0.2);
        }
        .footer {
            background-color: #fcfcfc;
            border-top: 1px solid #ececed;
            padding: 20px 24px;
            font-size: 14px;
            color: #6b6d6f;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='email-header'>
            <h2 style='margin: 0; color: #ffffff; font-size: 20px;'>ID Básica</h2>
        </div>
        
        <div class='email-content'>
            <h2 class='header'>{$message_title}</h2>
            
            <p>Solicitud de movimiento de personal - {$message_title}</p>
            
            <div class='details-box'>
                <h3>Detalles:</h3>
                <ul>
                    <li><strong>Empleado:</strong> " . esc_html( $employee_name ) . "</li>
                    <li><strong>Fecha:</strong> {$date_created}</li>
                    <li><strong>Solicitante:</strong> " . esc_html( $author->display_name ) . "</li>
                    <li><strong>Tipo de destinatario:</strong> " . esc_html( $recipient_type ) . "</li>
                </ul>
            </div>
            
            <div class='cta-button'>
                <a href='{$application_url}'>Ver Solicitud</a>
            </div>
        </div>
        
        <div class='footer'>
            <p style='margin: 0;'>
                <strong>ID Básica</strong> - Sistema de Gestión de Solicitudes<br>
                Este es un mensaje automático del sistema.<br>
                Por favor, no responda a este correo electrónico.
            </p>
        </div>
    </div>
</body>
</html>";

	return $message;
}

/**
 * Send notification email using WordPress wp_mail
 *
 * @since 1.0.0
 * @param array  $recipients Array of email addresses
 * @param string $subject    Email subject
 * @param string $message    Email message (HTML)
 */
function id_basica_send_notification_email( $recipients, $subject, $message ) {
	// Debug: Log email sending attempt
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Attempting to send email - Subject: {$subject}" );
		error_log( "ID Basica DEBUG: Raw recipients: " . print_r( $recipients, true ) );
	}

	// Remove duplicates and empty emails
	$recipients = array_filter( array_unique( $recipients ) );
	
	if ( empty( $recipients ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: No valid recipients - email not sent" );
		}
		return;
	}

	// Debug: Log final recipients
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Final recipients: " . implode( ', ', $recipients ) );
	}

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
	);

	// Debug: Log email headers
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Email headers: " . print_r( $headers, true ) );
	}

	// Send email to each recipient separately for privacy
	$sent_count = 0;
	$failed_count = 0;
	
	foreach ( $recipients as $email ) {
		// Debug: Log individual email attempt
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Sending email to: {$email}" );
		}
		
		$result = wp_mail( $email, $subject, $message, $headers );
		
		if ( $result ) {
			$sent_count++;
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Email sent successfully to: {$email}" );
			}
		} else {
			$failed_count++;
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "ID Basica DEBUG: Failed to send email to: {$email}" );
			}
		}
	}

	// Log final results
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Email sending completed - Sent: {$sent_count}, Failed: {$failed_count}" );
		error_log( "ID Basica DEBUG: Email notification sent to: " . implode( ', ', $recipients ) . " | Subject: {$subject}" );
	}
}

/**
 * Validate email notification settings
 *
 * @since 1.0.0
 * @return array Array of missing email configurations
 */
function id_basica_validate_email_settings() {
	// Debug: Log validation check
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Validating email settings..." );
	}

	$missing = array();

	// Check director de administracion email
	$director_email = get_field( 'director_de_administracion_email', 'option' );
	if ( ! $director_email ) {
		$missing[] = 'Director de Administración Email';
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Missing director_de_administracion_email in options" );
		}
	} else {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Found director_de_administracion_email: {$director_email}" );
		}
	}

	// Check capital humano email
	$capital_humano_email = get_field( 'capital_humano_email', 'option' );
	if ( ! $capital_humano_email ) {
		$missing[] = 'Capital Humano Email';
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Missing capital_humano_email in options" );
		}
	} else {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Found capital_humano_email: {$capital_humano_email}" );
		}
	}

	// Check coordinador fiscal email
	$coordinador_fiscal_email = get_field( 'coordinador_fiscal_email', 'option' );
	if ( ! $coordinador_fiscal_email ) {
		$missing[] = 'Coordinador Fiscal Email';
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Missing coordinador_fiscal_email in options" );
		}
	} else {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "ID Basica DEBUG: Found coordinador_fiscal_email: {$coordinador_fiscal_email}" );
		}
	}

	// Debug: Log final result
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( "ID Basica DEBUG: Email validation complete. Missing: " . ( empty( $missing ) ? 'None' : implode( ', ', $missing ) ) );
	}

	return $missing;
}

/**
 * Add admin notice if email settings are not configured
 *
 * @since 1.0.0
 */
function id_basica_check_email_settings_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$missing = id_basica_validate_email_settings();

	if ( ! empty( $missing ) ) {
		$settings_url = admin_url( 'admin.php?page=intranet-email-notifications' );
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<strong>ID Basica:</strong> Faltan configurar direcciones de correo para las notificaciones de Movimiento de Personal.
				<br>
				Campos faltantes: <?php echo esc_html( implode( ', ', $missing ) ); ?>
				<br>
				<a href="<?php echo esc_url( $settings_url ); ?>">Configurar ahora</a>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'id_basica_check_email_settings_notice' );

/**
 * Add notification status column to applications admin list
 *
 * @since 1.0.0
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function id_basica_add_notification_status_column( $columns ) {
	// Only add to application post type
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'application' ) {
		$columns['notification_status'] = __( 'Estado de Notificaciones', 'id-basica' );
	}
	return $columns;
}
add_filter( 'manage_posts_columns', 'id_basica_add_notification_status_column' );

/**
 * Display notification status in applications admin list
 *
 * @since 1.0.0
 * @param string $column  The column name
 * @param int    $post_id The post ID
 */
function id_basica_show_notification_status_column( $column, $post_id ) {
	if ( $column === 'notification_status' ) {
		$form_name = get_field( 'form_name', $post_id );
		
		if ( $form_name === 'Movimiento de Personal' ) {
			$signatures = array(
				'Jefe Inmediato' => get_field( 'firma_de_jefe_inmediato', $post_id ),
				'Autorización'   => get_field( 'firma_de_autorizacion', $post_id ),
				'Capital Humano' => get_field( 'firma_de_capital_humano', $post_id ),
				'Coordinador Fiscal' => get_field( 'firma_de_coordinador_fiscal', $post_id ),
			);

			$status_icons = array();
			foreach ( $signatures as $role => $signature ) {
				$icon = ! empty( $signature ) ? '✅' : '⏳';
				$status_icons[] = "{$icon} {$role}";
			}

			echo '<div style="font-size: 11px; line-height: 1.2;">' . implode( '<br>', $status_icons ) . '</div>';
		} else {
			echo '—';
		}
	}
}
add_action( 'manage_posts_custom_column', 'id_basica_show_notification_status_column', 10, 2 );
