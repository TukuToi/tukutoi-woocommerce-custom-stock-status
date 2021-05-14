<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, human name, version, registers styles and scripts.
 * Loads dependencies, filters WooCommerce Stock status hooks.
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/admin
 * @author     TukuToi <hello@tukutoi.com>
 */
class Tkt_Woocommerce_Custom_Stock_Status_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
     * The Human Name of the plugin
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $human_plugin_name    The humanly readable plugin name
     */
    private $human_plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    All saved options of the plugin.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $human_plugin_name, $version ) {

		$this->plugin_name 			= $plugin_name;
		$this->human_plugin_name 	= $human_plugin_name;
		$this->version 				= $version;
		$this->options 				= get_option( $this->plugin_name );
		/**
		 *		[14-May-2021 10:03:15 UTC] Array
				(
				    [tkt_wccss_stock_states] => Out Of Beda,Whatever,Else,On Order
				    [tkt_wccss_state_colors] => #333333,#44444,#666666
				)
		*/
		$this->load_dependencies();

	}

	/**
     * Include file with Settings Class
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies() {

        /**
         * The class responsible for defining and instantiating all Setttings in the Plugins options page.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-tkt-woocommerce-custom-stock-status-settings.php';

    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tkt_Woocommerce_Custom_Stock_Status_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tkt_Woocommerce_Custom_Stock_Status_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tkt-woocommerce-custom-stock-status-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tkt_Woocommerce_Custom_Stock_Status_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tkt_Woocommerce_Custom_Stock_Status_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tkt-woocommerce-custom-stock-status-admin.js', array( 'jquery' ), $this->version, false );

	}

	private function dissect_plugin_options( $return ){

		$option_array = array();

		foreach ($this->options as $option => $value) {
			$options_array[$option] = explode(',', $value);
		}

		foreach ($options_array['tkt_wccss_stock_states'] as $status ) {
			$option_array['tkt_wccss_stock_states'][ sanitize_title( $status ) ] = __( $status, $this->plugin_name );
		}

		foreach ($options_array['tkt_wccss_state_colors'] as $color ) {
			$option_array['tkt_wccss_state_colors'][] = $color;
		}
		$option_array['tkt_wccss_state_colors_mapped'] = array_combine( array_flip( $option_array['tkt_wccss_stock_states'] ) , $option_array['tkt_wccss_state_colors'] );
		$this->option_array = $option_array;

		return $option_array[$return];

	}

	/**
	 * Product Stock Status Options
	 */
	public function filter_woocommerce_product_stock_status_options( $status ) {

		foreach( $this->dissect_plugin_options('tkt_wccss_stock_states') as $tkt_wccss_stock_status_key => $tkt_wccss_stock_status_name ){
			$status[$tkt_wccss_stock_status_key] = $tkt_wccss_stock_status_name;
		}
	
	    return $status;

	}

	/**
	 * Filter WC Products Admin Table Stock Stati
	 */
	public function filter_woocommerce_admin_stock_html( $stock_html, $product ) {
	    // Simple
	    if ( $product->is_type( 'simple' ) ) {
	        // Get stock status
	        $product_stock_status = $product->get_stock_status();
	    // Variable
	    } elseif ( $product->is_type( 'variable' ) ) {
	        foreach( $product->get_visible_children() as $variation_id ) {
	            // Get product
	            $variation = wc_get_product( $variation_id );
	            
	            // Get stock status
	            $product_stock_status = $variation->get_stock_status();
	            
	            /*
	            Currently the status of the last variant in the loop will be displayed.
	            
	            So from here we need to add our own logic, depending on what we expect from our custom stock status.
	            
	            By default, for the existing statuses. The status displayed on the admin products list table for variable products is determined as:
	            
	            - Product should be in stock if a child is in stock.
	            - Product should be out of stock if all children are out of stock.
	            - Product should be on backorder if all children are on backorder.
	            - Product should be on backorder if at least one child is on backorder and the rest are out of stock.

	            This version of the plugin does not further handle this issue.
	            */
	        }
	    }

	 	if( !empty( $this->option_array['tkt_wccss_stock_states'][ $product_stock_status ] ) ){
	 		$stock_html = '<mark class="'. $product_stock_status .'" style="background:transparent none;color:'. $this->dissect_plugin_options('tkt_wccss_state_colors_mapped')[$product_stock_status] .';font-weight:700;line-height:1;">' . $this->option_array['tkt_wccss_stock_states'][ $product_stock_status ] . '</mark>';
	 	}

	    
	    return $stock_html;
	}

}
