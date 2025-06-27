<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and beginning of the dashboard layout
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<meta name="robots" content="noindex" />
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<div class="dashboard">
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

		<!-- Main Content -->
		<main class="dashboard__main">
			<!-- Dashboard Header -->
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
