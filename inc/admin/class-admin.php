<?php

namespace CB_PDP_schedule\Inc\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://pgcsoaring.com
 * @since      1.0.0
 *
 * @author    Philadelphia Glider Council -- Dave Johnson
 */
class Admin {

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
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

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
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cb-pdp_pdp_schedule-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	    wp_register_script( 'workingjs',  plugins_url('/cb-pdp_schedule/assets/js/workingjs.js'));
	    wp_register_script( 'zxml',  plugins_url('/cb-pdp_schedule/assets/js/zxml.js'));
	    wp_register_script( 'CalendarPopup',  plugins_url('/cb-pdp_schedule/assets/js/CalendarPopup.js'));
	    wp_register_script( 'javascripts',  plugins_url('/cb-pdp_schedule/assets/js/javascripts.js'));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp_schedule-admin.js', 
		array( 'jquery', 'javascripts', 'CalendarPopup', 'zxml', 'workingjs'  ), $this->version, false );

	}
	public function add_admin_tab_calendar(  $page_tabs_enhanced){
           $page_tabs_enhanced[] = array( "tab"=>"html_seasion_setup" , "title"=> "Field Duty Setup", "page"=>"cloud_base",
           "plug_path"=>plugin_dir_path(__FILE__).'views/' );

           $page_tabs_enhanced[] = array( "tab"=>"html_trade_setup" , "title"=> "Trade Setup", "page"=>"cloud_base",
           "plug_path"=>plugin_dir_path(__FILE__).'views/' );   
           return  $page_tabs_enhanced;
    }

}
