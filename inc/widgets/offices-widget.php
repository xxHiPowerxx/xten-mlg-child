<?php
/*
	Plugin Name: Offices Widget
	Plugin URI: https://github.com/xxHiPowerxx/xten-mlg-child
	Description: Offices with phone numbers and Addresses
	Author: Ricky Adams
	Author URI: https://rickyradams.com
 */

/**
 * Adds XTen_Offices widget.
 */
class XTen_Offices extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			parent::__construct(
				'XTen_Offices',
				__('Offices', 'xten'), // Name
				array('description' => __('Offices with phone numbers and Addresses', 'xten'),)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget($args, $instance) {

			// Field Id of offices.
			$field_id = 'field_5fea27a18a30b';
			$post_ids = $instance['acf'][$field_id];
			$html     = null;

			ob_start();
			$html = ob_get_clean();
			

			echo $args['before_widget'];

				echo xten_render_component( 'offices-list', array( 'post_ids' => $post_ids ) );

			echo $args['after_widget'];
		}
	}

// register XTen_Offices widget
function register_xten_offices_widget() {
		register_widget('XTen_Offices');
}

add_action('widgets_init', 'register_xten_offices_widget');