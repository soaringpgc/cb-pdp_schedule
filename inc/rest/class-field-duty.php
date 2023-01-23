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
class Field_Duty extends \Cloud_Base_Rest {

	public function register_routes() {

  	$version = '1';
    $namespace = 'cloud_base/v' . $version;
    $base = 'route';
	 // the extra (?:/ ...  ) makes the parmater optional 
 		register_rest_route( $namespace, '/field_duty(?:/(?P<id>[\d]+))?', array (
 			array(
       		'methods'  => \WP_REST_Server::READABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_get_field_duty' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),        	
   		 	), array(
       		'methods'  => \WP_REST_Server::CREATABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_post_field_duty' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_field_duty' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_field_duty' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
   		 	)) 
   		 );	
    }      
	public function pdp_get_field_duty( \WP_REST_Request $request) {
	global $wpdb;
		$table_name =  'wp_cloud_base_field_duty';
 		$calendar_name =  'wp_cloud_base_calendar';
 				
		if(isset($request['limit'])){
			$limit = $request['limit'];
		} else {
			$limit = 25; 
		}
		if(isset($request['offset'])){
			$offset = $request['offset'];
		} else {
			$offset = 0; 
		}		
				 
 		if (isset($request['start'])){
 			$start = new \DateTime($request['start']);
 			if (isset($request['stop'])){
 				$stop = new \DateTime($request['stop']);
 				$sql = $wpdb->prepare("SELECT * FROM {$table_name} f INNER JOIN {$calendar_name} c ON f.calendar_id = c.id WHERE c.calendar_date BETWEEN %s AND %s LIMIT %d OFFSET %d" ,  
  					$start->format("Y-m-d"), $stop->format("Y-m-d"), $limit, $offset );	 
 			} else {
  			$sql = $wpdb->prepare("SELECT * FROM {$table_name} f INNER JOIN {$calendar_name} c ON f.calendar_id = c.id WHERE c.calendar_date= %s " ,  
  					$start->format("Y-m-d"));	 																		
 			}												
 		} else {
  			$start = new \DateTime('now');
			$stop = clone $start;
  			$stop = $stop->modify('+14 day')->format("Y-m-d");	
  			$sql = $wpdb->prepare("SELECT * FROM {$table_name} f INNER JOIN {$calendar_name} c ON f.calendar_id = c.id WHERE c.calendar_date BETWEEN %s AND %s LIMIT %d OFFSET %d" ,  
  				$start->format("Y-m-d"), $stop, $limit, $offset );	
 		} 			
 		$items = $wpdb->get_results($sql);
 	  	return new \WP_REST_Response ($items);
 
	}
//  create new field_duty 
	public function pdp_post_field_duty( \WP_REST_Request $request) {

		global $wpdb; 
		$table_name =  'wp_cloud_base_field_duty';
		
	// need calendar_id, trade_id, and member_id  	
		if(isset($request['calendar_id']) && isset($request['trade_id']) && isset($request['member_id'])){	  
  			$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_id` = %d AND trade_id = %d" , $request['calendar_id'], $request['trade_id'] );	
			$id = $wpdb->get_var($sql); 
 			if( $id == null ){
	  			$record = array( 'calendar_id'=>  $request['calendar_id'], 'trade_id'=> $request['trade_id'], 'member_id'=>  $request['member_id']);	 			  	 
 				$result = $wpdb->insert($table_name, $record);				
 			} else {
 				return new \WP_Error( 'duplicate', esc_html__( 'member already assigned', 'my-text-domain' ), array( 'status' => 409) );
 			} 
 		    return new \WP_REST_Response ( $result); 		
		} else {
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );
		}
	}	
	
//  update field_duty. 	
	public function pdp_update_field_duty( \WP_REST_Request $request) {
		global $wpdb; 
 		$table_name =  'wp_cloud_base_field_duty';
 		
 		if (isset($request['scheduling']) && (isset($request['id']) || isset($request['date'] )) ){
 			$record = array('scheduling'=> $request['scheduling'] );
		    if (isset($request['id'])  ){		    
		    	$result = $wpdb->update($table_name, $record, array('id' => $request['id'] ));
		    }		
		    if(isset($request['date']) ){	 	
		    	$result = $wpdb->update($table_name, $record, array('calendar_date' => $request['date'] ));				    
		    }
		   	return new \WP_REST_Response ( $result); 	 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
 
	}			
//  delete field_duty. 	
	public function pdp_delete_field_duty( \WP_REST_Request $request) {

		global $wpdb; 
		$table_name =  'wp_cloud_base_field_duty';		
		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'Id is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		}	
		$wpdb->delete($table_name , array('id'=> $request['id']));			
	}	
}
