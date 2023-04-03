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
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_field_duty' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_field_duty' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_operatioins_access_check' ),  		      	
   		 	)) 
   		 );	
    }      
	public function pdp_get_field_duty( \WP_REST_Request $request) {
	global $wpdb;
		$table_name =  $wpdb->prefix . 'cloud_base_field_duty';
 		$calendar_name =  $wpdb->prefix . 'cloud_base_calendar';
 		$trade_name =  $wpdb->prefix . 'cloud_base_trades';
		$results_array = array();

		if(isset($request['limit'])){
			$limit = $request['limit'];
		} else {
			$limit = 80; 
		}
		if(isset($request['offset'])){
			$offset = $request['offset'];
		} else {
			$offset = 0; 
		}			//  'member_id'=>$value->member_id,
		if(isset($request['fc'])){			
 			if (isset($request['start'])){
 				$start = new \DateTime($request['start']);
 				if (isset($request['end'])){
 					$stop = new \DateTime($request['end']);
  					$sql = $wpdb->prepare("SELECT f.id as id, c.calendar_date, c.session, f.trade, f.member_id, t.trade, t.id as tradeId FROM {$calendar_name} c INNER JOIN {$table_name} f ON c.id=f.calendar_id INNER JOIN {$trade_name} t ON f.trade = t.id WHERE c.calendar_date BETWEEN %s AND %s  ORDER BY c.calendar_date LIMIT %d OFFSET %d" ,  
					$start->format("Y-m-d"), $stop->format("Y-m-d"), $limit, $offset );	 									
 				} else {
  				$sql = $wpdb->prepare("SELECT f.id as id, c.calendar_date, c.session, f.trade, f.member_id FROM {$calendar_name} c INNER JOIN {$table_name} f ON c.id=f.calendar_id WHERE c.calendar_date = %s LIMIT %d OFFSET %d", $start->format("Y-m-d"), $limit, $offset );	 
 				}												
 			} else {
				return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );
 			} 	
 			$cdays = $wpdb->get_results($sql);
// return new \WP_REST_Response ($cdays);
			foreach($cdays as $value){ 
				if($value->member_id != null) {
					$f = get_user_meta($value->member_id, 'first_name', true  );
  					$l = get_user_meta($value->member_id, 'last_name', true  );  	
					$r = array ('id'=> $value->id, 'title'=>  $value->trade.": " .$f .' ' .$l , 'groupId'=>$value->trade, 'color'=>'green', 'start'=> $value->calendar_date, 'session'=>$value->session, 'tradeId'=>$value->trade,  'member_id'=>$value->member_id );				
				} else {				
					$r = array ( 'id'=> $value->id, 'title'=>'No '.$value->trade. ' assigned', 'groupId'=>$value->trade, 'color'=>'red', 'start'=> $value->calendar_date, 'session'=>$value->session, 'tradeId'=>$value->trade );				
				}
				array_push($results_array, $r );	
			}	
 				
			return new \WP_REST_Response ($results_array);
		} 

		if(isset($request['member_id']) && isset($request['session'])){
  			$sql = $wpdb->prepare("SELECT f.id as id, c.calendar_date, c.session, f.trade, f.member_id, t.trade, t.id as tradeId FROM {$calendar_name} c INNER JOIN {$table_name} f ON c.id=f.calendar_id INNER JOIN {$trade_name} t ON f.trade = t.id WHERE f.member_id =  %d ORDER BY c.calendar_date LIMIT %d OFFSET %d" ,  
					$request['member_id'], $limit, $offset );	 									
		 	$results = $wpdb->get_results($sql);
			return new \WP_REST_Response ($results);
		}		
 									 
 		if (isset($request['start'])){
 			$start = new \DateTime($request['start']);
 			if (isset($request['stop'])){
 				$stop = new \DateTime($request['stop']);
 				$sql = $wpdb->prepare("SELECT *, t.id as trade_id FROM {$table_name} f INNER JOIN {$calendar_name} c ON f.calendar_id = c.id INNER JOIN {$trade_name} t on f.trade = t.id WHERE c.calendar_date BETWEEN %s AND %s LIMIT %d OFFSET %d" ,  
  					$start->format("Y-m-d"), $stop->format("Y-m-d"), $limit, $offset );	 
 			} else {
  			$sql = $wpdb->prepare("SELECT *, t.id as trade_id FROM {$table_name} f INNER JOIN {$calendar_name} c ON f.calendar_id = c.id INNER JOIN {$trade_name} t on f.trade = t.id WHERE c.calendar_date= %s " ,  
  					$start->format("Y-m-d"));	 																		
 			}												
 		} else {
  			$start = new \DateTime('now');
			$stop = clone $start;
  			$stop = $stop->modify('+14 day')->format("Y-m-d");	
  			$sql = $wpdb->prepare("SELECT *, t.id as trade_id  FROM {$table_name} f INNER JOIN {$calendar_name} c ON f.calendar_id = c.id INNER JOIN {$trade_name} t on f.trade = t.id WHERE c.calendar_date BETWEEN %s AND %s LIMIT %d OFFSET %d" ,  
  				$start->format("Y-m-d"), $stop, $limit, $offset );	
 		} 			
 		$items = $wpdb->get_results($sql);
// 		 	  	return new \WP_REST_Response ($sql);
 	  	return new \WP_REST_Response ($items);
 
	}
//  create new field_duty 
	public function pdp_post_field_duty( \WP_REST_Request $request) {

		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_field_duty';
		
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
 		$calendar_name   =  $wpdb->prefix . 'cloud_base_calendar';
		$field_name      =  $wpdb->prefix . 'cloud_base_field_duty';

		$member = null;
	  	if (isset($request['member_id']) &&  !($request['member_id'] ==0 )) { // get id of the member leave at null if zero 
			$member = $request['member_id'] ;
		}
		if (isset($request['id'])){
			$sql = $wpdb->prepare("SELECT id FROM {$field_name} WHERE `id` = %d" ,  $request['id']);	
 	 		$id = $wpdb->get_var($sql); 
 			if( $id == null ){
 				return new \WP_Error( 'Failed', esc_html__( 'Not Found', 'my-text-domain' ), array( 'status' => 404) );	     
 			} else {
 				$record = array( 'member_id'=>$member );		// update record 		 	 						
 				$result = $wpdb->update($field_name, $record, array('id' => $id ));	// update existing. 
 				$sql = $wpdb->prepare("SELECT * FROM {$field_name} WHERE `id` = %d" ,  $request['id']);	
 	 			$result = $wpdb->get_results($sql); 
 				return new \WP_REST_Response ( $result); 	 
 			}
					
		} elseif (isset($request['date']) && isset($request['trade_id']) ){ // get id of the date		
 	   		$sql = $wpdb->prepare("SELECT id FROM {$calendar_name} WHERE `calendar_date` = %s" ,  $request['date']);	
 	 		$id = $wpdb->get_var($sql); 
 			if( $id == null ){
 				return new \WP_Error( ' Failed', esc_html__( 'Not Found', 'my-text-domain' ), array( 'status' => 404) );	     
 			} else{
  			 	 $sql = $wpdb->prepare("SELECT id FROM {$field_name} WHERE `calendar_id` = %d AND `trade` = %d " , $id, $request['trade_id'] );	 	
 				 $fid = $wpdb->get_var($sql); // get field duty record. 	
				 if( $fid == null ){
 				 	if ( $request['trade_id'] != "1" ){
 						return new \WP_Error( ' Failed', esc_html__( 'Not Found', 'my-text-domain' ), array( 'status' => 404) );	
 					}  else {
 					 	$record = array('calendar_id'=> $id ,'trade'=> $request['trade_id'], 'member_id'=>$member );// new record 			
 						$result = $wpdb->insert($field_name, $record);	 // add new 
 //							return new \WP_REST_Response ( $wpdb->last_query); 	
 					} 					  
  				 } else{
 				   	$record = array('trade'=> $request['trade_id'], 'member_id'=>$member );		// update record 		 	 						
 					$result = $wpdb->update($field_name, $record, array('id' => $fid ));	// update existing. 
 				}
 			}
		   	return new \WP_REST_Response ( $result); 	 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
  	   } 
	}			
//  delete field_duty. 	
	public function pdp_delete_field_duty( \WP_REST_Request $request) {

		global $wpdb; 
		$table_name =  $wpdb->prefix . 'loud_base_field_duty';		
		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'Id is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		}	
		$wpdb->delete($table_name , array('id'=> $request['id']));			
	}	
}
