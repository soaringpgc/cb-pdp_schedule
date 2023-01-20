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
class Calendar extends \Cloud_Base_Rest {

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
		$date1 =  strtotime('first day of january');
        $date2 =  strtotime('last day of december');     
        $s_date1 = date('Y-m-d', $date1 );
        $s_date2 = date('Y-m-d', $date2 );  
        $fist_day_year =  new \DateTime( $s_date1 );
        $last_day_year =  new \DateTime( $s_date2 );   
		 	
		if(isset($request['date'])){
			$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `calendar_date` = %s" ,  $request['date'] );										
		} elseif(isset($request['id'])){
			$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `id` = %d" ,  $request['id'] );				
						
		} else {
 			if(isset($request['session'])){
 			 	$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `session` = %d  AND `calendar_date` BETWEEN %s AND %s", 
 	 			 	 $request['session'], $fist_day_year->format("Y-m-d") , $last_day_year->format("Y-m-d")  );	 			 		
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
	This will generate or update enteries for every day from Jan 1 of this year to Jan 31st of next
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
 			$c = 0; 
	  		$u = 0;
	  		$s_count=-1;
	  		$session_dates = array(  $jan_this_year, $s1, $s2, $s3, $e3, $jan_next_year);
	  		$sessions = array('0', '1', '2', '3', '0');
  
	  	    for ( $j = 0; $j < 5; $j++) {	
 	  	    	 $s_count++;	
	  	    	 for($i = $session_dates[$j]; $i <= $session_dates[$j+1] ; $i->modify('+1 day') ) {							 
  			 	  	$record = array( 'calendar_date'=>  $i->format("Y-m-d"), 'session'=> $sessions[$j], 'scheduling'=>  in_array( $i->format('w'), $s_days ) );	 			  	 
  			 	   	$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_date` = %s" ,  $i->format("Y-m-d"));	
			 	 	$id = $wpdb->get_var($sql); 
  			 	   	if ($id != null ) {
  			 	   		$result = $wpdb->update($table_name, $record, array('id' => $id ));	
  			 	   		$u++;
  			 	   	} else {
  			 	   		$result = $wpdb->insert($table_name, $record);	
  			 	   		$c++;
  			 	   	}		
			 	 	$sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE `calendar_date` = %s" ,  $i->format("Y-m-d"));				   
   			 	 }	
 		    } 
 		    $count = array( 'updated' => $u, 'created'=>$c);
 		    return new \WP_REST_Response ( $count); 		
		} else {
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );
		}
	}	
//  update dates. 	
	public function pdp_update_dates( \WP_REST_Request $request) {
 		global $wpdb; 
 		$table_name =  'wp_cloud_base_calendar';
 		
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
}
