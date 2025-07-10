<?php
/**
 * Debug Helper for ID Basica Email System
 * 
 * This file provides debugging utilities for the email notification system.
 * Access this file directly to view recent logs and debug information.
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Only allow access if user is logged in and has admin privileges
if ( ! defined( 'ABSPATH' ) ) {
	// If not in WordPress context, try to load it
	$wp_load_path = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';
	if ( file_exists( $wp_load_path ) ) {
		require_once $wp_load_path;
	}
}

// Security check
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'You do not have sufficient permissions to access this page.' );
}

// Check if debug mode is enabled
if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
	echo '<div style="color: red; font-weight: bold; margin: 20px;">WP_DEBUG is not enabled. Please enable it in wp-config.php to see debug logs.</div>';
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>ID Basica Debug Helper</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 20px; }
		.debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
		.debug-section h3 { margin-top: 0; color: #333; }
		.log-entry { margin: 5px 0; padding: 8px; background: #f9f9f9; border-radius: 3px; font-family: monospace; }
		.log-entry.error { background: #ffebee; }
		.log-entry.success { background: #e8f5e8; }
		.button { background: #0073aa; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px; display: inline-block; margin: 5px; }
		.button:hover { background: #005a87; }
		.info-box { background: #e7f3ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
		.test-form { background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 20px 0; }
	</style>
</head>
<body>
	<h1>ID Basica Email System Debug Helper</h1>
	
	<div class="info-box">
		<strong>Current Status:</strong>
		<ul>
			<li>WP_DEBUG: <?php echo defined( 'WP_DEBUG' ) && WP_DEBUG ? '✅ Enabled' : '❌ Disabled'; ?></li>
			<li>WP_DEBUG_LOG: <?php echo defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ? '✅ Enabled' : '❌ Disabled'; ?></li>
			<li>Error Log Path: <?php echo ini_get( 'error_log' ) ?: 'Default PHP error log'; ?></li>
		</ul>
	</div>

	<div class="debug-section">
		<h3>📧 Recent Email Debug Logs</h3>
		<p>Recent logs related to email notifications:</p>
		
		<?php
		// Try to read the WordPress debug log
		$log_file = WP_CONTENT_DIR . '/debug.log';
		if ( file_exists( $log_file ) && is_readable( $log_file ) ) {
			$log_content = file_get_contents( $log_file );
			$log_lines = explode( "\n", $log_content );
			
			// Filter for ID Basica related logs
			$id_basica_logs = array_filter( $log_lines, function( $line ) {
				return strpos( $line, 'ID Basica' ) !== false;
			} );
			
			// Get the last 50 entries
			$recent_logs = array_slice( array_reverse( $id_basica_logs ), 0, 50 );
			
			if ( empty( $recent_logs ) ) {
				echo '<div class="log-entry">No ID Basica debug logs found. Try submitting a form to generate logs.</div>';
			} else {
				foreach ( $recent_logs as $log_line ) {
					$class = '';
					if ( strpos( $log_line, 'ERROR' ) !== false || strpos( $log_line, 'Failed' ) !== false ) {
						$class = 'error';
					} elseif ( strpos( $log_line, 'sent successfully' ) !== false ) {
						$class = 'success';
					}
					
					echo '<div class="log-entry ' . $class . '">' . esc_html( $log_line ) . '</div>';
				}
			}
		} else {
			echo '<div class="log-entry error">Debug log file not found or not readable at: ' . esc_html( $log_file ) . '</div>';
		}
		?>
		
		<a href="?clear_logs=1" class="button">Clear Debug Logs</a>
		<a href="?" class="button">Refresh</a>
	</div>

	<div class="debug-section">
		<h3>🔧 Debug Actions</h3>
		
		<?php
		// Handle clear logs action
		if ( isset( $_GET['clear_logs'] ) && current_user_can( 'manage_options' ) ) {
			if ( file_exists( $log_file ) && is_writable( $log_file ) ) {
				file_put_contents( $log_file, '' );
				echo '<div class="log-entry success">Debug logs cleared successfully!</div>';
			} else {
				echo '<div class="log-entry error">Could not clear debug logs. File not writable.</div>';
			}
		}
		?>
		
		<form method="post" class="test-form">
			<h4>Test Email Configuration</h4>
			<p>Check if email settings are properly configured:</p>
			
			<?php
			if ( isset( $_POST['test_email_config'] ) ) {
				echo '<h4>Email Configuration Test Results:</h4>';
				
				// Check ACF fields
				$missing_config = array();
				$acf_fields = array(
					'director_de_administracion_email' => 'Director de Administración Email',
					'capital_humano_email' => 'Capital Humano Email',
					'coordinador_fiscal_email' => 'Coordinador Fiscal Email'
				);
				
				foreach ( $acf_fields as $field_name => $field_label ) {
					$value = get_field( $field_name, 'option' );
					if ( empty( $value ) ) {
						$missing_config[] = $field_label;
						echo '<div class="log-entry error">❌ Missing: ' . esc_html( $field_label ) . '</div>';
					} else {
						echo '<div class="log-entry success">✅ Configured: ' . esc_html( $field_label ) . ' = ' . esc_html( $value ) . '</div>';
					}
				}
				
				// Check WordPress mail settings
				$admin_email = get_option( 'admin_email' );
				$site_name = get_bloginfo( 'name' );
				
				echo '<div class="log-entry">📧 WordPress Admin Email: ' . esc_html( $admin_email ) . '</div>';
				echo '<div class="log-entry">🏢 Site Name: ' . esc_html( $site_name ) . '</div>';
				
				if ( empty( $missing_config ) ) {
					echo '<div class="log-entry success">✅ All email configuration looks good!</div>';
				} else {
					echo '<div class="log-entry error">❌ Missing configurations: ' . implode( ', ', $missing_config ) . '</div>';
				}
			}
			?>
			
			<input type="submit" name="test_email_config" value="Test Email Configuration" class="button">
		</form>
	</div>

	<div class="debug-section">
		<h3>📝 Usage Instructions</h3>
		<ol>
			<li>Make sure <code>WP_DEBUG</code> and <code>WP_DEBUG_LOG</code> are enabled in your wp-config.php</li>
			<li>Submit a form with signatures to trigger email notifications</li>
			<li>Refresh this page to see the debug logs</li>
			<li>Look for entries with "ID Basica DEBUG" to track the email flow</li>
		</ol>
		
		<h4>What to Look For:</h4>
		<ul>
			<li><strong>Form saves:</strong> "ACF Save Post" entries show when forms are saved</li>
			<li><strong>Notification logic:</strong> Messages showing which notifications are triggered</li>
			<li><strong>Email sending:</strong> Details about recipients and email sending results</li>
			<li><strong>Template loading:</strong> Information about email template generation</li>
		</ul>
	</div>
</body>
</html>
