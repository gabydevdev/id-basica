<?php
/**
 * Template Name: Page Login
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<meta name="robots" content="noindex" />
</head>

<body <?php body_class('bg-primary'); ?>>
	<?php wp_body_open(); ?>
	<!-- Login Content -->
	<div class="login" style="--bg-image: url('<?php echo get_template_directory_uri(); ?>/images/login-bg.jpg');">
		<div class="container container--narrow">
			<div class="login__container">
				<!-- Login Form Section -->
				<div class="login__form-section">
					<div class="login__header">
						<h1 class="login__title">¡Hola!</h1>
						<p class="login__subtitle">bienvenid@ a ID Básica</p>
					</div>

					<?php
					if (! is_user_logged_in()) { // Display WordPress login form:
						$args = array(
							'redirect'       => admin_url(),
							'form_id'        => 'loginform-custom',
							'label_username' => __('Correo electrónico'),
							'label_password' => __('Contraseña'),
							'label_remember' => __('Recuérdame'),
							'label_log_in'   => __('Iniciar sesión'),
							'remember'       => true
						);
						wp_login_form($args);
					} else { // If logged in:
						wp_loginout(home_url()); // Display "Log Out" link.
						echo " | ";
						wp_register('', ''); // Display "Site Admin" link.
					}
					?>

					<!-- Forgot Password Link -->
					<div class="login__forgot-password">
						<a href="<?php echo wp_lostpassword_url(); ?>">¿Olvidaste tu contraseña?</a>
					</div>
				</div>

				<!-- Login Branding Section -->
				<div class="login__branding-section">
					<div class="login__branding-wrapper">
						<div class="login__logo">
							<img src="<?php echo get_template_directory_uri(); ?>/images/id-basica-logo.svg" alt="<?php bloginfo( 'name' ); ?>" class="login__logo-img">
						</div>

						<div class="login__branding-content">
							<!-- <h2 class="login__branding-title">Buzón de sugerencias, comentarios o quejas</h2> -->
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<?php wp_footer(); ?>

</body>

</html>
