<?php
/**
 * Template for displaying single application posts.
 *
 * This template displays individual application posts with
 * a custom layout featuring invoice-style formatting and
 * ACF field integration for application data.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		$acf_fields = id_basica_get_acf_fields();

		$form_name = isset( $acf_fields['form_name'] ) ? $acf_fields['form_name'] : '';
		$form_type = !empty($form_name) ? strtolower( str_replace( ' ', '_', $form_name ) ) : '';

		?>

		<div class="container">
			<div class="content__main">

				<!-- Printable Page -->
				<div class="invoice">

					<div class="invoice__header">
						<div class="invoice__logo">
							<img src="<?php echo ID_BASICA_URI; ?>/images/id-basica-logo.png" alt="<?php bloginfo( 'name' ); ?>" height="188" width="44"/>
						</div>

						<h1 class="invoice__title">
							<?php echo esc_html( $form_name ); ?>
						</h1>

						<div class="invoice__actions">
							<a href="<?php echo esc_url( $edit_url ); ?>" class="button button--primary" aria-label="<?php echo esc_html( 'Editar esta solicitud', ID_BASICA_DOMAIN ); ?>">
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

							<button type="button" class="button button--outline-secondary" id="print-invoice-btn" onclick="window.print();">
								<?php esc_attr_e( 'Print', ID_BASICA_DOMAIN ); ?>
							</button>
						</div>

						<div class="invoice__date">
							<?php echo esc_html( $acf_fields['fecha_de_formato'] ); ?>
						</div>
					</div>

					<?php get_template_part( 'template-parts/application/form', $form_type ); ?>

				</div>

			</div>
		</div>

		<?php

	}
}

get_footer();
