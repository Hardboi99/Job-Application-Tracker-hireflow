<?php
// WP Admin dashboard widget
defined('ABSPATH') || exit;

class HF_Dashboard_Widget {

	public function __construct() {
		add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);
	}

	public function add_dashboard_widget() {
		wp_add_dashboard_widget('hireflow_dashboard_widget', 'HireFlow Overview', [$this, 'render_widget']);
	}

	public function render_widget() {
		$args = [
			'post_type'      => 'hireflow_application',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		];

		// show only user's apps if they aren't admin
		if (!current_user_can('manage_options')) {
			$args['author'] = get_current_user_id();
		}

		$apps = get_posts($args);
		$total = count($apps);
		
		$stats = [
			'applied'             => 0,
			'interview_scheduled' => 0,
			'rejected'            => 0,
			'offer_received'      => 0,
			'accepted'            => 0,
		];

		foreach ($apps as $app) {
			$status = get_post_meta($app->ID, '_hf_status', true);
			if (isset($stats[$status])) {
				$stats[$status]++;
			}
		}

		?>
		<div style="font-family: 'Inter', sans-serif;">
			<div style="background: #06101A; color: #E8F0FE; padding: 15px; border-radius: 12px;">
				<h3 style="margin-top:0; color:#00C9A7;">Total Applications: <?php echo esc_html($total); ?></h3>
				
				<!-- Using grid here since WP dashboard can be tight -->
				<ul style="list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
					<li style="background: #112236; padding: 10px; border-radius: 8px;">
						<span style="color: #3B82F6;">Applied:</span> <?php echo esc_html($stats['applied']); ?>
					</li>
					<li style="background: #112236; padding: 10px; border-radius: 8px;">
						<span style="color: #F59E0B;">Interview:</span> <?php echo esc_html($stats['interview_scheduled']); ?>
					</li>
					<li style="background: #112236; padding: 10px; border-radius: 8px;">
						<span style="color: #EF4444;">Rejected:</span> <?php echo esc_html($stats['rejected']); ?>
					</li>
					<li style="background: #112236; padding: 10px; border-radius: 8px;">
						<span style="color: #10B981;">Offer:</span> <?php echo esc_html($stats['offer_received']); ?>
					</li>
					<li style="background: #112236; padding: 10px; border-radius: 8px; grid-column: span 2; text-align: center;">
						<span style="color: #00C9A7;">Accepted:</span> <?php echo esc_html($stats['accepted']); ?>
					</li>
				</ul>
				
				<!-- sparkline placeholder -->
				<svg width="100%" height="40" style="margin-bottom: 10px; background: #112236; border-radius:4px;">
					<path d="M0 30 L 30 10 L 60 20 L 90 5" stroke="#00C9A7" stroke-width="2" fill="none"/>
				</svg>

				<div style="text-align: center;">
					<a href="<?php echo admin_url('edit.php?post_type=hireflow_application'); ?>" style="color: #00C9A7; text-decoration: none; margin-right: 15px;">View All</a>
					<a href="<?php echo admin_url('post-new.php?post_type=hireflow_application'); ?>" style="color: #00C9A7; text-decoration: none;">+ Add New</a>
				</div>
			</div>
		</div>
		<?php
	}
}
