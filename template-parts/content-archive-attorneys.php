<?php
/**
 * Template part for displaying a single archive-attorney in a collection of archive-attorneys
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

// Enqueue Stylesheet.
$handle             = 'content-archive-attorneys';
$component_css_path = '/assets/css/' . $handle . '.min.css';
$component_css_file = get_stylesheet_directory() . $component_css_path;
if ( file_exists( $component_css_file ) ) :
	wp_register_style(
		$handle . '-css',
		get_theme_file_uri( $component_css_path ),
		array(
			'child-style',
		),
		filemtime( $component_css_file ),
		'all'
	);
	wp_enqueue_style( $handle . '-css' );
endif;

global $post;
$post_type          = get_post_type( $post );
$singular_post_name = str_replace( ' ', '-', strtolower( get_post_type_labels( get_post_type_object( $post_type ) )->singular_name ) );
$permalink          = esc_url( get_permalink( $post ) );
$post_id            = get_the_id( $post );
$component_name     = "listed-$singular_post_name";

$attorney_title      = $args['attorney_title'] ? : esc_attr( get_field( 'attorney_title' ) );
$attorney_title_attr = str_replace( ' ', '-', strtolower( $attorney_title ) );

// TODO: Get OptimalAttorney Portait Size size
// $attorney_portrait_id = get_post_thumbnail_id( $post );
// $attorney_portrait_optimal_image_size = xten_get_optimal_image_size(
// 	$attorney_portrait_id,
// 	array(null, 422),
// 	array(1, 1)
// );
$portrait_size      = ( $attorney_title === 'Founding Partner' || $attorney_title === 'Senior Partner' ) ?
	448 :
	273;
$attorney_portrait  = get_the_post_thumbnail(
	$post,
	$portrait_size,
	// $attorney_portrait_optimal_image_size,
	array(
		'class' => "$component_name-portrait"
	)
);

$attorney_full_name       = get_the_title( $post );
$attorney_full_name_array = explode(' ', $attorney_full_name );
$attorney_first_name      = $attorney_full_name_array[0];
$attorney_formatted_name  = str_replace( '.', '', str_replace( ' ', '-', strtolower( $attorney_full_name ) ) );
$archive_id               = esc_attr( "archive-attorney-$attorney_formatted_name" );
$anchor_tooltip           = esc_attr( "Read More About $attorney_first_name" );

// $attorney_practice_areas        = get_field( 'attorney_practice_areas' );

?>
<div id="<?php echo "$archive_id" ?>" <?php post_class( "$component_name $attorney_title_attr"  ); ?>>
	<a id="<?php echo "anchor-$archive_id" ?>" class="<?php echo "anchor-$component_name" ?>" href="<?php echo $permalink; ?>" title="<?php echo $anchor_tooltip; ?>">
		<div class="<?php echo "$component_name-portrait-wrapper"; ?>">
			<?php echo $attorney_portrait; ?>
		</div>
		<div class="<?php echo "$component_name-name-title"; ?>">
			<span class="<?php echo "$component_name-name"; ?>"><?php echo $attorney_full_name; ?></span>
			<span class="<?php echo "$component_name-title"; ?>"><?php echo $attorney_title; ?></span>
		</div>
	</a>
</div><!-- #<?php echo "$archive_id" ?> -->
