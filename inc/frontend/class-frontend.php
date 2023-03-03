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
		 
//		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css?ver=6.1.1', array( ), $this->version, 'all' );
//        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css';                                                                                             
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
 	    
//NOTE :NTFS!!!!  enqueue_scripts and add_inline script moved to the shortcode callback so 
// it is not call when NOT needed.!


 	    	     	                                                                     
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
	public function schedule_request( $atts = array() ) {
		add_action( 'wp_enqueue_script', function(){

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp-schedule-frontend.js', array( 'jquery', 'jquery-ui-widget',
 				'underscore',  'moment', 'calendar'), $this->version, true  );	
				
			}		
		);
	
		ob_start();
	    	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
			include ('pdp/html_cb_pdp_request_list_member.php' );
		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // schedule_request()	
	
	public function instructor_portal( $atts = array() ) {

		ob_start();
	    	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
			include ('pdp/html_cb_pdp_request_list_cfig.php' );
		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // schedule_request()	
	public function cb_pdp_calendar( $atts = array() ) {
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );
 			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp-schedule-frontend.js', array( 'jquery', 'jquery-ui-widget',
 				'underscore',  'moment', 'calendar'), $this->version, true  );	
			$enabled = get_option('cloudbase_enabled_sessions', false );

    		$dateToBePassed = array(
    		    'ajax_url' =>  admin_url('admin-ajax.php'),
    			'restURL' => esc_url_raw( rest_url() ),
     			'nonce' => wp_create_nonce( 'wp_rest' ),
     			'success' => __( 'Flight Has been updated!', 'your-text-domain' ),
     			'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
    			'current_user_id' => get_current_user_id(),
     			'current_user_role' => $this->user_roles(),
     			'current_user_role_name' =>   $this->user_roles() != null ? wp_roles()->get_names()[ $this->user_roles() ] : '' ,
     			'enabled_sessions' => $enabled,
     			'trade_authority' => $this->trade_authority(),
//      			'authorities' => get_option('cloud_base_authoritys'),
     			'user_can' => $this->user_can()
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
		
	/**
	 * This function brings up the flight details page. This is where glider, pilot
	 * instructor, tow pilot and tug are selected. Also corrections can be make to 
	 * take off/landing time and tow alitude. 
	 */
     public function cb_pdp_training_request(){ 
      	if (isset($_GET['page'])){
    		switch($_GET['page']){
     			case('enter_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_enter_cfig.php');
     				break;	
    			case('enter_request_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_enter_member_by_cfig.php');
     				break;	
     			case('enter_request_member'):
     				include_once( 'pdp/html_cb_pdp_request_enter_member.php');
     				break;	
     			case('enter_vacay'):
     				include_once( 'pdp/html_cb_pdp_request_enter_vacay.php');
     				break;	
     			case('enter_request'):
     				include_once( 'pdp/html_cb_pdp_request_enter.php');
     				break;	
    			case('list_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_list_cfig.php');
     				break;	
   				case('list_member'):
     				include_once( 'pdp/html_cb_pdp_request_list_member.php');
     				break;	
   				case('list'):
     				include_once( 'pdp/html_cb_pdp_request_list.php');
     				break;	
     			case('modify_cfig_auto'):
     				include_once( 'pdp/html_cb_pdp_request_modify_cfig_auto.php');
     				break;
    			case('modify_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_modify_cfig.php');
     				break;
   				case('modify_cfig2_auto'):
     				include_once( 'pdp/html_cb_pdp_request_modify_cfig2_auto.php');
     				break;
    			case('modify_member'):
     				include_once( 'pdp/html_cb_pdp_request_modify_member.php');
     				break;
    			case('vacation_view_cfig_by_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_vacation_view_cfig_by_cfig.php');
     				break;		
     			case('cfig_schedule_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_vacation_view_cfig.php');
     				break;	 
     			case('vacation_view'):
     				include_once( 'pdp/html_cb_pdp_request_vacation_view.php');
     				break;	
    			case('vacay_modify'):
     				include_once( 'pdp/html_cb_pdp_request_vacy_modify.php');
     				break;	     		   		     				    				    		   		
     		}
     	} elseif(isset($_POST['page'])){
 
       		switch($_POST['page']){
     			case('enter_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_enter_cfig.php');
     				break;	
    			case('enter_request_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_enter_member_by_cfig.php');
     				break;	
     			case('enter_request_member'):
     				include_once( 'pdp/html_cb_pdp_request_enter_member.php');
     				break;	
     			case('enter_vacay'):
     				include_once( 'pdp/html_cb_pdp_request_enter_vacay.php');
     				break;	
     			case('enter_request'):
     				include_once( 'pdp/html_cb_pdp_request_enter.php');
     				break;	
    			case('list_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_list_cfig.php');
     				break;	
   				case('list_member'):
     				include_once( 'pdp/html_cb_pdp_request_list_member.php');
     				break;	
   				case('list'):
     				include_once( 'pdp/html_cb_pdp_request_list.php');
     				break;	
     			case('modify_cfig_auto'):
     				include_once( 'pdp/html_cb_pdp_request_modify_cfig_auto.php');
     				break;
    			case('modify_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_modify_cfig.php');
     				break;
   				case('modify_cfig2_auto'):
     				include_once( 'pdp/html_cb_pdp_request_modify_cfig2_auto.php');
     				break;
    			case('modify_member'):
     				include_once( 'pdp/html_cb_pdp_request_modify_member.php');
     				break;	
    			case('vacation_view_cfig_by_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_vacation_view_cfig_by_cfig.php');
     				break;		
     			case('cfig_schedule_cfig'):
     				include_once( 'pdp/html_cb_pdp_request_vacation_view_cfig.php');
     				break;	 
     			case('vacation_view'):
     				include_once( 'pdp/html_cb_pdp_request_vacation_view.php');
     				break;	
    			case('vacay_modify'):
     				include_once( 'pdp/html_cb_pdp_request_vacy_modify.php');
     				break;	     		   		     				    				    		   		
     		}       		
     	} else {
       		include_once( 'pdp/html_cb_pdp_request_list_member.php');
     	}

     } //cb_pdp_training_request()
     public function pdp_cfig_schedule(){ 
//     	if (isset($_GET['key'])) {
			$return_page = $_GET['source_page'];
     		include_once( 'pdp/html_cb_pdp_vacation_view.php');
//     	}else {
//     		wp_redirect($_GET['source_page']);
//     	}
     } //pdp_cfig_schedule()     
     
 /**
 * This function updates the takeoff and landing time. 
 *  if varable $_POST['start'] is "1" (true), it updates the take off time if 
 *  anything else it update landing time. It is called via admin-ajax and javascript. 
 *
 */
     public function pdp_update_time(){
		global $PGCwp; // database handle for accessing wordpress db
		global $PGCi;  // database handle for PDP external db
    
     	if (isset($_POST['key'])) {
     		$key = $_POST['key'];
     		if($_POST['start'] == '1'){
     			$PGCwp->update('pgc_flightsheet', array('Takeoff'=> $_POST['thetime']), array('Key'=> $key)); 
     		} else {
     			$sql = $PGCwp->prepare( "SELECT `Takeoff` FROM  pgc_flightsheet WHERE `Key` = %d", $key);
     			$start_time = \DateTime::createFromFormat('H:i:s', $PGCwp->get_var($sql));			
     			$landing_time =\DateTime::createFromFormat('H:i:s', $_POST['thetime']);
     			$delta = $landing_time->diff($start_time);
     			$dec_delta = round($delta->h + $delta->i/60, 2, PHP_ROUND_HALF_UP); 		
     			$PGCwp->update('pgc_flightsheet', array('Landing'=> $_POST['thetime'], 'Time'=>$dec_delta), array('Key'=> $key)); 
     		}
      	}		
     } //pdp_update_time()    
               	
	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

		add_shortcode( 'cb_pgc_schedule_request', array( $this, 'schedule_request' ) );
		add_shortcode( 'cb_pgc_instructor_portal', array( $this, 'instructor_portal' ) );
		add_shortcode( 'cb_pdp_calendar', array( $this, 'cb_pdp_calendar' ) );

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
	 		} elseif(in_array('chief_flight', $roles)){
	 			return('chief_flight');
	 		}  elseif(in_array('chief_tow' , $roles)){
	 			return('chief_tow');
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
     	$capabiliteis = array( 'manage_options', 'chief_flight', 'chief_tow', 
 			'edit_gc_operations',  'cb_edit_instruction', 'edit_gc_tow', 'field_manager', 
 			'assistant_field_manager', 'read' );		
 		if( is_user_logged_in() ) { // check if there is a logged in user 	 						
 			forEach($capabiliteis as $c ){
 				if (current_user_can( $c ) ){
 					return($c);
 				}
 		 	}
 	 	} else {		 
			return('read');  	 
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
}
