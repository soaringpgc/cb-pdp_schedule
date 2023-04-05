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
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::EDITABLE,  
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_update_dates' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	), array(
   		 	'methods'  => \WP_REST_Server::DELETABLE,
        	// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        	'callback' => array( $this, 'pdp_delete_dates' ),
        	// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
       		'permission_callback' => array($this, 'cloud_base_admin_access_check' ),  		      	
   		 	)) 
   		 );	
    }
      
	public function pdp_get_dates( \WP_REST_Request $request) {
		global $wpdb;
		$table_name =  $wpdb->prefix . 'cloud_base_calendar';
		
		if(isset($request['limit'])){
			$limit = $request['limit'];
		} else {
			$limit = 31; 
		}
		if(isset($request['offset'])){
			$offset = $request['offset'];
		} else {
			$offset = 0; 
		}		
		
		$date1 =  strtotime('first day of january');
        $date2 =  strtotime('last day of december');     
        $s_date1 = date('Y-m-d', $date1 );
        $s_date2 = date('Y-m-d', $date2 );  
        $fist_day_year =  new \DateTime( $s_date1 );
        $last_day_year =  new \DateTime( $s_date2 );   
		 	
		if(isset($request['session_end'])){	
 	// last day of a session this year. 		
			$sql = $wpdb->prepare("SELECT * FROM {$table_name}  WHERE `session` = %s AND  `calendar_date` >= %s ORDER BY calendar_date DESC LIMIT 1", $request['session_end'], $fist_day_year->format("Y-m-d") );			
		} elseif(isset($request['session_start'])){	
		// first day of a session this year. 		
			$sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE `session` = %s AND `calendar_date` >= %s  LIMIT 1",  $request['session_start'], $fist_day_year->format("Y-m-d") );			
		} elseif(isset($request['date'])){
		// specified date
			$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `calendar_date` = %s" ,  $request['date'] );										
		} elseif(isset($request['id'])){
		// specified id 
			$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `id` = %d" ,  $request['id'] );				
						
		} else {
		// get all of the days in the specified session of this year. 
 			if(isset($request['session'])){
 			 	$sql = $wpdb->prepare("SELECT * FROM {$table_name} s WHERE `session` = %d  AND `calendar_date` BETWEEN %s AND %s LIMIT %d OFFSET %d", 
 	 			 	 $request['session'], $fist_day_year->format("Y-m-d") , $last_day_year->format("Y-m-d"),  $limit, $offset );		 			 	  			 		
 			} elseif (isset($request['start'])){
 			// range of dates
 				if (isset($request['stop'])){
 					$stop = $request['stop'] ;
 				} else {
 					// if stop is not specified, return two weeks. 
 					$end = new \DateTime($request['start']);
 					$stop = $end->modify('+14 day')->format("Y-m-d");							
 				}
 				$sql = $wpdb->prepare("SELECT * FROM {$table_name}  WHERE `calendar_date` >= %s AND  `calendar_date` <= %s  LIMIT %d OFFSET %d ",  
 						$request['start'], $stop,  $limit, $offset);						
 			} else {
 			// if nothing specified return the next two weeks. 
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
		$table_name = $wpdb->prefix .  'cloud_base_calendar';
		$field_name = $wpdb->prefix .  'cloud_base_field_duty';
		$trade_name = $wpdb->prefix . 'cloud_base_trades';
		
		if(isset($request['s1']) && isset($request['s2']) && isset($request['s3']) && isset($request['e3']) ){	  
			
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
	year. Overlap next year so we have all of Juanuary to set up.
*/
            $jan_this_year =  new \DateTime( $s_date1 );
            $jan_next_year =  new \DateTime( $s_date2 );     
 			$jan_next_year->modify('+31 day');
// session start days are stored in the options table
			$schedule_days = get_option('cloudbase_tp_weekly', false );	
		
 			$c = 0; 
	  		$u = 0;
	  		$s_count=-1;
	  		$session_dates = array(  $jan_this_year, $s1, $s2, $s3, $e3, $jan_next_year);
	  		$sessions = array('0', '1', '2', '3', '0');
  
	  	    for ( $j = 0; $j < 5; $j++) {	
 	  	    	 $s_count++;	
	  	    	 for($i = $session_dates[$j]; $i <= $session_dates[$j+1] ; $i->modify('+1 day') ) {	
	  	    	 	  	    	 						 
  			 	  	$record = array( 'calendar_date'=>  $i->format("Y-m-d"), 'session'=> $sessions[$j]);	 			  	  
  			 	   	$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_date` = %s" ,  $i->format("Y-m-d"));		  	    	 
	  	    	 						  			  	 
 					// See if the calendar data already exists. 
  			 	   	$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_date` = %s" ,  $i->format("Y-m-d"));	
			 	 	$id = $wpdb->get_var($sql); 			 	 				 	 	
  			 	   	if ($id != null ) {  // it exists update it. 
  			 	   		$result = $wpdb->update($table_name, $record, array('id' => $id ));	
  			 	   		$u++;
  			 	   	} else { // Does not exist create a new one. 
  			 	   		$result = $wpdb->insert($table_name, $record);	
  			 	   		$id = $wpdb->insert_id;  // get the id of the record just inserted. 
  			 	   		$c++;
  			 	   	};	
   			 		$sql = $wpdb->prepare("SELECT MAX(id)FROM {$trade_name}");	
   			 		$max_t = $wpdb->get_var($sql);  
					if( $sessions[$j] === '0'){
						$max_t = 1;						
					}	
			 	 	for ($t = 1 ; $t <= $max_t; $t++ )	{	
			 	 		if($schedule_days[$t-1][$i->format('w')] == 1 ){ // if the weekday has a schedule flag for this trade create an entry in the field duty table. 
			 	 			$record = array( 'calendar_id'=>  $id, 'trade'=> $t, 'member_id'=>NULL );				 	 	
		 	 				$sql = $wpdb->prepare("SELECT id FROM {$field_name} WHERE `calendar_id` = %d  AND `trade` = %d ",  $id, $t);	
			 	 			$tid = $wpdb->get_var($sql); 			 	 			 	 	
  			 	   			if ($tid == null ) { // no record in field duty add it for date and trade with no member assigned.   			 	   			
								$result = $wpdb->insert($field_name, $record);	
  			 	   			}				 	   	
  			 	   		}  			 	   		
  			 	   	}	
   			 	 }	
 		    } 
 		    $count = array( 'updated' => $u, 'created'=>$c);
 		    return new \WP_REST_Response ( $count); 		
		} else {
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );
		}
	}	
//  update dates.  Only used for holidays and special dates. 	
	public function pdp_update_dates( \WP_REST_Request $request) {
 		global $wpdb; 
 		$table_name = $wpdb->prefix . 'cloud_base_calendar';
		$field_name = $wpdb->prefix . 'cloud_base_field_duty';
		
		if(isset($request['scheduling'])){
			$trade = explode(",", $request['scheduling']);
		} else {
 			$trade = array(0, 0, 0);
		};
 // return new \WP_REST_Response($trade); 			
  		if (isset($request['date'] )){ // get id of the date
 	   		$sql = $wpdb->prepare("SELECT id FROM {$table_name} WHERE `calendar_date` = %s" ,  $request['date']);	
 	 		$id = $wpdb->get_var($sql); 

   			$sql = $wpdb->prepare("SELECT MAX(id)FROM {$trade_name}");	
   			$max_t = $wpdb->get_var($sql);  
			if( $sessions[$j] === '0'){
				$max_t = 1;						
			}	
			
 			for ($t = 1 ; $t <= $max_t; $t++ )	{	// for each trade. 	
 				if($trade[$t-1] == "1"){
  					$record = array( 'calendar_id'=>  $id, 'trade'=> $t, 'member_id'=>NULL );		// new record 		 	 	
  	 	 			$sql = $wpdb->prepare("SELECT id FROM {$field_name} WHERE `calendar_id` = %s AND `trade`=%d",  $id, $t);	// does date and trade exist?
  					$tid = $wpdb->get_var($sql); 
  					if ($tid === null) {
 						$result = $wpdb->insert($field_name, $record);	 // add new 
 					} else {
 						$result = $wpdb->update($field_name, $record, array('id' => $tid ));	// update existing. 
 					}		
 				}
 			}
		   	return new \WP_REST_Response ($result); 	 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}			
//  delete date. 	
	public function pdp_delete_dates( \WP_REST_Request $request) {
	// NOt implemented. 
	
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_calendar';		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'Id is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		}		
		return new \WP_Error( 'rest_api_sad', esc_html__( 'Something went horribly wrong.', 'my-text-domain' ), array( 'status' => 500 ) );
	}	
}
