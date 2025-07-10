<?php
$post   = get_post();
$fields = id_basica_get_acf_fields();

ID_BASICA\DEV\console_log( $fields );
ID_BASICA\DEV\console_log( $post );
?>

<div class="invoice__body">
	<div>
		<div>
			Nombre completo
			<?php echo esc_html( $fields['nombre_de_empleado'] ); ?>
		</div>
		<div class="col-sm-4">
			Razón del cambio
			<?php echo implode(', ', $fields['razon_del_cambio_1']); ?>
		</div>
		<div class="col-sm-4">
			Cambio de
			<?php echo implode(', ', $fields['tipo_de_cambio']); ?>
		</div>
		<div class="col-sm-4">
			Tipo de contrato
			<?php echo implode(', ', $fields['tipo_de_contrato']); ?>
		</div>
		<hr>
		<div>
			Puesto
			<?php echo esc_html( $fields['puesto'] ); ?>
		</div>
		<div>
			Departamento
			<?php echo esc_html( $fields['departamento'] ); ?>
		</div>
		<div class="col-3">
			Porcentaje del aumento
			<?php echo esc_html( $fields['porcentaje_de_aumento'] . '%' ); ?>
		</div>
		<div class="col-3">
			A partir de
			<?php echo esc_html( $fields['fecha_del_cambio'] ); ?>
		</div>
	</div>
	<div>
		<div class="col-6">
			Jefe Inmediato
		</div>
		<div class="col-6">
			Director de Administración
		</div>
		<div class="col-6">
			Capital Humano
		</div>
		<div class="col-6">
			Coordinador Fiscal
		</div>
	</div>
</div>
