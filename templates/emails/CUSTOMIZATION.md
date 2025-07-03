# Email Template Customization Guide

## Overview

The email notification system now uses separate PHP template files instead of inline HTML in the functions file. This makes it much easier to customize email designs and maintain them.

## Template Structure

```
templates/emails/
├── base-template.php          # Main template with styling
├── creation.php              # New application
├── jefe-inmediato.php        # Jefe Inmediato signed
├── autorizacion.php          # Director authorized
├── capital-humano.php        # Capital Humano approved
├── coordinador-fiscal.php    # Final completion
├── test.php                  # Simple test template
└── README.md                 # This documentation
```

## How It Works

1. When an email needs to be sent, `id_basica_get_notification_email_template()` is called
2. The function prepares all variables using `id_basica_get_email_template_variables()`
3. Variables are extracted and made available to the template
4. The appropriate template file is included based on the workflow stage
5. Output buffering captures the HTML and returns it as a string

## Available Variables

All templates have access to these variables:

### Core Variables
- `$post_id` - Application post ID
- `$author` - WP_User object (form author)
- `$stage` - Current workflow stage
- `$application_url` - URL to view application
- `$employee_name` - Employee name
- `$date_created` - Application creation date
- `$site_name` - WordPress site name
- `$admin_email` - Admin email address

### Application Fields
- `$puesto_actual` - Current position
- `$puesto_nuevo` - New position
- `$departamento_actual` - Current department
- `$departamento_nuevo` - New department
- `$tipo_movimiento` - Type of movement

### Template-Specific Variables
Each template defines:
- `$title` - Email title
- `$body` - Main message
- `$stage_indicator` - Visual stage indicator
- `$additional_details` - Array of extra details

## Customization Examples

### 1. Modifying an Existing Template

Edit `templates/emails/creation.php`:

```php
<?php
// Define template variables
$title = 'Your Custom Title';
$body = 'Your custom message here.';
$stage_indicator = 'CUSTOM STAGE INDICATOR';

// Add custom details
$additional_details = array(
    '<strong>Custom Field:</strong> ' . get_field('custom_field', $post_id),
    '<strong>Priority:</strong> High',
);

// Include base template
include get_template_directory() . '/templates/emails/base-template.php';
?>
```

### 2. Creating a Custom Template

Create `templates/emails/my-custom.php`:

```php
<?php
$title = 'My Custom Email';
$body = 'This is my custom email template.';

// Include base template or create your own HTML
include get_template_directory() . '/templates/emails/base-template.php';
?>
```

Then update the template map in `acf/functions.php`:

```php
$template_map = array(
    'creation'           => 'creation.php',
    'my_custom_stage'    => 'my-custom.php',
    // ... other templates
);
```

### 3. Using a Completely Custom Design

Create your template without the base template:

```php
<?php
// templates/emails/custom-design.php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html($title); ?></title>
    <style>
        /* Your custom CSS here */
        body { background: #your-color; }
    </style>
</head>
<body>
    <!-- Your custom HTML here -->
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo esc_html($body); ?></p>
</body>
</html>
```

### 4. Adding Dynamic Content

Templates can use any WordPress function:

```php
<?php
// Get custom data
$department_head = get_field('department_head', $post_id);
$priority = get_field('priority_level', $post_id);

$additional_details = array(
    '<strong>Department Head:</strong> ' . esc_html($department_head),
    '<strong>Priority:</strong> ' . esc_html($priority),
    '<strong>Submission Time:</strong> ' . get_the_time('H:i', $post_id),
);

// Include base template
include get_template_directory() . '/templates/emails/base-template.php';
?>
```

## Adding New Variables

To add new variables to all templates, edit the `id_basica_get_email_template_variables()` function in `acf/functions.php`:

```php
function id_basica_get_email_template_variables($post_id, $author, $stage) {
    $variables = array(
        // ... existing variables ...
        'my_custom_field' => get_field('my_custom_field', $post_id),
        'current_time'    => current_time('mysql'),
    );
    
    return apply_filters('id_basica_email_template_variables', $variables, $post_id, $author, $stage);
}
```

## Using Filters

You can modify variables using WordPress filters:

```php
// In your theme's functions.php or plugin
add_filter('id_basica_email_template_variables', function($variables, $post_id, $author, $stage) {
    // Add custom variables
    $variables['company_logo'] = get_theme_mod('company_logo');
    $variables['custom_message'] = get_option('email_custom_message');
    
    return $variables;
}, 10, 4);
```

## Testing Templates

1. Use the `test.php` template for quick testing
2. Temporarily change the template map to use test.php
3. Create a test application and trigger notifications
4. Check email output in your email client

## Best Practices

1. **Always escape output**: Use `esc_html()`, `esc_url()`, etc.
2. **Check for empty values**: Use conditional checks
3. **Keep templates focused**: One template per stage
4. **Use the base template**: For consistent styling
5. **Test thoroughly**: Check in multiple email clients
6. **Document changes**: Add comments explaining customizations

## Troubleshooting

### Template Not Loading
- Check file path and permissions
- Verify template map in functions.php
- Look for PHP syntax errors

### Variables Not Available
- Check `id_basica_get_email_template_variables()` function
- Verify variable names (case sensitive)
- Use `extract()` if needed

### Styling Issues
- Use inline CSS for email compatibility
- Test in multiple email clients
- Avoid complex CSS features
