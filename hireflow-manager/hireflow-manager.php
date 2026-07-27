<?php
/**
 * Plugin Name: HireFlow Manager
 * Plugin URI: https://hireflow.example.com
 * Description: A professional Job Application Tracker plugin for WordPress.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * Text Domain: hireflow
 */

defined('ABSPATH') || exit;

define('HF_VERSION', '1.0.0');
define('HF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HF_PLUGIN_FILE', __FILE__);

require_once HF_PLUGIN_DIR . 'includes/class-hf-security.php';
require_once HF_PLUGIN_DIR . 'includes/class-hf-hooks.php';
require_once HF_PLUGIN_DIR . 'includes/class-hf-settings.php';
require_once HF_PLUGIN_DIR . 'includes/class-hf-admin.php';
require_once HF_PLUGIN_DIR . 'includes/class-hf-dashboard-widget.php';
require_once HF_PLUGIN_DIR . 'includes/class-hf-shortcodes.php';
require_once HF_PLUGIN_DIR . 'includes/class-hf-rest-api.php';

class HireFlow_Manager {

	private static $instance = null;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		register_activation_hook(HF_PLUGIN_FILE, [$this, 'hf_plugin_activate']);
		register_deactivation_hook(HF_PLUGIN_FILE, [$this, 'hf_plugin_deactivate']);
		
		add_action('plugins_loaded', [$this, 'init']);
	}

	public function init() {
		load_plugin_textdomain('hireflow', false, dirname(plugin_basename(HF_PLUGIN_FILE)) . '/languages');
		
		new HF_Security();
		new HF_Hooks();
		new HF_Settings();
		new HF_Admin();
		new HF_Dashboard_Widget();
		new HF_Shortcodes();
		new HF_REST_API();
	}

	public function hf_plugin_activate() {
		// populate defaults if it's a fresh install
		if (!get_option('hf_settings')) {
			$defaults = [
				'default_status'    => 'applied',
				'apps_per_page'     => 10,
				'allow_guest_view'  => 0,
				'send_emails'       => 1,
				'admin_email'       => get_option('admin_email'),
				'primary_color'     => '#00C9A7',
				'max_upload_size'   => '5MB',
				'allowed_types'     => ['pdf' => '1', 'doc' => '1', 'docx' => '1'],
			];
			update_option('hf_settings', $defaults);
		}
		
		flush_rewrite_rules(); // gotta flush for custom routes
	}

	public function hf_plugin_deactivate() {
		flush_rewrite_rules();
	}
}

HireFlow_Manager::get_instance();
