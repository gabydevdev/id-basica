<!-- Sidebar -->
<aside class="sidebar">

	<div class="sidebar__logo">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		} else {
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="sidebar__logo-text"><?php bloginfo( 'name' ); ?></span>
			</a>
			<?php
		}
		?>
	</div>

	<!-- Mobile menu toggle -->
	<button class="menu-toggle d-md-none">
		<i class="fas fa-bars"></i>
	</button>

	<?php
	// Display the dashboard-sidebar widget area if active
	if ( is_active_sidebar( 'dashboard-sidebar' ) ) {
		dynamic_sidebar( 'dashboard-sidebar' );
	}
	?>

</aside>
