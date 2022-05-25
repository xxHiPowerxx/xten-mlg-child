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

/**
 * Attorneys Shortcode
 * Renders Attorneys
 */
function attorneys_list_shortcode( $atts = '' ) {
	// When Shortcode is used $atts defaults to ''.
	// Ensure that this gets converted to an array.
	$atts = $atts === '' ? array() : $atts;

	$atts = shortcode_atts( array( 'is_listed' => true ), $atts );

	// TODO: utilize xten_render_component() for this component.
	// Get Component Function.
	$post_type  = 'attorneys';
	$args       = array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'post_date'  => 'ASC',
		),
		// 'order'          => 'ASC',
	);
	$post_query = new WP_Query( $args );
	ob_start();
	if ( $post_query->have_posts() ) :
		$founding_partner     = '';
		$senior_partners      = '';
		$non_senior_attorneys = '';
		while ( $post_query->have_posts() ) :
			$post_query->the_post();
			$atts['attorney_title'] = get_field( 'attorney_title' );
			ob_start();
			get_template_part( 'template-parts/content-archive', $post_type, $atts );
			if ( $atts['attorney_title'] === 'Founding Partner' ) :
				$founding_partner .= ob_get_clean();
			elseif ( $atts['attorney_title'] === 'Senior Partner' ) :
				$senior_partners .= ob_get_clean();
			else:
				$non_senior_attorneys .= ob_get_clean();
			endif;
		endwhile;
		ob_start();
		?>
		<div class="attorneys-list">
			<?php if ( $founding_partner ) : ?>
				<div class="founding-partner-list">
					<?php echo $founding_partner; ?>
				</div>
				<?php
			endif;
			if ( $senior_partners ) :
				?>
				<div class="senior-partners-list">
					<?php echo $senior_partners; ?>
				</div>
				<?php
			endif;
			if ( $non_senior_attorneys ) :
				?>
				<div class="non-senior-attorneys-list">
					<?php echo $non_senior_attorneys; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	endif;
	wp_reset_query();
	return ob_get_clean();
}
add_shortcode( 'attorneys_list', 'attorneys_list_shortcode' );