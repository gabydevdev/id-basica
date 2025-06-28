jQuery(document).ready(function($) {
    var mediaUploader;

    // Upload profile picture
    $('#upload-profile-picture').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Choose Profile Picture',
            button: {
                text: 'Choose Picture'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            
            $('#profile_picture').val(attachment.id);
            
            var imageHtml = '<img src="' + attachment.url + '" style="border-radius: 50%; max-width: 150px; height: auto;" alt="Profile Picture" />';
            $('#profile-picture-preview').html(imageHtml);
            
            $('#remove-profile-picture').show();
        });

        mediaUploader.open();
    });

    // Remove profile picture
    $('#remove-profile-picture').on('click', function(e) {
        e.preventDefault();
        
        $('#profile_picture').val('');
        
        // Get default gravatar
        var userId = $('input[name="user_id"]').val() || window.location.search.match(/user_id=(\d+)/)?.[1];
        if (userId) {
            // Create a simple hash for the demo - in real implementation you'd get the actual gravatar
            var defaultAvatar = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?s=150&d=mp';
            var imageHtml = '<img src="' + defaultAvatar + '" style="border-radius: 50%; max-width: 150px; height: auto;" alt="Profile Picture" />';
            $('#profile-picture-preview').html(imageHtml);
        }
        
        $(this).hide();
    });
});
