<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Cloud_Base
 * @subpackage Cloud_Base/public/partials
 
id="assign_trade_popup"  class="popup-content"
 */
?>
 <div style="text-align: center; "  > 
<?php

function instruction_Request_submit(){
		$requestType = $_SERVER['REQUEST_METHOD'];
		if($requestType == 'GET'){
			return;
		} 			
 		global $wpdb; 
// 		$table_instruction =  $wpdb->prefix . 'cloud_base_instruction';		
 		$table_type =  $wpdb->prefix . 'cloud_base_instruction_type';		
		
  		$user = get_user_by('ID', $_POST['member_id'] );
 		$user_meta = get_userdata($_POST['member_id']  );
 		$request_date = $_POST['request_date'] ;	
 		isset($request['inst_type']) ? $inst_type = $request['inst_type'] : $inst_type = 1 ;
 		$inst_type = $_POST['inst_type'] ;	
		$query_params = array( 'member_id'=> $user->ID, 'enter_date'=> date('Y-m-d'),
			'request_date'=> $request_date, 'inst_type'=> $inst_type);
		$display_name = $user->first_name .' '.  $user->last_name;
		if(isset($_POST['cfig1']) && (trim($_POST['cfig1'])!="") && ($_POST['cfig1'] > 0 )){
			   $cfig1 = get_user_by('ID', $_POST['cfig1'] );
			   $query_params = array_merge($query_params, array('cfig1'=>$cfig1->ID));	 
		} else {
			 $cfig1 = null;
		}
		if(isset($_POST['cfig2']) && (trim($_POST['cfig2'])!="") && ($_POST['cfig2'] > 0 )){		 
			 $cfig2 = get_user_by('ID', $_POST['cfig2'] );
			 $query_params = array_merge($query_params, array('cfig2'=> $cfig2->ID));			 
		} else {
			 $cfig2 = null;
		}
		if(isset($_POST['member_weight'])) {
			 $member_weight = $_POST['member_weight'] ;
			 add_user_meta($user->ID, 'weight',   $_POST['member_weight'], true);
		}		
		isset($_POST['confirmed']) ? $confirmed = 1 : $confirmed = 0 ;		
		isset($_POST['scheduling_assistance']) ? $scheduling_assistance = 1 : $scheduling_assistance = 0 ;
		$query_params = array_merge($query_params, array('confirmed'=> $confirmed));	
		$query_params = array_merge($query_params, array('scheduling_assistance'=> $scheduling_assistance));
		
		isset($_POST['comment']) ? $comment = $_POST['comment'] : $comment = "" ;
		$query_params = array_merge($query_params, array('comment'=> $comment));
 		$rest_request = new \WP_REST_REQUEST( 'POST', '/cloud_base/v1/instruction' ) ;  
		$rest_request->set_query_params($query_params );		   		  		
    	$rest_response = rest_do_request( $rest_request);    
    	
    	if ($rest_response->is_error()) {
     		$message = $rest_response->as_error()->get_error_messages()[0];
     		echo "<script>alert('$message');</script>"; 
    	} else  {  		
   		$sql = 'SELECT request_type FROM '. $table_type .' WHERE id=' . $inst_type ;
   		$inst_text = $wpdb->get_var($sql);
   		   		 		
   		$msg = 'Member: ' . $display_name . ', is requesting instruction on ' . substr($request_date, 0,10) . "<br>\n";  
   		$msg .=  'In the area of: ' . $inst_text  ."<br>\n";
   		$msg .=  'Student Weight is: ' . $member_weight ."<br>\n";
   		$msg .=  'Comment: ' . $comment ."<br>";
   		if( $cfig1 != null ){
   			   	$msg .=  'Request Instructor is: ' .  $cfig1->first_name .' '. $cfig1->last_name ."<br>\n";
   		}
   		if( $cfig2 != null ){
   			$msg .=  'Alternate Instructor is: ' . $cfig2->first_name .' '. $cfig2->last_name  ."<br>\n";	
   		}   		  		
   		$msg .=  'Preconfirmed with Insturctor: ' ;
   		if ($confirmed ){
   			$msg .= 'true';
   		} else {
   			$msg .= 'false';
   		}
   		$msg .= "<br>\n";
   		$msg .=  'Scheduling Assistance Requested: ' ; 
   		if ($scheduling_assistance ){
   			$msg .= 'true';
   		} else {
   			$msg .= 'false';
   		}
   		$msg .= "<br>\n";
   				 		
		$subject = "Instruction requested for: " . $display_name  ;
		$to = ""; 
		if($scheduling_assistance ){
			$sql = "SELECT wp_users.user_email FROM wp_users INNER JOIN wp_usermeta ON wp_users.ID = wp_usermeta.user_id WHERE wp_usermeta.meta_value like '%schedule_assist%' "; 
			$ops_emails = $wpdb->get_results($sql);			
			foreach ( $ops_emails as $m ){
				$to .= $m->user_email .', ';
			};
		}
		$to .= $user_meta->user_email.', '; 
		$to .= $cfig1 != null ? $cfig1->user_email : null ; 
		$to .= ', ' ;
		$to .= $cfig2 != null ? $cfig2->user_email : null ; 
		$headers = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\n";
// 		$headers .= 'From: <webmaster@pgcsoaring.com>' . "\n";
// var_dump($subject, $msg, $to )	;
// die();	
		
   		mail($to,$subject,$msg,$headers);
		echo('<p> Your Instruction Request has been entered.</p> ');

		}
}
function display_instruction_Request(){
	if(!is_user_logged_in()){
 		return;
	}
	global $wpdb; 
	$user = wp_get_current_user();
	$user_meta = get_userdata( $user->ID );
	$display_name = $user_meta->first_name .' '.  $user_meta->last_name;
	$user_weight = $user_meta->weight; 

	global $wpdb;
 	$table_name =  $wpdb->prefix . 'cloud_base_instruction_type';			 	
	$sql = "SELECT * FROM {$table_name}";				
	$instruction_types = $wpdb->get_results($sql);	

	echo('<div class="table-container popup-content" id="editinstruction"> ');
	echo ('<form id="instruction_request" action="#" method="POST" ><div >');
	echo(' <p id="display_date" >date</p>');

	$students = get_users(['role__in' => 'subscriber' ] );
	$instructors = get_users(['role__in' => 'cfi_g' ] );
	
	$roles = ( array ) $user->roles;
	
// Normally drop down will auto select logged in user and be hidden/unchanagable.
// however if user is CFIG allow to select any member to put on schedule. 	

// 	if(current_user_can('cb_edit_instruction')){ 
	if(in_array('cfi_g', $roles) ||  in_array('schedule_assist', $roles)) { 
//   		echo ('<div >');
		echo ('<div id="member_id" class="table-row" > <label for="member_id" style=color:black class="table-col">Student: </label>
    		<div class="table-col" > <select class="event_cal_form" name="member_id" id="member_id" form="instruction_request">
    		<option value=NULL>Student</option>');       
    	foreach($students as $key){ 	
    	  if( $key->ID == $user->ID ){
    	  		echo '<option selected value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  } else {
    	  		echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  }     			  	
    	}; 
	 echo ( '</select></div></div>');
	 	} else {
	 	 echo ('<div><input type="hidden" id="member_id" name="member_id" value='. $user->ID.' >');	 
     	 echo(' </div> ');			
	 	}

     echo ('<div id="prim_instructor" class="table-row"  > <label for="cfig1" style=color:black class="table-col">Instructor: </label>
    		<div class="table-col" > <select class="event_cal_form" name="cfig1" id="cfig1" form="instruction_request">
    		<option value=-1>Instructor</option> 
    		<option value=-1>Scheduling Assistance</option>');        
    	foreach($instructors as $key){ 	
 		  if( $key->ID == $user->ID ){
    	   		echo '<option selected value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  } else {
    	  		echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  }  
     	 };             
     echo ( '</select><div class="caption">Select Scheduling Assistance to for assistance in finding an instructor. </div></div></div> ');

 		if((in_array('cfi_g', $roles) ||  in_array('schedule_assist', $roles)) ){  // if instructor is scheduling auto pre-confirm 
		      echo('<div class="table-row"><label for="array_mergeirmed" style=color:black class="table-col">Confirmed: </label>
		  	 	<div class="table-col" > <input class="confirmed_check" type="checkbox" checked id="confirmed" name="confirmed" >
		  	 	</input></div></div>');
		}   else {
		      echo('<div class="table-row"><label for="array_mergeirmed" style=color:black class="table-col">Confirmed: </label>
		  	 	<div class="table-col" > <input class="confirmed_check" type="checkbox"  id="confirmed" name="confirmed" >
		  	 	</input><div class="caption">If you have pre-arranged with an instructor click here. </div></div></div>');
		}

      echo ('<div id="cfig2ructor" class="table-row" > <label for="cfig2" style=color:black class="table-col">Alt Inst: </label>
     	 		<div class="table-col" ><select class="event_cal_form" name="cfig2" id="cfig2" form="instruction_request">
     	 		<option value=NULL>Alt Instructor</option>');       
     			  foreach($instructors as $key){ 	
     			  	echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
     	 		 };             
     	 		echo ( '</select></div></div> ');
 
      		echo ('<div id="inst_type"  class="table-row"> <label for="inst_type" style=color:black class="table-col">Instruction Type: </label>
     	 		<div class="table-col" ><select class="event_cal_form" name="inst_type" id="inst_type" form="instruction_request">');       
     			  foreach($instruction_types as $key){ 	
     			  		echo '<option value=' . $key->id . '>'. $key->request_type . '</option>';
     	 		 };             
     	 		echo ( '</select></div> </div>');
     	 		   	 		
 			echo('<div class="table-row"><label for="comment" class="table-col">Comment</label>				
 			<div class="table-col" ><textarea id="comment" name="comment" rows="2", cols="55"></textarea></div></div>');


     	 		echo('<div class="table-row"><label for="member_weight" style=color:black class="table-col">Member Weight: </label>
     	 		 <div class="table-col" > <input type="number" id="member_weight" name="member_weight" value='.$user_weight .' ></input></div></div>');
 
//      	 		echo('<div class="table-row"><label for="scheduling_assistance" style=color:black class="table-col">Scheduling Assistance Requested? </label>
//      	 		 <div class="table-col" > <input  class="confirmed_check" type="checkbox" id="scheduling_assistance" name="scheduling_assistance" ></input></div></div>');
	echo(' <input type="hidden" id="request_date" name="request_date" ></input>');
	echo('<div><input type="submit" value="Submit" >'); //
	echo('<input type="button" value="Cancel"  onclick="hideinstructionrequest()" >'); //
	echo('</div></form> </div></div>');
// 	echo('<div id="cfig_accept" title="Instructor Acceptance" class="popup-content" <div id="dialogText" >stuff here </div></div>');
	echo('<div id="cfig_accept" title="Instructor Acceptance">  <div id="dialogText" > </div></div>');
	
// 	echo('<div id="pop_up_dialog" title="dialog" <div id="dialogText" class="popup-content"></div></div>');

	if(current_user_can('schedule_assist')){ 
// 		echo('<div id=editdate>  </div>');
		echo ('<div id="assigned_instructor" >');	 // 
		echo ('<form id="schedule_assist" action="#" >');
	
		echo(' <label for="assigned_instructor" style=color:black class="table-col">Instructor: </label>
    		 <select class="instructor_select" name="assigned_cfig" id="assigned_cfig" form="schedule_assist">
    		<option value=NULL>Instructor:</option>');       
    	foreach($instructors as $key){ 	
    	  if( $key->ID == $user->ID ){
    	  		echo '<option selected value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  } else {
    	  		echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  }     			  	
    	}; 
	 	echo ( '</select>');	 	
	 	echo('<input type="button" value="Cancel"  onclick="hideassigninstuctor()" >'); // 		
		echo('</form></div> ');
	 		 	
	}

	echo('<button type="button" id="showInstruction">Instructions: </button><div id="instructions" class="popup-content"><p><u>Students</u>: Click in the DAY you wish to schedule instruction. In the pop up form select your instructor, Alternate instructor if desired, 
			type of instruction, weight and any comment for the instructor. If you have pre- confermed with your primary instructor check the pre-confirmed box. If you 
		need help selecting an instructor select "Scheduling Assistance" as the primary instructor and we will attempt to match you up with an available instructor. </p>
		<p><u>Instructors</u>: You will receive an email when a student selects you as a primary or alternate instructor. Click on the colored bar with the students name to accept or 
		cancel the instruction. Instructors can pre-schedule students for future  weeks by selecting the arrows at the top and selecting the student name. Note: any instructor
		can over ride or steal another instructors student. This is so if a CFI-G is unavailable they can ask another CFIG to take over. Please use this with care. 	
	</p> </div>');

	echo ('<div id="calendar" "></div>');
	echo('<div style="width: 160px; margin: 0 auto; background: #000; color: #fff;"><input style="text-align: center;" type="button" value="Display Weekend schedule"  onclick="dumpweekendschedule()" ></div>'); //
// 	echo('<div id="dumpschedule"></div>');
	echo('<div>Note: The time slot shown for your instruction is not necessarly the time of your lesson. The Field Manager 
		and instructors will determine flying order. All students are required to be at the field by 8:00 to assist in setting up 
		for the days flying. </div>');


 }	
	
?> 
 




