<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xten
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> class="no-js xten-theme-bg-color">
	<head>
		<!--insert meta tags  -->
		<?php require_once get_template_directory() . '/inc/meta-tags.php'; ?>
		<?php wp_head(); ?>
	</head>

	<body data-spy="scroll" data-target="#xten-scroll-nav" data-offset="100" <?php body_class(); ?>>

		<div id="load-splash" class="loading">
			<?php
			$display_load_splash = get_theme_mod( 'xten_display_load_splash', true );
			if ( $display_load_splash ) :
				?>
				<div class="load-splash-inner">
					<?php echo $GLOBALS['xten-site-logo']; ?>
				</div>
				<?php
			endif; // endif ( $display_load_splash ) :
			?>
		</div>
		<div id="page" class="site">
			<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'xten' ); ?></a>

				<?php require_once get_template_directory() . '/inc/header/mobile-menu.php'; ?>

				<?php
				$size_header_pad = 'sizeHeaderPad';
				$size_header_ref = 'sizeHeaderRef';
				?>
				<div class="header-wrapper sizeHeaderRef">

					<?php
					$site_name                 = esc_attr( get_bloginfo() );
					$mobile_nav_breakpoint     = $GLOBALS['mobile_nav_breakpoint'];
					$root_dir                  = get_template_directory_uri();
					$font                      = 'roboto';
					$style                     =
						'@font-face {' .
							'font-family:' . $font . ';' .
							'src: url(' . $root_dir . '/assets/fonts/' . $font . '/' . $font . '.ttf);' .
						'}' .
						'@media (min-width:' . $mobile_nav_breakpoint . 'px ){' .
							'.xten-site-header .site-branding{' .
								'margin-right:6rem;' .
							'}' .
						'}';
					wp_register_style( 'xten-site-header-inline-style', false );
					wp_enqueue_style( 'xten-site-header-inline-style', '', 'xten-content-css' );
					wp_add_inline_style( 'xten-site-header-inline-style', $style );

					$child_logo_type   = ' logo-type-' . str_replace( '_', '-', $GLOBALS['xten-logo-type'] );
					$logo_link_classes = 'custom-logo-link' . $child_logo_type;
					$logo_link_attrs   = xten_stringify_attrs( array(
						'class'    => $logo_link_classes,
						'href'     => esc_url( home_url( '/' ) ),
						'rel'      => 'home',
						'itemprop' => 'url',
						'title'    => $site_name,
					) );
					?>
					<header id="masthead" class="site-header scrolledPastHeaderRef fixed-header">
						<div class="navbar" id="mainNav">
							<div class="container-fuid header-container">
								<div class="site-branding">
									<a <?php echo $logo_link_attrs; ?>>
										<span class="hide-me">Home Link</span>
										<div class="ctnr-custom-logo">
											<?php echo $GLOBALS['xten-site-logo']; ?>
										</div>
									</a>
								</div><!-- .site-branding -->
								<nav id="nav-mega-menu" class="main-navigation desktop-navigation navbar-nav ml-auto" aria-label="<?php esc_attr_e( 'Main menu', 'xten' ); ?>">
									<?php
									$menu_name = 'primary';
									$locations = get_nav_menu_locations();

									if ( $locations && isset( $locations[ $menu_name ] ) && $locations[ $menu_name ] > 0 ) :
										$nav_menu = wp_nav_menu(
											array(
												'theme_location' => 'primary',
												'menu_id'        => 'desktop-menu',
												'container'      => 'ul',
												'depth'          => 2,
												'walker'         => new XTen_Walker(),
												// ensure that $nav_menu does not render until we tell it to.
												'echo'           => false,
											)
										);
										echo $nav_menu;
									endif;
									?>
								</nav><!-- #site-navigation -->
								<?php

								// Get Customizer Setting for Search Icon.
								$main_nav_search = get_theme_mod( 'main_nav_search', true );
								if ( $main_nav_search ) :
									?>
									<button class="header-search-toggle" type="button" data-toggle="collapse" data-target="#header-search" aria-controls="header-search" aria-expanded="false" aria-label="Toggle search">
										<i class="fas fa-search"></i>
									</button>
								<?php	endif; ?>

								<?php
								$display_site_phone_number = get_theme_mod( 'xten_site_phone_number_with_logo', false );
								$social_media_icons_list = social_media_icons_list_shortcode();
								if (
									$display_site_phone_number ||
									( $main_nav_search || $nav_menu ) ||
									$social_media_icons_list
								) :
									?>
									<div class="site-phone-number-mobile-nav-wrapper">
										<?php
										if ( $display_site_phone_number || $social_media_icons_list ) :
											?>
											<div class="site-phone-number-social-icons-wrapper">
												<?php
												if ( $social_media_icons_list ) :
													?>
													<div class="header-social-media-icons-wrapper">
														<?php echo $social_media_icons_list; ?>
													</div>
													<?php
												endif; // endif ( $social_media_icons_list ) :
												if ( $display_site_phone_number ) :
													$site_phone_number = get_site_phone_number_func( true );
													?>
													<div class="header-site-phone-number-wrapper">
														<span class="fa fa-mobile-alt site-phone-number-icon"></span>
														<?php
														echo $site_phone_number;
														?>
													</div>
													<?php
													endif; // endif ( $display_site_phone_number ) :
													?>
												</div>
												<?php
											endif; // endif ( $display_site_phone_number || $social_media_icons_list ) :
											?>

										<?php if ( $main_nav_search || $nav_menu ) : ?>
											<button id="mobile-nav-open" class="mobile-toggler collapsed" type="button" data-toggle="collapse" aria-controls="mobile-sidebar" aria-expanded="false" aria-label="Toggle navigation" tabindex="0" data-target="#mobile-sidebar">
												<div class="mobile-toggler-icon">
													<i class="fas fa-bars"></i>
												</div>
											</button>
										<?php endif; ?>
									</div>
								<?php endif; //endif ($display_site_phone_number ||...) : ?>
							</div><!-- /.header-container -->
						</div><!-- /#mainNav -->
						<?php	if ( $main_nav_search ) : ?>
							<div class="collapse header-search" id="header-search">
								<div class="header-search-wrapper">
									<div class="container">
										<div class="row">
											<div class=" col-sm-12 col-lg-8 offset-lg-2">
												<?php echo get_search_form(); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php	endif; ?>
					</header><!-- #masthead -->

				</div>
			</div>

			<div id="page-wrapper" class="page-wrapper">
				<div class="content-wrapper <?php echo esc_html( $size_header_pad ); ?>">
					<?php require_once get_template_directory() .  '/inc/alert-content.php'; ?>
