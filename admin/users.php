<?php
/**
 * User-related functions
 *
 * This file contains all user management functionality including custom columns,
 * profile picture management, and user interface customizations.
 *
 * @package ID_Basica
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove default admin color scheme picker.
 *
 * @since 1.0.0
 */
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

/**
 * Add custom columns to users table.
 *
 * Removes default Posts and Email columns and adds custom columns
 * for location, position, department, and immediate supervisor.
 *
 * @since 1.0.0
 * @param array $columns Existing columns array.
 * @return array Modified columns array.
 */
function id_basica_add_user_columns( $columns ) {
	// Remove default Posts and Email columns.
	unset( $columns['posts'] );
	unset( $columns['email'] );

	// Reorder columns with Avatar first.
	$new_columns                   = array();
	$new_columns['cb']             = $columns['cb']; // Keep checkbox.
	$new_columns['username']       = $columns['username'];
	$new_columns['name']           = $columns['name'];
	$new_columns['role']           = $columns['role'];
	$new_columns['ubicacion']      = __( 'Ubicación', 'id-basica' );
	$new_columns['puesto']         = __( 'Puesto', 'id-basica' );
	$new_columns['departamento']   = __( 'Departamento', 'id-basica' );
	$new_columns['jefe_inmediato'] = __( 'Jefe Inmediato', 'id-basica' );

	return $new_columns;
}
add_filter( 'manage_users_columns', 'id_basica_add_user_columns' );

/**
 * Display content for custom user columns.
 *
 * Retrieves and displays user meta data for custom columns in the users table.
 * Handles ubicacion (sede), puesto, departamento, and jefe_inmediato fields.
 *
 * @since 1.0.0
 * @param string $output      Custom column output.
 * @param string $column_name Name of the column.
 * @param int    $user_id     ID of the user.
 * @return string Column content.
 */
function id_basica_show_user_column_content( $output, $column_name, $user_id ) {
	switch ( $column_name ) {
		case 'ubicacion':
			$ubicacion = get_user_meta( $user_id, 'sede', true );
			$output    = $ubicacion ? esc_html( $ubicacion ) : '—';
			break;

		case 'puesto':
			$puesto = get_user_meta( $user_id, 'puesto', true );
			$output = $puesto ? esc_html( $puesto ) : '—';
			break;

		case 'departamento':
			$departamento = get_user_meta( $user_id, 'departamento', true );
			$output       = $departamento ? esc_html( $departamento ) : '—';
			break;

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
 * Make custom user columns sortable.
 *
 * Adds sorting functionality to custom user columns in the admin users table.
 *
 * @since 1.0.0
 * @param array $columns Sortable columns array.
 * @return array Modified sortable columns array.
 */
function id_basica_make_user_columns_sortable( $columns ) {
	$columns['ubicacion']      = 'ubicacion';
	$columns['puesto']         = 'puesto';
	$columns['departamento']   = 'departamento';
	$columns['jefe_inmediato'] = 'jefe_inmediato';

	return $columns;
}
add_filter( 'manage_users_sortable_columns', 'id_basica_make_user_columns_sortable' );

/**
 * Handle sorting for custom user columns.
 *
 * Modifies the user query to sort by custom meta fields when requested.
 * Supports sorting by ubicacion, puesto, departamento, and jefe_inmediato.
 *
 * @since 1.0.0
 * @param WP_User_Query $query User query object.
 */
function id_basica_handle_user_column_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby       = $query->get( 'orderby' );
	$custom_fields = array( 'ubicacion', 'puesto', 'departamento', 'jefe_inmediato' );

	if ( in_array( $orderby, $custom_fields, true ) ) {
		$query->set( 'meta_key', $orderby );
		$query->set( 'orderby', 'meta_value' );

		// Special handling for date sorting.
		if ( 'fecha_ingreso' === $orderby ) {
			$query->set( 'orderby', 'meta_value_datetime' );
		}
	}
}
add_action( 'pre_get_users', 'id_basica_handle_user_column_sorting' );

/**
 * Enqueue media uploader scripts for profile picture.
 *
 * Enqueues necessary scripts for the profile picture upload functionality
 * on user profile and edit user pages.
 *
 * @since 1.0.0
 * @param string $hook The current admin page hook.
 */
function id_basica_enqueue_profile_picture_scripts( $hook ) {
	if ( 'profile.php' !== $hook && 'user-edit.php' !== $hook ) {
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

function id_basica_user_edit_page_scripts() {
	?>
	<style>
		#application-passwords-section,
		#e-notes,
		#e-notes+table,
		.user-description-wrap,
		.user-profile-picture,
		table:has(label[for="elementor_enable_ai"]),
		table:has(label[for="elementor_pro_enable_notes_notifications"]),
		#profile-nav {
			display: none !important;
		}

		.user-user-login-wrap #user_login {
			border: none;
			box-shadow: none;
			background: transparent;
			padding: 0;
		}

		.user-url-wrap,
		.user-user-login-wrap .description {
			display: none;
		}

		.select2-container,
		#ure_select_other_roles+.ms-parent,
		#ure_select_other_roles+.ms-parent .ms-drop.bottom,
		#display_name {
			max-width: 25em;
			width: min(100%, 25em);
		}

		#ure_select_other_roles+.ms-parent .ms-choice {
			font-size: 14px;
			height: 28px;
			line-height: 27px;
			padding-right: 23px;
			border-color: #7e8993;
		}

		#ure_select_other_roles+.ms-parent .ms-choice>div.icon-caret {
			background: url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) 0 0 / 16px 16px no-repeat;
			border: 0;
			width: 16px;
			height: 16px;
			margin-left: -11px;
			margin-top: -7px;
		}

		#ure_select_other_roles+.ms-parent .ms-drop.bottom li {
			max-width: 100%;
			max-width: -webkit-fill-available;
		}

		#ure_select_other_roles+.ms-parent .ms-drop.bottom li label {
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			padding-left: 0;
		}

		#ure_select_other_roles+.ms-parent .ms-drop.bottom li label input[type="checkbox"] {
			position: relative;
			margin: 0 0.5em 0 0;
		}

		body.profile-php div#profile-page.wrap form#your-profile,
		body.user-edit-php div#profile-page.wrap form#your-profile {
			padding-top: 0;
		}

		body.profile-php div#profile-page.wrap form#your-profile h3:first-of-type,
		body.user-edit-php div#profile-page.wrap form#your-profile h3:first-of-type {
			margin-top: 1em;
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const headings = document.querySelectorAll('h2, h3');
			headings.forEach(function (heading) {
				if (heading.textContent.includes('About') ||
					heading.textContent.includes('Elementor')) {
					heading.style.display = 'none';
				}
			});

			const tableRows = document.querySelectorAll('.form-table tr');
			tableRows.forEach(function (row) {
				const th = row.querySelector('th');
				if (th && (th.textContent.includes('Elementor'))) {
					row.style.display = 'none';
				}
			});

			const profileNavTabs = document.querySelectorAll('#profile-nav a, #profile-nav li');
			profileNavTabs.forEach(function (tab) {
				if (tab.textContent.includes('Extended Profile')) {
					tab.style.display = 'none';
				}
			});
		});
	</script>
	<?php
}
add_action( 'admin_head-profile.php', 'id_basica_user_edit_page_scripts' );
add_action( 'admin_head-user-edit.php', 'id_basica_user_edit_page_scripts' );

function id_basica_users_page_scripts() {
	?>
	<style>
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

		.wp-list-table .column-jefe_inmediato {
			width: 15%;
		}
	</style>
	<?php
}
add_action( 'admin_head-users.php', 'id_basica_users_page_scripts' );

/**
 * Customize new user page styling and functionality.
 *
 * Adds custom CSS and JavaScript to enhance the new user creation page,
 * including role selector styling and automatic username generation from email.
 *
 * @since 1.0.0
 */
function id_basica_user_new_page_scripts() {
	?>
	<style>
		.acf-field[data-name="curp"] > .acf-input,
		.acf-field[data-name="rfc"] > .acf-input,
		.acf-field[data-name="nss"] > .acf-input,
		.acf-field[data-name="domicilio_personal"] > .acf-input,
		.acf-field[data-name="contacto_de_emergencia"] > .acf-input,
		.acf-field[data-name="contacto_de_emergencia_tel"] > .acf-input {
			text-transform: uppercase;
		}

		.select2-container,
		#ure_select_other_roles+.ms-parent,
		#ure_select_other_roles+.ms-parent .ms-drop.bottom {
			max-width: 25em;
		}

		#ure_select_other_roles+.ms-parent .ms-choice {
			font-size: 14px;
			height: 28px;
			line-height: 27px;
			padding-right: 23px;
			border-color: #7e8993;
		}

		#ure_select_other_roles+.ms-parent .ms-choice>div.icon-caret {
			background: url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) 0 0 / 16px 16px no-repeat;
			border: 0;
			width: 16px;
			height: 16px;
			margin-left: -11px;
			margin-top: -7px;
		}

		#ure_select_other_roles+.ms-parent .ms-drop.bottom li {
			max-width: 100%;
			max-width: -webkit-fill-available;
		}

		#ure_select_other_roles+.ms-parent .ms-drop.bottom li label {
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			padding-left: 0;
		}

		#ure_select_other_roles+.ms-parent .ms-drop.bottom li label input[type="checkbox"] {
			position: relative;
			margin: 0 0.5em 0 0;
		}

		tr.form-field:has(label[for="url"]) {
			display: none;
		}
	</style>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			console.log('Custom user creation script loaded');

			const sendUserNotificationField = document.getElementById('send_user_notification');
			sendUserNotificationField.checked = false;

			const emailField = document.getElementById('email');
			const userLoginField = document.getElementById('user_login');

			emailField.addEventListener('keyup', function () {
				userLoginField.value = emailField.value;
			});
		});
	</script>
	<?php
}
add_action( 'admin_head-user-new.php', 'id_basica_user_new_page_scripts' );

/**
 * Add custom profile picture field to user profile.
 *
 * Displays a custom profile picture upload field on user profile and
 * edit user pages with preview functionality.
 *
 * @since 1.0.0
 * @param WP_User $user The user object being edited.
 */
function id_basica_add_user_profile_picture_field( $user ) {
	$profile_picture = get_user_meta( $user->ID, 'profile_picture', true );
	?>
	<h3><?php esc_html_e( 'Profile Picture', 'id-basica' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="profile_picture"><?php esc_html_e( 'Profile Picture', 'id-basica' ); ?></label></th>
			<td>
				<input type="hidden" id="profile_picture" name="profile_picture" value="<?php echo esc_attr( $profile_picture ); ?>" />
				<div id="profile-picture-preview">
					<?php if ( $profile_picture ) : ?>
						<?php
						$image = wp_get_attachment_image(
							$profile_picture,
							array( 150, 150 ),
							false,
							array( 'style' => 'border-radius: 50%; max-width: 150px; height: auto;' )
						);
						echo $image;
						?>
					<?php else : ?>
						<img src="<?php echo esc_url( get_avatar_url( $user->ID, array( 'size' => 150 ) ) ); ?>" style="border-radius: 50%; max-width: 150px; height: auto;" alt="<?php esc_attr_e( 'Profile Picture', 'id-basica' ); ?>" />
					<?php endif; ?>
				</div>
				<p>
					<button type="button" class="button" id="upload-profile-picture"><?php esc_html_e( 'Upload Picture', 'id-basica' ); ?></button>
					<button type="button" class="button" id="remove-profile-picture" <?php echo $profile_picture ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Remove Picture', 'id-basica' ); ?></button>
				</p>
				<p class="description"><?php esc_html_e( 'Upload a custom profile picture. Recommended size: 150x150 pixels.', 'id-basica' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'id_basica_add_user_profile_picture_field', 5, 1 );
add_action( 'edit_user_profile', 'id_basica_add_user_profile_picture_field', 5, 1 );

/**
 * Save custom profile picture field.
 *
 * Handles saving the custom profile picture field data when
 * user profile is updated.
 *
 * @since 1.0.0
 * @param int $user_id The ID of the user being updated.
 */
function id_basica_save_user_profile_picture_field( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	if ( isset( $_POST['profile_picture'] ) ) {
		update_user_meta( $user_id, 'profile_picture', sanitize_text_field( wp_unslash( $_POST['profile_picture'] ) ) );
	}
}
add_action( 'personal_options_update', 'id_basica_save_user_profile_picture_field' );
add_action( 'edit_user_profile_update', 'id_basica_save_user_profile_picture_field' );

/**
 * Replace default avatar with custom profile picture.
 *
 * Replaces the default WordPress avatar with a custom profile picture
 * if one has been uploaded by the user. Falls back to an empty div
 * if no custom picture is available (removes gravatar).
 *
 * @since 1.0.0
 * @param string $avatar      Image tag for the user's avatar.
 * @param mixed  $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash,
 *                            user email, WP_User object, WP_Post object, or WP_Comment object.
 * @param int    $size        Square avatar width and height in pixels to retrieve.
 * @param string $default     URL for the default image or a default type. Accepts '404', 'retro',
 *                            'monsterid', 'wavatar', 'indenticon', 'mystery', 'mm', 'mysteryman',
 *                            'blank', or 'gravatar_default'.
 * @param string $alt         Alternative text to use in the avatar image tag.
 * @return string Avatar image tag or empty div.
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
					'style' => 'border-radius: 50%;',
				)
			);

			if ( $image ) {
				return $image;
			}
		}

		// Return empty div if no custom profile picture (removes default gravatar).
		return '<div class="avatar avatar-' . (int) $size . '" style="width: ' . $size . 'px; height: ' . $size . 'px; border-radius: 50%; background-color: #f0f0f0; display: inline-block;"></div>';
	}

	return $avatar;
}
add_filter( 'get_avatar', 'id_basica_custom_avatar', 10, 5 );
