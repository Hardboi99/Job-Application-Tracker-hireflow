<?php
// WP Settings API wrapper
defined('ABSPATH') || exit;

class HF_Settings {

	public function __construct() {
		add_action('admin_menu', [$this, 'add_settings_page']);
		add_action('admin_init', [$this, 'register_settings']);
	}

	public function add_settings_page() {
		add_submenu_page(
			'hireflow',
			'HireFlow Settings',
			'Settings',
			'manage_options',
			'hireflow-settings',
			[$this, 'settings_page_html']
		);
	}

	public function register_settings() {
		register_setting('hf_settings_group', 'hf_settings', ['sanitize_callback' => ['HF_Security', 'sanitize_settings']]);

		// General
		add_settings_section('hf_general_section', 'General Settings', null, 'hireflow-settings');
		add_settings_field('default_status', 'Default Status', [$this, 'field_default_status'], 'hireflow-settings', 'hf_general_section');
		add_settings_field('apps_per_page', 'Applications Per Page', [$this, 'field_apps_per_page'], 'hireflow-settings', 'hf_general_section');
		add_settings_field('allow_guest_view', 'Allow Guest View', [$this, 'field_allow_guest_view'], 'hireflow-settings', 'hf_general_section');
		
		// Email
		add_settings_section('hf_email_section', 'Email Settings', null, 'hireflow-settings');
		add_settings_field('send_emails', 'Send Notifications', [$this, 'field_send_emails'], 'hireflow-settings', 'hf_email_section');
		add_settings_field('admin_email', 'Admin Email', [$this, 'field_admin_email'], 'hireflow-settings', 'hf_email_section');

		// Advanced
		add_settings_section('hf_advanced_section', 'Advanced Settings', null, 'hireflow-settings');
		add_settings_field('primary_color', 'Primary Color', [$this, 'field_primary_color'], 'hireflow-settings', 'hf_advanced_section');
		add_settings_field('max_upload_size', 'Resume Max File Size', [$this, 'field_max_upload_size'], 'hireflow-settings', 'hf_advanced_section');
	}

	public function field_default_status() {
		$settings = get_option('hf_settings');
		$val = $settings['default_status'] ?? 'applied';
		echo '<select name="hf_settings[default_status]">';
		echo '<option value="applied" ' . selected($val, 'applied', false) . '>Applied</option>';
		echo '<option value="interview_scheduled" ' . selected($val, 'interview_scheduled', false) . '>Interview Scheduled</option>';
		echo '</select>';
	}

	public function field_apps_per_page() {
		$settings = get_option('hf_settings');
		$val = $settings['apps_per_page'] ?? 10;
		echo '<input type="number" name="hf_settings[apps_per_page]" value="' . esc_attr($val) . '" min="1" max="50">';
	}

	public function field_allow_guest_view() {
		$settings = get_option('hf_settings');
		$val = $settings['allow_guest_view'] ?? 0;
		echo '<input type="checkbox" name="hf_settings[allow_guest_view]" value="1" ' . checked(1, $val, false) . '>';
	}

	public function field_send_emails() {
		$settings = get_option('hf_settings');
		$val = $settings['send_emails'] ?? 1;
		echo '<input type="checkbox" name="hf_settings[send_emails]" value="1" ' . checked(1, $val, false) . '>';
	}

	public function field_admin_email() {
		$settings = get_option('hf_settings');
		$val = $settings['admin_email'] ?? get_option('admin_email');
		echo '<input type="email" name="hf_settings[admin_email]" value="' . esc_attr($val) . '">';
	}

	public function field_primary_color() {
		$settings = get_option('hf_settings');
		$val = $settings['primary_color'] ?? '#00C9A7';
		echo '<input type="color" name="hf_settings[primary_color]" value="' . esc_attr($val) . '">';
	}

	public function field_max_upload_size() {
		$settings = get_option('hf_settings');
		$val = $settings['max_upload_size'] ?? '5MB';
		echo '<select name="hf_settings[max_upload_size]">';
		echo '<option value="2MB" ' . selected($val, '2MB', false) . '>2MB</option>';
		echo '<option value="5MB" ' . selected($val, '5MB', false) . '>5MB</option>';
		echo '<option value="10MB" ' . selected($val, '10MB', false) . '>10MB</option>';
		echo '</select>';
	}

	public function settings_page_html() {
		if (!current_user_can('manage_options')) return;
		?>
		<div class="wrap">
			<h1>HireFlow Settings</h1>
			<form action="options.php" method="post">
				<?php
				settings_fields('hf_settings_group');
				do_settings_sections('hireflow-settings');
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
