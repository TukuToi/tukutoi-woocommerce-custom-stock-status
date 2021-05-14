<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.tukutoi.com/
 * @since             1.0.0
 * @package           Tkt_Woocommerce_Custom_Stock_Status
 *
 * @wordpress-plugin
 * Plugin Name:       TukuToi WooCommerce Custom Stock Status
 * Plugin URI:        https://www.tukutoi.com/program/tukutoi-woocommerce-custom-stock-status
 * Description:       Register your own WooCommerce Custom Stock Statūs and assign custom colors to them.
 * Version:           1.0.0
 * Author:            TukuToi
 * Author URI:        https://www.tukutoi.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tkt-woocommerce-custom-stock-status
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TKT_WOOCOMMERCE_CUSTOM_STOCK_STATUS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tkt-woocommerce-custom-stock-status-activator.php
 */
function activate_tkt_woocommerce_custom_stock_status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tkt-woocommerce-custom-stock-status-activator.php';
	Tkt_Woocommerce_Custom_Stock_Status_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tkt-woocommerce-custom-stock-status-deactivator.php
 */
function deactivate_tkt_woocommerce_custom_stock_status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tkt-woocommerce-custom-stock-status-deactivator.php';
	Tkt_Woocommerce_Custom_Stock_Status_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tkt_woocommerce_custom_stock_status' );
register_deactivation_hook( __FILE__, 'deactivate_tkt_woocommerce_custom_stock_status' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tkt-woocommerce-custom-stock-status.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tkt_woocommerce_custom_stock_status() {

	$plugin = new Tkt_Woocommerce_Custom_Stock_Status();
	$plugin->run();

}
run_tkt_woocommerce_custom_stock_status();
