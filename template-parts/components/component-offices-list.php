<?php
/**
 * This Component Renders one or more Offices.
 * @package xten
 */
function component_offices_list( $args = null ) {

	// Enqueue Stylesheet.
	$handle             = 'offices-list';
	$component_handle   = 'component-' . $handle;
	$component_css_path = '/assets/css/' . $component_handle . '.min.css';
	$component_css_file = get_stylesheet_directory() . $component_css_path;
	if ( file_exists( $component_css_file ) ) :
		wp_register_style(
			$component_handle . '-css',
			get_theme_file_uri( $component_css_path ),
			array(
				'child-style',
			),
			filemtime( $component_css_file ),
			'all'
		);
		wp_enqueue_style( $component_handle . '-css' );
	endif;

	$styles = '';

	$post_ids = $args['post_ids'];

	$html = '';
	if ( is_numeric( $post_ids ) || is_object( $post_ids ) ) :
		$_args = array(
			'post_id'            => $post_ids,
			'inc_featured_image' => $args['inc_featured_image'],
		);
		$html .= xten_render_component( 'office', $args );
	else:
		// if ( $post_ids === null || $post_ids !== '' ) :
		if ( empty( $post_ids ) ) :
			$post_ids = get_posts( array (
				'post_type'   => 'offices',
				'numberposts' => -1,
				'order'       => 'ASC',
				'orderby'     => 'menu_order',
			) );
		endif;
		foreach ( $post_ids as $post_id ) :
			$_args = array(
				'post_id'            => $post_id,
				'inc_featured_image' => $args['inc_featured_image'],
			);
			$html .= xten_render_component( 'office', $_args );
		endforeach;
		$count = count( $post_ids );
		$multiple_attr_val;
		if ( $count % 5 === 0 ) :
			$multiple_attr_val = 'multiple-of-5';
		elseif ( $count % 4 === 0 ) :
			$multiple_attr_val = 'multiple-of-4';
		elseif ( $count % 3 === 0 ) :
			$multiple_attr_val = 'multiple-of-3';
		endif;
		$multiple_att = $multiple_attr_val ?
			" data-multiple=\"$multiple_attr_val\"" :
			null;
	endif;

	if ( $html !== '' ) :
		$component_id = xten_register_component_id( $handle );
		$start_tag    = '<div id="' . $component_id . '" class="component-offices-list"' . $multiple_att . '>';
		$end_tag      = '</div>';
		$html         = $start_tag . $html . $end_tag;
		return $html;
	endif;
}
