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
class Instruction extends \Cloud_Base_Rest {

	public function register_routes() {

  	$version = '1';
    $namespace = 'cloud_base/v' . $version;
    $base = 'route';
	 // the extra (?:/ ...  ) makes the parmater optional 
 		register_rest_route( $namespace, '/instruction(?:/(?P<id>[\d]+))?', array (
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
       		'permission_callback' => array($this, 'cloud_base_dummy_access_check' ),  		      	
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
       		'permission_callback' => array($this, 'cloud_base_members_access_check' ),  		      	
   		 	)) 
   		 );	
    }
	public function pdp_get_instruction ( \WP_REST_Request $request) {
		global $wpdb;
 		$table_instruction =  $wpdb->prefix . 'cloud_base_instruction';
 		$table_type =  $wpdb->prefix . 'cloud_base_instruction_type';	
 		$results_array = array();

		if(isset($request['start']) && isset($request['end'])){			
  			$sql = $wpdb->prepare("SELECT *, i.id as id FROM {$table_instruction} i INNER JOIN {$table_type} t ON t.id=i.inst_type WHERE i.request_date BETWEEN %s AND %s", $request['start'], $request['end'] );	 
			$items = $wpdb->get_results($sql);
				
			foreach($items as $value){ 
				$start = new \DateTime($value->request_date);
				
				$end = new \DateTime($value->request_date);
				$end->add(new \DateInterval("PT1H"));
						
				if($value->scheduling_assistance == 1  ){				
					$c = 'orange';
					$tc = 'white';	
				}
				$f = get_user_meta($value->member_id, 'first_name', true  );
  				$l = get_user_meta($value->member_id, 'last_name', true  );  
  				$u_name = 	 $l. ', ' .$f ;
				$title = $u_name ;
				
				if($value->assigned_cfig_id != 0) {
					$f = get_user_meta($value->assigned_cfig_id, 'first_name', true  );
  					$l = get_user_meta($value->assigned_cfig_id, 'last_name', true  );  
  					$i_name = $l. ', ' .$f ;	
					$c = 'green';
					$tc = 'white';	
					$title .=  '/' . $i_name;
				} elseif($value->cfig1_id != 0) {
					$f = get_user_meta($value->cfig1_id, 'first_name', true  );
  					$l = get_user_meta($value->cfig1_id, 'last_name', true  );  	
					$i_name = 	 $l. ', ' .$f ;
					$c ='yellow';
					$tc = 'black';
					if ($value->cfig_confirmed == 1){
						$c ='blue';
						$tc = 'white';
						$title .=  '/' . $i_name;
					}					
				}
 			$r = array ( 'id'=> $value->id, 'title'=>$title, 'color'=>$c, 'textColor'=>$tc, 
 				'start'=> $start->format('Y-m-d H:i:s'), 'end'=> $end->format('Y-m-d G:i:s'), 
 				'cfig1'=>$value->cfig1_id, 'cfig2'=>$value->cfig2_id , 'member_id'=>$value->member_id, 'cfiga'=>$value->assigned_cfig_id );				

				array_push($results_array, $r );	
			}	 				
			return new \WP_REST_Response ($results_array);	
		
		} else {
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
		}	
	}
//  create new instruction request 
	public function pdp_post_instruction ( \WP_REST_Request $request) {
		$max_per_hour = 3; // this needs to be a configuration changeable value. 
		$hours_per_day = 4; // this needs to be a configuration changeable value. 
		$first_instruction = 9; // this needs to be a configuration changeable value. 
		$lesson_length = 1; // this needs to be a configuration changeable value. 
		$inst = null;
		$cfig2 = null;
				
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_instruction';	
		$table_type =  $wpdb->prefix . 'cloud_base_instruction_type';	
		if(isset($request['request_date']) ){	
			$request_date =  new \DateTime($request['request_date']);
			if(isset($request['member_id']) ){
				$user = get_user_by('ID', $request['member_id'] );
			} else {
				$user = wp_get_current_user();				
			} 				
			$user_meta = get_userdata( $user->ID );
			$display_name = $user_meta->first_name .' '.  $user_meta->last_name;				
			
			if(isset($request['scheduling_assistance'] ) && ($request['scheduling_assistance']!= 0 )){
				$assistance = true ;			
			} else {
				$assistance = false ;
				if(isset($request['cfig1']) ){
					$inst = get_user_by('ID', $request['cfig1'] );
					$inst_meta = get_userdata( $inst->ID );
					$display_cfig1 = $inst_meta->first_name .' '.  $inst_meta->last_name;
				}
				if(isset($request['cfig2']) ){
					$cfig2 = get_user_by('ID', $request['cfig2'] );
					$cfig2_meta = get_userdata( $cfig2->ID );
					$display_cfig2 = $cfig2_meta->first_name .' '.  $cfig2_meta->last_name;
				}			
			}
			if(isset($request['confirmed'] ) && ($request['confirmed']!= 0 )){
				$confirmed = true ;	
			} else {
				$confirmed = false;
			}					
			isset($request['inst_type']) ? $inst_type = $request['inst_type'] : $inst_type = 1 ;
			isset($request['comment']) ? $comment = $request['comment'] : $comment = NULL ;
			if( isset($request['weight']) ){
				$member_weight = $request['weight'] ;
				update_user_meta( $user->ID, 'weight', $request['weight'] )	;		
			} else {			
				$member_weight = $user_meta->weight; 
			}			
 		    $sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE DATE(request_date) = %s", $request_date->format('Y-m-d'));	
			$result = $wpdb->get_results($sql);
			$inst_count = $wpdb->num_rows; 

			foreach( $result as $k){
				if( $k->member_id == $user->ID ) {
					return new \WP_Error( 'duplicate', esc_html__( 'instruction requests exists', 'my-text-domain' ), array( 'status' => 409) );
				}
			}			
			
			if ($inst_count > $max_per_hour *$hours_per_day ){
				return new \WP_Error( 'max limit', esc_html__( 'All instruction slots filled', 'my-text-domain' ), array( 'status' => 409) );
			}
			$time_slot = floor($inst_count/$max_per_hour) + $first_instruction  ;
			$assigned_time = $request_date->setTime( $time_slot ,0, 0 ); 

			if($assistance){
				$record = array( 
					'member_id'=>  $user->ID, 
					'enter_date'=> date("Y-m-d"), 
					'request_date'=> $assigned_time->format('Y-m-d H:i:s'), 
					'cfig1_id'=> null,  
					'cfig2_id'=> null, 
					'cfig_confirmed'=> false, 
					'assigned_cfig_id'=> null,
					'scheduling_assistance'=> $assistance , 
					'inst_type'=> $inst_type , 
					'request_notes'=> $comment
				);	
			} else {
				$record = array( 
					'member_id'=>  $user->ID, 
					'enter_date'=> date("Y-m-d"), 
					'request_date'=> $assigned_time->format('Y-m-d H:i:s'), 
					'cfig1_id'=> $inst->ID,  
					'cfig2_id'=>  isset($request['cfig2']) ?  $cfig2->ID: null, 
					'cfig_confirmed'=> $confirmed, 
					'assigned_cfig_id'=> null,
					'scheduling_assistance'=> $assistance , 
					'inst_type'=> $inst_type , 
					'request_notes'=> $comment
				);	
			}			 	 				
  			$result = $wpdb->insert($table_name , $record);							
  			$msg = 'Member ' . $display_name . ' is requesting instruction on: ' . $request_date->format('Y-m-d') .  "<br>\n";	
			if ( $assistance ) {
				$subject = "PGC Instruction Assistance for: " . $display_name ;
   				$sql = "SELECT wp_users.user_email FROM wp_users INNER JOIN wp_usermeta ON wp_users.ID = wp_usermeta.user_id WHERE wp_usermeta.meta_value like '%tow_scheduler%' "; 
				$send_emails = $wpdb->get_results($sql);	
				$to ="";
				foreach ( $send_emails as $m ){
					$to .= $m->user_email .', ';
				};
				$to .= $user_meta->user_email; 
							
			} else {
				$subject = "PGC Instruction Member: " . $display_name ;
				$to = $user_meta->user_email  . ', ' . $inst_meta->user_email; 	
				isset($request['cfig2']) ?  $to .=  ', ' .  $cfig2_meta->user_email : ""; 
				
//  				$to .=  ', ' .  $cfig2_meta->user_email; 		
			}
			
			$msg .= 'Member weight: ' . $member_weight .  "<br>\n"; 
			$sql = ('Select request_type FROM ' . $table_type . ' WHERE id=' . $inst_type);
			$msg .= 'Instruction type: ' . $wpdb->get_var($sql) .  "<br>\n"; 
			$msg .= 'Email:  <a href="mailto:' . $user_meta->user_email .  '">'. $user_meta->user_email  .'</a><br>\n'; 
		 	
			$headers = "MIME-Version: 1.0" . "\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\n";
			$headers .= 'From: <webmaster@pgcsoaring.com>' . "\n";
			mail($to, $subject, $msg, $headers);
			return new \WP_REST_Response ( 'Request entered'); 	

	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}	
//  update instruction request . 	
	public function pdp_update_instruction( \WP_REST_Request $request) {
 		global $wpdb; 
 		$table_name =  $wpdb->prefix . 'cloud_base_instruction';
 		$table_type =  $wpdb->prefix . 'cloud_base_instruction_type';
 		if (isset($request['id']) && (isset($request['cfig'])) ){
 			$record = array( 'assigned_cfig_id'=>$request['cfig']);		// update record 		 	 						
  			$result = $wpdb->update($table_name, $record, array('id' => $request['id']));	// update existing. 	
 		    $sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $request['id']);	
			$record = $wpdb->get_row($sql);
			$member = get_user_by('ID', $record->member_id );
			$user_meta = get_userdata( $member->ID );
			$display_name = $user_meta->first_name .' '.  $user_meta->last_name;				
			$request_date = new \DateTime($record->request_date);
			$cfig = get_user_by('ID', $record->assigned_cfig_id);
			$cfig_meta = get_userdata( $member->ID );
			$display_cfig = $cfig_meta->first_name .' '.  $cfig_meta->last_name;				

			$to = $cfig->user_email . ', ' .  $member->user_email  ; 		
			if(current_user_can('schedule_assist')){
				$schedule_user= get_current_user();
				$to  .= $schedule_user->user_email;
			}

  			$msg = 'Instruction Schedule for: ' . $display_name . ' has been updated for: ' . $request_date->format('Y-m-d') .  "<br>\n";	
			$msg .= 'Member weight: ' . $user_meta->weight .  "<br>\n"; 
			$sql = ('Select request_type FROM ' . $table_type . ' WHERE id=' . $record->inst_type );
			$msg .= 'Instruction type: ' . $wpdb->get_var($sql) .  "<br>\n"; 
			$msg .= 'Instructor assigned: ' . $display_cfig .  "<br>\n"; 
			$subject = "PGC Instruction For: " . $display_name ;
		 	
			$headers = "MIME-Version: 1.0" . "\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\n";
			$headers .= 'From: <webmaster@pgcsoaring.com>' . "\n";

   		mail($to, $subject, $msg, $headers);
		   	return new \WP_REST_Response ( $result); 	 	
	     } else {	     
			return new \WP_Error( ' Failed', esc_html__( 'missing parameter(s)', 'my-text-domain' ), array( 'status' => 422) );	     
 	     }
	}					
//  delete trade entry. 	
	public function pdp_delete_instruction ( \WP_REST_Request $request) {
		global $wpdb; 
		$table_name =  $wpdb->prefix . 'cloud_base_instruction';		
		if (!isset($request['id'])){
			return new \WP_Error( 'Id missing', esc_html__( 'ID is required', 'my-text-domain' ), array( 'status' => 400 ) );		
		} else {
			$wpdb->delete($table_name, array( 'id' => $request['id']) );
		}	
	}	
}
