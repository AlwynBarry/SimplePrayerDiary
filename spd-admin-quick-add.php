<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page to add a new prayer item.
 *
 * @since 1.3.0
 */
function spd_quick_add_page() {

	if ( ! current_user_can( 'publish_posts' ) ) {
		return;
	}
	?>

	<div class="wrap spd-quick-add">
		<h1><?php esc_html_e( 'Quick Add', 'simple-prayer-diary' ); ?></h1>

		<?php
		$message = spd_quick_add_get_message();
		if ( $message ) { ?>
		<div class="notice"><?php echo esc_html( $message ); ?></div>
		<?php } ?>

		<div id="poststuff" class="spd-quick-add metabox-holder">
			<div class="postbox-container">
				<?php
				add_meta_box( 'spd_quick_add_metabox', esc_html__( 'Quickly add a Prayer Item', 'simple-prayer-diary' ), 'spd_quick_add_metabox', 'spd_quick_add_page', 'normal' );
				do_meta_boxes( 'spd_quick_add_page', 'normal', '' );
				?>
			</div>
		</div>

	</div>

	<?php
}


/*
 * Metabox for admin page to add a new Prayer Item.
 *
 * @since 1.3.0
 */
function spd_quick_add_metabox() {

	if ( ! current_user_can( 'publish_posts' ) ) {
		return;
	}

	global $wp_locale;
	$nonce = wp_create_nonce( 'spd_dashboard_quick_add' );
	?>
	<form name="spd_quick_add" id="spd_quick_add" action="#" method="POST" accept-charset="UTF-8">
		<input type="hidden" name="spd_dashboard_quick_add" id="spd_dashboard_quick_add" value="<?php echo $nonce; ?>" />
		<input type="hidden" name="spd_quick_add_action" id="spd_quick_add_action" value="spd_quick_add_action" />
		<input type="hidden" name="post_type" value="spd_prayer_item" />

		<table>
			<tbody>

			<tr>
			<td colspan="2">
				<label for="spd_dashboard_content">
					<span class="title"><?php esc_html_e( 'Content', 'simple-prayer-diary' ); ?></span><br />
					<textarea rows="3" cols="15" autocomplete="off" name="spd_dashboard_content" class="spd_dashboard_content editor-area" style="min-width:400px;min-height:140px;" placeholder="<?php esc_attr_e( 'Your prayer item...', 'simple-prayer-diary' ); ?>"></textarea>
				</label>
			</td>
			</tr>

			<tr>
			<td>
				<span class="title"><?php esc_html_e( 'Date', 'simple-prayer-diary' ); ?></span>
			</td>
			<td>
				<?php
				$date = current_time( 'timestamp' );
				$dd = date_i18n( 'd', $date );
				$mm = date_i18n( 'm', $date );
				$yy = date_i18n( 'Y', $date );
				$hh = date_i18n( 'H', $date );
				$mn = date_i18n( 'i', $date );
				?>
        <label for="yy">
					<span class="screen-reader-text"><?php esc_html_e( 'Year', 'simple-prayer-diary' ); ?></span>
					<input type="text" class="yy" name="yy" value="<?php echo esc_attr( $yy ); ?>" size="4" maxlength="4" autocomplete="off" />
				</label>
				<label for="mm">
					<span class="screen-reader-text"><?php esc_html_e( 'Month', 'simple-prayer-diary' ); ?></span>
					<select class="mm" name="mm">
					<?php
					for ( $i = 1; $i < 13; $i = $i + 1 ) {
						$monthnum = zeroise($i, 2);
						echo '
						<option value="' . $monthnum . '" ' . selected( $monthnum, $mm, false ) . '>';
						/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
						echo sprintf( esc_html__( '%1$s-%2$s', 'simple-prayer-diary' ), $monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . '</option>';
					}
					?>
					</select>
				</label>
        <label for="dd">
					<span class="screen-reader-text"><?php esc_html_e( 'Day', 'simple-prayer-diary' ); ?></span>
					<input type="text" class="dd" name="dd" value="<?php echo esc_attr( $dd ); ?>" size="2" maxlength="2" autocomplete="off" />
				</label>
			</td>
			</tr>

			<tr>
			<td>
				<label>
					<span class="title"><?php esc_html_e( 'Status', 'simple-prayer-diary' ); ?></span>
				</label>
			</td>
			<td>
				<select name="spd_dashboard_status">
					<option value="future"><?php esc_html_e( 'Scheduled', 'simple-prayer-diary' ); ?></option>
					<option value="draft"><?php esc_html_e( 'Draft', 'simple-prayer-diary' ); ?></option>
					<option value="publish"><?php esc_html_e( 'Published', 'simple-prayer-diary' ); ?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">
			<?php
			$taxonomy = get_taxonomy( 'spd_category' );
			if ( is_object( $taxonomy ) && is_a( $taxonomy, 'WP_Taxonomy' ) ) {
				?>
				<label class="inline-edit-cats" for="spd_category-checklist">
					<span class="title inline-edit-categories-label"><?php echo esc_html( $taxonomy->labels->name ); ?></span>
				</label>
				<ul class="cat-checklist spd_category-checklist">
					<?php wp_terms_checklist( null, array( 'taxonomy' => $taxonomy->name ) ); ?>
				</ul>
				<?php
			} ?>
			</td>
			</tr>

			<tr>
			<td colspan="2">
				<span class="spd-save">
					<input type="submit" name="spd_dashboard_submit" class="button button-primary spd-save" value="<?php esc_attr_e( 'Publish Prayer Item', 'simple-prayer-diary' ); ?>" />
				</span>
			</td>
			</tr>

			</tbody>
		</table>
	</form>

	<?php
}


/*
 * The hook to add a admin page for quick edit.
 *
 * @since 1.3.0
 */
function spd_adminmenu_quick_add() {

	if ( ! current_user_can('publish_posts') ) {
		return;
	}

	add_submenu_page( 'edit.php?post_type=spd_prayer_item', esc_html__( 'Quick Add', 'simple-prayer-diary'), /* translators: Menu entry */ esc_html__('Quick Add', 'simple-prayer-diary'), 'publish_posts', 'spd-admin-quick-add.php', 'spd_quick_add_page' );

}
add_action('admin_menu', 'spd_adminmenu_quick_add');


/*
 * Save data entered into the admin page for quick edit.
 *
 * @since 1.3.0
 */
function spd_quick_add_save() {

	if ( isset($_POST['spd_quick_add_action']) && $_POST['spd_quick_add_action'] === 'spd_quick_add_action' ) {

		$verified = false;
		if ( isset( $_POST['spd_dashboard_quick_add'] ) ) {
			$nonce = $_POST['spd_dashboard_quick_add'];
			$verified = wp_verify_nonce( $nonce, 'spd_dashboard_quick_add' );
		}
		if ( $verified === false ) {
			spd_quick_add_get_message( esc_html__( 'Unable to submit this form, please refresh and try again.', 'simple-prayer-diary' ) );
			return;
		}

		if ( ! current_user_can('publish_posts') ) {
			spd_quick_add_get_message( esc_html__( 'Unable to submit this form, you have no permissions to publish a prayer item.', 'simple-prayer-diary' ) );
			return;
		}

		$spd_dashboard_title = '';
		if ( isset($_POST['spd_dashboard_title'] )) {
			$spd_dashboard_title = wp_kses_post($_POST['spd_dashboard_title']);
		}

		$spd_dashboard_content = '';
		if ( isset($_POST['spd_dashboard_content']) ) {
			$spd_dashboard_content = wp_kses_post($_POST['spd_dashboard_content']);
		}
		// Wrap content in the Paragraph block.
    /*
		if ( false === strpos( $spd_dashboard_content, '<!-- wp:paragraph -->' ) ) {
			$spd_dashboard_content = sprintf(
				'<!-- wp:paragraph -->%s<!-- /wp:paragraph -->',
				str_replace( array( "\r\n", "\r", "\n" ), '<br />', $spd_dashboard_content )
			);
		}
    */
    $spd_dashboard_content = str_replace( array( "\r\n", "\r", "\n" ), '<br />', $spd_dashboard_content );

		$date_was_posted = true;
	  foreach ( array( 'yy', 'mm', 'dd' ) as $timeunit ) {
			if ( empty( $_POST["$timeunit"] ) ) {
				$date_was_posted = false;
				break;
			}
		}

		$date = current_time( 'timestamp' );
		$dd = date_i18n( 'd', $date );
		$mm = date_i18n( 'm', $date );
		$yy = date_i18n( 'Y', $date );
		$hh = '23';
		$mn = '59';
		$ss = '00';
		if ( $date_was_posted === true ) {
			$yy = $_POST['yy'];
			$mm = $_POST['mm'];
			$dd = $_POST['dd'];
			$dd = ( $dd > 31 ) ? 31 : $dd;
		}
    $post_date = "$yy-$mm-$dd $hh:$mn:$ss";
    $mm_name = date('M', strtotime($post_date));
    $title = "$mm_name $dd";

		/* Setting both dates will set the published date to this, instead of when moderating. */
		$post_date_gmt = get_gmt_from_date( $post_date );

		$allowed_stati = array( 'future', 'draft', 'publish' );
		$spd_dashboard_status = 'future';
		if ( isset($_POST['spd_dashboard_status']) ) {
			$posted_status = wp_kses_post($_POST['spd_dashboard_status']);
			foreach ( $allowed_stati as $allowed_status ) {
				if ( $posted_status === $allowed_status ) {
					$spd_dashboard_status = $posted_status;
				}
			}
		}

		$post_data = array(
			'post_parent'    => 0,
			'post_status'    => $spd_dashboard_status,
			'post_type'      => 'spd_prayer_item',
			'post_date'      => $post_date,
			'post_date_gmt'  => $post_date_gmt,
			'post_author'    => get_current_user_id(),
			'post_password'  => '',
			'post_content'   => $spd_dashboard_content,
			'post_title'     => $title,
			'menu_order'     => 0,
		);

		if ( isset($_POST['tax_input']['spd_category']) ) {
			$tax_input = $_POST['tax_input'];
			$post_data['tax_input']['spd_category'] = array_map( 'absint', $tax_input['spd_category'] );
		}

		$post_id = wp_insert_post( $post_data );

		if ( empty( $post_id ) ) {
			spd_quick_add_get_message( esc_html__( 'Sorry, something went wrong with saving your prayer item. Please contact a site admin.', 'simple-prayer-diary' ) );
		} else {
			spd_quick_add_get_message( esc_html__( 'Your prayer item was saved.', 'simple-prayer-diary' ) );
		}

	}

}
add_action('admin_init', 'spd_quick_add_save');


/*
 * Set and/or get message for the admin page for quick edit.
 *
 * @param  string text string with message.
 * @return string text string with message.
 *
 * @since 1.3.0
 */
function spd_quick_add_get_message( $message = '' ) {

	static $message_static;

	if ( $message_static ) {
		return $message_static;
	} else {
		$message_static = '';
	}

	if ( strlen( $message ) > 0 ) {
		$message_static = wp_kses_post( $message );
	}

	return $message_static;

}
