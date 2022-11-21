<?php

/*
 * WordPress Actions and Filters.
 * See the Plugin API in the Codex:
 * http://codex.wordpress.org/Plugin_API
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add link to the main plugin page.
 *
 * @since 1.0.0
 */
function spd_links( $links, $file ) {
	if ( $file === plugin_basename( dirname(__FILE__) . '/simple-prayer-diary.php' ) ) {
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=spd_prayer_item' ) . '">' . esc_html__( 'Prayer Items', 'simple-prayer-diary' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'spd_links', 10, 2 );


/*
 * Load Language files for frontend and backend.
 *
 * @since 1.0.0
 */
function spd_load_lang() {
	load_plugin_textdomain( 'simple-prayer-diary', false, SPD_FOLDER . '/lang' );
}
add_action('plugins_loaded', 'spd_load_lang');
