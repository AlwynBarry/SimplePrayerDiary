<?php
/*
Plugin Name: Simple Prayer Diary
Plugin URI: https://wordpress.org/plugins/simple-prayer-diary/
Description: Simple Prayer Diary is an prayer diary for people who just want a simple dated list of prayer items.
Version: 1.0.0 (from v1.4.2 of Super Simple Event Calendar)
Author: Alwyn Barry from Super Simple Event Calendar by Marcel Pol
Author URI: https://github.com/AlwynBarry/SimplePrayerDiary
License: GPLv2 or later
Text Domain: simple-prayer-diary
Domain Path: /lang/


Copyright 2022 Alwyn Barry, 2018 - 2022  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version
define('SPD_VER', '1.4.2');


/*
 * Todo List:
 *
 */


/*
 * Definitions
 */
define('SPD_FOLDER', plugin_basename(dirname(__FILE__)));
define('SPD_DIR', WP_PLUGIN_DIR . '/' . SPD_FOLDER);
define('SPD_URL', plugins_url( '/', __FILE__ ));


require_once SPD_DIR . '/spd-hooks.php';
require_once SPD_DIR . '/spd-posttypes.php';
require_once SPD_DIR . '/spd-shortcode.php';
require_once SPD_DIR . '/spd-taxonomy-content-filter.php';
require_once SPD_DIR . '/spd-widget-prayer-diary.php';

// Functions and pages for the backend
if ( is_admin() ) {
	require_once SPD_DIR . '/spd-admin-quick-add.php';
}

// Add the CSS
function add_my_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'spd-style',  $plugin_url . "css/simple-prayer-diary.css");
}
add_action( 'wp_enqueue_scripts', 'add_my_css' );


/*
 * Get the terms of each prayer item post in the form of classes.
 *
 * @param int $postid instance of WP_Post
 * @return string text with term classes of this prayer item post.
 * @since 1.1.1
 */
function spd_get_term_classes( $postid ) {
	$postid = (int) $postid;
	$categories = get_the_terms( $postid, 'spd_category' );
	$classes = array();

	if ( $categories && ! is_wp_error( $categories ) ) {
		$classes[] = 'spd-category';
		foreach ( $categories as $category ) {
			if ( isset( $category->term_id ) ) {
				$class = sanitize_html_class( $category->slug, $category->term_id );
				$classes[] = 'spd-category-' . $class;
				$classes[] = 'spd-category-' . $category->term_id;
			}
		}
	}
	$classes = join( ' ', $classes );
	return $classes;
}
