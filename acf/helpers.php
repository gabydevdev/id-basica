<?php
/**
 * ACF Helper Functions
 *
 * @package ID_Basica
 * @version 1.0.0
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the ACF field value with error handling.
 *
 * This function safely retrieves an ACF field value with proper error checking
 * to ensure the ACF plugin is active before attempting to get field data.
 *
 * @since 1.0.0
 *
 * @param string   $selector The field name or key to retrieve.
 * @param int|null $post_id  The post ID to get the field for. Defaults to the current post.
 * @return mixed The value of the ACF field or null if not found or ACF is not active.
 */
function id_basica_get_acf_field( $selector, $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		_e( 'ACF plugin is not active or get_field function is not available', ID_BASICA_DOMAIN );
		return null;
	}

	$field = ( $post_id !== null ) ? get_field( $selector, $post_id ) : get_field( $selector );

	return $field;
}

/**
 * Get all ACF fields for a post with error handling.
 *
 * This function safely retrieves all ACF fields for a given post with proper error checking
 * to ensure the ACF plugin is active before attempting to get field data.
 *
 * @since 1.0.0
 *
 * @param int|string|null $post_id The post ID to get the fields for, 'options' for options page,
 *                                 or null for current post. Defaults to the current post.
 * @return array|null The array of ACF fields or null if not found or ACF is not active.
 */
function id_basica_get_acf_fields( $post_id = null ) {
	// Check if ACF plugin is active and get_fields function exists
	if ( ! function_exists( 'get_fields' ) ) {
		_e( 'ACF plugin is not active or get_fields function is not available', ID_BASICA_DOMAIN );
		return null;
	}

	// Get fields based on post_id parameter
	$fields = ( $post_id !== null ) ? get_fields( $post_id ) : get_fields();

	return $fields;
}

/**
 * ACF Form Shortcode Handler
 *
 * Renders an ACF form through a shortcode. Supports both creating new posts
 * and editing existing posts based on URL parameters.
 *
 * Usage: [acf_form field_groups="1,2,3"]
 *
 * If no field_groups are specified in the shortcode, the function will check for
 * a 'group_field' ACF field as a fallback to determine which field groups to display.
 *
 * @since 1.0.0
 *
 * @param array $atts {
 *     Shortcode attributes.
 *
 *     @type string $field_groups Comma-separated list of ACF field group IDs to display.
 *                                Default empty (shows all field groups or falls back to 'group_field').
 * }
 * @return string The HTML output of the ACF form.
 */
function id_basica_acf_form_shortcode( $atts ) {
	// Parse shortcode attributes with defaults
	$atts = shortcode_atts(
		array(
			'field_groups' => '', // Comma-separated field group IDs
		),
		$atts,
		'acf_form'
	);

	$args = array();

	// Check for existing post ID in URL parameter for editing
	// Default to creating a new post
	$post_id = 'new_post';
	if ( ! empty( $_GET['solicitud_id'] ) ) {
		$maybe_id = absint( sanitize_text_field( wp_unslash( $_GET['solicitud_id'] ) ) );
		// Verify the post exists before using it
		if ( get_post_status( $maybe_id ) ) {
			$post_id = $maybe_id;
		}
	}

	$args['post_id'] = $post_id;

	// Configure new post settings if creating a new entry
	if ( $post_id === 'new_post' ) {
		$args['new_post'] = array(
			'post_type'   => 'application',
			'post_status' => 'publish',
		);
	}

	// Debug output for development environment only
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		ID_BASICA\DEV\console_log( 'Group field value: ' . wp_json_encode( id_basica_get_acf_field( 'group_field' ) ) );
	}

	// Determine which field groups to display
	// Priority: 1) Shortcode attribute, 2) ACF 'group_field' value, 3) All available groups
	if ( ! empty( $atts['field_groups'] ) ) {
		// Use field groups specified in shortcode attribute
		$field_group_ids = array_map( 'trim', explode( ',', $atts['field_groups'] ) );
		$args['field_groups'] = array_filter( $field_group_ids ); // Remove empty values
	} else {
		// Try to get field groups from ACF 'group_field' as fallback
		$group_field_value = id_basica_get_acf_field( 'group_field' );
		
		if ( ! empty( $group_field_value ) ) {
			// Validate that group_field contains valid field group data
			if ( is_array( $group_field_value ) ) {
				$args['field_groups'] = $group_field_value;
			} elseif ( is_string( $group_field_value ) ) {
				// Handle comma-separated string values
				$field_group_ids = array_map( 'trim', explode( ',', $group_field_value ) );
				$args['field_groups'] = array_filter( $field_group_ids );
			} else {
				// Handle single ID value
				$args['field_groups'] = array( $group_field_value );
			}
		} else {
			// No specific field groups defined, show all available
			$args['field_groups'] = false;
		}
	}

	// Configure form display settings
	$args['submit_value']          = esc_html__( 'Send', ID_BASICA_DOMAIN );
	$args['label_placement']       = 'top';
	$args['instruction_placement'] = 'field';
	$args['uploader']              = 'basic';
	$args['html_submit_button']    = '<input type="submit" class="acf-button button button-primary button-large" value="%s" />';

	// Debug output for development environment only
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		ID_BASICA\DEV\console_log( $args );
	}

	// Capture form output and return as string
	ob_start();
	acf_form( $args );
	return ob_get_clean();
}

/**
 * Register the ACF form shortcode.
 *
 * Makes the [acf_form] shortcode available for use in posts, pages, and widgets.
 *
 * @since 1.0.0
 */
add_shortcode( 'acf_form', 'id_basica_acf_form_shortcode' );

/**
 * Handle ACF form submission for applications.
 *
 * This function runs after ACF saves post data and handles:
 * - Setting the post title from the 'entry_title' field
 * - Setting the 'application_type' taxonomy from the 'form_name' field
 * - Generating a unique slug based on author ID, date, and timestamp
 *
 * @since 1.0.0
 * @param int $post_id The post ID that was saved
 */
function id_basica_handle_application_post_save( $post_id ) {
	// Only process application posts
	if ( get_post_type( $post_id ) !== 'application' ) {
		return;
	}

	// Avoid infinite loops by removing the action temporarily
	remove_action( 'acf/save_post', 'id_basica_handle_application_post_save', 15 );

	$post_update_data = array();
	$needs_update = false;

	// 1. Set post title from 'entry_title' field
	$entry_title = get_field( 'entry_title', $post_id );
	if ( ! empty( $entry_title ) ) {
		$post_update_data['post_title'] = sanitize_text_field( $entry_title );
		$needs_update = true;

		// Debug logging
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf( 
				'ID Basica - Application %d: Setting title to "%s"', 
				$post_id, 
				$entry_title 
			) );
		}
	}

	// 2. Generate unique slug based on encoded author ID, date, and timestamp
	$post = get_post( $post_id );
	if ( $post ) {
		$author_id = $post->post_author;
		$encoded_author_id = id_basica_encode_user_id( $author_id );
		$date_created = get_the_date( 'Ymd', $post_id ); // Format: 20250702
		$timestamp = get_the_date( 'His', $post_id ); // Hour, minute, second
		
		// Format: app-{encoded_author_id}{date}{timestamp}
		// Example: app-x7k2m920250702143052
		$new_slug = sprintf( 
			'app-%s%s%s', 
			$encoded_author_id,
			$date_created, 
			$timestamp 
		);

		// Ensure slug is unique
		$original_slug = $new_slug;
		$counter = 1;
		while ( get_page_by_path( $new_slug, OBJECT, 'application' ) && get_page_by_path( $new_slug, OBJECT, 'application' )->ID !== $post_id ) {
			$new_slug = $original_slug . '-' . $counter;
			$counter++;
		}

		$post_update_data['post_name'] = $new_slug;
		$needs_update = true;

		// Debug logging
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf( 
				'ID Basica - Application %d: Setting slug to "%s" (author: %d->%s, date: %s, time: %s)', 
				$post_id, 
				$new_slug, 
				$author_id,
				$encoded_author_id, 
				$date_created, 
				$timestamp 
			) );
		}
	}

	// Update the post if we have changes
	if ( $needs_update ) {
		$post_update_data['ID'] = $post_id;
		wp_update_post( $post_update_data );
	}

	// 3. Set taxonomy term from 'form_name' field
	$form_name = get_field( 'form_name', $post_id );
	if ( ! empty( $form_name ) ) {
		// Check if the term exists, if not create it
		$term = get_term_by( 'name', $form_name, 'application_type' );
		
		if ( ! $term ) {
			// Create the term if it doesn't exist
			$term_result = wp_insert_term( 
				$form_name, 
				'application_type',
				array(
					'slug' => sanitize_title( $form_name )
				)
			);
			
			if ( ! is_wp_error( $term_result ) ) {
				$term_id = $term_result['term_id'];
			} else {
				// Log error if term creation fails
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( sprintf( 
						'ID Basica - Application %d: Failed to create term "%s": %s', 
						$post_id, 
						$form_name, 
						$term_result->get_error_message() 
					) );
				}
				$term_id = null;
			}
		} else {
			$term_id = $term->term_id;
		}

		// Assign the term to the post
		if ( $term_id ) {
			$result = wp_set_post_terms( $post_id, array( $term_id ), 'application_type' );
			
			// Debug logging
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				if ( is_wp_error( $result ) ) {
					error_log( sprintf( 
						'ID Basica - Application %d: Failed to set taxonomy term "%s": %s', 
						$post_id, 
						$form_name, 
						$result->get_error_message() 
					) );
				} else {
					error_log( sprintf( 
						'ID Basica - Application %d: Set taxonomy term "%s" (ID: %d)', 
						$post_id, 
						$form_name, 
						$term_id 
					) );
				}
			}
		}
	}

	// Re-add the action
	add_action( 'acf/save_post', 'id_basica_handle_application_post_save', 15 );
}
add_action( 'acf/save_post', 'id_basica_handle_application_post_save', 15 );

/**
 * Populate entry_title when form_name changes.
 *
 * This function updates the entry_title field when the form_name field is updated,
 * ensuring consistency between the two fields for new applications.
 *
 * @since 1.0.0
 * @param mixed $value The field value
 * @param int   $post_id The post ID
 * @param array $field The field array
 * @return mixed The field value
 */
function id_basica_update_entry_title_on_form_name_change( $value, $post_id, $field ) {
	// Only update for application posts and when form_name is being saved
	if ( get_post_type( $post_id ) !== 'application' || empty( $value ) ) {
		return $value;
	}

	// Check if this is a new post or if entry_title is empty
	$existing_entry_title = get_field( 'entry_title', $post_id );
	$post = get_post( $post_id );
	$is_new_post = ( ! $post || get_post_status( $post ) === 'auto-draft' );

	if ( $is_new_post || empty( $existing_entry_title ) ) {
		$current_user = wp_get_current_user();
		$current_date = date( 'd/m/Y' );

		if ( $current_user->exists() ) {
			$new_entry_title = sanitize_text_field(
				$value . ' - ' . $current_user->display_name . ' - ' . $current_date
			);

			// Update the entry_title field
			update_field( 'entry_title', $new_entry_title, $post_id );

			// Debug logging
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf( 
					'ID Basica - form_name update: Updated entry_title to "%s" for application %d',
					$new_entry_title,
					$post_id
				) );
			}
		}
	}

	return $value;
}
add_filter( 'acf/update_value/name=form_name', 'id_basica_update_entry_title_on_form_name_change', 10, 3 );

/**
 * Encode user ID for slug generation.
 *
 * Creates an obfuscated representation of the user ID that's not easily readable
 * but still deterministic (same input always produces same output).
 *
 * @since 1.0.0
 * @param int $user_id The user ID to encode
 * @return string The encoded user ID
 */
function id_basica_encode_user_id( $user_id ) {
	// Use a simple encoding method that's deterministic but not obvious
	// This creates a hash-like string from the user ID + a site-specific salt
	$salt = defined( 'AUTH_SALT' ) ? AUTH_SALT : 'id_basica_salt';
	$hash = hash( 'crc32b', $user_id . $salt );
	
	// Convert hex to base36 for shorter, more readable string
	$encoded = base_convert( $hash, 16, 36 );
	
	return $encoded;
}

/**
 * Decode user ID from encoded slug.
 *
 * Attempts to find the original user ID from an encoded slug.
 * This is mainly for debugging purposes since the encoding is one-way.
 *
 * @since 1.0.0
 * @param string $encoded_id The encoded user ID from slug
 * @param int    $max_user_id Maximum user ID to check (default 10000)
 * @return int|false The original user ID if found, false otherwise
 */
function id_basica_decode_user_id( $encoded_id, $max_user_id = 10000 ) {
	// Brute force approach - check all possible user IDs up to max
	// This is only practical for smaller user bases and debugging
	for ( $i = 1; $i <= $max_user_id; $i++ ) {
		if ( id_basica_encode_user_id( $i ) === $encoded_id ) {
			return $i;
		}
	}
	
	return false;
}
