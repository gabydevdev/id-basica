# Email Template System for Movimiento de Personal

This directory contains the email templates used for notifications in the Movimiento de Personal workflow.

## Structure

```
templates/emails/
├── base-template.php          # Base HTML template with styling
├── creation.php              # New application created
├── jefe-inmediato.php        # Jefe Inmediato signed
├── autorizacion.php          # Director de Administración authorized
├── capital-humano.php        # Capital Humano approved
└── coordinador-fiscal.php    # Coordinador Fiscal completed
```

## Available Variables

All template files have access to these variables:

### Required Variables
- `$post_id` - The application post ID
- `$author` - WP_User object of the form author
- `$application_url` - URL to view the application
- `$employee_name` - Name of the employee
- `$date_created` - Date the application was created
- `$stage` - Current workflow stage

### Template-specific Variables
Each template can define:
- `$title` - Email subject/title
- `$body` - Main email content
- `$stage_indicator` - Visual indicator of current stage
- `$additional_details` - Array of additional details to display

## Customization

### Modifying Templates
1. Edit the specific template file for the stage you want to modify
2. Templates are standard PHP files - you can use any PHP/HTML
3. All variables are automatically escaped when displayed
4. Changes take effect immediately (no cache clearing needed)

### Adding New Templates
1. Create a new PHP file in this directory
2. Define the required variables (`$title`, `$body`, etc.)
3. Include the base template: `include get_template_directory() . '/templates/emails/base-template.php';`
4. Update the `$template_map` array in `acf/functions.php`

### Styling
- Main styles are in `base-template.php`
- Inline styles are used for maximum email client compatibility
- Mobile-responsive design included
- Colors match the ID Básica theme

## Testing

To test email templates:
1. Create a test application
2. Progress through the workflow stages
3. Check email output in your email client
4. Enable `WP_DEBUG` to see email sending logs

## Fallback System

If a template file is missing or corrupted, the system automatically falls back to a simple HTML template to ensure emails are always sent.
