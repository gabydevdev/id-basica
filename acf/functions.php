<?php

add_filter(
	'acfe/form/load_form/form=movimiento-de-personal',
	function () {

		$solicitud_id = (int) $_GET['solicitud_id'];

		$form['solicitud_id'] = $solicitud_id;

		return $form;
	}
);
