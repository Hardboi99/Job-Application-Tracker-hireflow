<?php
// Central spot for custom action/filter hooks
defined('ABSPATH') || exit;

class HF_Hooks {

	public function __construct() {
		add_action('transition_post_status', [$this, 'trigger_status_change_hook'], 10, 3);
		add_action('hireflow_application_status_changed', [$this, 'send_status_email'], 10, 3);
		add_action('user_register', [$this, 'trigger_user_registered']);
	}

	public function trigger_status_change_hook($new_status, $old_status, $post) {
		if ($post->post_type !== 'hireflow_application') return;
		
		$old_meta_status = get_post_meta($post->ID, '_hf_status_prev', true);
		$new_meta_status = get_post_meta($post->ID, '_hf_status', true);
		
		// If the custom status changed, fire our own hook
		if ($old_meta_status !== $new_meta_status) {
			do_action('hireflow_application_status_changed', $post->ID, $old_meta_status, $new_meta_status);
			update_post_meta($post->ID, '_hf_status_prev', $new_meta_status);
		}
	}

	public function send_status_email($post_id, $old_status, $new_status) {
		$settings = get_option('hf_settings');
		if (empty($settings['send_emails'])) return;

		$admin_email = $settings['admin_email'] ?? get_option('admin_email');
		$title = get_the_title($post_id);
		
		$subject = apply_filters('hireflow_email_subject', "Application Status Changed: $title");
		$body = apply_filters('hireflow_email_body', "The status for application '$title' changed from $old_status to $new_status.");
		
		$headers = ['Content-Type: text/html; charset=UTF-8'];
		wp_mail($admin_email, $subject, $body, $headers);
	}

	public function trigger_user_registered($user_id) {
		do_action('hireflow_user_registered', $user_id);
	}
}
