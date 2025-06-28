<?php

/**
 * User-related functions
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

/**
 * Add custom columns to users table
 *
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function id_basica_add_user_columns( $columns ) {
	// Remove default Posts and Email columns
	unset( $columns['posts'] );
	unset( $columns['email'] );

	// Reorder columns with Avatar first
	$new_columns       = array();
	$new_columns['cb'] = $columns['cb']; // Keep checkbox
	// $new_columns['avatar'] = __( 'Avatar', 'id-basica' );
	$new_columns['username']       = $columns['username'];
	$new_columns['name']           = $columns['name'];
	$new_columns['role']           = $columns['role'];
	$new_columns['ubicacion']      = __( 'Ubicación', 'id-basica' );
	$new_columns['puesto']         = __( 'Puesto', 'id-basica' );
	$new_columns['departamento']   = __( 'Departamento', 'id-basica' );
	// $new_columns['fecha_ingreso']  = __( 'Fecha de Ingreso', 'id-basica' );
	$new_columns['jefe_inmediato'] = __( 'Jefe Inmediato', 'id-basica' );

	return $new_columns;
}
add_filter( 'manage_users_columns', 'id_basica_add_user_columns' );

/**
 * Display content for custom user columns
 *
 * @param string $output Custom column output
 * @param string $column_name Name of the column
 * @param int    $user_id ID of the user
 * @return string Column content
 */
function id_basica_show_user_column_content( $output, $column_name, $user_id ) {
	switch ( $column_name ) {
		// case 'avatar':
		// 	// Use custom profile picture if available, otherwise show empty
		// 	$profile_picture = get_user_meta( $user_id, 'profile_picture', true );
		// 	if ( $profile_picture ) {
		// 		$output = wp_get_attachment_image(
		// 			$profile_picture,
		// 			array( 32, 32 ),
		// 			false,
		// 			array(
		// 				'class' => 'avatar avatar-32 photo',
		// 				'style' => 'border-radius: 50%; display: block; margin: 0 auto;'
		// 			)
		// 		);
		// 	} else {
		// 		$output = '<div class="avatar avatar-32" style="width: 32px; height: 32px; border-radius: 50%; background-color: #f0f0f0; display: block; margin: 0 auto;"></div>';
		// 	}
		// 	break;

		case 'ubicacion':
			$ubicacion = get_user_meta( $user_id, 'sede', true );
			$output = $ubicacion ? esc_html( $ubicacion ) : '—';
			break;

		case 'puesto':
			$puesto = get_user_meta( $user_id, 'puesto', true );
			$output = $puesto ? esc_html( $puesto ) : '—';
			break;

		case 'departamento':
			$departamento = get_user_meta( $user_id, 'departamento', true );
			$output = $departamento ? esc_html( $departamento ) : '—';
			break;

		// case 'fecha_ingreso':
		// 	$fecha_ingreso = get_user_meta( $user_id, 'fecha_de_ingreso', true );
		// 	if ( $fecha_ingreso ) {
		// 		$date   = date_create( $fecha_ingreso );
		// 		$output = $date ? date_format( $date, 'd/m/Y' ) : '—';
		// 	} else {
		// 		$output = '—';
		// 	}
		// 	break;

		case 'jefe_inmediato':
			$jefe_id = get_user_meta( $user_id, 'jefe_inmediato', true );
			if ( $jefe_id ) {
				$jefe   = get_userdata( $jefe_id );
				$output = $jefe ? esc_html( $jefe->display_name ) : '—';
			} else {
				$output = '—';
			}
			break;
	}

	return $output;
}
add_filter( 'manage_users_custom_column', 'id_basica_show_user_column_content', 10, 3 );

/**
 * Make custom user columns sortable
 *
 * @param array $columns Sortable columns
 * @return array Modified sortable columns
 */
function id_basica_make_user_columns_sortable( $columns ) {
	$columns['ubicacion']      = 'ubicacion';
	$columns['puesto']         = 'puesto';
	$columns['departamento']   = 'departamento';
	// $columns['fecha_ingreso']  = 'fecha_ingreso';
	$columns['jefe_inmediato'] = 'jefe_inmediato';

	return $columns;
}
add_filter( 'manage_users_sortable_columns', 'id_basica_make_user_columns_sortable' );

/**
 * Handle sorting for custom user columns
 *
 * @param WP_User_Query $query User query object
 */
function id_basica_handle_user_column_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby       = $query->get( 'orderby' );
	$custom_fields = array( 'ubicacion', 'puesto', 'departamento', 'jefe_inmediato' );

	if ( in_array( $orderby, $custom_fields ) ) {
		$query->set( 'meta_key', $orderby );
		$query->set( 'orderby', 'meta_value' );

		// Special handling for date sorting
		if ( $orderby === 'fecha_ingreso' ) {
			$query->set( 'orderby', 'meta_value_datetime' );
		}
	}
}
add_action( 'pre_get_users', 'id_basica_handle_user_column_sorting' );

/**
 * Add quick edit link to user row actions
 *
 * @param array   $actions User row actions
 * @param WP_User $user User object
 * @return array Modified actions
 */
function id_basica_add_user_quick_edit_link( $actions, $user ) {
	if ( current_user_can( 'edit_user', $user->ID ) ) {
		$actions['quick_edit'] = '<a href="#" class="editinline" data-user-id="' . $user->ID . '">' . __( 'Quick Edit', 'id-basica' ) . '</a>';
	}
	return $actions;
}
// add_filter( 'user_row_actions', 'id_basica_add_user_quick_edit_link', 10, 2 );

/**
 * Add quick edit form to users table
 */
function id_basica_add_user_quick_edit_form() {
	$screen = get_current_screen();

	if ( $screen && $screen->id === 'users' ) : ?>
		<table style="display: none">
			<tbody id="inlineedit">
				<tr id="inline-edit" class="inline-edit-row" style="display: none">
					<td colspan="<?php echo get_column_headers( $screen ) ? count( get_column_headers( $screen ) ) : 5; ?>" class="colspanchange">
						<div class="inline-edit-wrapper">
							<fieldset class="inline-edit-col-left">
								<legend class="inline-edit-legend"><?php _e( 'Quick Edit User', 'id-basica' ); ?></legend>
								<div class="inline-edit-col">
									<label>
										<span class="title"><?php _e( 'Ubicación', 'id-basica' ); ?></span>
										<span class="input-text-wrap">
											<input type="text" name="sede" class="ptitle" value="" />
										</span>
									</label>

									<label>
										<span class="title"><?php _e( 'Puesto', 'id-basica' ); ?></span>
										<span class="input-text-wrap">
											<input type="text" name="puesto" class="ptitle" value="" />
										</span>
									</label>
								</div>
							</fieldset>

							<fieldset class="inline-edit-col-center">
								<div class="inline-edit-col">
									<label>
										<span class="title"><?php _e( 'Departamento', 'id-basica' ); ?></span>
										<span class="input-text-wrap">
											<input type="text" name="departamento" class="ptitle" value="" />
										</span>
									</label>

									<label>
										<span class="title"><?php _e( 'Fecha de Ingreso', 'id-basica' ); ?></span>
										<span class="input-text-wrap">
											<input type="date" name="fecha_de_ingreso" class="ptitle" value="" />
										</span>
									</label>
								</div>
							</fieldset>

							<fieldset class="inline-edit-col-right">
								<div class="inline-edit-col">
									<label>
										<span class="title"><?php _e( 'Jefe Inmediato', 'id-basica' ); ?></span>
										<span class="input-text-wrap">
											<select name="jefe_inmediato" class="ptitle">
												<option value=""><?php _e( 'Select...', 'id-basica' ); ?></option>
												<?php
												$users = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
												foreach ( $users as $user ) {
													echo '<option value="' . $user->ID . '">' . esc_html( $user->display_name ) . '</option>';
												}
												?>
											</select>
										</span>
									</label>
								</div>
							</fieldset>

							<div class="submit inline-edit-save">
								<button type="button" class="button cancel alignleft"><?php _e( 'Cancel' ); ?></button>
								<button type="button" class="button button-primary save alignright"><?php _e( 'Update User' ); ?></button>
								<span class="spinner"></span>
								<br class="clear" />
								<div class="notice notice-error notice-alt inline hidden">
									<p class="error"></p>
								</div>
							</div>
						</div>
						<input type="hidden" name="user_id" value="" />
						<input type="hidden" name="action" value="inline_save_user" />
						<?php wp_nonce_field( 'user_quick_edit_nonce', 'user_quick_edit_nonce' ); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	endif;
}
// add_action( 'admin_footer', 'id_basica_add_user_quick_edit_form' );

/**
 * Handle AJAX request for quick edit save
 */
function id_basica_handle_user_quick_edit_save() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['user_quick_edit_nonce'], 'user_quick_edit_nonce' ) ) {
		wp_die( __( 'Security check failed', 'id-basica' ) );
	}

	$user_id = intval( $_POST['user_id'] );

	// Check permissions
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		wp_die( __( 'You do not have permission to edit this user', 'id-basica' ) );
	}

	// Update user meta fields
	$fields = array( 'sede', 'puesto', 'departamento', 'fecha_de_ingreso', 'jefe_inmediato', 'profile_picture' );

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_user_meta( $user_id, $field, sanitize_text_field( $_POST[ $field ] ) );
		}
	}

	// Return updated row HTML
	$user = get_userdata( $user_id );
	if ( $user ) {
		$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
		$wp_list_table->single_row( $user );
	}

	wp_die();
}
// add_action( 'wp_ajax_inline_save_user', 'id_basica_handle_user_quick_edit_save' );

/**
 * Enqueue JavaScript for user quick edit functionality
 */
function id_basica_enqueue_user_quick_edit_scripts( $hook ) {
	if ( $hook !== 'users.php' ) {
		return;
	}

	wp_enqueue_script(
		'id-basica-user-quick-edit',
		ID_BASICA_URI . '/admin/assets/js/user-quick-edit.js',
		array( 'jquery' ),
		ID_BASICA_VERSION,
		true
	);

	wp_localize_script(
		'id-basica-user-quick-edit',
		'userQuickEdit',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'user_quick_edit_nonce' ),
		)
	);
}
// add_action( 'admin_enqueue_scripts', 'id_basica_enqueue_user_quick_edit_scripts' );

/**
 * Enqueue media uploader scripts for profile picture
 */
function id_basica_enqueue_profile_picture_scripts( $hook ) {
	if ( $hook !== 'profile.php' && $hook !== 'user-edit.php' ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'id-basica-profile-picture',
		ID_BASICA_URI . '/admin/assets/js/profile-picture.js',
		array( 'jquery' ),
		ID_BASICA_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'id_basica_enqueue_profile_picture_scripts' );

/**
 * Add custom CSS for user table column widths
 */
function id_basica_user_table_styles() {
	$screen = get_current_screen();
	if ( $screen && $screen->id === 'users' ) {
		?>
		<style>
			/*.wp-list-table .column-avatar {
					width: 60px;
					text-align: center;
				}*/
			/*.wp-list-table .column-avatar .avatar {
					vertical-align: middle;
					margin: 0;
				}*/
			.wp-list-table .column-username {
				width: auto;
			}

			.wp-list-table td.column-username {
				display: flex;
				flex-wrap: wrap;
				position: relative;
				padding-left: 40px;
			}

			.wp-list-table td.column-username .avatar {
				position: absolute;
				left: 0;
			}

			.wp-list-table td.column-username strong {
				display: inline-block;
				margin-top: 4px;
				max-width: 100%;
			}

			.wp-list-table .column-name {
				width: 15%;
			}

			.wp-list-table .column-role {
				width: 8%;
			}

			.wp-list-table .column-ubicacion {
				width: 12%;
			}

			.wp-list-table .column-puesto {
				width: 12%;
			}

			.wp-list-table .column-departamento {
				width: 12%;
			}

			/* .wp-list-table .column-fecha_ingreso {
				width: 10%;
			} */

			.wp-list-table .column-jefe_inmediato {
				width: 15%;
			}

			/* Ensure all table cells have consistent vertical alignment */
			/*.wp-list-table tbody tr td {
					vertical-align: middle;
				}*/
			/* Fix avatar alignment specifically */
			/*.wp-list-table .column-avatar div {
					margin: 0 auto;
				}*/
		</style>
		<?php
	}
	
	// Hide Application Passwords section on user edit pages
	if ( $screen && ( $screen->id === 'profile' || $screen->id === 'user-edit' ) ) {
		?>
		<style>
			#application-passwords-section,
			#e-notes,
			#e-notes + table,
			.user-description-wrap,
			.user-profile-picture,
			table:has(label[for="elementor_enable_ai"]),
			#profile-nav {
				display: none !important;
			}

			.user-user-login-wrap #user_login {
				border: none;
				box-shadow: none;
				background: transparent;
				padding: 0;
			}

			.user-user-login-wrap .description {
				display: none;
			}
		</style>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const headings = document.querySelectorAll('h2, h3');
				headings.forEach(function(heading) {
					if (heading.textContent.includes('About') || 
						heading.textContent.includes('Biographical') ||
						heading.textContent.includes('Bio')) {
						heading.style.display = 'none';
					}
				});
				
				const tableRows = document.querySelectorAll('.form-table tr');
				tableRows.forEach(function(row) {
					const th = row.querySelector('th');
					if (th && (th.textContent.includes('Elementor'))) {
						row.style.display = 'none';
					}
				});
				
				const profileNavTabs = document.querySelectorAll('#profile-nav a, #profile-nav li');
				profileNavTabs.forEach(function(tab) {
					if (tab.textContent.includes('Extended Profile')) {
						tab.style.display = 'none';
					}
				});
			});
		</script>
		<?php
	}
}
add_action( 'admin_head', 'id_basica_user_table_styles' );

/**
 * Add custom profile picture field to user profile
 */
function id_basica_add_user_profile_picture_field( $user ) {
	$profile_picture = get_user_meta( $user->ID, 'profile_picture', true );
	?>
	<h3><?php _e( 'Profile Picture', 'id-basica' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="profile_picture"><?php _e( 'Profile Picture', 'id-basica' ); ?></label></th>
			<td>
				<input type="hidden" id="profile_picture" name="profile_picture" value="<?php echo esc_attr( $profile_picture ); ?>" />
				<div id="profile-picture-preview">
					<?php if ( $profile_picture ) :
						$image = wp_get_attachment_image( $profile_picture, array( 150, 150 ), false, array( 'style' => 'border-radius: 50%; max-width: 150px; height: auto;' ) );
						echo $image;
					else : ?>
						<img src="<?php echo get_avatar_url( $user->ID, array( 'size' => 150 ) ); ?>" style="border-radius: 50%; max-width: 150px; height: auto;" alt="<?php _e( 'Profile Picture', 'id-basica' ); ?>" />
					<?php endif; ?>
				</div>
				<p>
					<button type="button" class="button" id="upload-profile-picture"><?php _e( 'Upload Picture', 'id-basica' ); ?></button>
					<button type="button" class="button" id="remove-profile-picture" <?php echo $profile_picture ? '' : 'style="display:none;"'; ?>><?php _e( 'Remove Picture', 'id-basica' ); ?></button>
				</p>
				<p class="description"><?php _e( 'Upload a custom profile picture. Recommended size: 150x150 pixels.', 'id-basica' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'id_basica_add_user_profile_picture_field', 5, 1 );
add_action( 'edit_user_profile', 'id_basica_add_user_profile_picture_field', 5, 1 );

/**
 * Save custom profile picture field
 */
function id_basica_save_user_profile_picture_field( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	if ( isset( $_POST['profile_picture'] ) ) {
		update_user_meta( $user_id, 'profile_picture', sanitize_text_field( $_POST['profile_picture'] ) );
	}
}
add_action( 'personal_options_update', 'id_basica_save_user_profile_picture_field' );
add_action( 'edit_user_profile_update', 'id_basica_save_user_profile_picture_field' );

/**
 * Replace default avatar with custom profile picture
 */
function id_basica_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	$user = null;

	if ( is_numeric( $id_or_email ) ) {
		$user = get_user_by( 'id', (int) $id_or_email );
	} elseif ( is_object( $id_or_email ) ) {
		if ( ! empty( $id_or_email->user_id ) ) {
			$user = get_user_by( 'id', (int) $id_or_email->user_id );
		}
	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( $user && is_object( $user ) ) {
		$profile_picture = get_user_meta( $user->ID, 'profile_picture', true );

		if ( $profile_picture ) {
			$image = wp_get_attachment_image(
				$profile_picture,
				array( $size, $size ),
				false,
				array(
					'alt'   => $alt,
					'class' => 'avatar avatar-' . (int) $size . ' photo',
					'style' => 'border-radius: 50%;'
				)
			);

			if ( $image ) {
				return $image;
			}
		}

		// Return empty div if no custom profile picture (removes default gravatar)
		return '<div class="avatar avatar-' . (int) $size . '" style="width: ' . $size . 'px; height: ' . $size . 'px; border-radius: 50%; background-color: #f0f0f0; display: inline-block;"></div>';
	}

	return $avatar;
}
add_filter( 'get_avatar', 'id_basica_custom_avatar', 10, 5 );
