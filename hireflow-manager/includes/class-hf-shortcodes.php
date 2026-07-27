<?php
// All front-end shortcodes
defined('ABSPATH') || exit;

class HF_Shortcodes {

	public function __construct() {
		add_shortcode('hireflow_recent_applications', [$this, 'recent_applications']);
		add_shortcode('hireflow_stats', [$this, 'stats_boxes']);
		add_shortcode('hireflow_application_form', [$this, 'application_form']);
	}

	public function recent_applications($atts) {
		if (!is_user_logged_in()) {
			return '<p>Please login to view your applications.</p>';
		}

		$atts = shortcode_atts([
			'limit'  => 5,
			'status' => 'all',
		], $atts);

		$args = [
			'post_type'      => 'hireflow_application',
			'post_status'    => 'publish',
			'posts_per_page' => absint($atts['limit']),
			'author'         => get_current_user_id(),
		];

		if ($atts['status'] !== 'all') {
			$args['meta_query'] = [
				[
					'key'   => '_hf_status',
					'value' => sanitize_text_field($atts['status']),
				]
			];
		}

		$query = new WP_Query($args);
		ob_start();

		if ($query->have_posts()) {
			?>
			<table class="hf-table" style="width:100%; text-align:left; border-collapse: collapse;">
				<thead>
					<tr>
						<th style="padding: 8px; border-bottom: 1px solid #1E3A5F;">Company</th>
						<th style="padding: 8px; border-bottom: 1px solid #1E3A5F;">Job Title</th>
						<th style="padding: 8px; border-bottom: 1px solid #1E3A5F;">Status</th>
						<th style="padding: 8px; border-bottom: 1px solid #1E3A5F;">Date</th>
					</tr>
				</thead>
				<tbody>
				<?php while ($query->have_posts()) : $query->the_post(); 
					$post_id = get_the_ID();
					$company = get_post_meta($post_id, '_hf_company', true);
					$status = get_post_meta($post_id, '_hf_status', true);
					$date = get_post_meta($post_id, '_hf_date_applied', true);
					?>
					<tr>
						<td style="padding: 8px; border-bottom: 1px solid #1E3A5F;"><?php echo esc_html($company); ?></td>
						<td style="padding: 8px; border-bottom: 1px solid #1E3A5F;"><?php the_title(); ?></td>
						<td style="padding: 8px; border-bottom: 1px solid #1E3A5F;"><span class="hf-badge hf-badge-<?php echo esc_attr($status); ?>"><?php echo esc_html(str_replace('_', ' ', $status)); ?></span></td>
						<td style="padding: 8px; border-bottom: 1px solid #1E3A5F;"><?php echo esc_html($date); ?></td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
			<?php
			wp_reset_postdata();
		} else {
			echo '<p>No applications found.</p>';
		}

		return ob_get_clean();
	}

	public function stats_boxes($atts) {
		if (!is_user_logged_in()) return '';

		$apps = get_posts([
			'post_type'      => 'hireflow_application',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => get_current_user_id(),
		]);
		
		$total = count($apps);
		$interviews = 0;
		$offers = 0;
		$rejections = 0;

		foreach ($apps as $app) {
			$status = get_post_meta($app->ID, '_hf_status', true);
			if ($status === 'interview_scheduled') $interviews++;
			if ($status === 'offer_received') $offers++;
			if ($status === 'rejected') $rejections++;
		}

		ob_start();
		?>
		<div class="hf-stats-boxes" style="display: flex; gap: 20px; flex-wrap: wrap;">
			<div class="hf-stat-box" style="background: #112236; padding: 20px; border-radius: 12px; flex: 1; text-align: center;">
				<h4 style="margin: 0; color: #8BA3C7;">Total</h4>
				<p style="font-size: 24px; color: #E8F0FE; margin: 10px 0 0;"><?php echo esc_html($total); ?></p>
			</div>
			<div class="hf-stat-box" style="background: #112236; padding: 20px; border-radius: 12px; flex: 1; text-align: center;">
				<h4 style="margin: 0; color: #8BA3C7;">Interviews</h4>
				<p style="font-size: 24px; color: #F59E0B; margin: 10px 0 0;"><?php echo esc_html($interviews); ?></p>
			</div>
			<div class="hf-stat-box" style="background: #112236; padding: 20px; border-radius: 12px; flex: 1; text-align: center;">
				<h4 style="margin: 0; color: #8BA3C7;">Offers</h4>
				<p style="font-size: 24px; color: #10B981; margin: 10px 0 0;"><?php echo esc_html($offers); ?></p>
			</div>
			<div class="hf-stat-box" style="background: #112236; padding: 20px; border-radius: 12px; flex: 1; text-align: center;">
				<h4 style="margin: 0; color: #8BA3C7;">Rejections</h4>
				<p style="font-size: 24px; color: #EF4444; margin: 10px 0 0;"><?php echo esc_html($rejections); ?></p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function application_form($atts) {
		if (!is_user_logged_in()) {
			return '<p>Please login to add an application.</p>';
		}

		ob_start();
		?>
		<form id="hf-application-form" class="hf-form" method="post" action="" style="background: #112236; padding: 20px; border-radius: 12px;">
			<?php wp_nonce_field('hf_submit_application', 'hf_nonce'); ?>
			<div class="hf-form-group" style="margin-bottom: 15px;">
				<label for="hf_company" style="display: block; color: #8BA3C7; margin-bottom: 5px;">Company</label>
				<input type="text" id="hf_company" name="hf_company" required style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #1E3A5F; background: #06101A; color: #E8F0FE;">
			</div>
			<div class="hf-form-group" style="margin-bottom: 15px;">
				<label for="hf_job_title" style="display: block; color: #8BA3C7; margin-bottom: 5px;">Job Title</label>
				<input type="text" id="hf_job_title" name="hf_job_title" required style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #1E3A5F; background: #06101A; color: #E8F0FE;">
			</div>
			<div class="hf-form-group" style="margin-bottom: 15px;">
				<label for="hf_status" style="display: block; color: #8BA3C7; margin-bottom: 5px;">Status</label>
				<select id="hf_status" name="hf_status" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #1E3A5F; background: #06101A; color: #E8F0FE;">
					<option value="applied">Applied</option>
					<option value="interview_scheduled">Interview Scheduled</option>
					<option value="offer_received">Offer Received</option>
					<option value="rejected">Rejected</option>
					<option value="accepted">Accepted</option>
				</select>
			</div>
			<button type="submit" class="hf-btn" style="background: #00C9A7; color: #06101A; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Save Application</button>
		</form>
		<?php
		return ob_get_clean();
	}
}
