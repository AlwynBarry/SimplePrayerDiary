<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Shortcode to be used in the content. Displays the prayer diary simply in nested DIVs that can be styled.
 *
 * Parameters:
 *   - tax_query category parameters for term_ids (since 1.4.0).
 *   - posts_per_page integer, default -1 (all posts) (since 1.4.1).
 *
 * @since 1.0.0
 */
function spd_shortcode( $atts ) {

	$shortcode_atts = shortcode_atts( array( 'posts_per_page' => -1 ), $atts );
	$posts_per_page = (int) $shortcode_atts['posts_per_page'];
	if ( $posts_per_page === -1 ) {
		$nopaging = true;
	} else {
		$nopaging = false;
	}

	$output = '';

	$tax_query = array();
	if ( ! empty( $atts['category'] ) ) {
		$cat_in = explode( ',', $atts['category'] );
		$cat_in = array_map( 'absint', array_unique( (array) $cat_in ) );
		if ( ! empty( $cat_in ) ) {
			$tax_query['relation'] = 'OR';
			$tax_query[] = array(
				'taxonomy'         => 'spd_category',
				'terms'            => $cat_in,
				'field'            => 'term_id',
				'include_children' => true,
			);
		}
	}

	$args = array(
		'post_type'      => 'spd_prayer_item',
		'post_status'    => 'future',
		'posts_per_page' => $posts_per_page,
		'nopaging'       => $nopaging,
		'orderby'        => 'date',
		'order'          => 'ASC',
		'tax_query'      => $tax_query,
	);

	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		$output .= '
		<div class="spd-shortcode-prayer-diary">
		';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$postlink = '';
			if ( is_user_logged_in() ) {
				$postlink = get_edit_post_link( get_the_ID() );
				if ( strlen( $postlink ) > 0 ) {
					$postlink = ' <a class="post-edit-link" href="' . esc_attr( $postlink ) . '">' . esc_html__('(edit)', 'simple-prayer-diary') . '</a>';
				}
			}
			$classes = spd_get_term_classes( get_the_ID() );
			$output .= '
			<div class="' . $classes . '">
				<div class="spd-title">' . get_the_title() . ' </div>
				<div class="spd-content">' . get_the_content() . $postlink . '</div>
			</div>
      ';
		}
		$output .= '
		</div>
		';


	} else {
		// no posts found
		esc_html__( 'No future prayer items found', 'simple-prayer-diary' );
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	return $output;

}
add_shortcode( 'simple_prayer_diary', 'spd_shortcode' );


/*
* days_in_month($month, $year)
* Returns the number of days in a given month and year, taking into account leap years.
*
* $month: numeric month (integers 1-12)
* $year: numeric year (any integer)
*
* Prec: $month is an integer between 1 and 12, inclusive, and $year is an integer.
* Post: none
*/
// corrected by ben at sparkyb dot net
function days_in_month($month, $year)
{
  // calculate number of days in a month
  return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}



/*
 * Shortcode to be used in the content to display the prayer diary in a month format.
 *
 * Parameters:
 *   - tax_query category parameters for term_ids
 *   - month integer 1-12, default 0 (current month)
 *
 * @since 1.5.0
 */
function spd_month_shortcode( $atts ) {

  $shortcode_atts = shortcode_atts( array( 'month' => 0 ), $atts );
  $month = (int) $shortcode_atts['month'];
  $date = current_time( 'timestamp' );
  $year = (int) date_i18n( 'Y', $date );
  if ( $month === 0 ) {
    $month = date_i18n( 'n', $date );
  } else {
    if ( $month < 1 ) {
      $month = 1;
    } else {
      if ( $month > 12) {
        $month = 12;
      }
    }
  }

  $tax_query = array();
  if ( ! empty( $atts['category'] ) ) {
    $cat_in = explode( ',', $atts['category'] );
    $cat_in = array_map( 'absint', array_unique( (array) $cat_in ) );
    if ( ! empty( $cat_in ) ) {
      $tax_query['relation'] = 'OR';
      $tax_query[] = array(
				'taxonomy'         => 'spd_category',
				'terms'            => $cat_in,
				'field'            => 'term_id',
				'include_children' => true,
			);
		}
	}

	$args = array(
		'date_query' => array(
      'year'           => $year,
      'monthnum'       => $month,
                          ),
    'post_type'      => 'spd_prayer_item',
    'post_status'    => 'publish,future',
		'orderby'        => 'date',
		'order'          => 'ASC',
    'nopaging'       => true,
    'posts_per_page' => -1,
		'tax_query'      => $tax_query,
	);

	// The Query
	$the_query = new WP_Query( $args );

  /* Initialise the string gathering the output */
	$output = '';
  
	/* The Loop (hidden a bit in the loop to output the calendar table) */
	if ( $the_query->have_posts() ) {
		$output .= '
    <div class="spd-shortcode-month-header">
    ';
    $output .= date_i18n( 'F', $date ); /* The full month name */
    $output .= '
    </div>
		<div class="spd-shortcode-prayer-diary">
      <table class="spd-responsive-table">
        <thead>
          <tr>
    ';
    /* Add the day headers for the table */
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Mon', 'simple-prayer-diary') . '</td>';
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Tue', 'simple-prayer-diary') . '</td>';
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Wed', 'simple-prayer-diary') . '</td>';
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Thu', 'simple-prayer-diary') . '</td>';
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Fri', 'simple-prayer-diary') . '</td>';
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Sat', 'simple-prayer-diary') . '</td>';
    $output .= '        <td class="spd-day-header">' . esc_html__( 'Sun', 'simple-prayer-diary') . '</td>';
    $output .= '
          </tr>
        </thead>
        <tbody>
          <tr>
    ';

    /* Find the number of days in this month, and the offset into the month of the first day */
    $numberOfDays = days_in_month( $month, $year );
    $firstDate = new DateTime($year . '-' . $month . '-01');
    $offset = $firstDate->format('w');
    $row_number = 1;

    /* Output the extra days at the start of the month */
    for ( $i = 1; $i <= $offset; $i++ ) {
      $output .= '<td class="spd-no-day"></td>';
    }

    /* Get the first post in the month to start with */
		$the_query->the_post();
    $queryDay = (int) get_the_date('j');
      
    /* Work through the actual days in the month */
    $day = 1;
    while ( $day <= $numberOfDays ) {
      if ( ($day + $offset - 1) % 7 == 0 && $day != 1 ) {
        $output .= '
          </tr>
          <tr>
        ';
        $row_number++;
      }
      $output .= '<td class="spd-day"><div class="spd-day-cell">';
      $output .= '<div class="spd-day-date">' . $day . '</div>';
      while ( $day == $queryDay ) {
        $postlink = '';
        if ( is_user_logged_in() ) {
          $postlink = get_edit_post_link( get_the_ID() );
          if ( strlen( $postlink ) > 0 ) {
            $postlink = ' <a class="post-edit-link" href="' . esc_attr( $postlink ) . '">' . esc_html__('(edit)', 'simple-prayer-diary') . '</a>';
          }
        }
        $classes = spd_get_term_classes( get_the_ID() );
        $output .= '
          <div class="spd-day-content">' . get_the_content() . $postlink . '</div>
        ';
        /* Get the next post */
        if ( $the_query->have_posts() ) {
          $the_query->the_post();
          $queryDay = (int) get_the_date('j');
        } else {
          $queryDay = 32; /* One more than the maximum number of days */
        }
      }
      $output .= '</div></td>';
      $day++;
    }
 
    /* Output the rest of the month table beyond the end of the month */
    while ( ($day + $offset) <= $row_number * 7 ) {
      $output .= '<td class="spd-no-day"></td>';
      $day++;
    }

    /* Finish the table */
		$output .= '
          </tr>
        </tbody>
      </table>
		</div>
		';


	} else {
		/* no posts found */
		esc_html__( 'No prayer items found', 'simple-prayer-diary' );
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	return $output;

}
add_shortcode( 'simple_prayer_month_diary', 'spd_month_shortcode' );
