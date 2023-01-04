<?php

namespace CB_PDP_template\Inc\Frontend;

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cb-pdp_template-frontend.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp_template-frontend.js', array( 'jquery' ), $this->version, false );		
    	wp_localize_script( $this->plugin_name, 'PDP_FLIGHT_SUBMITTER', array(
    		'ajax_url' =>  admin_url('admin-ajax.php'),
    		'root' => esc_url_raw( rest_url() ),
     		'nonce' => wp_create_nonce( 'wp_rest' ),
     		'success' => __( 'Flight Has been updated!', 'your-text-domain' ),
     		'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
     		'current_user_id' => get_current_user_id()
    		)	
    	);	

	}
	public function schedule_request( $atts = array() ) {

		ob_start();
	    	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	    	$flight_atts = shortcode_atts(array( 'view_only'=>"true"), $atts);
//			include ('views/html_cb_pdp_request_list_member.php' );
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

	} // register_shortcodes()
	/**
	 * This function redirects to the longin page if the user is not logged in.
	 *
	 */
     public function pdp_no_login(){
     	wp_redirect(home_url());
     } //
     public function cleanData(&$str)
     {
        // escape tab characters
        $str = preg_replace("/\t/", "\\t", $str);
        // escape new lines
        $str = preg_replace("/\r?\n/", "\\n", $str);
        // convert 't' and 'f' to boolean values
        if($str == 't') $str = 'TRUE';
        if($str == 'f') $str = 'FALSE';
    
        // force certain number/date formats to be imported as strings
        if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
          $str = "'$str";
        }
    
        // escape fields that include double quotes
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
     }     
}
