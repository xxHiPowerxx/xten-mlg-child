<?php
/**
 * File that includes shortcode functions.
 *
 *
 * @package xten
 */

/**
 * Offices Shortcode
 * Renders Offices
 */
function offices_list_shortcode( $atts = '' ) {
	// When Shortcode is used $atts defaults to ''.
	// Ensure that this gets converted to an array.
	$atts = $atts === '' ? array() : $atts;

	// Get Component Function.
	return xten_render_component( 'offices-list' );
}
add_shortcode( 'offices_list', 'offices_list_shortcode' );

/**
 * Practice Areas Shortcode
 * Renders Practice Areas
 */
function practice_areas_list_shortcode( $atts = '' ) {
	// When Shortcode is used $atts defaults to ''.
	// Ensure that this gets converted to an array.
	$atts = $atts === '' ? array() : $atts;

	$atts = shortcode_atts( array( 'is_listed' => true ), $atts );

	// Get Component Function.
	$post_type  = 'practice-areas';
	$args       = array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	);
	$post_query = new WP_Query( $args );
	ob_start();
	if ( $post_query->have_posts() ) :
		?>
		<div class="practice-areas-list">
			<?php
			while ( $post_query->have_posts() ) :
				$post_query->the_post();
				get_template_part( 'template-parts/content-archive', $post_type, $atts );
			endwhile;
			?>
		</div>
		<?php
	endif;
	wp_reset_query();
	return ob_get_clean();
}
add_shortcode( 'practice_areas_list', 'practice_areas_list_shortcode' );