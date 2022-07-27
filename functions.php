<?php
/*This file is part of the XTen Child Theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function enqueue_child_styles() {
	$parent_style = 'parent-style';
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array( 'xten-vendor-bootstrap-css' ) );
		wp_enqueue_style(
			'child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( $parent_style, 'xten-base-style' ),
			filemtime( get_stylesheet_directory() . '/style.css' ) 
		);

		// Register Styles
		$child_theme_css_path = '/assets/css/child-theme.min.css';
		wp_register_style( 'child-theme-css', get_stylesheet_directory_uri() . $child_theme_css_path, array( 'xten-base-style' ), filemtime( get_stylesheet_directory() . $child_theme_css_path ), 'all' );

		// Register Scripts
		$child_theme_js_path = '/assets/js/child-theme.min.js';
		wp_register_script( 'child-theme-js', get_stylesheet_directory_uri() . $child_theme_js_path, array(), filemtime( get_stylesheet_directory() . $child_theme_js_path ), true );

		// Enqueue Styles
		wp_enqueue_style( 'child-theme-css' );

		// Enqueue Scripts
		wp_enqueue_script( 'child-theme-js' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99 );
	
// IF ACF-JSON is a requirement create the acf-json folder and uncoment the following
// // Load fields.
add_filter('acf/settings/load_json', 'child_acf_json_load_point');

function child_acf_json_load_point( $paths ) {

    // remove original path (optional)
    unset($paths[0]);


    // append path
    $paths[] = get_stylesheet_directory() . '/acf-json';


    // return
    return $paths;

}

function change_video_source_on_cover_block( $block_content, $block ) {
	if (
		'core/cover' !== $block['blockName'] &&
		! isset( $block['attrs']['url'] )
	) :
		return $block_content;
	endif;
	/*
	?><pre><?php var_dump( $block['attrs']['url']); ?></pre><?php
	die;
	*/
	$return  = 'my-image-block<div class="my-image-block">';
	$return .= $block_content;
	$return .= '</div>';

	return $return;
}

// add_action('enqueue_block_editor_assets', function() {
// 	wp_enqueue_script('mlg-gutenberg-filters', get_stylesheet_directory_uri() . '/assets/js/gutenberg-filters.min.js', ['wp-edit-post']);
// });

/**
 * Utility Functions
 */
require get_stylesheet_directory() . '/inc/utility-functions.php';

/**
 * Shortcodes
 */
require get_stylesheet_directory() . '/inc/shortcodes.php';

/**
 * Custom Post Types.
 */
require get_stylesheet_directory() . '/inc/custom-post-types.php';

/**
 * Widgets.
 */
require get_stylesheet_directory() . '/inc/widgets/offices-widget.php';

/**
 * Include WPCF7 Custom Tags
 */
require get_stylesheet_directory() . '/inc/wpcf7-custom-tags.php';

function xten_use_short_practice_area_title_for_menu_items( $title, $menu_item, $args, $depth ) {
	if (
		$menu_item->type === 'post_type' &&
		$menu_item->object === 'practice-areas'
	) :
		$title = esc_attr( get_field( 'short_title', $menu_item->object_id ) ) ? : get_the_title();
	endif;
	return $title;
}
add_filter( 'nav_menu_item_title', 'xten_use_short_practice_area_title_for_menu_items', 10, 4 );

function xten_add_icon_to_practice_area_menu_items( $item_output, $item, $depth, $args ) {
	if (
		$item->type === 'post_type' &&
		$item->object === 'practice-areas'
	) :
		$icon      = xten_get_fc_icon( $item->object_id );
		$fragments = explode('>', $item_output);
		$string    = '';
		foreach ( $fragments as $index => $fragment ) :
			if ( $index === array_key_last( $fragments ) ) :
				break;
			endif;
			$string .= "$fragment>";
			if ( $index === array_key_first( $fragments ) ) :
				$string .= "<span class=\"practice-area-icon\">$icon</span>";
			endif;
		endforeach;
		$item_output = $string;
		/*?><pre><?php
		var_dump($string);
		?></pre><?php
		die;*/
	endif;

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'xten_add_icon_to_practice_area_menu_items', 10, 4 );
