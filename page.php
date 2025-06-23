<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- Dashboard Content -->
<div class="content mt-6">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="container">

		<div class="content__main">
			<?php the_content(); ?>
		</div>

	</div>
	<?php endwhile; ?>
	<?php endif; ?>

</div>

<?php
get_footer();
