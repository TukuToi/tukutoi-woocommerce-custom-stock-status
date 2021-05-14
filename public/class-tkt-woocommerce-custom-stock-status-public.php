<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/public
 * @author     TukuToi <hello@tukutoi.com>
 */
class Tkt_Woocommerce_Custom_Stock_Status_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tkt-woocommerce-custom-stock-status-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tkt-woocommerce-custom-stock-status-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Product Availabiluty classes
	 */
	public function filter_woocommerce_get_availability_class( $class, $product ) {
	    switch( $product->get_stock_status() ) {
	        case 'replaced':
	            $class = 'replaced'; 
	        break;
	        case 'restricted':
	            $class = 'restricted'; 
	        break;
	        case 'discontinued':
	            $class = 'discontinued'; 
	        break;
	        case 'on_order':
	            $class = 'on-order'; 
	        break;
	        case 'available_to_order':
	            $class = 'available-to-order'; 
	        break;

	    }

	    return $class;

	}

	/**
	 * Product Availability Text
	 */
	public function filter_woocommerce_get_availability_text( $availability, $product ) {

	    switch( $product->get_stock_status() ) {
	        case 'replaced':
	            $availability = __( 'Replaced', 'woocommerce' );
	        break;
	        case 'restricted':
	            $availability = __( 'Restricted', 'woocommerce' ); 
	        break;
	        case 'discontinued':
	            $availability = __( 'Discontinued', 'woocommerce' );
	        break;
	        case 'available_to_order':
	            $availability = __( 'Out of Stock - Available to Order', 'woocommerce' );
	        break;
	        case 'on_order':
	            $availability = __( 'Out of Stock - On Order', 'woocommerce' );
	        break;
	    }

    	return $availability; 

	}

	/**
	 * Add To cart WC text
	 */
	public function edit_add_to_cart_text( $text, $product ) {

	  	switch ($product->get_stock_status()) {
		    case 'discontinued':
		    case 'outofstock':
		    case 'on_order':
		    case 'onbackorder':
		      $text = 'View Part';
		    break;
		    case 'replaced':
		      $text = 'Purchase the Replacement';
		      break;
		    case 'restricted':
		      $text = 'Send the piece to us';
		      break;
		    case 'available_to_order':
		      $text = 'Add To Cart, Ships in 1-3 Weeks';
		      break;
		    default:
		      return $text;
		      break;
	  	}

	  	return $text;

	}

	/**
	 * Add to cart URL
	 */
	public function edit_add_to_cart_url( $url, $product ) {

	  	$replacement_sku = get_post_meta($product->get_id(), 'wpcf-replacement-part', true) != '' ? get_post_meta($product->get_id(), 'wpcf-replacement-part', true) : false;
	  	$replacement_id  = wc_get_product_id_by_sku($replacement_sku);
	  	switch ($product->get_stock_status()) {
		    case 'discontinued':
		    case 'outofstock':
		    case 'on_order':
		    case 'onbackorder':
		      $url = $product->get_permalink();
		      break;
		    case 'replaced':
		      $url = '?add-to-cart='.$replacement_id;
		      break;
		    case 'restricted':
		      $url = get_site_url() .'/send-part-for-reparation/?part_number='.$product->get_id();
		      break;
		    case 'available_to_order':
		      $url = '?add-to-cart='. $product->get_id();
		      break;
		    default:
		      return $url;
		      break;
	  	}

	  	return $url;

	}

}
