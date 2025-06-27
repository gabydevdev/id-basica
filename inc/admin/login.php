<?php

/**
 * Bypass Force Login to allow for exceptions.
 *
 * @param bool $bypass Whether to disable Force Login. Default false.
 * @param string $visited_url The visited URL.
 * @return bool
 */
function id_basica_forcelogin_bypass( $bypass, $visited_url ) {
	if ( is_page( array( 'login' ) ) ) {
		$bypass = true;
	}

	return $bypass;
}
add_filter( 'v_forcelogin_bypass', 'id_basica_forcelogin_bypass', 10, 2 );

/**
 * Redirect default login page to custom login page
 */
function id_basica_custom_login_page() {
	$login_page = home_url( '/login/' ); // Adjust URL to match your page slug
	$page       = basename( $_SERVER['REQUEST_URI'] );

	if ( $page == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		// Preserve the redirect_to parameter if it exists
		$redirect_to = '';
		if ( isset( $_GET['redirect_to'] ) ) {
			$redirect_to = '?redirect_to=' . urlencode( $_GET['redirect_to'] );
		}
		
		wp_redirect( $login_page . $redirect_to );
		exit;
	}
}
add_action( 'init', 'id_basica_custom_login_page' );

/**
 * Redirect after logout
 */
function id_basica_redirect_after_logout() {
	wp_redirect( home_url( '/login/' ) );
	exit;
}
add_action( 'wp_logout', 'id_basica_redirect_after_logout' );

/**
 * Redirect users to homepage after successful login
 */
function id_basica_redirect_after_login( $redirect_to, $request, $user ) {
	// Check if user login was successful
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// If there's a specific redirect URL requested (user was trying to access a protected page)
		if ( ! empty( $request ) ) {
			// Make sure it's a valid URL within our site
			$parsed_request = parse_url( $request );
			$parsed_home = parse_url( home_url() );
			
			// Check if the request is for the same domain
			if ( isset( $parsed_request['host'] ) && $parsed_request['host'] === $parsed_home['host'] ) {
				return $request;
			}
		}
		
		// Check if there's a redirect_to parameter in the URL
		if ( ! empty( $redirect_to ) && $redirect_to !== admin_url() ) {
			// Make sure it's a valid URL within our site
			$parsed_redirect = parse_url( $redirect_to );
			$parsed_home = parse_url( home_url() );
			
			// Check if the redirect is for the same domain and not wp-admin
			if ( isset( $parsed_redirect['host'] ) && $parsed_redirect['host'] === $parsed_home['host'] ) {
				// Don't redirect to wp-admin unless specifically requested
				if ( strpos( $redirect_to, '/wp-admin' ) === false || 
					 ( isset( $_GET['redirect_to'] ) && strpos( $_GET['redirect_to'], '/wp-admin' ) !== false ) ) {
					return $redirect_to;
				}
			}
		}
		
		// Default fallback to homepage
		return home_url();
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'id_basica_redirect_after_login', 10, 3 );

/**
 * Force users to login with email only (disable username login)
 */
function id_basica_email_login_only( $user, $username, $password ) {
	// If username is provided and it's not an email, prevent login
	if ( ! empty( $username ) && ! is_email( $username ) ) {
		return new WP_Error( 'invalid_email', __( 'Por favor, usa tu dirección de correo electrónico para iniciar sesión.' ) );
	}
	
	return $user;
}
add_filter( 'wp_authenticate_user', 'id_basica_email_login_only', 10, 3 );

/**
 * Remove username from login form and force email input type
 */
function id_basica_customize_login_form() {
	?>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			// Find the username field and change its type to email
			var usernameField = document.querySelector('#loginform-custom input[name="log"]');
			if (usernameField) {
				usernameField.type = 'email';
				usernameField.setAttribute('placeholder', 'correo@ejemplo.com');
				usernameField.setAttribute('required', 'required');
			}
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'id_basica_customize_login_form' );
