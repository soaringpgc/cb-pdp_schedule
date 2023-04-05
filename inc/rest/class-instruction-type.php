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
class Instruction_type extends \Cloud_Base_Rest {

	public function register_routes() {

  	$version = '1';
    $namespace = 'cloud_base/v' . $version;
    $base = 'route';
	 // the extra (?:/ ...  ) makes the parmater optional 
 		register_rest_route( $namespace, '/instruction_type(?:/(?P<id>[\d]+))?', array (
 			array(
       		'methods'  => \WP_REST_Server::READABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_get_instruction' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),        	
   		 	), array(
       		'methods'  => \WP_REST_Server::CREATABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_post_instruction' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_instruction' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_instruction' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_admin_access_check' ),  		      	
   		 	)) 
   		 );	
    }
	public function pdp_get_instruction ( \WP_REST_Request $request) {
		global $wpdb;
 		$table_name =  $wpdb->prefix . 'cloud_base_instruction_type';
			 	
		if(isset($request['id'])){			
			$sql = $wpdb->prepare("SELECT * FROM {$table_name}  WHERE id = %d" , $request['id']);
		} else {
			$sql = "SELECT * FROM {$table_name}";			
		}	
		$items = $wpdb->get_results($sql);
		return new \WP_REST_Response ($items); 	
	}
//  create new instruction type 
	public function pdp_post_instruction ( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_instruction_type';	
		if(isset($request['request_type']) ){	  
 		    $sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE request_type = %s " ,   $request['request_type']);	
			$result = $wpdb->get_results($sql); ; 
		    if( $result == null) {
		    	$record = array('request_type'=>$request['request_type'] );
		    	return new \WP_REST_Response ( $wpdb->insert($table_name, $record )); 
		    } else {
		    	return new \WP_Error( 'duplicate', esc_html__( 'instruction type exists', 'my-text-domain' ), array( 'status' => 409) );
		    }							
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}	
//  update instruction type . 	
	public function pdp_update_instruction( \WP_REST_Request $request) {
 		global $wpdb; 
 		$table_name =  $wpdb->prefix . 'cloud_base_instruction_type';
 		if(isset($request['request_type'])){
 			$record = array('request_type' =>$request['request_type']);		
 		}
 		if (isset($request['id']) && (isset($request['request_type'])) ){
		    if (isset($request['id'])  ){		    
		    	$result = $wpdb->update($table_name, $record, array('id' => $request['id'] ));
		    }		
		   	return new \WP_REST_Response ( $result); 	 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}					
//  delete trade entry. 	
	public function pdp_delete_instruction ( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_instruction_type';		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'ID is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		} else {
			$wpdb->delete($table_name, array( 'id' => $request['id']) );
		}	
	}	
}
