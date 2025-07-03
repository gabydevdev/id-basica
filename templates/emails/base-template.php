<?php
/**
 * Base email template for Movimiento de Personal notifications
 *
 * Available variables:
 * - $title: Email title
 * - $body: Email body content
 * - $employee_name: Name of the employee
 * - $date_created: Date the application was created
 * - $author: WP_User object of the form author
 * - $application_url: URL to view the application
 * - $stage: Current workflow stage
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html( $title ); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .details-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 16px;
        }
        .details-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .details-box li {
            margin-bottom: 8px;
        }
        .cta-button {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button a {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .cta-button a:hover {
            background-color: #2980b9;
        }
        .footer {
            border-top: 1px solid #ddd;
            margin-top: 30px;
            padding-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .stage-indicator {
            background-color: #e8f4fd;
            border: 1px solid #3498db;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2 class="header"><?php echo esc_html( $title ); ?></h2>
        
        <?php if ( ! empty( $stage_indicator ) ) : ?>
        <div class="stage-indicator">
            <?php echo esc_html( $stage_indicator ); ?>
        </div>
        <?php endif; ?>
        
        <div class="content">
            <p><?php echo esc_html( $body ); ?></p>
        </div>
        
        <div class="details-box">
            <h3>Detalles de la Solicitud:</h3>
            <ul>
                <li><strong>Empleado:</strong> <?php echo esc_html( $employee_name ); ?></li>
                <li><strong>Fecha de solicitud:</strong> <?php echo esc_html( $date_created ); ?></li>
                <li><strong>Solicitante:</strong> <?php echo esc_html( $author->display_name ); ?> (<?php echo esc_html( $author->user_email ); ?>)</li>
                <?php if ( ! empty( $additional_details ) && is_array( $additional_details ) ) : ?>
                    <?php foreach ( $additional_details as $detail ) : ?>
                        <li><?php echo wp_kses_post( $detail ); ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="cta-button">
            <a href="<?php echo esc_url( $application_url ); ?>">
                Ver Solicitud Completa
            </a>
        </div>
        
        <div class="footer">
            Este es un mensaje automático del sistema ID Básica.<br>
            Por favor, no responda a este correo electrónico.
        </div>
    </div>
</body>
</html>
