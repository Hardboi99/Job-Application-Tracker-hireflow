<?php
// Handles custom wp-json endpoints
defined('ABSPATH') || exit;

class HF_REST_API {

	public function __construct() {
		add_action('rest_api_init', [$this, 'register_routes']);
	}

	public function register_routes() {
		register_rest_route('hireflow/v1', '/stats', [
			'methods'             => 'GET',
			'callback'            => [$this, 'get_stats'],
			'permission_callback' => function() { return is_user_logged_in(); }
		]);

		register_rest_route('hireflow/v1', '/applications', [
			'methods'             => 'GET',
			'callback'            => [$this, 'get_applications'],
			'permission_callback' => function() { return is_user_logged_in(); }
		]);

		register_rest_route('hireflow/v1', '/applications', [
			'methods'             => 'POST',
			'callback'            => [$this, 'create_application'],
			'permission_callback' => function() { return is_user_logged_in(); }
		]);

		register_rest_route('hireflow/v1', '/applications/(?P<id>\d+)', [
			'methods'             => 'PUT',
			'callback'            => [$this, 'update_application'],
			'permission_callback' => [$this, 'check_ownership']
		]);

		register_rest_route('hireflow/v1', '/applications/(?P<id>\d+)', [
			'methods'             => 'DELETE',
			'callback'            => [$this, 'delete_application'],
			'permission_callback' => [$this, 'check_ownership']
		]);
	}

	public function check_ownership($request) {
		if (!is_user_logged_in()) return false;
		$post = get_post($request['id']);
		return $post && ((int) $post->post_author === get_current_user_id());
	}

	public function get_stats($request) {
		$apps = get_posts([
			'post_type'      => 'hireflow_application',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => get_current_user_id(),
		]);
		
		$stats = [
			'total' => count($apps),
			'applied' => 0,
			'interview_scheduled' => 0,
			'rejected' => 0,
			'offer_received' => 0,
			'accepted' => 0,
			'monthly_data' => [], // mock array
		];

		foreach ($apps as $app) {
			$status = get_post_meta($app->ID, '_hf_status', true);
			if (isset($stats[$status])) {
				$stats[$status]++;
			}
		}

		return rest_ensure_response($stats);
	}

	public function get_applications($request) {
		$query = new WP_Query([
			'post_type'      => 'hireflow_application',
			'post_status'    => 'publish',
			'author'         => get_current_user_id(),
			'posts_per_page' => $request->get_param('per_page') ?: 10,
			'paged'          => $request->get_param('page') ?: 1,
		]);
		
		$data = [];
		foreach ($query->posts as $post) {
			$data[] = [
				'post_id'    => $post->ID,
				'title'      => $post->post_title,
				'company'    => get_post_meta($post->ID, '_hf_company', true),
				'job_title'  => $post->post_title,
				'status'     => get_post_meta($post->ID, '_hf_status', true),
				'dates'      => get_post_meta($post->ID, '_hf_date_applied', true),
				'location'   => get_post_meta($post->ID, '_hf_location', true),
				'work_mode'  => get_post_meta($post->ID, '_hf_work_mode', true),
			];
		}
		
		return rest_ensure_response($data);
	}

	public function create_application($request) {
		// Nonce is handled by WP core REST auth usually
		$title = sanitize_text_field($request->get_param('hf_job_title'));
		$company = sanitize_text_field($request->get_param('hf_company'));
		$status = sanitize_text_field($request->get_param('hf_status'));
		
		$post_id = wp_insert_post([
			'post_title'  => $title,
			'post_type'   => 'hireflow_application',
			'post_status' => 'publish',
			'post_author' => get_current_user_id()
		]);
		
		if (!is_wp_error($post_id)) {
			update_post_meta($post_id, '_hf_company', $company);
			update_post_meta($post_id, '_hf_status', $status);
			return rest_ensure_response(['success' => true, 'post_id' => $post_id, 'message' => 'Application created.']);
		}
		
		return new WP_Error('create_failed', 'Failed to create application.', ['status' => 500]);
	}

	public function update_application($request) {
		$post_id = $request['id'];
		$status = sanitize_text_field($request->get_param('hf_status'));
		if ($status) {
			update_post_meta($post_id, '_hf_status', $status);
		}
		return rest_ensure_response(['success' => true, 'message' => 'Application updated.']);
	}

	public function delete_application($request) {
		wp_trash_post($request['id']);
		return rest_ensure_response(['success' => true, 'message' => 'Application deleted.']);
	}
}
