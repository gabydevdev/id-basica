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
 * Additional login redirect handling for Force Login and other plugins
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
 * Redirect users to homepage after successful login
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
 * Redirect after logout
 */
function id_basica_redirect_after_logout() {
	wp_redirect( home_url( '/login/' ) );
	exit;
}
add_action( 'wp_logout', 'id_basica_redirect_after_logout' );
