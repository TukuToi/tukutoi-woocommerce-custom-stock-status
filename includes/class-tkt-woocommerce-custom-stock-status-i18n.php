<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/includes
 * @author     TukuToi <hello@tukutoi.com>
 */
class Tkt_Woocommerce_Custom_Stock_Status_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tkt-woocommerce-custom-stock-status',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
