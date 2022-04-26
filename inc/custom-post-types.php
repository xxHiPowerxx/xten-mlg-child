<?php
/**
 * File included in functions for Creating Custom Post Types
 *
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * @package xten
 */

/**
 * A Function that will render our custom post types.
 */
function xten_child_custom_post_types() {
	/**
	 * Utility Function that Creates the Post Label programmaitcally.
	 */
	function create_post_labels(
		$post_singular,
		$post_plural      = null,
		$post_description = null
	) {
		$post_plural      = $post_plural === null ?
												$post_singular . 's' :
												$post_plural;
		$post_description = $post_description === null ?
												$post_plural :
												$post_description;
		$post_handle      = str_replace( ' ','-', strtolower( $post_plural ) );

		$return_result    = array(
			'post_singular'    => $post_singular,
			'post_plural'      => $post_plural,
			'post_description' => $post_description,
			'post_handle'      => $post_handle,
		);

		return $return_result;
	}
		
	/**
	 * Create Custom Options Page for Post Type.
	 */
	if ( $settings_page || function_exists( 'acf_add_options_page' ) ) :
		acf_add_options_sub_page(
			array(
				'page_title'  => $post_plural . ' Settings',
				'menu_title'  => $post_plural . ' Settings',
				'parent_slug' => 'edit.php?post_type=' . $post_handle,
			)
		);
	endif;

	/*   Offices   */

	/**
	 * Create Offices Custom Post Type
	 */
	$post_singular    = 'Office';
	$post_names       = create_post_labels(
		$post_singular,
		null,
		'Offices and their Locations and Phone Numbers'
	);
	$post_plural      = $post_names['post_plural'];
	// Get ACF Field created for Office Descriptions.
	$post_description = get_field( $post_handle . '_description', 'option' ) ? :
		$post_names['post_description'];
	$post_handle      = $post_names['post_handle'];

	$icon             = 'location';
	$menu_icon        = 'dashicons-' . $icon;
	$theme            = 'xten';
	// Set UI labels for Custom Post Type
	$post_labels = array(
		'name'               => _x( $post_plural, 'Post Type General Name', $theme ),
		'singular_name'      => _x( $post_singular, 'Post Type Singular Name', $theme ),
		'menu_name'          => __( $post_plural, $theme ),
		'parent_item_colon'  => __( 'Parent ' . $post_singular, $theme ),
		'all_items'          => __( 'All ' . $post_plural, $theme ),
		'view_item'          => __( 'View ' . $post_singular, $theme ),
		'add_new_item'       => __( 'Add New ' . $post_singular, $theme ),
		'add_new'            => __( 'Add New', $theme ),
		'edit_item'          => __( 'Edit ' . $post_singular, $theme ),
		'update_item'        => __( 'Update ' . $post_singular, $theme ),
		'search_items'       => __( 'Search ' . $post_singular, $theme ),
		'not_found'          => __( 'Not Found', $theme ),
		'not_found_in_trash' => __( 'Not found in Trash', $theme ),
	);
	// Set other options for Custom Post Type
	$args = array(
		'label'               => __( $post_plural, $theme ),
		'description'         => __( $post_description, $theme ),
		'labels'              => $post_labels,
		// Features this CPT supports in Post Editor
		'supports'            => array(
															'title',
															'thumbnail',
															'custom-fields',
															'page-attributes',
														),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		// 'taxonomies'          => array( 'practice-areas' ),
		/* A hierarchical CPT is like Pages and can have
		 * Parent and child items. A non-hierarchical CPT
		 * is like Posts.
		 */ 
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
		'show_in_rest'        => false,
		'menu_icon'           => $menu_icon,
		'rewrite'             => false,
	);
	// Registering your Custom Post Type
	register_post_type( $post_handle, $args );

	/*   /Offices   */

}

/**
 *  Hook into the 'init' action so that the function
 * Containing our post type registration is not
 * unnecessarily executed.
 */

add_action( 'init', 'xten_child_custom_post_types', 0 );