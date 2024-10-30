<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://persaper.it/custom-post-type-related-post
 * @since      1.0.0
 *
 * @package    CPTRP
 * @subpackage cptrp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CPTRP
 * @subpackage cptrp/includes
 * @author     Davide de Mattia <dvddemattia@gmail.com>
 */
class cptrp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_load_plugin_textdomain() {

		load_plugin_textdomain(
			'cptrp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
