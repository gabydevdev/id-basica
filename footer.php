<?php
/**
 * The template for displaying the footer.
 *
 * Contains the footer content and closes the dashboard layout structure.
 * Includes copyright information and closes all necessary HTML containers.
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
			</div><!-- .dashboard__content from header.php -->

			<!-- Footer -->
			<footer class="footer">
				<div class="container">
					<p class="footer__copyright">
						&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.
						<?php esc_html_e( 'All rights reserved.', ID_BASICA_DOMAIN ); ?>
					</p>
				</div>
			</footer>

		</main><!-- .dashboard__main from header.php -->

	</div><!-- .dashboard from header.php -->

	<?php wp_footer(); ?>

</body>
</html>
