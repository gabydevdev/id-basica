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
		wp_redirect( $login_page );
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
