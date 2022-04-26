<?php
/**
 * Utility Functions
 *
 * @package xten
 */

/**
 * XTen Child Utilities
 * Makes Utility functions available throughout theme.
 */
class XTenChildUtilities {
	/**
	 * Global Utilites
	 * Calls functions to be used globally throughout theme.
	 */
	public function global_utilities() {
		if ( ! function_exists( 'xten_split_office_title' ) ) :
			function xten_split_office_title( $office_title ) {
				$seperator = ' - ';
				$office_titles = preg_split("#$seperator#", $office_title);
				$_office_title_s = '';
				foreach ( $office_titles as $_office_title ) :
					$_seperator = $office_titles[0] === $_office_title ?
						null :
						"<span>$seperator</span>";
					$_office_title_s .= " $_seperator <span>$_office_title</span>";
				endforeach;
				return $_office_title_s;
			}
		endif; // endif ( ! function_exists( 'xten_split_office_title' ) ) :
	}
}

$ob = new XTenChildUtilities();
$ob->global_utilities();
