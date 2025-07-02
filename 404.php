<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * Displays a user-friendly 404 error page with dashboard styling
 * and navigation back to the main dashboard.
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="error-page">
	<h1 class="error-page__code">404</h1>
	<h2 class="error-page__title"><?php esc_html_e( 'Page Not Found', ID_BASICA_DOMAIN ); ?></h2>
	<p class="error-page__message">
		<?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', ID_BASICA_DOMAIN ); ?>
	</p>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
		<i class="fas fa-home"></i> <?php esc_html_e( 'Back to Dashboard', ID_BASICA_DOMAIN ); ?>
	</a>
</div>

<?php
get_footer();
