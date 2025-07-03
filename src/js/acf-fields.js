(function ($) {

	if (typeof acf.addAction !== 'undefined') {

		acf.addAction('load', function () {

			var postID = acf.get('post_id');
			console.log('Post ID:', postID);

			if (postID === 'new_post') {
				console.log('This is a new post, not yet saved.');

				var signatureFields = acf.getFields({
					type: 'signature',
				});
				console.log('Fields of type signature:', signatureFields);

				// Loop through each signature field and disable it
				signatureFields.forEach(function (field) {
					console.log('Disabling signature field:', field);
					idBasicaSignature.disable(field.$el);
				});
			} else {
				console.log('This is an existing post.');
				
				var currentUserId = id_basica_acf_ajax_object.current_user_id;
				var userIdField = acf.getField('user_id');
				
				// If current user ID matches user_id field, keep all signature fields disabled
				if (userIdField && userIdField.val() == currentUserId) {
					console.log('Current user matches user_id field, keeping all signature fields disabled');
					var signatureFields = acf.getFields({
						type: 'signature',
					});
					signatureFields.forEach(function (field) {
						idBasicaSignature.disable(field.$el);
					});
				} else {
					// Check jefe_inmediato_id and enable firma_de_jefe_inmediato if match
					var jefeInmediatoId = acf.getField('jefe_inmediato_id');
					if (jefeInmediatoId && jefeInmediatoId.val() == currentUserId) {
						var firmaJefeInmediato = acf.getField('firma_de_jefe_inmediato');
						if (firmaJefeInmediato) {
							idBasicaSignature.enable(firmaJefeInmediato.$el);
							console.log('Enabled firma_de_jefe_inmediato for current user');
						}
					}
					
					// Check direccion_de_administracion_id and enable firma_de_autorizacion if match
					var direccionAdminId = acf.getField('direccion_de_administracion_id');
					if (direccionAdminId && direccionAdminId.val() == currentUserId) {
						var firmaAutorizacion = acf.getField('firma_de_autorizacion');
						if (firmaAutorizacion) {
							idBasicaSignature.enable(firmaAutorizacion.$el);
							console.log('Enabled firma_de_autorizacion for current user');
						}
					}
					
					// Check capital_humano_id and enable firma_de_capital_humano if match
					var capitalHumanoId = acf.getField('capital_humano_id');
					if (capitalHumanoId && capitalHumanoId.val() == currentUserId) {
						var firmaCapitalHumano = acf.getField('firma_de_capital_humano');
						if (firmaCapitalHumano) {
							idBasicaSignature.enable(firmaCapitalHumano.$el);
							console.log('Enabled firma_de_capital_humano for current user');
						}
					}
					
					// Check coordinador_fiscal_id and enable firma_de_coordinador_fiscal if match
					var coordinadorFiscalId = acf.getField('coordinador_fiscal_id');
					if (coordinadorFiscalId && coordinadorFiscalId.val() == currentUserId) {
						var firmaCoordinadorFiscal = acf.getField('firma_de_coordinador_fiscal');
						if (firmaCoordinadorFiscal) {
							idBasicaSignature.enable(firmaCoordinadorFiscal.$el);
							console.log('Enabled firma_de_coordinador_fiscal for current user');
						}
					}
				}
			}
		});

	}
})(jQuery);
