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
	// Only process application posts
	if ( get_post_type( $post_id ) !== 'application' ) {
		return;
	}

	// Check if this is a 'Movimiento de Personal' form
	$form_name = get_field( 'form_name', $post_id );
	if ( $form_name !== 'Movimiento de Personal' ) {
		return;
	}

	// Get current user (form author)
	$author_id = get_post_field( 'post_author', $post_id );
	$author    = get_userdata( $author_id );
	
	if ( ! $author ) {
		return;
	}

	// Get signature field values
	$firma_jefe_inmediato      = get_field( 'firma_de_jefe_inmediato', $post_id );
	$firma_autorizacion        = get_field( 'firma_de_autorizacion', $post_id );
	$firma_capital_humano      = get_field( 'firma_de_capital_humano', $post_id );
	$firma_coordinador_fiscal  = get_field( 'firma_de_coordinador_fiscal', $post_id );

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

	// Check if this is a new post (creation)
	$is_new_post = get_post_meta( $post_id, '_acf_notifications_sent', true ) !== 'initial';

	// Determine what notifications to send based on what changed
	if ( $is_new_post && ! empty( $firma_jefe_inmediato ) ) {
		// 1) On creation with jefe inmediato signature
		id_basica_send_creation_notification( $post_id, $author );
		update_post_meta( $post_id, '_acf_notifications_sent', 'initial' );
		
	} elseif ( ! empty( $firma_jefe_inmediato ) && empty( $prev_signatures['firma_de_jefe_inmediato'] ) ) {
		// 2) When firma_de_jefe_inmediato is first added
		id_basica_send_jefe_inmediato_notification( $post_id, $author );
		
	} elseif ( ! empty( $firma_autorizacion ) && empty( $prev_signatures['firma_de_autorizacion'] ) ) {
		// 3) When firma_de_autorizacion is first added
		id_basica_send_autorizacion_notification( $post_id, $author );
		
	} elseif ( ! empty( $firma_capital_humano ) && empty( $prev_signatures['firma_de_capital_humano'] ) ) {
		// 4) When firma_de_capital_humano is first added
		id_basica_send_capital_humano_notification( $post_id, $author );
		
	} elseif ( ! empty( $firma_coordinador_fiscal ) && empty( $prev_signatures['firma_de_coordinador_fiscal'] ) ) {
		// 5) When firma_de_coordinador_fiscal is first added
		id_basica_send_coordinador_fiscal_notification( $post_id, $author );
	}

	// Store current signature values for next comparison
	update_post_meta( $post_id, '_prev_signatures', array(
		'firma_de_jefe_inmediato'     => $firma_jefe_inmediato,
		'firma_de_autorizacion'       => $firma_autorizacion,
		'firma_de_capital_humano'     => $firma_capital_humano,
		'firma_de_coordinador_fiscal' => $firma_coordinador_fiscal,
	) );
}
add_action( 'acf/save_post', 'id_basica_handle_movimiento_personal_notifications', 20 );

/**
 * Send notification email on application creation
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_creation_notification( $post_id, $author ) {
	$recipients = array( $author->user_email );
	
	// Get jefe inmediato email from form field
	$jefe_inmediato_id = get_field( 'jefe_inmediato_id', $post_id );
	if ( $jefe_inmediato_id ) {
		$jefe_inmediato = get_userdata( $jefe_inmediato_id );
		if ( $jefe_inmediato ) {
			$recipients[] = $jefe_inmediato->user_email;
		}
	}

	$subject = 'Nueva solicitud de Movimiento de Personal - ' . get_the_title( $post_id );
	$message = id_basica_get_notification_email_template( $post_id, $author, 'creation' );
	
	id_basica_send_notification_email( $recipients, $subject, $message );
}

/**
 * Send notification when jefe inmediato signs
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_jefe_inmediato_notification( $post_id, $author ) {
	$recipients = array( $author->user_email );
	
	// Get director de administracion email from form field
	$direccion_administracion_id = get_field( 'direccion_de_administracion_id', $post_id );
	if ( $direccion_administracion_id ) {
		$direccion_administracion = get_userdata( $direccion_administracion_id );
		if ( $direccion_administracion ) {
			$recipients[] = $direccion_administracion->user_email;
		}
	}

	$subject = 'Solicitud de Movimiento de Personal firmada por Jefe Inmediato - ' . get_the_title( $post_id );
	$message = id_basica_get_notification_email_template( $post_id, $author, 'jefe_inmediato' );
	
	id_basica_send_notification_email( $recipients, $subject, $message );
}

/**
 * Send notification when director de administracion signs
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_autorizacion_notification( $post_id, $author ) {
	$recipients = array( $author->user_email );
	
	// Get capital humano email from form field
	$capital_humano_id = get_field( 'capital_humano_id', $post_id );
	if ( $capital_humano_id ) {
		$capital_humano = get_userdata( $capital_humano_id );
		if ( $capital_humano ) {
			$recipients[] = $capital_humano->user_email;
		}
	}

	$subject = 'Solicitud de Movimiento de Personal autorizada - ' . get_the_title( $post_id );
	$message = id_basica_get_notification_email_template( $post_id, $author, 'autorizacion' );
	
	id_basica_send_notification_email( $recipients, $subject, $message );
}

/**
 * Send notification when capital humano signs
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_capital_humano_notification( $post_id, $author ) {
	$recipients = array( $author->user_email );
	
	// Get coordinador fiscal email from form field
	$coordinador_fiscal_id = get_field( 'coordinador_fiscal_id', $post_id );
	if ( $coordinador_fiscal_id ) {
		$coordinador_fiscal = get_userdata( $coordinador_fiscal_id );
		if ( $coordinador_fiscal ) {
			$recipients[] = $coordinador_fiscal->user_email;
		}
	}

	$subject = 'Solicitud de Movimiento de Personal con Vo. Bo. de Capital Humano - ' . get_the_title( $post_id );
	$message = id_basica_get_notification_email_template( $post_id, $author, 'capital_humano' );
	
	id_basica_send_notification_email( $recipients, $subject, $message );
}

/**
 * Send notification when coordinador fiscal signs (final step)
 *
 * @since 1.0.0
 * @param int     $post_id The post ID
 * @param WP_User $author  The form author
 */
function id_basica_send_coordinador_fiscal_notification( $post_id, $author ) {
	$recipients = array( $author->user_email );

	$subject = 'Solicitud de Movimiento de Personal COMPLETADA - ' . get_the_title( $post_id );
	$message = id_basica_get_notification_email_template( $post_id, $author, 'coordinador_fiscal' );
	
	id_basica_send_notification_email( $recipients, $subject, $message );
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
function id_basica_get_email_template_variables( $post_id, $author, $stage ) {
	$variables = array(
		'post_id'         => $post_id,
		'author'          => $author,
		'stage'           => $stage,
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
	 * @param array   $variables Template variables
	 * @param int     $post_id   The post ID
	 * @param WP_User $author    The form author
	 * @param string  $stage     The workflow stage
	 */
	return apply_filters( 'id_basica_email_template_variables', $variables, $post_id, $author, $stage );
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
function id_basica_get_notification_email_template( $post_id, $author, $stage ) {
	// Get all template variables
	$template_vars = id_basica_get_email_template_variables( $post_id, $author, $stage );
	
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

	// Check if template file exists
	if ( ! file_exists( $template_path ) ) {
		// Fallback to a simple template if file doesn't exist
		return id_basica_get_fallback_email_template( $post_id, $author, $stage );
	}

	// Start output buffering to capture the template output
	ob_start();
	
	// Include the template file
	include $template_path;
	
	// Get the template output
	$message = ob_get_clean();

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
function id_basica_get_fallback_email_template( $post_id, $author, $stage ) {
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

	$message = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>{$message_title}</title>
</head>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
        <h2 style='color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;'>
            {$message_title}
        </h2>
        
        <p>Solicitud de movimiento de personal - {$message_title}</p>
        
        <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;'>
            <h3 style='margin-top: 0; color: #2c3e50;'>Detalles:</h3>
            <ul style='margin: 0; padding-left: 20px;'>
                <li><strong>Empleado:</strong> " . esc_html( $employee_name ) . "</li>
                <li><strong>Fecha:</strong> {$date_created}</li>
                <li><strong>Solicitante:</strong> " . esc_html( $author->display_name ) . "</li>
            </ul>
        </div>
        
        <p style='text-align: center; margin: 30px 0;'>
            <a href='{$application_url}' 
               style='background-color: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                Ver Solicitud
            </a>
        </p>
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
	// Remove duplicates and empty emails
	$recipients = array_filter( array_unique( $recipients ) );
	
	if ( empty( $recipients ) ) {
		return;
	}

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
	);

	// Send email to each recipient separately for privacy
	foreach ( $recipients as $email ) {
		wp_mail( $email, $subject, $message, $headers );
	}

	// Log the notification (optional - for debugging)
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'ID Basica: Email notification sent to: ' . implode( ', ', $recipients ) . ' | Subject: ' . $subject );
	}
}

/**
 * Validate email notification settings
 *
 * @since 1.0.0
 * @return array Array of missing email configurations
 */
function id_basica_validate_email_settings() {
	$missing = array();

	if ( ! get_field( 'director_de_administracion_email', 'option' ) ) {
		$missing[] = 'Director de Administración Email';
	}

	if ( ! get_field( 'capital_humano_email', 'option' ) ) {
		$missing[] = 'Capital Humano Email';
	}

	if ( ! get_field( 'coordinador_fiscal_email', 'option' ) ) {
		$missing[] = 'Coordinador Fiscal Email';
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
