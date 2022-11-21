<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


if (function_exists('register_sidebar') && class_exists('WP_Widget')) {

	/*
	 * Widget to display the next Prayer Items.
	 *
	 * @since 1.0.0
	 */
	class SPD_Widget_Prayer_Diary extends WP_Widget {

		/* Constructor */
		function __construct() {
			$widget_ops = array(
				'classname' => 'spd_widget_prayer_diary',
				'description' => esc_html__( 'Simple Prayer Diary.', 'simple-prayer-diary'  ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'spd_widget_prayer_diary', esc_html__( 'Prayer Diary', 'simple-prayer-diary'  ), $widget_ops );
			$this->alt_option_name = 'spd_widget_prayer_diary';
		}

		/** @see WP_Widget::widget */
		function widget( $args, $instance ) {
			extract($args);

			$default_value = array(
					'title'       => esc_html__('Prayer Diary', 'simple-prayer-diary'),
					'num_entries' => 3,
					'category'    => 0,
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );

			$widget_title  = esc_attr($instance['title']);
			$num_entries   = (int) esc_attr($instance['num_entries']);
			$category      = (int) esc_attr($instance['category']);
			$postid        = (int) esc_attr($instance['postid']);

			$tax_query = array();
			if ( $category > 0 ) {
				$tax_query[] = array(
					'taxonomy'         => 'spd_category',
					'terms'            => $category,
					'field'            => 'term_id',
					'include_children' => true,
				);
			}

			// Init
			$widget_html = '';

			$widget_html .= $before_widget;
			$widget_html .= '
			<div class="spd-widget-prayer-diary">';

			if ($widget_title !== FALSE) {
				$widget_html .= $before_title . apply_filters('widget_title', $widget_title) . $after_title;
			}

			$widget_html .= '
				<ul class="spd-widget-prayer-diary-list">';

			$args = array(
				'post_type'      => 'spd_prayer_item',
				'post_status'    => 'future',
				'posts_per_page' => $num_entries,
				'orderby'        => 'date',
				'order'          => 'ASC',
				'tax_query'      => $tax_query,
			);

			// The Query
			$the_query = new WP_Query( $args );

			// The Loop
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$classes = spd_get_term_classes( get_the_ID() );
					$widget_html .= '
					<li class="spd-widget-prayer-item ' . $classes . '">
						<span class="spd-title">' . get_the_title() . '</span><br />
						<span class="spd-content">' . nl2br(get_the_content()) . '</span>
					</li>';
				}
			}
			/* Restore original Post Data */
			wp_reset_postdata();

			$widget_html .= '
				</ul>';

			// Post the link to the Calendar.
			if ( (int) $postid > 0 ) {
				$permalink = get_permalink( $postid );
				$widget_html .= '
				<p class="spd-widget-prayer-diary-link">
					<a href="' . $permalink . '" title="' . esc_attr__('View Full Prayer Diary.', 'simple-prayer-diary') . '">' . esc_html__('Full Diary', 'simple-prayer-diary') . ' &raquo;</a>
				</p>';
			}

			$widget_html .= '
			</div>
			' . $after_widget;

			if ( $the_query->have_posts() ) {
				// Only display widget if there are any entries
				echo $widget_html;
			}
		}

		/** @see WP_Widget::update */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']       = strip_tags($new_instance['title']);
			$instance['num_entries'] = (int) $new_instance['num_entries'];
			$instance['category']      = (int) $new_instance['category'];
			$instance['postid']      = (int) $new_instance['postid'];

			return $instance;
		}

		/** @see WP_Widget::form */
		function form( $instance ) {

			$default_value = array(
					'title'       => esc_html__('Prayer Diary', 'simple-prayer-diary'),
					'num_entries' => 3,
					'category'    => 0,
					'postid'      => 0,
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );

			$title         = esc_attr($instance['title']);
			$num_entries   = (int) esc_attr($instance['num_entries']);
			$category      = (int) esc_attr($instance['category']);
			$postid        = (int) esc_attr($instance['postid']);
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" /><?php esc_html_e('Title:', 'simple-prayer-diary'); ?></label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('num_entries'); ?>" /><?php esc_html_e('Number of items:', 'simple-prayer-diary'); ?></label>
				<br />
				<select id="<?php echo $this->get_field_id('num_entries'); ?>" name="<?php echo $this->get_field_name('num_entries'); ?>">
					<?php
					for ($i = 1; $i <= 15; $i++) {
						echo '<option value="' . $i . '"';
						if ( $i === $num_entries ) {
							echo ' selected="selected"';
						}
						echo '>' . $i . '</option>';
					}
					?>
				</select>
			</p>

			<?php
			$args = array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false,
				);
			$categories = get_terms( 'spd_category', $args );
			if ( is_array( $categories ) && ! empty( $categories ) ) {
				?>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>" /><?php esc_html_e('Only Prayer Items from this category:', 'simple-prayer-diary'); ?></label>
				<br />
				<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
					<option value="0" <?php if ( 0 === $category ) { echo ' selected="selected"'; } ?> > <?php esc_html_e( 'Select...', 'simple-prayer-diary' ); ?></option>
					<?php foreach ( $categories as $item ) {
						echo '<option value="' . (int) $item->term_id . '"';
						if ( $item->term_id === $category ) {
							echo ' selected="selected"';
						}
						echo '>' . esc_html( $item->name ) . '</option>';
					}
					?>
				</select>
			</p>
			<?php } ?>

			<p>
				<label for="<?php echo $this->get_field_id('postid'); ?>"><?php esc_html_e('Select the page of the prayer diary:', 'simple-prayer-diary'); ?></label>
				<br />
				<select id="<?php echo $this->get_field_id('postid'); ?>" name="<?php echo $this->get_field_name('postid'); ?>">
					<option value="0"><?php esc_html_e('Select page', 'simple-prayer-diary'); ?></option>
					<?php
					$args = array(
						'post_type'              => 'page',
						'orderby'                => 'title',
						'order'                  => 'ASC',
						'posts_per_page'         => 500,
						'update_post_term_cache' => false,
						'update_post_meta_cache' => false,
					);

					$sel_query = new WP_Query( $args );
					if ( $sel_query->have_posts() ) {
						while ( $sel_query->have_posts() ) {
							$sel_query->the_post();
							$selected = false;
							if ( get_the_ID() === $postid ) {
								$selected = true;
							}
							echo '<option value="' . get_the_ID() . '"'
							. selected( $selected )
							. '>' . get_the_title() . '</option>';
						}
					}
					wp_reset_postdata(); ?>
				</select>
			</p>

			<?php
		}
	}

	function spd_widget_prayer_diary_init() {
		register_widget('SPD_Widget_Prayer_Diary');
	}
	add_action('widgets_init', 'spd_widget_prayer_diary_init' );
}

