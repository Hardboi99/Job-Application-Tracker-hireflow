# Plugin Documentation

## Overview
The `hireflow-manager` plugin handles all data architecture for the HireFlow system, including Custom Post Types, Taxonomies, Meta Boxes, and REST API endpoints.

## Architecture
- Main class: `HireFlow_Manager` (Singleton)
- Hooks are registered in the `run()` method.

## Action Hooks

### `hireflow_after_application_save`
Fires after an application is saved or updated.
- **Parameters:** `$post_id` (int), `$post` (WP_Post), `$update` (bool)
- **Example:**
```php
add_action('hireflow_after_application_save', function($post_id, $post, $update) {
    // Custom logic here
}, 10, 3);
```

## Filter Hooks

### `hireflow_application_statuses`
Filters the default application statuses.
- **Parameters:** `$statuses` (array)
- **Example:**
```php
add_filter('hireflow_application_statuses', function($statuses) {
    $statuses['on_hold'] = 'On Hold';
    return $statuses;
});
```

## Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[hireflow_dashboard]` | Renders the user dashboard view. |
| `[hireflow_applications]` | Renders a table of user applications. |

## REST API
- **Endpoint:** `/wp-json/hireflow/v1/applications`
- **Method:** GET
- **Response:** JSON array of application data.
