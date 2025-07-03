<?php
/**
 * Simple test email template
 * 
 * Use this to test email template functionality without complex styling
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title = 'Test Email - ' . ucfirst( $stage );
$body = 'This is a test email for the ' . $stage . ' stage.';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $title ); ?></title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2><?php echo esc_html( $title ); ?></h2>
    <p><?php echo esc_html( $body ); ?></p>
    
    <div style="background: #f0f0f0; padding: 15px; margin: 20px 0;">
        <h3>Details:</h3>
        <ul>
            <li><strong>Employee:</strong> <?php echo esc_html( $employee_name ); ?></li>
            <li><strong>Date:</strong> <?php echo esc_html( $date_created ); ?></li>
            <li><strong>Author:</strong> <?php echo esc_html( $author->display_name ); ?></li>
            <li><strong>Stage:</strong> <?php echo esc_html( $stage ); ?></li>
        </ul>
    </div>
    
    <p><a href="<?php echo esc_url( $application_url ); ?>">View Application</a></p>
</body>
</html>
