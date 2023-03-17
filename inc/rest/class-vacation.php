<?php
namespace CB_PDP_schedule\Inc\Rest;
/**
 * The rest functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    cb-pdp_schedule
 * @subpackage cb-pdp_schedule/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and examples to create your REST access
 * methods. Don't forget to validate and sanatize incoming data!
 *
 * @package    cb-pdp_schedule
 * @subpackage cb-pdp_schedule/public
 * @author     Your Name <email@example.com>
 */
class Vacation extends \Cloud_Base_Rest {

	public function register_routes() {

  	$version = '1';
    $namespace = 'cloud_base/v' . $version;
    $base = 'route';
	 // the extra (?:/ ...  ) makes the parmater optional 
 		register_rest_route( $namespace, '/vacation(?:/(?P<id>[\d]+))?', array (
 			array(
       		'methods'  => \WP_REST_Server::READABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_get_vacation' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),        	
   		 	), array(
       		'methods'  => \WP_REST_Server::CREATABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_post_vacation' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_vacation' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_vacation' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_instruction_access_check' ),  		      	
   		 	)) 
   		 );	
    }
      
	public function pdp_get_vacation ( \WP_REST_Request $request) {
		global $wpdb;
 		$table_name =  $wpdb->prefix . 'cloud_base_vacation';
 		$calendar_name =  $wpdb->prefix . 'cloud_base_calendar';
// 		 	
		if(isset($request['date'])){			
			$sql = $wpdb->prepare("SELECT v.member_id FROM {$table_name} v INNER JOIN {$calendar_name} c ON v.vacation_date =c.id   WHERE c.calendar_date = %s" , $request['date']);
 			$items = $wpdb->get_results($sql);
 		    return new \WP_REST_Response ($items); 	
//  			$i=0;
//  			$vac_list =array();
// 			foreach ($item as $id) {
// 				$vac_list[$i] = get_userdata( $id);
// 				$i++;
// 			}
// 			return new \WP_REST_Response ($vac_list); 
		} elseif (isset($request['member_id']))	{
			$sql = $wpdb->prepare("SELECT c.id as 'cid', c.calendar_date, v.member_id  FROM {$calendar_name} c INNER JOIN {$table_name} v ON v.vacation_date = c.id  WHERE v.member_id = %d" , $request['member_id']);
			$items = $wpdb->get_results($sql);
			return new \WP_REST_Response ($items); 
		} else {
			$date1 = new \DateTime();
			$date2 = clone $date1;
			$date2->modify('+14 day');
			$date2->format("Y-m-d"); 
			$sql = $wpdb->prepare("SELECT v.member_id, c.id as 'cid', v.id as 'vid', c.calendar_date FROM {$table_name} v INNER JOIN {$calendar_name} c ON v.vacation_date = c.id  WHERE c.calendar_date BETWEEN %s AND %s" , $date1->format("Y-m-d"), $date2->format("Y-m-d"));
			$items = $wpdb->get_results($sql);
		    return new \WP_REST_Response ($items); 
		}	
	}
//  create new vacation entry
	public function pdp_post_vacation ( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_vacation';
		
	// need start of each session and days of week to schedule. 	
		if(isset($request['s1']) && isset($request['s2']) && isset($request['member_id'])){	  
			
		    $s1 =  $request['s1'] ;
		    $s2 =  $request['s2'] ;
		    
		    if ($s2 < $s1 ) {
		    	return new \WP_Error( 'end less than start', esc_html__( 'invalid dates', 'my-text-domain' ), array( 'status' => 400) );
		    }
		    $s = 0;
 		    for( $i = $s1; $i <= $s2; $i++){
 		    	$sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE `member_id` = %d AND `vacation_date` = %d" ,   $request['member_id'],  $i);	
 		    	$result = $wpdb->get_results($sql); ; 	
		    	if( $result == null) {
		    		$s++;
		    		$record = array('member_id'=>$request['member_id'], 'vacation_date'=> $i );
		    		$wpdb->insert($table_name, $record );
		    	}		    	
 		    }
 			return new \WP_REST_Response ( $s); 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}			
//  delete vacation entry. 	
	public function pdp_delete_vacation ( \WP_REST_Request $request) {
	
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_vacation ';		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'Id is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		}	
		$wpdb->delete($table_name , array('id'=> $request['id']));	
	}	
}
