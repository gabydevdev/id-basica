<?php
/**
 * Login functionality for ID Basica.
 *
 * @package ID_Basica
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Bypass Force Login to allow for exceptions.
 *
 * @param bool   $bypass Whether to disable Force Login. Default false.
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
 * Filter the login URL to use custom login page.
 *
 * Replaces the default WordPress login URL with a custom login page
 * located at /login/. Preserves redirect_to and reauth parameters.
 *
 * @since 1.0.0
 * @param string $login_url    The login URL. Not HTML-encoded.
 * @param string $redirect     The path to redirect to on login.
 * @param bool   $force_reauth Whether to force reauthorization.
 * @return string Modified login URL pointing to custom login page.
 */
function id_basica_login_url_filter( $login_url, $redirect, $force_reauth ) {
	// This will append /login/ to you main site URL
	$login_url = home_url( '/login/' );

	if ( ! empty( $redirect ) ) {
		$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
	}

	if ( $force_reauth ) {
		$login_url = add_query_arg( 'reauth', '1', $login_url );
	}

	return $login_url;
}
add_filter( 'login_url', 'id_basica_login_url_filter', 10, 3 );

/**
 * Redirect users to homepage after successful login.
 *
 * Filters the login redirect URL to send successfully logged-in
 * users to the site homepage instead of the WordPress admin dashboard.
 *
 * @since 1.0.0
 * @param string           $redirect_to The redirect destination URL.
 * @param string           $request     The requested redirect destination URL passed as a parameter.
 * @param WP_User|WP_Error $user        WP_User object on success, WP_Error object on failure.
 * @return string The modified redirect URL.
 */
function id_basica_redirect_after_login( $redirect_to, $request, $user ) {
	// Check if user login was successful
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		return home_url();
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'id_basica_redirect_after_login', 10, 3 );

/**
 * Redirect users to login page after logout.
 *
 * Automatically redirects users to the custom login page
 * after they log out, instead of the default WordPress behavior.
 *
 * @since 1.0.0
 */
function id_basica_redirect_after_logout() {
	wp_redirect( home_url( '/login/' ) );
	exit;
}
add_action( 'wp_logout', 'id_basica_redirect_after_logout' );
