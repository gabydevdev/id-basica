<?php
/**
 * Template Name: ACF Form
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

acf_form_head();

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<div class="container">
			<div class="content__main">
				<?php the_content(); ?>
			</div>
		</div>
		<?php
	}
}

get_footer();
