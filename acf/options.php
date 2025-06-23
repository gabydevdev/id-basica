<?php
/**
 * ACF custom configuration and field registrations
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if ACF is active
if ( ! class_exists( 'ACF' ) ) {
	return;
}

/**
 * Register ACF Options Pages
 */
function id_basica_register_acf_options_pages() {
	// Check if function exists
	if ( function_exists( 'acf_add_options_page' ) ) {
		// Add parent options page
		acf_add_options_page(
			array(
				'page_title' => __( 'Intranet Setup', 'id-basica' ),
				'menu_title' => __( 'Intranet Setup', 'id-basica' ),
				'menu_slug'  => 'intranet-settings',
				'capability' => 'manage_options',
				'redirect'   => true,
				'icon_url'   => 'dashicons-welcome-view-site',
				'position'   => 3,
			)
		);

		// Add sub options pages
		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'General Settings', 'id-basica' ),
				'menu_title'  => __( 'General Settings', 'id-basica' ),
				'parent_slug' => 'intranet-settings',
				'menu_slug'   => 'intranet-general-settings',
			)
		);

		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'Layout Settings', 'id-basica' ),
				'menu_title'  => __( 'Layout Settings', 'id-basica' ),
				'parent_slug' => 'intranet-settings',
				'menu_slug'   => 'intranet-layout-settings',
			)
		);

		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'Widget Settings', 'id-basica' ),
				'menu_title'  => __( 'Widget Settings', 'id-basica' ),
				'parent_slug' => 'intranet-settings',
				'menu_slug'   => 'intranet-widget-settings',
			)
		);
	}
}
add_action( 'acf/init', 'id_basica_register_acf_options_pages' );

/**
 * Register custom ACF fields
 */
function id_basica_register_acf_fields() {
	// Check if function exists
	if ( function_exists( 'acf_add_local_field_group' ) ) {

		// General Settings Fields
		acf_add_local_field_group(
			array(
				'key'      => 'group_intranet_general_settings',
				'title'    => 'General Settings',
				'fields'   => array(
					array(
						'key'           => 'field_intranet_logo',
						'label'         => 'Intranet Logo',
						'name'          => 'intranet_logo',
						'type'          => 'image',
						'instructions'  => 'Upload a logo for the dashboard.',
						'required'      => 0,
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'           => 'field_intranet_title',
						'label'         => 'Intranet Title',
						'name'          => 'intranet_title',
						'type'          => 'text',
						'instructions'  => 'Enter the title for the dashboard.',
						'required'      => 0,
						'default_value' => 'Intranet Dashboard',
					),
					array(
						'key'           => 'field_footer_text',
						'label'         => 'Footer Text',
						'name'          => 'footer_text',
						'type'          => 'text',
						'instructions'  => 'Enter the text that will appear in the footer.',
						'required'      => 0,
						'default_value' => '© ' . date('Y') . ' Company Name. All rights reserved.',
					),
					array(
						'key'           => 'field_primary_color',
						'label'         => 'Primary Color',
						'name'          => 'primary_color',
						'type'          => 'color_picker',
						'instructions'  => 'Select the primary color for the dashboard.',
						'required'      => 0,
						'default_value' => '#dd2222',
					),
					array(
						'key'           => 'field_secondary_color',
						'label'         => 'Secondary Color',
						'name'          => 'secondary_color',
						'type'          => 'color_picker',
						'instructions'  => 'Select the secondary color for the dashboard.',
						'required'      => 0,
						'default_value' => '#020202',
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'intranet-general-settings',
						),
					),
				),
			)
		);

		// Layout Settings Fields
		acf_add_local_field_group(
			array(
				'key'      => 'group_intranet_layout_settings',
				'title'    => 'Layout Settings',
				'fields'   => array(
					array(
						'key'          => 'field_show_welcome_widget',
						'label'        => 'Show Welcome Widget',
						'name'         => 'show_welcome_widget',
						'type'         => 'true_false',
						'instructions' => 'Toggle the welcome widget on the dashboard.',
						'required'     => 0,
						'default_value' => 1,
						'ui'           => 1,
					),
					array(
						'key'          => 'field_welcome_message',
						'label'        => 'Welcome Message',
						'name'         => 'welcome_message',
						'type'         => 'textarea',
						'instructions' => 'Enter the welcome message for the dashboard.',
						'required'     => 0,
						'default_value' => 'Welcome to the Company Intranet Intranet. Here you can find all the resources you need.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_show_welcome_widget',
									'operator' => '==',
									'value'    => 1,
								),
							),
						),
					),
					array(
						'key'          => 'field_intranet_layout',
						'label'        => 'Intranet Layout',
						'name'         => 'intranet_layout',
						'type'         => 'select',
						'instructions' => 'Select the layout for the dashboard.',
						'required'     => 0,
						'choices'      => array(
							'default' => 'Default (Widgets Grid)',
							'columns' => 'Two Columns',
							'masonry' => 'Masonry Grid',
						),
						'default_value' => 'default',
					),
					array(
						'key'          => 'field_sidebar_position',
						'label'        => 'Sidebar Position',
						'name'         => 'sidebar_position',
						'type'         => 'select',
						'instructions' => 'Select the position for the sidebar.',
						'required'     => 0,
						'choices'      => array(
							'left'  => 'Left',
							'right' => 'Right',
						),
						'default_value' => 'left',
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'intranet-layout-settings',
						),
					),
				),
			)
		);

		// Widget Settings Fields
		acf_add_local_field_group(
			array(
				'key'      => 'group_intranet_widget_settings',
				'title'    => 'Widget Settings',
				'fields'   => array(
					array(
						'key'          => 'field_widget_order',
						'label'        => 'Widget Order',
						'name'         => 'widget_order',
						'type'         => 'repeater',
						'instructions' => 'Set the order of widgets on the dashboard.',
						'required'     => 0,
						'min'          => 0,
						'max'          => 0,
						'layout'       => 'table',
						'button_label' => 'Add Widget',
						'sub_fields'   => array(
							array(
								'key'          => 'field_widget_type',
								'label'        => 'Widget Type',
								'name'         => 'widget_type',
								'type'         => 'select',
								'instructions' => '',
								'required'     => 0,
								'choices'      => array(
									'stats'     => 'Quick Stats',
									'activity'  => 'Recent Activity',
									'events'    => 'Upcoming Events',
									'documents' => 'Recent Documents',
									'tasks'     => 'Tasks Overview',
									'custom'    => 'Custom Widget',
								),
								'default_value' => 'stats',
							),
							array(
								'key'          => 'field_widget_title',
								'label'        => 'Widget Title',
								'name'         => 'widget_title',
								'type'         => 'text',
								'instructions' => '',
								'required'     => 0,
							),
							array(
								'key'          => 'field_widget_enabled',
								'label'        => 'Enabled',
								'name'         => 'widget_enabled',
								'type'         => 'true_false',
								'instructions' => '',
								'required'     => 0,
								'default_value' => 1,
								'ui'           => 1,
							),
						),
					),
					array(
						'key'          => 'field_custom_widgets',
						'label'        => 'Custom Widgets',
						'name'         => 'custom_widgets',
						'type'         => 'repeater',
						'instructions' => 'Create custom widgets for the dashboard.',
						'required'     => 0,
						'min'          => 0,
						'max'          => 0,
						'layout'       => 'block',
						'button_label' => 'Add Custom Widget',
						'sub_fields'   => array(
							array(
								'key'          => 'field_custom_widget_title',
								'label'        => 'Widget Title',
								'name'         => 'custom_widget_title',
								'type'         => 'text',
								'instructions' => '',
								'required'     => 1,
							),
							array(
								'key'          => 'field_custom_widget_icon',
								'label'        => 'Widget Icon',
								'name'         => 'custom_widget_icon',
								'type'         => 'text',
								'instructions' => 'Enter a Font Awesome icon class (e.g., fas fa-chart-bar).',
								'required'     => 0,
								'default_value' => 'fas fa-chart-bar',
							),
							array(
								'key'          => 'field_custom_widget_content',
								'label'        => 'Widget Content',
								'name'         => 'custom_widget_content',
								'type'         => 'wysiwyg',
								'instructions' => '',
								'required'     => 0,
								'tabs'         => 'all',
								'toolbar'      => 'full',
								'media_upload' => 1,
							),
						),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'intranet-widget-settings',
						),
					),
				),
			)
		);
	}
}
add_action( 'acf/init', 'id_basica_register_acf_fields' );

// Include custom ACF field groups
require_once IDBASICA_THEME_DIR . '/acf/group-fields.php';
