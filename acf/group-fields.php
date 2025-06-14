<?php
/**
 * ACF field group for Movimiento de personal (Personal Movement)
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register ACF field group for 'application' post type
add_action( 'acf/init', function() {
	if ( function_exists( 'acf_add_local_field_group' ) ) {
		acf_add_local_field_group( array(
			'key' => 'group_movimiento_personal',
			'title' => 'Movimiento de personal',
			'fields' => array(
				array(
					'key' => 'field_fecha',
					'label' => 'Fecha',
					'name' => 'fecha',
					'type' => 'date_picker',
				),
				array(
					'key' => 'field_nombre',
					'label' => 'Nombre',
					'name' => 'nombre',
					'type' => 'text',
				),
				array(
					'key' => 'field_razon_cambio',
					'label' => 'Razón del cambio',
					'name' => 'razon_cambio',
					'type' => 'checkbox',
					'choices' => array(
						'Promoción' => 'Promoción',
						'Ajuste' => 'Ajuste',
						'Modificación de contrato' => 'Modificación de contrato',
						'Transferencia' => 'Transferencia',
						'Otro (especifique)' => 'Otro (especifique)',
					),
				),
				array(
					'key' => 'field_cambio_de',
					'label' => 'Cambio de',
					'name' => 'cambio_de',
					'type' => 'checkbox',
					'choices' => array(
						'Sueldo' => 'Sueldo',
						'Puesto' => 'Puesto',
						'Horario' => 'Horario',
						'No aplica' => 'No aplica',
					),
				),
				array(
					'key' => 'field_tipo_contrato',
					'label' => 'Tipo de contrato',
					'name' => 'tipo_contrato',
					'type' => 'checkbox',
					'choices' => array(
						'Obra determinada' => 'Obra determinada',
						'Tiempo determinado' => 'Tiempo determinado',
						'Tiempo indeterminado' => 'Tiempo indeterminado',
						'Honorarios' => 'Honorarios',
					),
				),
				array(
					'key' => 'field_condiciones_actuales',
					'label' => 'Condiciones actuales',
					'name' => 'condiciones_actuales',
					'type' => 'group',
					'sub_fields' => array(
						array(
							'key' => 'field_condiciones_actuales_puesto',
							'label' => 'Puesto',
							'name' => 'puesto',
							'type' => 'select',
							'choices' => array(), // Add choices as needed
						),
						array(
							'key' => 'field_condiciones_actuales_departamento',
							'label' => 'Departamento',
							'name' => 'departamento',
							'type' => 'text',
						),
					),
				),
				array(
					'key' => 'field_condiciones_nuevas',
					'label' => 'Condiciones nuevas',
					'name' => 'condiciones_nuevas',
					'type' => 'group',
					'sub_fields' => array(
						array(
							'key' => 'field_condiciones_nuevas_puesto',
							'label' => 'Puesto',
							'name' => 'puesto',
							'type' => 'select',
							'choices' => array(), // Add choices as needed
						),
						array(
							'key' => 'field_condiciones_nuevas_departamento',
							'label' => 'Departamento',
							'name' => 'departamento',
							'type' => 'text',
						),
						array(
							'key' => 'field_condiciones_nuevas_razon',
							'label' => 'Razón del cambio',
							'name' => 'razon',
							'type' => 'text',
						),
						array(
							'key' => 'field_condiciones_nuevas_porcentaje_aumento',
							'label' => 'Porcentaje de aumento',
							'name' => 'porcentaje_aumento',
							'type' => 'number',
						),
						array(
							'key' => 'field_condiciones_nuevas_a_partir_de',
							'label' => 'A partir de',
							'name' => 'a_partir_de',
							'type' => 'date_picker',
						),
					),
				),
				array(
					'key' => 'field_firma_jefe',
					'label' => 'Firma Jefe Inmediato',
					'name' => 'firma_jefe',
					'type' => 'text',
				),
				array(
					'key' => 'field_firma_ch',
					'label' => 'Firma Capital Humano',
					'name' => 'firma_ch',
					'type' => 'text',
				),
				array(
					'key' => 'field_firma_direccion',
					'label' => 'Firma Dirección',
					'name' => 'firma_direccion',
					'type' => 'text',
				),
				array(
					'key' => 'field_firma_recibe',
					'label' => 'Firma Recibe',
					'name' => 'firma_recibe',
					'type' => 'text',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'application',
					),
				),
			),
		) );
	}
});
