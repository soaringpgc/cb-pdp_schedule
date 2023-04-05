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
class Trades extends \Cloud_Base_Rest {

	public function register_routes() {

  	$version = '1';
    $namespace = 'cloud_base/v' . $version;
    $base = 'route';
	 // the extra (?:/ ...  ) makes the parmater optional 
 		register_rest_route( $namespace, '/trades(?:/(?P<id>[\d]+))?', array (
 			array(
       		'methods'  => \WP_REST_Server::READABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_get_trade' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),        	
   		 	), array(
       		'methods'  => \WP_REST_Server::CREATABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_post_trade' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_trade' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_trade' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_admin_access_check' ),  		      	
   		 	)) 
   		 );	
    }
      
	public function pdp_get_trade ( \WP_REST_Request $request) {
		global $wpdb;
 		$table_name =  $wpdb->prefix . 'cloud_base_trades';
 		$cloud_base_authoritys = get_option('cloud_base_authoritys');
 // authority array is stored in WP options, It is created/updated on activation of Cloudbase plugin. 
			 	
		if(isset($request['id'])){			
			$sql = $wpdb->prepare("SELECT * FROM {$table_name}  WHERE id = %d" , $request['id']);
 			$items = $wpdb->get_results($sql);
// 		    return new \WP_REST_Response ($items); 	
		} else {
		
			$sql = "SELECT * FROM {$table_name}";
			$items = $wpdb->get_results($sql);
//		    return new \WP_REST_Response ($sql); 
		}	
		if( $wpdb->num_rows > 0 ) {
			foreach($items as $k=> $v){	
//	NTFS: The CAPABILITY is stored in the database, however it does not look pretty
// use the above array to reverse lookup the primary AUTHORITY that can signoff an item. 	
			$wp_roles = new \WP_Roles();
			$items[$k]->role_label =  $wp_roles->roles[$v->role]['name'];    //  display name of role. 					
			$items[$k]->authority_label =  $cloud_base_authoritys[$v->authority];
			$items[$k]->overrideauthoritylabel =  $cloud_base_authoritys[$v->overrideauthority];
			}			
 		 }	
 			
		return new \WP_REST_Response ($items); 	
	}
//  create new trade entry
	public function pdp_post_trade ( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_trades';
		$sessionMax = isset($request['sessionmax']) ? $request['sessionmax'] : 0;
		$yearMin = isset($request['yearmin']) ? $request['yearmin'] : 0;
		$authority = isset($request['authority']) ? $request['authority'] : "";
		$over_ride_authority = isset($request['overrideauthority']) ? $request['overrideauthority'] : "";
		$trade_role = isset($request['role']) ? $request['role'] : "inactive"; // default role with least authority. 
 	
		if(isset($request['trade']) ){	  
 		    $sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE trade = %s " ,   $request['trade']);	
			$result = $wpdb->get_results($sql); ; 
		    if( $result == null) {
		    	$record = array('trade'=>$request['trade'], 'authority'=>$request['authority'], 'role' => $trade_role,
		    	'overrideauthority'=>$over_ride_authority , 'sessionmax'=>$sessionMax, 'yearmin'=>$yearMin);
		    	return new \WP_REST_Response ( $wpdb->insert($table_name, $record )); 
		    } else {
		    	return new \WP_Error( 'duplicate', esc_html__( 'trade exists', 'my-text-domain' ), array( 'status' => 409) );
		    }			
				
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}	
//  update trade. 	
	public function pdp_update_trade( \WP_REST_Request $request) {
 		global $wpdb; 
 		$table_name =  $wpdb->prefix . 'cloud_base_trades';
 		if(isset($request['trade'])){
 			$record['trade'] =  $request['trade'] ;
 		}
 		 if(isset($request['role'])){
 			$record['role'] =  $request['role'] ;
 		}
 		if(isset($request['authority'])){
 			$record['authority'] =  $request['authority'] ;
 		}
 		if(isset($request['overrideauthority'])){
 			$record['overrideauthority'] =  $request['overrideauthority'] ;
 		}
 		if(isset($request['sessionmax'])){
 			$record['sessionmax'] =  $request['sessionmax'] ;
 		} 		
 		 if(isset($request['yearmin'])){
 			$record['yearmin'] =  $request['yearmin'] ;
 		} 		
 		if (isset($request['id']) && (isset($request['trade'])) ){
// 			$record = array('trade'=> $request['trade'] );
		    if (isset($request['id'])  ){		    
		    	$result = $wpdb->update($table_name, $record, array('id' => $request['id'] ));
		    }		
		   	return new \WP_REST_Response ( $result); 	 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}		
			
//  delete trade entry. 	
	public function pdp_delete_trade ( \WP_REST_Request $request) {
 		global $wpdb; 
 		$table_name =  $wpdb->prefix . 'cloud_base_trades';	
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'ID is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		} else {
			$wpdb->delete($table_name, array( 'id' => $request['id']) );
			return new \WP_REST_Response ( 'success'); 	 	
		}
		
//  		return new \WP_Error( 'Not allowed', esc_html__( 'Trade delete not allowed', 'my-text-domain' ), array( 'status' => 405 ) );	
		
	}	
}
