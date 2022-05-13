<?php
/**
 * Template part for displaying a single archive-practice-area in a collection of archive-practice-areas
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xten
 */

// Enqueue Stylesheet.
$handle             = 'content-archive-practice-areas';
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
$post_type_id       = esc_attr( "$post_type-$post_id" );
$component_name     = "listed-$singular_post_name";
?>
<a id="<?php echo "$post_type_id" ?>" <?php post_class( $component_name .' box-shadow-em' ); ?> href="<?php echo $permalink; ?>" rel="bookmark">
	<div class="post-body">
		<div class="post-body-inner">
			<div class="<?php echo $component_name ?>-background">
				<?php
				$featured_image = get_the_post_thumbnail(
					$post_id,
					'full',
					array('class'=>"$component_name-featured-image")
				);
				if ( $featured_image ) :
					?>
					<div class="<?php echo $component_name ?>-featured-image-wrapper">
						<?php echo $featured_image; ?>
					</div>
					<?php
				endif;
				?>
				<div class="<?php echo $component_name ?>-background-overlay gradient-scroll"></div>
			</div>
			<div class="<?php echo $component_name ?>-foreground">
				<header class="entry-header">
					<div class="entry-meta xten-highlight-font">
						<?php
						$title           = esc_attr( get_field( 'short_title' ) ) ? : get_the_title();
						$icon_row_layout = null;
						if ( have_rows( 'icon_fc' ) ) :
							while ( have_rows( 'icon_fc' ) ) :
								the_row();
								$icon_row_layout = get_row_layout();
								break;
							endwhile;
						endif;
						$icon = xten_get_icon_fc( $icon_row_layout );
						if ( is_singular() && ! $args['is_listed'] ) :
							?>
							<h1 class="entry-title">
								<?php if ( $icon ) : ?>
									<span class="<?php echo $component_name; ?>-icon"><?php echo $icon; ?></span>
								<?php endif; ?>
								<span class="<?php echo $component_name; ?>-title"><?php echo $title; ?></span>
							</h1>
							<?php
						else :
							?>
							<h5 class="entry-title">
								<?php if ( $icon ) : ?>
									<span class="<?php echo $component_name; ?>-icon"><?php echo $icon; ?></span>
								<?php endif; ?>
								<span class="<?php echo $component_name; ?>-title"><?php echo $title; ?></span>
							</h5>
							<?php
						endif;
						?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->
				<?php
				$description = xten_kses_post( xten_get_post_meta_description() );
				if ( $description ) :
					?>
					<div class="entry-content">
						<?php echo $description; ?>
					</div><!-- .entry-content -->
				<?php endif; // endif ( $description ) : ?>
			</div>
		</div>
	</div>
</a><!-- #<?php echo "$post_type_id" ?> -->
