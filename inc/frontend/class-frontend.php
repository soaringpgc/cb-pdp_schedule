<?php

namespace CB_PDP_schedule\Inc\Frontend;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       http://pgcsoaring.com
 * @since      1.0.0
 *
 * @author    Philadelphia Glider Council -- Dave Johnson
 */
class Frontend {

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
	 * Register the stylesheets for the public-facing side of the site.
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
		 
//  		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css', array( ), $this->version, 'all' );
        wp_enqueue_style(  'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');                                                                                             
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cb-pdp_schedule-frontend.css', array( ), $this->version, 'all' );

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
 	    wp_register_script( 'calendar', 'https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js');
        wp_register_script( 'jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js'); 	    
       	wp_register_script( 'fcadaptive', 'https://cdn.jsdelivr.net/npm/@fullcalendar/adaptive@6.1.5/index.global.min.js'); 	
 	    
//  	    wp_register_script( 'calendar_daygrid',' https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.4/index.global.min.js');
 	    
//NOTE :NTFS!!!!  enqueue_scripts and add_inline script moved to the shortcode callback so 
// it is not called when NOT needed.!
 	    	     	                                                                     
// 		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp-schedule-frontend.js', array( 'jquery', 'jquery-ui-widget',
// 		 'underscore',  'moment', 'calendar'), $this->version, false );		

// 		$enabled = get_option('cloudbase_enabled_sessions', false );
//    	wp_localize_script( $this->plugin_name, 'PDP_SCHEDULER', array(
//     		'ajax_url' =>  admin_url('admin-ajax.php'),
//     		'restURL' => esc_url_raw( rest_url() ),
//      		'nonce' => wp_create_nonce( 'wp_rest' ),
//      		'success' => __( 'Flight Has been updated!', 'your-text-domain' ),
//      		'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
//    		)	
//    	);	
//     	$dateToBePassed = array(
//     	    'ajax_url' =>  admin_url('admin-ajax.php'),
//     		'restURL' => esc_url_raw( rest_url() ),
//      		'nonce' => wp_create_nonce( 'wp_rest' ),
//      		'success' => __( 'Flight Has been updated!', 'your-text-domain' ),
//      		'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
//     		'current_user_id' => get_current_user_id(),
//      		'current_user_role' => $this->user_roles(),
//      		'enabled_sessions' => $enabled,
//      		'trade_authority' => $this->trade_authority(),
//     		);   	
//     	wp_add_inline_script( $this->plugin_name, 'const passed_vars = ' . json_encode ( $dateToBePassed  ), 'before'
//     	);    	
	}

	public function cb_pdp_calendar( $atts = array() ) {
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );
// not working quite the way I expected. 
			$show_days = shortcode_atts(array( 'show_days'=>'all'), $atts, 'cb_pdp_calendar' )['show_days'];
			switch($show_days){
				case 'all'    : $hide_days = array();
				break;
				case 'three'  : $hide_days = array(1, 2, 4, 5 );
				break;
				case 'weekend': $hide_days = array(1, 2, 4, 5 );
				break;
				case 'week'   : $hide_days = array(0, 6);
				break;
			}
						
 			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp-schedule-frontend.js', array( 'jquery', 
 				'underscore',  'moment', 'calendar'), $this->version, true  );	
			$enabled = get_option('cloudbase_enabled_sessions', false );
			$current_user = wp_get_current_user();
    		$dateToBePassed = array(
    		    'ajax_url' =>  admin_url('admin-ajax.php'),
    			'restURL' => esc_url_raw( rest_url() ),
     			'nonce' => wp_create_nonce( 'wp_rest' ),
//      			'success' => __( 'Flight Has been updated!', 'your-text-domain' ),
//      			'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
    			'current_user_id' => get_current_user_id(),
     			'current_user_role' => $this->user_roles(),
     			'current_user_role_name' =>   $this->user_roles() != null ? wp_roles()->get_names()[ $this->user_roles() ] : '' ,
     			'enabled_sessions' => $enabled,
     			'trade_authority' => $this->trade_authority(),
     			'user_can' => $this->user_can(),
     			'current_user_caps' => $current_user->allcaps, // these two need to be cleand up later. 
     			'hide_days' => $hide_days
    			);   	
    		wp_add_inline_script( $this->plugin_name, 'const passed_vars = ' . json_encode ( $dateToBePassed  ), 'before'
    		); 
		ob_start();
	    	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
			include ('views/html_cb_pdp_calendar.php' );
		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // cb_pdp_calendart()	
	public function cb_pdp_select_fd( $atts = array() ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		ob_start();	    	
// 	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
			include ('views/html_cb_pdp_select_fd.php' );
			field_duty_submit_request();
			display_fd_choices();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	} //cb_pdp_select_fd
	public function cb_pdp_vac_view( $atts = array() ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

 			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp-vacation_view.js', array( 'jquery', 
 				'underscore',  'moment', 'calendar'), $this->version, true  );	

    		$dateToBePassed = array(
    		    'ajax_url' =>  admin_url('admin-ajax.php'),
    			'restURL' => esc_url_raw( rest_url() ),
     			'nonce' => wp_create_nonce( 'wp_rest' ),
    			'current_user_id' => get_current_user_id(),
     			'current_user_role' => $this->user_roles(),
     			'current_user_role_name' =>   $this->user_roles() != null ? wp_roles()->get_names()[ $this->user_roles() ] : '' ,

    			);   	
    		wp_add_inline_script( $this->plugin_name, 'const passed_vars = ' . json_encode ( $dateToBePassed  ), 'before'
    		); 


		ob_start();	    	
// 	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
			include ('views/html_cb_vac_view.php' );
// 			field_duty_submit_request();
// 			display_fd_choices();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	} //cb_pdp_vac_view	
	public function cb_pdp_instruction_request( $atts = array() ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		if(!is_user_logged_in()){
 			return;
		}
 			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb_pdp_instruction_request.js', array( 'jquery', 
 				'underscore',  'moment', 'calendar', 'jqueryui'), $this->version, true  );	
			$current_user = wp_get_current_user();
// 			$current_cap =array();
// 			foreach ($current_user->allcaps as $k=>$v ){
// 				if (strpos($k, "cb_" ) !== false) {
// 					array_push(	$current_cap, $k);
// 				} 
// 			}
    		$dateToBePassed = array(
    		    'ajax_url' =>  admin_url('admin-ajax.php'),
    			'restURL' => esc_url_raw( rest_url() ),
     			'nonce' => wp_create_nonce( 'wp_rest' ),
    			'current_user_id' => get_current_user_id(),
     			'current_user_role' => $this->user_roles(),
     			'current_user_role_name' =>   $this->user_roles() != null ? wp_roles()->get_names()[ $this->user_roles() ] : '' ,
				'current_user_caps' => $current_user->allcaps
    			);   	
    		wp_add_inline_script( $this->plugin_name, 'const passed_vars = ' . json_encode ( $dateToBePassed  ), 'before'  );
		ob_start();	    	
// 	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
			include ('views/html_cb_pdp_instruction_request.php' );
 			instruction_Request_submit();
			display_instruction_Request();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	} //cb_pdp_instruction_request		
               	
	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

// 		add_shortcode( 'cb_pgc_schedule_request', array( $this, 'schedule_request' ) );
// 		add_shortcode( 'cb_pgc_instructor_portal', array( $this, 'instructor_portal' ) );
		add_shortcode( 'cb_pdp_calendar', array( $this, 'cb_pdp_calendar' ) );
		add_shortcode( 'cb_pdp_select_fd', array( $this, 'cb_pdp_select_fd' ) );
		add_shortcode( 'cb_pdp_vac_view', array( $this, 'cb_pdp_vac_view' ) );
		add_shortcode( 'cb_pdp_instruction_request', array( $this, 'cb_pdp_instruction_request' ) );

	} // register_shortcodes()
	/**
	 * This function redirects to the longin page if the user is not logged in.
	 *
	 */
     public function pdp_no_login(){
     	wp_redirect(home_url());
     } //
     public function user_roles()
     {
 		if( is_user_logged_in() ) { // check if there is a logged in user 	 
	 		$user = wp_get_current_user(); // getting the current user 
	 		$roles = ( array ) $user->roles; // obtaining the role 	 
	 		if(in_array('administrator', $roles)) {
	 			return('administrator');
	 		} elseif(in_array('cfig_scheduler', $roles)){
	 			return('cfig_scheduler');
	 		} elseif(in_array('tow_scheduler', $roles)){
	 			return('tow_scheduler');
	 		} elseif(in_array('schedule_assist', $roles)){
	 			return('schedule_assist');
// 	 		}  elseif(in_array('chief_tow' , $roles)){
// 	 			return('chief_tow');
	 		}  elseif(in_array('chief_of_ops', $roles)){
	 			return('chief_of_ops');
	 		}  elseif(in_array('operations' , $roles)){
	 			return('operations');
	 		}  elseif(in_array('tow_pilot', $roles)){
	 			return('tow_pilot');
	 		}  elseif(in_array('cfi_g', $roles)){
	 			return('cfi_g');
	 		}  elseif(in_array('field_manager', $roles)){
	 			return('field_manager');
	 		}  elseif(in_array('assistant_field_manager', $roles)){
	 			return('assistant_field_manager');
	 		}
	 	} else {		 
			return null ; // if there is no logged in user return empty array  	 
	 	}
	 }
	 public function user_can()
     {
     	$capabiliteis = array( 'manage_options', 'cfig_scheduler', 'tow_scheduler', 'chief_flight',
     		 'chief_tow', 'edit_gc_operations',  'cb_edit_instruction', 'edit_gc_tow', 
     		'field_manager', 'assistant_field_manager', 'read', 'schedule_assist', 'read' );		
 		if( is_user_logged_in() ) { // check if there is a logged in user 	 						
 			forEach($capabiliteis as $c ){
 				if (current_user_can( $c ) ){
 					return($c);
 				}
 		 	}
 	 	} 
	 }
     public function trade_authority()
    	 {
 		if( is_user_logged_in() ) { // check if there is a logged in user 	 
 			$rest_request = new \WP_REST_REQUEST( 'GET', '/cloud_base/v1/trades' ) ;  
   			$rest_request->set_query_params(array('session_start'=> 1));
  			$rest_response = rest_do_request( $rest_request);      		
 			$server = rest_get_server();
  			$data = $server->response_to_data( $rest_response, false );
			return($data);			
	 	} else {		 
			return array(); // if there is no logged in user return empty array  	 
	 	}
     }
     public function user_cb_capability($user){
     	// requires user object      	     		
   		$current_cap =array();
			foreach ($user->allcaps as $k=>$v ){
				if (strpos($k, "cb_" ) !== false) {
					array_push(	$current_cap, $k);
			} 
		}
        return($current_cap ) ;     
     }
}
