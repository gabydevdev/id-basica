<?php
/**
 * Template Name: Single Application
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$fields = id_basica_get_acf_fields();
?>

<!-- Dashboard Content -->
<div class="content mt-6">

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
	<div class="container">
		<div class="content__main">
			<?php // the_content(); ?>
			<div class="invoice">
				<div class="invoice__header">
					<a href="#">
						<img class="invoice__logo" src="<?php echo ID_BASICA_URI; ?>/images/id-basica-logo.png" alt="<?php bloginfo( 'name' ); ?>" height="188" width="44"/>
					</a>
					<h1 class="invoice__title">
						<?php echo esc_html( $field_form_name ); ?>
					</h1>
					<div class="invoice__actions">

						<a href="<?php echo esc_url( $edit_url ); ?>" class="button button--primary" aria-label="Editar esta solicitud">
							<?php _e( 'Edit', ID_BASICA_DOMAIN ); ?>
						</a>

						<?php
						// if ( current_user_can( 'edit_post', get_the_ID() ) ) {
						// printf(
						// '<p><a href="%1$s" class="btn btn-secondary" aria-label="Editar esta solicitud">✏️ Editar solicitud</a></p>',
						// esc_url( get_edit_post_link( get_the_ID() ) )
						// );
						// }
						?>

						<button type="button" class="button button--outline-secondary" id="print-invoice-btn" onclick="window.print();">Print</button>
					</div>
					<div class="invoice__date">
						<?php echo esc_html( get_the_date() ); ?>
					</div>

				</div>

				<div class="invoice__body">
				</div>
			</div>
		</div>
	</div>
			<?php endwhile; ?>
	<?php endif; ?>

</div>

<?php
get_footer();
