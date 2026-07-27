<?php
// Handles wp-admin stuff for hireflow
defined('ABSPATH') || exit;

class HF_Admin {

	public function __construct() {
		add_action('admin_menu', [$this, 'add_admin_menu']);
		add_filter('manage_hireflow_application_posts_columns', [$this, 'set_custom_edit_columns']);
		add_action('manage_hireflow_application_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
		add_filter('manage_edit-hireflow_application_sortable_columns', [$this, 'sortable_columns']);
		add_action('admin_notices', [$this, 'admin_notice']);
		add_action('wp_ajax_hf_dismiss_notice', [$this, 'dismiss_notice']);
		add_action('admin_head', [$this, 'remove_cpt_from_admin_bar']);
	}

	public function add_admin_menu() {
		add_menu_page(
			__('HireFlow Dashboard', 'hireflow'),
			'HireFlow',
			'manage_options',
			'hireflow',
			[$this, 'dashboard_page'],
			'dashicons-clipboard',
			25
		);

		add_submenu_page(
			'hireflow',
			'Dashboard',
			'Dashboard',
			'manage_options',
			'hireflow',
			[$this, 'dashboard_page']
		);

		add_submenu_page(
			'hireflow',
			'All Applications',
			'All Applications',
			'manage_options',
			'edit.php?post_type=hireflow_application'
		);
	}

	public function dashboard_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e('HireFlow Dashboard', 'hireflow'); ?></h1>
			<p>Welcome to the HireFlow admin area.</p>
		</div>
		<?php
	}

	public function set_custom_edit_columns($columns) {
		unset($columns['date']); // date is boring
		$columns['title']         = 'Job Title';
		$columns['company']       = 'Company';
		$columns['status']        = 'Status';
		$columns['date_applied']  = 'Date Applied';
		$columns['interview_date']= 'Interview Date';
		$columns['actions']       = 'Actions';
		return $columns;
	}

	public function custom_column_content($column, $post_id) {
		switch ($column) {
			case 'company':
				echo esc_html(get_post_meta($post_id, '_hf_company', true));
				break;
			case 'status':
				$status = get_post_meta($post_id, '_hf_status', true);
				echo esc_html(ucwords(str_replace('_', ' ', $status)));
				break;
			case 'date_applied':
				echo esc_html(get_post_meta($post_id, '_hf_date_applied', true));
				break;
			case 'interview_date':
				echo esc_html(get_post_meta($post_id, '_hf_interview_date', true));
				break;
			case 'actions':
				echo '<a href="' . esc_url(get_edit_post_link($post_id)) . '">Edit</a>';
				break;
		}
	}

	public function sortable_columns($columns) {
		$columns['company'] = 'company';
		$columns['status'] = 'status';
		$columns['date_applied'] = 'date_applied';
		return $columns;
	}

	public function admin_notice() {
		$user_id = get_current_user_id();
		if (!get_user_meta($user_id, 'hf_dismissed_welcome_notice', true)) {
			?>
			<div class="notice notice-info is-dismissible" id="hf-welcome-notice">
				<p>Welcome to HireFlow! Try adding your first application.</p>
			</div>
			<script>
				jQuery(document).ready(function($) {
					$(document).on('click', '#hf-welcome-notice .notice-dismiss', function() {
						$.post(ajaxurl, {
							action: 'hf_dismiss_notice',
							nonce: '<?php echo esc_js(wp_create_nonce('hf_dismiss_notice')); ?>'
						});
					});
				});
			</script>
			<?php
		}
	}

	public function dismiss_notice() {
		check_ajax_referer('hf_dismiss_notice', 'nonce');
		update_user_meta(get_current_user_id(), 'hf_dismissed_welcome_notice', '1');
		wp_die();
	}

	public function remove_cpt_from_admin_bar() {
		if (!current_user_can('manage_options')) {
			global $wp_admin_bar;
			$wp_admin_bar->remove_node('new-hireflow_application');
		}
	}
}
