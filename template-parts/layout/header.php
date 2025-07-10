<!-- Header -->
<header class="header">

	<div class="header__left">
		<?php
		// Check if current user has admin privileges
		if ( current_user_can( 'administrator' ) || in_array( 'basica_admin', wp_get_current_user()->roles ) ) :
			?>
			<div class="header__admin-links">
				<a href="<?php echo admin_url(); ?>" class="header__admin-link" target="_blank">
					<span><?php _e( 'Administración', 'id-basica' ); ?></span>
				</a>
				<a href="<?php echo admin_url( 'users.php' ); ?>" class="header__admin-link" target="_blank">
					<span><?php _e( 'Usuarios', 'id-basica' ); ?></span>
				</a>
			</div>
		<?php endif; ?>
	</div>

	<div class="header__right">
		<div class="header__actions">
			<!-- Search Form -->
			<?php // get_search_form(); ?>

			<!-- User Menu -->
			<?php id_basica_user_menu(); ?>
		</div>
	</div>

</header>
