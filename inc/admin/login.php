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
 * Additional login redirect handling for Force Login and other plugins
 */
function id_basica_login_url_filter( $login_url, $redirect, $force_reauth ) {
	// Always redirect to our custom login page
	$custom_login_url = home_url( '/login/' );
	
	// Add redirect parameter if it exists
	if ( ! empty( $redirect ) ) {
		$custom_login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $custom_login_url );
	}
	
	return $custom_login_url;
}
add_filter( 'login_url', 'id_basica_login_url_filter', 10, 3 );

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
 * Redirect after logout
 */
function id_basica_redirect_after_logout() {
	wp_redirect( home_url( '/login/' ) );
	exit;
}
add_action( 'wp_logout', 'id_basica_redirect_after_logout' );
