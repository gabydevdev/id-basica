<?php
/**
 * Template for displaying individual pages.
 *
 * This template is used to display single pages within the dashboard system.
 * It provides the basic structure for page content with the dashboard layout.
 *
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
