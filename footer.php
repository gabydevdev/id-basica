<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package IDBasica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
		</div><!-- .content -->

		<!-- Dashboard Footer -->
		<footer class="footer">
			<div class="container">
				<p class="footer__copyright">
					&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.
					<?php esc_html_e( 'All rights reserved.', 'id-basica' ); ?>
				</p>
			</div>
		</footer>
	</main><!-- .dashboard__main -->
</div><!-- .dashboard -->

<?php wp_footer(); ?>

</body>
</html>
