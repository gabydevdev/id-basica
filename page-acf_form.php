<?php
/**
 * Template Name: ACF Form
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

acf_form_head();
get_header();
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
			<?php the_content(); ?>
		</div>

	</div>
			<?php endwhile; ?>
	<?php endif; ?>

</div>

<?php
get_footer();
