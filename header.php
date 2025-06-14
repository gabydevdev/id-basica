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

		<!-- Dashboard Navigation -->
		<?php idbasica_dashboard_menu(); ?>
	</aside>

	<!-- Main Content -->
	<main class="dashboard__main">
		<!-- Dashboard Header -->
		<header class="header">
			<div class="header__left">
				<h1 class="header__title">
					Welcome to the Dashboard
				</h1>
			</div>

			<div class="header__right">
				<div class="header__actions">
					<!-- Search Form -->
					<form class="header__search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search', 'idbasica' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
						<button type="submit" class="search-submit">
							<i class="fas fa-search"></i>
						</button>
					</form>

					<!-- User Menu -->
					<?php idbasica_user_menu(); ?>
				</div>
			</div>
		</header>

		<!-- Dashboard Content -->
		<div class="content">
