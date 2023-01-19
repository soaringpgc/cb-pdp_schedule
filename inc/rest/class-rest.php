<?php
namespace CB_PDP_schedule\Inc\Rest;
/**
 * The rest functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Cloud_Base
 * @subpackage Cloud_Base/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and examples to create your REST access
 * methods. Don't forget to validate and sanatize incoming data!
 *
 * @package    Cloud_Base
 * @subpackage Cloud_Base/public
 * @author     Your Name <email@example.com>
 */
class Rest extends \Cloud_Base_Rest {
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

	public function register_routes() {

  	$version = '1';
    $namespace = 'cloud_base/v' . $version;
    $base = 'route';
	 // the extra (?:/ ...  ) makes the parmater optional 
 		register_rest_route( $namespace, '/calendar(?:/(?P<id>[\d]+))?', array (
 			array(
       		'methods'  => \WP_REST_Server::READABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_get_dates' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),        	
   		 	), array(
       		'methods'  => \WP_REST_Server::CREATABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_post_dates' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_dates' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_dates' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
   		 	)) 
   		 );	
    }
      
	public function pdp_get_dates( \WP_REST_Request $request) {
		global $wpdb;
		$table_name =  'wp_cloud_base_calendar';
		 	
		if(isset($request['id'])){
			$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `id` = %d" ,  $request['id'] );	
		} else {
 			if(isset($request['session'])){
 			 	$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `session` = %d" ,  $request['session'] );			
 			} elseif (isset($request['start'])){
 				if (isset($request['stop'])){
 					$stop = $request['stop'] ;
 				} else {
 					$end = new \DateTime($request['start']);
 					$stop = $end->modify('+14 day')->format("Y-m-d");							
 				}
 				$sql = $wpdb->prepare("SELECT * FROM {$table_name}  WHERE `calendar_date` >= %s AND  `calendar_date` <=  = %s" ,  
 						$request['start'], $stop );	
 			} else {
  				$start = new \DateTime('now');
				$stop = clone $start;
  				$stop = $stop->modify('+14 day')->format("Y-m-d");	
  				$sql = $wpdb->prepare("SELECT * FROM {$table_name}  WHERE `calendar_date` >= %s AND  `calendar_date` <=  %s" ,  
  						$start->format("Y-m-d"), $stop );	
 			}
 		}	
 		$items = $wpdb->get_results($sql);
 	  	return new \WP_REST_Response ($items);
	}
//  create new date
	public function pdp_post_dates( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  'wp_cloud_base_calendar';
		
	// need start of each session and days of week to schedule. 	
		if(isset($request['s1']) && isset($request['s2']) && isset($request['s3']) && isset($request['e3']) &&
			isset($request['su']) && isset($request['m']) && isset($request['t']) && isset($request['w']) &&
			  isset($request['th']) && isset($request['f']) && isset($request['sa'])){	  
			  
				$s1 =  new \DateTime($request['s1']) ;
				$s2 =  new \DateTime($request['s2']) ;
				$s3 =  new \DateTime($request['s3']) ;
				$e3 =  new \DateTime($request['e3']) ;
				$date1 =  strtotime('first day of january');
                $date2 =  strtotime('last day of december');     

                $s_date1 = date('Y-m-d', $date1 );
                $s_date2 = date('Y-m-d', $date2 );     
    
 
/*
	This will generate enteries for every day from Jan 1 of this year to Jan 31st of next
	year. 
*/
                $jan_this_year =  new \DateTime( $s_date1 );
                $jan_next_year =  new \DateTime( $s_date2 );     
 				$jan_next_year->modify('+31 day');
		
 				$s_days = array (					
					($request['su'] == "1") ? 0 : -1, 
					($request['m']  == "1") ? 1 : -1, 
					($request['t']  == "1") ? 2 : -1, 
					($request['w']  == "1") ? 3 : -1, 
			  		($request['th'] == "1") ? 4 : -1, 
			  		($request['f']  == "1") ? 5 : -1, 
 			  		($request['sa'] == "1") ? 6 : -1 ) ; 
 			  
	  		
  			 for($i = $jan_this_year; $i <= $s2 ; $i-modify('+1 day') ) {
 				 
 			 	$record = array( 'calendar_date'=>  $i->format("Y-m-d"), 'session'=> '0', 'scheduling'=>  in_array( $i->format('w'), $s_days ) );	
	
  			  	$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_date` = %s" ,  $i->format("Y-m-d"));	
				$id = $wpdb->get_var($sql); 
  			  	if ($id != null ) {
  			  		$result = $wpdb->update($table_name, $record, array('id' => $id ));	
  			  	} else {
  			  		$result = $wpdb->insert($table_name, $record);	
  			  	}		
				$sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE `calendar_date` = %s" ,  $i->format("Y-m-d"));	

  				return new \WP_REST_Response ($wpdb->get_results($sql));				 			  			 
  			 }		
 			  	
 			  		
return new \WP_REST_Response ($s_days);		
		} else {
			return new \WP_Error( 'Insert Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );
		}
return new \WP_REST_Response ('huh?');		
		$insert_array = array();
		foreach ($flight_data as $key =>$value){
			if(isset($request[$value])){
				$insert_array += [$key=>$request[$value]];
			}
		}
		if (!empty($insert_array)){
			// if no date was supplied but other data is set, use today's date. 
			if (!isset($insert_array['Date'])){
				$insert_array += ['Date'=>date("Y-m-d")];
			}
			$result = $wpdb->insert($table_name, $insert_array );		
		}
		if ($result ){
			return new \WP_REST_Response ($items);
		} else {
			return new \WP_Error( 'Insert Failed', esc_html__( 'Unable to add Flight', 'my-text-domain' ), array( 'status' => 204 ) );
		}
	}	
//  update dates. 	
	public function pdp_update_dates( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  'wp_cloud_base_calendar';
		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'Id is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		}		
		$flight_data = array( 'Date'=>'date', 'Glider'=>'glider', 'Flight_Type'=>'flight_type', 'Pilot1'=>'pilot1', 
			'Pilot2'=>'pilot2', 'Takeoff'=>'takeoff', 'Landing'=>'landing', 'Time'=>'time', 'Tow Altitude'=>'tow_altitude', 
			'Tow Plane'=>'tow_plane', 'Tow Pilot'=>'tow_pilot', 'Tow Charge'=>'tow_charge', 'Notes'=>'notes', 
			'Ip'=>'ip', 'email'=>'email', 'mail_count'=>'mail_count', 'cfig_train'=>'cfig_train');	
		
		$update_array = array();
		foreach ($flight_data as $key =>$value){
			if(isset($request[$value])){
				$update_array += [$key=>$request[$value]];
			}
		}
		
		if (!empty($update_array)){
			$result = $wpdb->update($table_name, $update_array, array('Key'=>$request['id'] ));		
		}		
		if ($result ){
			return new \WP_REST_Response ($result);
		} else {
			return new \WP_Error( 'Update Failed', esc_html__( 'Unable to update Flight', 'my-text-domain' ), array( 'status' => 204 ) );
		}
	}			
//  delete daate. 	
	public function pdp_delete_dates( \WP_REST_Request $request) {
	// NOt implemented. 
	
		global $wpdb; 
		$table_name =  'wp_cloud_base_calendar';		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'Id is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		}		
		return new \WP_Error( 'rest_api_sad', esc_html__( 'Something went horribly wrong.', 'my-text-domain' ), array( 'status' => 500 ) );
	}	
	public function pdp_select_filters($request, $valid_filters){
	  $filter_string = "1";
	  $valid_keys = array_keys($valid_filters );		  
	  foreach($valid_keys as $key ){
	  	if(!empty($request[$key]) ){
	  		$filter_string = $filter_string . ' AND '. $valid_filters[$key] .'='.  $wpdb->prepare('%s' , $request[$key]);
	  	}
	  }
	return($filter_string);
	}	
}
