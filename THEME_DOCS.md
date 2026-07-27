# Theme Documentation

## Overview
The `hireflow-theme` provides the modern, dark-mode user interface for tracking applications. It relies on the `hireflow-manager` plugin for data.

## Design System
- **Primary:** `#0D1B2A` (Dark Navy)
- **Secondary:** `#1E3A5F`
- **Accent:** `#00C9A7` (Teal)
- **Background:** `#06101A`
- **Card Background:** `#112236`
- **Text:** `#E8F0FE`

## Template Hierarchy
- `front-page.php`: Homepage layout.
- `page-dashboard.php`: Dashboard template.
- `page-my-applications.php`: List view.
- `single-hireflow_application.php`: Single application details.

## Customization
To change the theme colors, override the CSS variables in `style.css`:
```css
:root {
    --hf-primary: #123456; /* Your custom color */
}
```

## JavaScript
- `main.js`: Handles modal interactions, form submissions via AJAX, and dynamic UI updates.
- Chart.js is integrated for rendering statistics on the dashboard.

## Child Themes
It is highly recommended to use a child theme if you plan to make extensive modifications to the template files or functions.
