<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add Custom Post Type with a taxonomy for Categories.
 *
 * @since 1.0.0
 */
function spd_post_type() {
	$labels = array(
		'name'                          => esc_attr__('Prayer Item', 'simple-prayer-diary'),
		'singular_name'                 => esc_attr__('Prayer Item', 'simple-prayer-diary'),
		'add_new'                       => esc_attr__('New Prayer Item', 'simple-prayer-diary'),
		'add_new_item'                  => esc_attr__('Add new Prayer Item', 'simple-prayer-diary'),
		'edit_item'                     => esc_attr__('Edit Prayer Item', 'simple-prayer-diary'),
		'new_item'                      => esc_attr__('New Prayer Item', 'simple-prayer-diary'),
		'view_item'                     => esc_attr__('View Prayer Item', 'simple-prayer-diary'),
		'search_items'                  => esc_attr__('Search Prayer Item', 'simple-prayer-diary'),
		'not_found'                     => esc_attr__('No Prayer Item found', 'simple-prayer-diary'),
		'not_found_in_trash'            => esc_attr__('No Prayer Item found in trash', 'simple-prayer-diary'),
		'parent_item_colon'             => '',
		'menu_name'                     => esc_attr__('Prayer Items', 'simple-prayer-diary'),
	);
	register_post_type('spd_prayer_item', array(
		'public'                        => true,
		'show_in_menu'                  => true,
		'show_ui'                       => true,
		'labels'                        => $labels,
		'hierarchical'                  => false,
		'supports'                      => array( 'title', 'editor' ),
		'capability_type'               => 'post',
		'taxonomies'                    => array( 'spd_category' ),
		'exclude_from_search'           => false,
		'rewrite'                       => true,
		'rewrite'                       => array(
		                                        'slug' => 'prayer-item',
		                                        'with_front' => true,
		                                   ),
    'show_in_rest'                  => true,
		'menu_icon'                     => 'dashicons-calendar',
		)
	);

	$labels = array(
		'name'                          => esc_attr__('Category', 'simple-prayer-diary'),
		'singular_name'                 => esc_attr__('Category', 'simple-prayer-diary'),
		'search_items'                  => esc_attr__('Search Category', 'simple-prayer-diary'),
		'popular_items'                 => esc_attr__('Popular Category', 'simple-prayer-diary'),
		'all_items'                     => esc_attr__('All Categories', 'simple-prayer-diary'),
		'parent_item'                   => esc_attr__('Parent Category', 'simple-prayer-diary'),
		'edit_item'                     => esc_attr__('Edit Category', 'simple-prayer-diary'),
		'update_item'                   => esc_attr__('Update Category', 'simple-prayer-diary'),
		'add_new_item'                  => esc_attr__('Add new Category', 'simple-prayer-diary'),
		'new_item_name'                 => esc_attr__('New Category name', 'simple-prayer-diary'),
		'not_found'                     => esc_attr__('No Category found', 'simple-prayer-diary'),
		'separate_items_with_commas'    => esc_attr__('Separate Categories with commas', 'simple-prayer-diary'),
		'add_or_remove_items'           => esc_attr__('Add or remove Categories', 'simple-prayer-diary'),
		'choose_from_most_used'         => esc_attr__('Choose Category from most used', 'simple-prayer-diary'),
		);

	$args = array(
		'label'                         => esc_attr__('Category', 'simple-prayer-diary'),
		'labels'                        => $labels,
		'public'                        => true,
		'hierarchical'                  => true,
		'show_ui'                       => true,
		'show_in_nav_menus'             => true,
		'args'                          => array( 'orderby' => 'date' ),
		'rewrite'                       => true,
		'rewrite'                       => array(
		                                        'slug' => 'spd_category',
		                                        'with_front' => true,
		                                   ),
		'query_var'                     => true,
	);
	register_taxonomy( 'spd_category', 'spd_prayer_item', $args );

}
add_action('init', 'spd_post_type');


/*
 * Show all Prayer Items in a taxonomy/term with correct order.
 *
 * @since 1.0.0
 */
function spd_pre_get_posts_taxonomy( $query ) {
	if ( $query->is_tax('spd_category') && $query->is_main_query() ) {
		$query->set( 'post_status', array( 'publish', 'future' ) );
		$query->set( 'posts_per_page', -1 );
		$query->set( 'nopaging', true );
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'ASC' );
	}
	// don't return.
	//return $query;
}
add_action( 'pre_get_posts', 'spd_pre_get_posts_taxonomy' );
