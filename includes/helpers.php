<?php
/**
 * Helper functions for the IDBasica theme
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gets the user's display name or username if display name is not set
 *
 * @return string User's name
 */
function idbasica_get_user_name() {
	$current_user = wp_get_current_user();

	if ( ! $current_user->exists() ) {
		return '';
	}

	if ( ! empty( $current_user->display_name ) ) {
		return $current_user->display_name;
	}

	return $current_user->user_login;
}

/**
 * Gets the user's avatar
 *
 * @param int    $size Avatar size in pixels
 * @param string $default Default avatar URL
 * @return string Avatar HTML
 */
function idbasica_get_user_avatar( $size = 40, $default = '' ) {
	$current_user = wp_get_current_user();

	if ( ! $current_user->exists() ) {
		return '';
	}

	return get_avatar( $current_user->ID, $size, $default );
}

/**
 * Check if the current user has the specified capability
 *
 * @param string $capability Capability to check
 * @return bool True if user has capability, false otherwise
 */
function idbasica_user_can( $capability ) {
	$current_user = wp_get_current_user();

	if ( ! $current_user->exists() ) {
		return false;
	}

	return current_user_can( $capability );
}

/**
 * Generate dashboard navigation menu
 *
 * @return string HTML markup for the dashboard navigation
 */
function idbasica_dashboard_menu() {
	// Check if menu exists
	if ( has_nav_menu( 'dashboard-menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'dashboard-menu',
				'menu_class'     => 'sidebar__menu',
				'container'      => false,
				'fallback_cb'    => 'idbasica_default_menu',
				'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			)
		);
	} else {
		// If no menu is set, display default menu
		idbasica_default_menu();
	}
}

/**
 * Default dashboard menu items when no menu is set
 */
function idbasica_default_menu() {
	?>
	<ul class="sidebar__menu">
		<li>
			<a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Dashboard', 'idbasica' ); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Profile', 'idbasica' ); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/documents/' ) ); ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Documents', 'idbasica' ); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/messages/' ) ); ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Messages', 'idbasica' ); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/calendar/' ) ); ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Calendar', 'idbasica' ); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/settings/' ) ); ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Settings', 'idbasica' ); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
				<span class="sidebar__menu-text"><?php esc_html_e( 'Logout', 'idbasica' ); ?></span>
			</a>
		</li>
	</ul>
	<?php
}

/**
 * Generate user menu for the dashboard header
 */
function idbasica_user_menu() {
	// Get current user info
	$current_user = wp_get_current_user();

	if ( ! $current_user->exists() ) {
		return;
	}

	?>
	<div class="user-menu">
		<a href="#" class="user-menu-toggle">
			<?php echo idbasica_get_user_avatar(); ?>
			<span class="user-name"><?php echo esc_html( idbasica_get_user_name() ); ?></span>
			<i class="fa fa-chevron-down"></i>
		</a>
		<div class="user-menu-dropdown">
			<ul>
				<li>
					<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>">
						<i class="fa fa-user"></i> <?php esc_html_e( 'Profile', 'idbasica' ); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( home_url( '/settings/' ) ); ?>">
						<i class="fa fa-cog"></i> <?php esc_html_e( 'Settings', 'idbasica' ); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
						<i class="fa fa-sign-out-alt"></i> <?php esc_html_e( 'Logout', 'idbasica' ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Check if user is on the dashboard
 *
 * @return bool True if on dashboard, false otherwise
 */
function idbasica_is_dashboard() {
	global $post;

	if ( ! $post ) {
		return false;
	}

	// Check if current page has dashboard in the slug or title
	return strpos( $post->post_name, 'dashboard' ) !== false ||
		   strpos( $post->post_title, 'Dashboard' ) !== false ||
		   has_shortcode( $post->post_content, 'idbasica_dashboard' );
}

/**
 * Get current page title
 *
 * @return string Page title
 */
function idbasica_get_page_title() {
	global $post;

	if ( ! $post ) {
		return '';
	}

	return get_the_title( $post->ID );
}

/**
 * Display a dashboard card
 *
 * @param string $title Card title
 * @param string $content Card content
 * @param array  $args Additional arguments
 */
function idbasica_card( $title, $content, $args = array() ) {
	// Default arguments
	$defaults = array(
		'footer'      => '',
		'class'       => '',
		'collapsible' => false,
		'icon'        => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$card_class = 'card';

	if ( ! empty( $args['class'] ) ) {
		$card_class .= ' ' . $args['class'];
	}

	if ( $args['collapsible'] ) {
		$card_class .= ' card--collapsible';
	}

	?>
	<div class="<?php echo esc_attr( $card_class ); ?>">
		<div class="card__header">
			<h3 class="card__title">
				<?php if ( ! empty( $args['icon'] ) ) : ?>
					<i class="<?php echo esc_attr( $args['icon'] ); ?> mr-2"></i>
				<?php endif; ?>
				<?php echo esc_html( $title ); ?>
			</h3>

			<?php if ( $args['collapsible'] ) : ?>
				<button class="card-toggle">
					<i class="fas fa-chevron-down"></i>
				</button>
			<?php endif; ?>
		</div>

		<div class="card__content">
			<?php echo wp_kses_post( $content ); ?>
		</div>

		<?php if ( ! empty( $args['footer'] ) ) : ?>
			<div class="card__footer">
				<?php echo wp_kses_post( $args['footer'] ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
