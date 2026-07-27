<?php
/*
 * Plugin settings page
 */
defined('ABSPATH') || exit;

$opts = get_option('hireflow_settings', []);
?>

<div class="wrap hf-admin-wrap">
    <h1>HireFlow Settings</h1>

    <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper hf-nav-tabs">
        <a href="#general" class="nav-tab nav-tab-active">General</a>
        <a href="#email" class="nav-tab">Email</a>
        <a href="#files" class="nav-tab">Files</a>
        <a href="#advanced" class="nav-tab">Advanced</a>
    </h2>

    <form method="post" action="options.php" class="hf-settings-form">
        <?php settings_fields('hireflow_settings_group'); ?>

        <div id="general" class="hf-tab-content active">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="hf_default_status">Default Status</label></th>
                    <td>
                        <select id="hf_default_status" name="hireflow_settings[default_status]">
                            <option value="applied" <?php selected($opts['default_status'] ?? '', 'applied'); ?>>Applied</option>
                            <option value="interview" <?php selected($opts['default_status'] ?? '', 'interview'); ?>>Interview</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hf_apps_per_page">Apps Per Page</label></th>
                    <td>
                        <input type="number" id="hf_apps_per_page" name="hireflow_settings[apps_per_page]" value="<?php echo esc_attr($opts['apps_per_page'] ?? '10'); ?>" min="1" max="100">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Guest Access</th>
                    <td>
                        <label>
                            <input type="checkbox" name="hireflow_settings[allow_guest]" value="1" <?php checked($opts['allow_guest'] ?? '', '1'); ?>>
                            Allow guests to view public apps
                        </label>
                    </td>
                </tr>
            </table>
        </div>

        <div id="email" class="hf-tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">Notifications</th>
                    <td>
                        <label>
                            <input type="checkbox" name="hireflow_settings[enable_email]" value="1" <?php checked($opts['enable_email'] ?? '', '1'); ?>>
                            Send emails on status change
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hf_admin_email">Admin Email</label></th>
                    <td>
                        <input type="email" id="hf_admin_email" name="hireflow_settings[admin_email]" value="<?php echo esc_attr($opts['admin_email'] ?? get_option('admin_email')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hf_email_subject">Subject Template</label></th>
                    <td>
                        <input type="text" id="hf_email_subject" name="hireflow_settings[email_subject]" value="<?php echo esc_attr($opts['email_subject'] ?? 'App Update: {company}'); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Test Email</th>
                    <td>
                        <button type="button" class="button" id="hf-test-email">Send Test</button>
                        <span class="spinner" id="hf-test-email-spinner"></span>
                    </td>
                </tr>
            </table>
        </div>

        <div id="files" class="hf-tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="hf_max_size">Max Resume Size</label></th>
                    <td>
                        <select id="hf_max_size" name="hireflow_settings[max_file_size]">
                            <option value="2" <?php selected($opts['max_file_size'] ?? '', '2'); ?>>2 MB</option>
                            <option value="5" <?php selected($opts['max_file_size'] ?? '', '5'); ?>>5 MB</option>
                            <option value="10" <?php selected($opts['max_file_size'] ?? '', '10'); ?>>10 MB</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Allowed Types</th>
                    <td>
                        <?php $types = $opts['allowed_types'] ?? ['pdf', 'doc']; // pdf/doc by default ?>
                        <label><input type="checkbox" name="hireflow_settings[allowed_types][]" value="pdf" <?php echo in_array('pdf', $types) ? 'checked' : ''; ?>> PDF</label><br>
                        <label><input type="checkbox" name="hireflow_settings[allowed_types][]" value="doc" <?php echo in_array('doc', $types) ? 'checked' : ''; ?>> DOC</label><br>
                        <label><input type="checkbox" name="hireflow_settings[allowed_types][]" value="docx" <?php echo in_array('docx', $types) ? 'checked' : ''; ?>> DOCX</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Upload Dir</th>
                    <td>
                        <code><?php $dir = wp_upload_dir(); echo esc_html($dir['basedir'] . '/hireflow'); ?></code>
                    </td>
                </tr>
            </table>
        </div>

        <div id="advanced" class="hf-tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">Debug Mode</th>
                    <td>
                        <label>
                            <input type="checkbox" name="hireflow_settings[debug_mode]" value="1" <?php checked($opts['debug_mode'] ?? '', '1'); ?>>
                            Enable logging
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Danger Zone</th>
                    <td>
                        <button type="button" class="button button-secondary hf-confirm-action" data-action="reset">Reset</button>
                        <button type="button" class="button button-danger hf-confirm-action" data-action="delete" style="color: #dc3232; border-color: #dc3232;">Delete Data</button>
                    </td>
                </tr>
            </table>
        </div>

        <?php submit_button(); ?>
    </form>
</div>
