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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cb-pdp_schedule-admin.css', array(), $this->version, 'all' );

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
        wp_register_script( 'cb_pdp_schedule_admin_templates',  plugins_url('/cb-pdp_schedule/inc/admin/js/templates.js'));
	    	    
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp_schedule-admin.js', 
		array( 'jquery', 'javascripts', 'CalendarPopup', 'zxml', 'underscore', 'backbone', 'workingjs', 'cb_pdp_schedule_admin_templates'  ), $this->version, false );

     		$dateToBePassed = array(
 					'root' => esc_url_raw( rest_url() ),
 					'nonce' => wp_create_nonce( 'wp_rest' ),
 					'success' => __( 'Data Has been updated!', 'your-text-domain' ),
 					'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
 					'current_user_id' => get_current_user_id()    	    	
     			);   	
     		wp_add_inline_script(  $this->plugin_name, 'const cp_schedule_admin_vars = ' . json_encode ( $dateToBePassed  ), 'before'
     		); 

	}
	public function add_admin_tab_calendar(  $page_tabs_enhanced){

// Need to revisit this, moving enque script here works for cloudbase, however as this is a plugin to
// cloudbase it does nto work. Should be a work around. -dsj ( want to move here so scripts are only
// enqueue when necessary. )

// 		add_action('admin_enqueue_scripts', function($hook) {
// 			if($hook !== $this->plugin_screen_hook_suffix){
// 				return;
// 			}
// 			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp_schedule-admin.js', 
// 			array( 'jquery', 'javascripts', 'CalendarPopup', 'zxml', 'underscore', 'backbone', 'workingjs', 'cb_pdp_schedule_admin_templates'  ), $this->version, false );
// // 			wp_enqueue_style( 'datepicker');
// //  			wp_enqueue_style( 'cloudbase_css');
// 				//localize data for script			
// 			
//      		$dateToBePassed = array(
//  					'root' => esc_url_raw( rest_url() ),
//  					'nonce' => wp_create_nonce( 'wp_rest' ),
//  					'success' => __( 'Data Has been updated!', 'your-text-domain' ),
//  					'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
//  					'current_user_id' => get_current_user_id()    	    	
//      			);   	
//      		wp_add_inline_script(  $this->plugin_name, 'const cp_schedule_admin_vars = ' . json_encode ( $dateToBePassed  ), 'before'
//      		); 	});		
	
           $page_tabs_enhanced[] = array( "tab"=>"html_seasion_setup" , "title"=> "Field Duty Setup", "page"=>"cloud_base",
           "plug_path"=>plugin_dir_path(__FILE__).'views/' );

           $page_tabs_enhanced[] = array( "tab"=>"html_trade_setup" , "title"=> "Trade Setup", "page"=>"cloud_base",
           "plug_path"=>plugin_dir_path(__FILE__).'views/' );   
           return  $page_tabs_enhanced;
    }

	public function cb_schedule_setup_response(){
		$match = $_POST['selection'];	
     	check_admin_referer('schedule_page');
	
 		if( strcmp($match,'Update Year') == 0 ){
 			// set up the Year
 			$s1 = $_POST['session1Start'];
 			$s2 = $_POST['session2Start'];
 			$s3 = $_POST['session3Start'];
 			$e3 = $_POST['session3end'];
 		
 			$rest_request = new \WP_REST_REQUEST( 'POST', '/cloud_base/v1/calendar' ) ;  
  			$rest_request->set_query_params(array('s1'=> $s1, 's2'=> $s2,'s3'=> $s3, 'e3'=> $e3));
   			$rest_response = rest_do_request( $rest_request);      		
		
		} elseif( strcmp($match, 'Update Daily') == 0 ){
			// configure the days of the week to schedule	
			update_option('cloudbase_tp_weekly', $_POST['weekschedule'], false );		  				
	 		
		}elseif(strcmp($match,'Add Holiday') == 0 ){
 	 		global $wpdb; 
  			$table_name =  $wpdb->prefix . 'cloud_base_calendar';
 			$field_name =  $wpdb->prefix . 'cloud_base_field_duty';
		
 			if(isset($_POST['holiday'])){
 				$trade = $_POST['holiday'];
 			} else {
  				$trade = array(0, 0, 0);
 			};		
   			if (isset($_POST['editdates'] )){ // get id of the date 
  	 	  		$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_date` = %s" ,  $_POST['editdates']);	
  	 			$id = $wpdb->get_var($sql); 
  				for ($t = 1 ; $t <= 3; $t++ )	{	// for each trade. 	
  					if($trade[$t-1] == "1"){
   						$record = array( 'calendar_id'=>  $id, 'trade'=> $t, 'member_id'=>NULL );		// new record 		 	 	
   	 		 			$sql = $wpdb->prepare("SELECT id FROM {$field_name} WHERE `calendar_id` = %s AND `trade`=%d",  $id, $t);	// does date and trade exist?
   						$tid = $wpdb->get_var($sql);     						
   						if ($tid === null) {
  							$result = $wpdb->insert($field_name, $record);	 // add new  							
  						} else {
  							$result = $wpdb->update($field_name, $record, array('id' => $tid ));	// update existing. 
  						}		
  					}
  				}		    	
 	 	    } 					
		}elseif(strcmp($match,'Enable Sessions') == 0 ){
			$sessions = array ($_POST['enablesession'][0], $_POST['enablesession'][1],$_POST['enablesession'][2]);
		
			update_option('cloudbase_enabled_sessions', $sessions , false );		  						
		}
		wp_redirect('options-general.php?page=cloud_base&tab=html_seasion_setup');	
    	exit();    		
    }  
}
