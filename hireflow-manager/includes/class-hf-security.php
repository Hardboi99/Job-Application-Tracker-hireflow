<?php
// Handles basic app security & sanitization
defined('ABSPATH') || exit;

class HF_Security {

	public function __construct() {
		add_action('init', [$this, 'add_security_headers']);
		add_action('wp_login_failed', [$this, 'log_failed_login']);
	}

	public static function create_nonce($action) {
		return wp_create_nonce($action);
	}

	public static function verify_nonce($nonce, $action) {
		if (!wp_verify_nonce($nonce, $action)) {
			wp_die(esc_html__('Security check failed.', 'hireflow'));
		}
		return true;
	}

	public static function sanitize_application_data($data) {
		$clean = [];
		foreach ($data as $k => $v) {
			$clean[sanitize_key($k)] = sanitize_text_field($v);
		}
		return $clean;
	}

	public static function validate_resume_upload($file) {
		$allowed = self::get_allowed_mime_types();
		$file_type = wp_check_filetype($file['name']);
		
		if (!in_array($file_type['type'], $allowed, true)) {
			return false;
		}
		
		// check size against settings
		$settings = get_option('hf_settings');
		$max_size = isset($settings['max_upload_size']) ? intval($settings['max_upload_size']) * 1024 * 1024 : 5242880; // default 5MB
		
		if ($file['size'] > $max_size) {
			return false;
		}
		
		return true;
	}

	public static function check_application_ownership($post_id) {
		$post = get_post($post_id);
		return $post && ((int) $post->post_author === get_current_user_id());
	}

	public static function get_allowed_mime_types() {
		return apply_filters('hireflow_allowed_file_types', [
			'pdf' => 'application/pdf',
			'doc' => 'application/msword',
			'docx'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		]);
	}

	public static function sanitize_settings($input) {
		$clean = [];
		if (isset($input['default_status'])) $clean['default_status'] = sanitize_text_field($input['default_status']);
		if (isset($input['apps_per_page'])) $clean['apps_per_page'] = absint($input['apps_per_page']);
		if (isset($input['allow_guest_view'])) $clean['allow_guest_view'] = absint($input['allow_guest_view']);
		if (isset($input['send_emails'])) $clean['send_emails'] = absint($input['send_emails']);
		if (isset($input['admin_email'])) $clean['admin_email'] = sanitize_email($input['admin_email']);
		if (isset($input['primary_color'])) $clean['primary_color'] = sanitize_hex_color($input['primary_color']);
		if (isset($input['max_upload_size'])) $clean['max_upload_size'] = sanitize_text_field($input['max_upload_size']);
		return $clean;
	}

	public function add_security_headers() {
		if (!is_admin() && !headers_sent()) {
			header('X-Content-Type-Options: nosniff');
			header('X-Frame-Options: SAMEORIGIN');
			header('X-XSS-Protection: 1; mode=block');
		}
	}

	public function log_failed_login($username) {
		// simple counter for brute force tracking
		$count = get_option('hf_failed_logins', 0);
		update_option('hf_failed_logins', $count + 1);
	}
}
