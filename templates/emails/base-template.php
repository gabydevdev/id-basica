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
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
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
        .logo-container {
            margin-bottom: 16px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        .email-content {
            padding: 24px;
        }
        .header {
            color: #202021;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            line-height: 1.25;
        }
        .content {
            margin-bottom: 20px;
            color: #434445;
            font-size: 16px;
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
        .details-box li:last-child {
            border-bottom: none;
        }
        .details-box li strong {
            color: #202021;
            font-weight: 600;
        }
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
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(221, 34, 34, 0.2);
        }
        .cta-button a:hover {
            background: linear-gradient(135deg, #cd1d1d 0%, #b81919 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(221, 34, 34, 0.3);
        }
        .footer {
            background-color: #fcfcfc;
            border-top: 1px solid #ececed;
            padding: 20px 24px;
            font-size: 14px;
            color: #6b6d6f;
            text-align: center;
        }
        .stage-indicator {
            background: linear-gradient(135deg, #e33b3b 0%, #d22 100%);
            background: linear-gradient(135deg, #e33b3b 0%, #d22 100%);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        /* Mobile responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0 16px;
                border-radius: 4px;
            }
            .email-header, .email-content, .footer {
                padding: 16px;
            }
            .header {
                font-size: 20px;
            }
            .cta-button a {
                padding: 14px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <?php
                $logo_path = get_template_directory() . '/images/id-basica-logo.svg';
                $logo_url = get_template_directory_uri() . '/images/id-basica-logo.svg';
                
                // If SVG exists, display it inline for better email compatibility
                if ( file_exists( $logo_path ) ) {
                    $logo_svg = file_get_contents( $logo_path );
                    // Remove the xmlns and viewBox for inline use and add white fill
                    $logo_svg = str_replace( 'fill="currentColor"', 'fill="#ffffff"', $logo_svg );
                    $logo_svg = str_replace( '<svg ', '<svg class="logo" ', $logo_svg );
                    echo $logo_svg;
                } else {
                    // Fallback to image tag if SVG not found
                    echo '<img src="' . esc_url( $logo_url ) . '" alt="ID Básica" class="logo" />';
                }
                ?>
            </div>
        </div>
        
        <div class="email-content">
            <?php if ( ! empty( $stage_indicator ) ) : ?>
            <div class="stage-indicator">
                <?php echo esc_html( $stage_indicator ); ?>
            </div>
            <?php endif; ?>
            
            <h2 class="header"><?php echo esc_html( $title ); ?></h2>
            
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
        </div>
        
        <div class="footer">
            <p style="margin: 0;">
                <strong>ID Básica</strong> - Sistema de Gestión de Solicitudes<br>
                Este es un mensaje automático del sistema.<br>
                Por favor, no responda a este correo electrónico.
            </p>
        </div>
    </div>
</body>
</html>
