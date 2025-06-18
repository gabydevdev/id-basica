/**
 * Admin JavaScript for ID Basica
 *
 * This file contains JavaScript to customize the WordPress admin interface
 */

(function($) {
    'use strict';

    // Run when the DOM is ready
    $(document).ready(function() {
        // Remove any dynamically added comment elements that might appear
        $('.comment-count, .comments-menu, #latest-comments, .misc-pub-section.misc-pub-comments').remove();

        // Hide comments column in list tables if it exists
        $('.fixed .column-comments, .fixed .comments').remove();

        // Hide discussion settings if they appear
        $('#discussion-settings, #commentsdiv, #commentstatusdiv').remove();

        // Remove comment counts from the admin menu
        $('#adminmenu span.awaiting-mod, #adminmenu .update-plugins').each(function() {
            $(this).remove();
        });

        // Hide Discussion from Settings menu
        $('#menu-settings a[href="options-discussion.php"]').parent().remove();

        // Temporarily hide post-related elements
        $('#menu-posts, #wp-admin-bar-new-post').remove();

        // Hide Posts from At a Glance widget
        $('#dashboard_right_now .post-count').remove();

        // Hide Quick Draft widget
        $('#dashboard_quick_press').remove();

        // Hide Posts from activity widget
        $('#dashboard_activity li.publish-post').remove();

        // Hide Posts related quick actions
        $('.welcome-panel-column ul li a[href*="post-new.php"]').not('[href*="post_type="]').parent().remove();
    });

})(jQuery);
