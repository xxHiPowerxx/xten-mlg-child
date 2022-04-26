<?php
/**
 * This Component Renders ONE Office.
 * @package xten
 */
function component_office( $args = null ) {

	// Enqueue Stylesheet.
	$handle             = 'office';
	$component_handle   = 'component-' . $handle;
	$component_css_path = '/assets/css/' . $component_handle . '.min.css';
	wp_register_style(
		$component_handle . '-css',
		get_theme_file_uri( $component_css_path ),
		array(
			'child-style',
			'xten-vendor-fancybox-css',
		),
		filemtime( get_stylesheet_directory() . $component_css_path ),
		'all'
	);

	wp_enqueue_style( $component_handle . '-css' );
	wp_enqueue_script( 'xten-fancybox-js' );

	$styles              = '';
	$component_id        = xten_register_component_id( $handle );
	$post_id             = $args['post_id'];
	$inc_featured_image  = $args['inc_featured_image'];
	$featured_image      = null;

	if ( $inc_featured_image ) :
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		$image_size   = array(null, 222.75);
		// Remove that filter
		add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
		$featured_image      = get_the_post_thumbnail(
			$post_id,
			$image_size,
			array( 'class' => 'office-featured-image fancybox' )
		);
		// add srcset filter back
		remove_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
	endif;

	$office_title        = get_the_title( $post_id );
	$office_address      = get_field( 'office_address', $post_id );
	$address_line_1      = esc_attr( $office_address['address_line_1'] );
	$address_line_2      = esc_attr( $office_address['address_line_2'] );
	$city                = esc_attr( $office_address['city'] );
	$state               = esc_attr( $office_address['state'] );
	$zip_code            = esc_attr( $office_address['zip_code'] );
	$office_phone_number = esc_attr( get_field( 'office_phone_number', $post_id ) );
	$google_maps_link    = esc_url( get_field( 'google_maps_link', $post_id ) );
	$google_maps_attrs   = $google_maps_link ?
		'href=' . $google_maps_link . ' target="_blank" title="View on Google Maps (opens in a new tab)"' :
		null;
	$office_open         = $office_phone_number !== '';
	$has_address         = false;
	foreach ( $office_address as $address_item ) :
		if ( $address_item !== '' ) :
			if ( $office_open === false ) :
				$office_open = true;
			endif; // endif ( $office_open === false ) :
			$has_address = true;
			break;
		endif;
	endforeach;
	ob_start();
	?>
	<div id="<?php echo $component_id; ?>" class="component-<?php echo $handle; ?>">
		<?php if ( $featured_image ) : ?>
			<div class="office-featured-image-wrapper">
				<?php echo $featured_image; ?>
			</div>
		<?php endif; ?>
		<div class="office-title-content-wrapper">
			<?php
			if ( $office_title ) :
				$_office_title = xten_split_office_title( $office_title );
				?>
				<h4 class="office-title"><span class="office-title-inner nowrap-parent"><?php echo $_office_title; ?></span></h4>
			<?php endif; ?>
			<?php if ( $office_open ) : ?>
				<div class="office-content">
					<?php if ( $office_phone_number !== '' ) : ?>
						<a class="anchor-office-phone-number" href="tel:<?php echo $office_phone_number; ?>"><span class="office-icon fas fa-mobile-alt"></span><span class="office-phone-number"><?php echo $office_phone_number; ?></span></a>
					<?php endif; ?>
					<?php if ( $has_address ) : ?>
						<a class="anchor-office-address" <?php echo $google_maps_attrs; ?>>
							<span class="office-icon fas fa-map-marker-alt"></span>
							<div class="office-address-wrapper">
								<?php if ( $address_line_1 !== '' || $address_line_2 !== '' ) : ?>
									<?php if ( $address_line_1 ) : ?>
										<span class="office-address-line-1"><?php echo $address_line_1; ?></span>
									<?php endif;?>
									<?php if ( $address_line_2 ) : ?>
										<span class="office-address-line-2"><?php echo $address_line_2; ?></span>
									<?php endif;?>
								<?php endif; // endif ( $address_line_1 !== '' || $address_line_2 !== '' ?>
								<?php
								if (
									$city !== '' ||
									$state !== '' ||
									$zip_code !== ''
								) :
								?>
									<span class="office-address-c-s-z">
										<?php if ( $city !== '' ) : ?>
											<span class="office-city"><?php echo $city; ?></span>
										<?php endif; ?>
										<?php if ( $state !== '' ) : ?>
											<span class="office-state"><?php echo $state; ?></span>
										<?php endif; ?>
										<?php if ( $zip_code !== '' ) : ?>
											<span class="office-zip-code"><?php echo $zip_code; ?></span>
										<?php endif; ?>
									</span>
								<?php endif; // endif ( $c || $s || $z ) : ?>
							</div>
						</a>
					<?php endif; // endif ( $has_address ) : ?>
				</div>
			<?php
			else :
				$coming_soon_text = xten_kses_post( get_field( 'coming_soon_text', $post_id, false ) ) ? : 'Coming Soon!';
				?>
				<div class="office-coming-soon">
					<span class="office-coming-soon-text"><?php echo $coming_soon_text; ?></span>
				</div>
			<?php endif; // endif ( ! $office_open ) : ?>
		</div>
	</div>

	<?php
	$html = ob_get_clean();
	return $html;

}
