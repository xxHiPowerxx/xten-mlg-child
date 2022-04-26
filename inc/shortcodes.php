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