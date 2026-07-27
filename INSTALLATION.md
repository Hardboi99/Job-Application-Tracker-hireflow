# Installation Guide

Follow these steps to install and configure HireFlow on your WordPress site.

## Prerequisites

- A local environment like LocalWP or XAMPP, or a live server.
- WordPress 6.0+ installed.

## Step-by-Step Installation

1. **Download:** Get the latest release of HireFlow.
2. **Install Theme:** Copy the `hireflow-theme` folder to `wp-content/themes/`.
3. **Install Plugin:** Copy the `hireflow-manager` folder to `wp-content/plugins/`.
4. **Activate Theme:** Go to Appearance > Themes and activate "HireFlow Theme".
5. **Activate Plugin:** Go to Plugins and activate "HireFlow Manager".
6. **Create Pages:**
   - Create a page named "Dashboard" and assign the "HireFlow Dashboard" template.
   - Create a page named "My Applications" and assign the "My Applications" template.
   - Create a page named "Add Application" and assign the "Add Application" template.
7. **Set Front Page:** Go to Settings > Reading and set your homepage to a static page (e.g., Dashboard).
8. **Navigation:** Go to Appearance > Menus and create a menu linking to these pages. Assign it to the "Primary Menu" location.
9. **Settings:** Navigate to HireFlow > Settings in the admin dashboard to configure default statuses and other preferences.

## Troubleshooting

- **404 Errors on Applications:** Go to Settings > Permalinks and click "Save Changes" to flush rewrite rules.
- **Styles Not Loading:** Ensure file permissions are correct and clear any caching plugins.
