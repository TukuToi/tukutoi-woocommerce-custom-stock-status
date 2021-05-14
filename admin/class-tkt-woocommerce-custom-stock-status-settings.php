<?php

/**
 * The settings of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/admin
 */

/**
 * The settings of the plugin.
 *
 * Defines the plugin name, human name, version and common code.
 * Creates settings page and manages settings for this plugin.
 *
 * @package    Tkt_Woocommerce_Custom_Stock_Status
 * @subpackage Tkt_Woocommerce_Custom_Stock_Status/admin
 * @author     TukuToi <hello@tukutoi.com>
 */

/**
 * Class Tkt_Woocommerce_Custom_Stock_Status_Settings
 *
 */
class Tkt_Woocommerce_Custom_Stock_Status_Admin_Settings {

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
     * TukuToi Common Code
     *
     * @since    1.0.0
     * @access   private
     * @var      TKT_Common    $common    TKT_Common instance.
     */
    private $common;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string       $plugin_name       The name of this plugin.
     * @param      string       $version           The version of this plugin.
     * @param      string       $human_plugin_name The human name of this plugin.
     * @param      TKT_Common   $common            The TukuToi Common Code Class.
     */
    public function __construct( $plugin_name, $human_plugin_name, $version, $common ) {

        $this->plugin_name  = $plugin_name;
        $this->human_plugin_name = $human_plugin_name;
        $this->version      = $version;
        $this->common       = $common;

    }

    /**
     * Enqueue Styles in Settings page
     * (registered in Tw_Seo_Admin)
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name . '-styles' );

    }

    /**
     * Add Menu Page of this plugin
     *
     * @since 1.0.0
     * @access public
     */
    public function setup_plugin_menu() {

        $pages[] = add_submenu_page( 
            $this->common->get_common_name(), 
            $this->human_plugin_name, 
            'WC Stock Status', 
            'manage_options', 
            $this->plugin_name, 
            array($this,'render_settings_page_content'), 
            2 
        );

        foreach ($pages as $page) {
            add_action( "admin_print_styles-{$page}", array($this->common,'enqueue_styles') );
            add_action( "admin_print_styles-{$page}", array($this,'enqueue_styles') );
        }

    }

    /**
     * Render Settings Page
     *
     * @since 1.0.0
     * @access public
     */
    public function render_settings_page_content( $active_tab = '' ) {
        $this->common->set_render_settings_page_content($active_tab = '', $this->plugin_name, $this->plugin_name, $this->plugin_name);
    }

    /**
     * This Plugins Settings Options.
     *
     * @return array
     * @since 1.0.0
     * @access public
     */
    public function settings_options() {

        $options = array(
            $this->plugin_name .'_stock_states'      => "WooCommerce Custom Stock Statūs",
            $this->plugin_name .'_state_colors'      => "WooCommerce Custom Stock Statūs Colors",
        );

        return $options;

    }

    /**
     * Provide Defaults for this Plugins Settings Options.
     *
     * @return array
     * @since 1.0.0
     * @access public
     */
    public function settings_options_defaults() {

        $defaults = array(
            $this->plugin_name .'_stock_states'      => '',
            $this->plugin_name .'_state_colors'      => '',
        );

        return $defaults;

    }

    /**
     * Initialise all Option Settings
     *
     * @since 1.0.0
     * @access public
     */
    public function initialize_settings() {

        // If the options don't exist, create them.
        if( false == get_option( $this->plugin_name ) ) {
            $default_array = $this->settings_options_defaults();
            add_option( $this->plugin_name, $default_array );
        }

     
        // register a new section
        add_settings_section(
            $this->plugin_name,
            __( 'Custom WooCommerce Stock Statūs', $this->plugin_name ),
            array( $this, 'general_options_callback'),
            $this->plugin_name
        );

        //Why create as many functions as there are options? Just use foreach($settings_array) to create each settings field
        foreach ($this->settings_options() as $option => $name) {
            add_settings_field(
                $option,
                __( $name, $this->plugin_name ),
                array($this, $option . '_cb'),
                $this->plugin_name,
                $this->plugin_name,
                [
                    'label_for' => $option,
                    'class' => $this->plugin_name .'_row',
                    $this->plugin_name .'_custom_data' => 'custom',
                ]
            );

        }

        register_setting( $this->plugin_name, $this->plugin_name );

    }

    /**
     * General Options Callback API
     * @since 1.0.0
     * @access public
     */
    public function general_options_callback() {
        $this->common->set_general_options_callback('Control the Custom WooCommerce Stock Statūs of your ', get_bloginfo('name'), ' Shop centrally in one place', $this->plugin_name);
    }

    public function tkt_wccss_stock_states_cb( $args ) {
        $options = get_option( $this->plugin_name );
        ?><span class="description"><?php _e( 'Enter human readable comma delimited Stock Statūs', $this->plugin_name ); ?>
            </span>
            <input type="text" class="tkt-option-input" id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>" name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[$this->plugin_name .'_stock_states'] ? $options[$this->plugin_name .'_stock_states'] : ''?>">
        <?php
    }

    public function tkt_wccss_state_colors_cb( $args ) {
        $options = get_option( $this->plugin_name );
        ?><span class="description"><?php _e( 'Enter comma delimited hex colorcodes for Stock Statūs', $this->plugin_name ); ?>
            </span>
            <input type="text" class="tkt-option-input" id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args[$this->plugin_name .'_custom_data'] ); ?>" name="<?php echo $this->plugin_name ?>[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[$this->plugin_name .'_state_colors'] ? $options[$this->plugin_name .'_state_colors'] : ''?>">
        <?php
    }
    
}
