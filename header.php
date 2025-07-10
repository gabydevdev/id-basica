<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and beginning of the dashboard layout
 *
 * @package ID_Basica
 * @since   1.0.0
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

		<?php get_template_part( 'template-parts/layout/sidebar' ); ?>

		<!-- Main Content -->
		<main class="dashboard__main">

			<?php get_template_part( 'template-parts/layout/header' ); ?>

			<!-- Content -->
			<div class="content dashboard__content">

