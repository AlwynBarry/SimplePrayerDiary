<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Replace the content of our taxonomy and single post.
 *
 * @since 1.0.0
 */
function spd_content_filter( $content ) {

	if ( is_admin() ) {
		return $content;
	}

	if ( is_tax('spd_category') && is_main_query() ) {
		$content .= '
    <div class="spd-contentfilter-prayer-diary">
    ';
		$postlink = '';
		if ( is_user_logged_in() ) {
			$postlink = get_edit_post_link( get_the_ID() );
			if ( strlen( $postlink ) > 0 ) {
				$postlink = ' <a class="post-edit-link" href="' . $postlink . '">' . esc_html__('(edit)', 'simple-prayer-diary') . '</a>';
			}
		}
		$classes = sdp_get_term_classes( get_the_ID() );
		$content .= '
			<div class="' . $classes . '">
				<div class="spd-title">' . get_the_title() . '</div>
				<div class="spd-content">' . nl2br(get_the_content()) . $postlink . '</div>
			</div>
			';
		$content .= '
    </div>
    ';
		return $content;
	}

	$post_type = get_post_type();
	if ( $post_type === 'spd_prayer_item' && is_singular() ) {
		$content .= '
    <div class="spd-contentfilter-prayer-diary">
    ';
		$postlink = '';
		if ( is_user_logged_in() ) {
			$postlink = get_edit_post_link( get_the_ID() );
			if ( strlen( $postlink ) > 0 ) {
				$postlink = ' <a class="post-edit-link" href="' . $postlink . '">' . esc_html__('(edit)', 'simple-prayer-diary') . '</a>';
			}
		}
		$classes = spd_get_term_classes( get_the_ID() );
		$content .= '
			<div class="' . $classes . '">
				<div class="spd-title">' . get_the_title() . '</div>
				<div class="spd-content">' . get_the_content() . $postlink . '</div>
			</tr>
			';
		$content .= '
    </div>
    ';
		return $content;
	}

	return $content;

}
add_filter( 'the_content', 'spd_content_filter', 12 );
