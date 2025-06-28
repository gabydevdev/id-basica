jQuery(document).ready(function($) {
    'use strict';

    // Handle quick edit link click
    $(document).on('click', '.editinline', function(e) {
        e.preventDefault();
        
        var userId = $(this).data('user-id');
        var userRow = $(this).closest('tr');
        
        // Hide all other inline edit rows
        $('.inline-edit-row').hide();
        userRow.siblings().show();
        
        // Clone the inline edit template
        var inlineEditRow = $('#inline-edit').clone(true);
        inlineEditRow.attr('id', 'edit-' + userId);
        
        // Populate fields with current values
        populateQuickEditForm(inlineEditRow, userRow, userId);
        
        // Insert the inline edit row after the current row
        userRow.hide().after(inlineEditRow);
        inlineEditRow.show();
        
        // Focus on first input
        inlineEditRow.find('input[name="sede"]').focus();
    });

    // Handle cancel button
    $(document).on('click', '.inline-edit-row .cancel', function(e) {
        e.preventDefault();
        cancelQuickEdit($(this));
    });

    // Handle save button
    $(document).on('click', '.inline-edit-row .save', function(e) {
        e.preventDefault();
        saveQuickEdit($(this));
    });

    // Handle escape key
    $(document).on('keydown', '.inline-edit-row', function(e) {
        if (e.keyCode === 27) { // Escape key
            cancelQuickEdit($(this).find('.cancel'));
        }
    });

    /**
     * Populate quick edit form with current user data
     */
    function populateQuickEditForm(inlineEditRow, userRow, userId) {
        // Set user ID
        inlineEditRow.find('input[name="user_id"]').val(userId);
        
        // Get current values from the row
        var sede = userRow.find('.column-ubicacion').text().trim();
        var puesto = userRow.find('.column-puesto').text().trim();
        var departamento = userRow.find('.column-departamento').text().trim();
        var fechaIngreso = userRow.find('.column-fecha_ingreso').text().trim();
        var jefeInmediato = userRow.find('.column-jefe_inmediato').text().trim();
        
        // Populate form fields
        if (sede !== '—') {
            inlineEditRow.find('input[name="sede"]').val(sede);
        }
        if (puesto !== '—') {
            inlineEditRow.find('input[name="puesto"]').val(puesto);
        }
        if (departamento !== '—') {
            inlineEditRow.find('input[name="departamento"]').val(departamento);
        }
        if (fechaIngreso !== '—') {
            // Convert from d/m/Y to Y-m-d for date input
            var dateParts = fechaIngreso.split('/');
            if (dateParts.length === 3) {
                var formattedDate = dateParts[2] + '-' + dateParts[1].padStart(2, '0') + '-' + dateParts[0].padStart(2, '0');
                inlineEditRow.find('input[name="fecha_de_ingreso"]').val(formattedDate);
            }
        }
        if (jefeInmediato !== '—') {
            // Find and select the boss option
            inlineEditRow.find('select[name="jefe_inmediato"] option').each(function() {
                if ($(this).text() === jefeInmediato) {
                    $(this).prop('selected', true);
                    return false;
                }
            });
        }
    }

    /**
     * Cancel quick edit
     */
    function cancelQuickEdit(cancelButton) {
        var inlineEditRow = cancelButton.closest('.inline-edit-row');
        var userRow = inlineEditRow.prev();
        
        inlineEditRow.remove();
        userRow.show();
    }

    /**
     * Save quick edit
     */
    function saveQuickEdit(saveButton) {
        var inlineEditRow = saveButton.closest('.inline-edit-row');
        var userRow = inlineEditRow.prev();
        var spinner = inlineEditRow.find('.spinner');
        var errorDiv = inlineEditRow.find('.notice-error');
        
        // Show spinner
        spinner.addClass('is-active');
        saveButton.prop('disabled', true);
        
        // Hide any previous errors
        errorDiv.addClass('hidden');
        
        // Prepare data
        var formData = {
            action: 'inline_save_user',
            user_id: inlineEditRow.find('input[name="user_id"]').val(),
            sede: inlineEditRow.find('input[name="sede"]').val(),
            puesto: inlineEditRow.find('input[name="puesto"]').val(),
            departamento: inlineEditRow.find('input[name="departamento"]').val(),
            fecha_de_ingreso: inlineEditRow.find('input[name="fecha_de_ingreso"]').val(),
            jefe_inmediato: inlineEditRow.find('select[name="jefe_inmediato"]').val(),
            user_quick_edit_nonce: inlineEditRow.find('input[name="user_quick_edit_nonce"]').val()
        };
        
        // Make AJAX request
        $.ajax({
            url: userQuickEdit.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                // Replace the user row with updated content
                var newRow = $(response);
                userRow.replaceWith(newRow);
                inlineEditRow.remove();
                
                // Show success message briefly
                showNotice('User updated successfully!', 'success');
            },
            error: function(xhr, status, error) {
                var errorMessage = 'An error occurred while updating the user.';
                if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                
                errorDiv.find('.error').text(errorMessage);
                errorDiv.removeClass('hidden');
            },
            complete: function() {
                spinner.removeClass('is-active');
                saveButton.prop('disabled', false);
            }
        });
    }

    /**
     * Show temporary notice
     */
    function showNotice(message, type) {
        var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        var notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
        
        $('.wrap h1').after(notice);
        
        setTimeout(function() {
            notice.fadeOut(function() {
                notice.remove();
            });
        }, 3000);
    }
});