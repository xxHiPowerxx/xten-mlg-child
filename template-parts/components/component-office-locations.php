<?php
/**
 * This Component Renders Office Locations and Map
 * @package xten
 */
function component_office_locations( $args = null ) {

	// Enqueue Stylesheet.
	$handle             = 'office-locations';
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

	$component_id                       = xten_register_component_id( $handle );
	$component_attrs_array              = array();
	$component_attrs_array['data-c-id'] = $component_id;
	$component_attrs_array['class']     = "component-$handle";

	$component_selector                 = "[data-c-id='{$component_attrs_array['data-c-id']}'].{$component_attrs_array['class']}";


	$_offices = array();

	$office_locations = get_field(
		'office_locations_component_group',
		'options'
	);
	$map_image  = $office_locations['map_image'];
	if ( $map_image ) :
		$map_img_elm = wp_get_attachment_image(
			$map_image,
			array(1090, null)
		);
	endif;

	// $offices = array();
	// $map_markers = $office_locations['map_marker_repeater'];
	// foreach ( $map_markers as $key=>$map_marker ) :
	// 	$offices[$key] = $map_marker['office'];
	// endforeach;
	// $offices = $office_locations['map_marker_repeater'];

	$component_attrs_s = xten_stringify_attrs( $component_attrs_array );

	ob_start();
	?>
	<div <?php echo $component_attrs_s; ?>>
		<div class="offices-list-wrapper">
			<?php
			foreach( $office_locations['map_marker_repeater'] as $key=>$map_marker ) :
				$office        = $map_marker['office'];
				$_offices[$key]['coordinates'] = $map_marker['coordinates_group'];

				$open                          = $key === 0;
				$_offices[$key]['open']        = $open;
				$office_name                   = esc_attr( $office->post_title );
				$office_name_s                 = xten_split_office_title( $office_name );
				$office_name_sel               = str_replace( ' ', '-', str_replace( '/', '', $office_name ) );
				$_offices[$key]['office_name'] = $office_name;
				$_offices[$key]['office_name_s'] = $office_name_s;

				$target                              = "$component_id-collapse-$key";
				$control_attrs_a                     = array();
				$control_attrs_a['id']               = "$component_id-collapse-control-$key";
				$control_attrs_a['class']            = "office-location-collapse-control collapse-control preventExpandedCollapse";
				$control_attrs_a['data-toggle']      = 'collapse';
				$control_attrs_a['aria-controls']    = $target;
				$control_attrs_a['data-target']      = "#$target";
				$control_attrs_a['aria-expanded']    = $open ? 'true' : 'false';
				$control_attrs_a['aria-label']       = 'Toggle ' . wp_strip_all_tags( $office_name ) . ' Office Location';
				$control_attrs_a['tabindex']         = '0';

				$collapse_attrs_a                    = array();
				$collapse_attrs_a['id']              = $target;
				$collapse_attrs_a['data-labelledby'] = $control_attrs_a['id'];
				$open_class                          = $open === true ? ' show' : null;
				$collapse_attrs_a['class']           = "collapse collapse-content office-location-collapse";
				$collapse_attrs_a['class']           = "map-marker-office-content collapse$open_class";
				$collapse_attrs_a['data-parent']     = "[data-c-id='{$component_attrs_array['data-c-id']}']";

				$control_attrs_s   = xten_stringify_attrs( $control_attrs_a );
				$collapse_attrs_s  = xten_stringify_attrs( $collapse_attrs_a );
				$_offices[$key]['control_attrs_a'] = $control_attrs_a;

				$args = array(
					'post_id'            => $office,
					'inc_featured_image' => true,
				);
				?>
				<div class="office-location-collapse-wrapper">
					<div <?php echo $control_attrs_s; ?>>
						<span class="office-location-icon fa fa-map-marker-alt"></span>
						<h4 class="office-title nowrap-parent"><?php echo $office_name_s; ?></h4>
					</div>
					<div <?php echo $collapse_attrs_s; ?>>
						<?php echo xten_render_component( 'office', $args ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="office-locations-map-and-markers">
			<?php if ( $map_img_elm ) : ?>
				<div class="office-locations-map">
					<?php
					echo $map_img_elm;

					if ( ! empty( $_offices ) ) : ?>
						<div class="office-locations-map-markers-wrapper">
							<?php
							foreach ( $_offices as $_office ) :
								// $_office['control_attrs_a']['id'] .= '-map-marker';
								$_office['control_attrs_a']['class'] = "office-location-map-marker-collapse-control collapse-control map-marker-{$_office['office_name_sel']} preventExpandedCollapse";

								$map_marker_selector = "#{$_office['control_attrs_a']['id']}.map-marker-{$_office['office_name_sel']}";

								$x_coordinates = $_office['coordinates']['x_coordinates'];
								$y_coordinates = $_office['coordinates']['y_coordinates'];
								if ( $x_coordinates || $y_coordinates ) :
									$rules = array();
									if ( $x_coordinates ) :
										$rules['left'] = esc_attr( $x_coordinates );
									endif;
									if ( $y_coordinates ) :
										$rules['top'] = esc_attr( $y_coordinates );
									endif;
									$styles .= xten_add_inline_style(
										$map_marker_selector,
										$rules
									);
								endif;

								$_control_attrs_s = xten_stringify_attrs( $_office['control_attrs_a'] );
								?>
								<div <?php echo $_control_attrs_s; ?>>
									<div class="map-marker-icon fa fa-map-marker-alt"></div>
									<?php
									if ( $_office['office_name_s'] ) :
										?>
										<div class="map-marker-office-name-wrapper">
											<div class="map-marker-office-name-bg"></div>
											<h5 class="map-marker-office-name nowrap-parent"><?php echo $_office['office_name_s']; ?></h5>
										</div>
									<?php endif; ?>
								</div>
								<?php
							endforeach;
							?>
						</div>
					<?php endif // endif ( ! empty( $_offices ) ) : ?>
				</div>
			<?php endif; // endif ( $map_img_elm ) : ?>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	if ( $styles !== '' ) :
		wp_register_style( $component_id, false );
		wp_enqueue_style( $component_id );
		wp_add_inline_style( $component_id, $styles );
	endif;

	return $html;

}
