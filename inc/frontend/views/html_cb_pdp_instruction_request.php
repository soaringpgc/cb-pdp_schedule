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

		$table_instruction =  $wpdb->prefix . 'cloud_base_instruction';		
 		$table_type =  $wpdb->prefix . 'cloud_base_instruction_type';			 	




}
function display_instruction_Request(){
	global $wpdb;
 // 	$table_name =  $wpdb->prefix . 'cloud_base_trades';
//   	$sql = "SELECT * FROM {$table_name} WHERE role = 'cfi_g' ";
//   	$trades = $wpdb->get_results($sql);

	$user = wp_get_current_user();
	$user_meta = get_userdata( $user->ID );
	$display_name = $user_meta->first_name .' '.  $user_meta->last_name;
	$user_weight = $user_meta->weight; 

	global $wpdb;
 	$table_name =  $wpdb->prefix . 'cloud_base_instruction_type';			 	
	$sql = "SELECT * FROM {$table_name}";				
	$instruction_types = $wpdb->get_results($sql);	

	echo('<div class="table-container popup-content" id="editinstruction"> ');
	echo ('<form id="instruction_request" action="#" ><div >');
	$students = get_users(['role__in' => 'subscriber' ] );
	$instructors = get_users(['role__in' => 'cfi_g' ] );
	
	$roles = ( array ) $user->roles;
// Normally drop down will auto select logged in user and be hidden/unchanagable.
// however if user is CFIG allow to select any member to put on schedule. 	
	if(in_array('cfi_g', $roles)){ 
		echo ('<div id="student"class="table-row" >');
		echo ('<div id="student"class="table-row student_hidden" >');	
		echo(' <label for="student" style=color:black class="table-col">Student: </label>
    		<div class="table-col" > <select class="event_cal_form" name="student" id="student" form="instruction_request">
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
	 	 echo ('<div><input type="hidden" id="student" name="student" value='. $user->ID.' >');	 	
	 	}
	 echo(' </div> ');		
     echo ('<div id="prim_instructor"class="table-row"  > <label for="prim_inst" style=color:black class="table-col">Instructor: </label>
    		<div class="table-col" > <select class="event_cal_form" name="prim_inst" id="prim_inst" form="instruction_request">
    		<option value=NULL>Instructor</option>');       
    	foreach($instructors as $key){ 	
 		  if( $key->ID == $user->ID ){
    	   		echo '<option selected value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  } else {
    	  		echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
    	  }  
     	 };             
     echo ( '</select></div></div> ');
     echo ('<div id="alt_instructor" class="table-row" > <label for="alt_inst" style=color:black class="table-col">Alt Inst: </label>
     	 		<div class="table-col" ><select class="event_cal_form" name="alt_inst" id="alt_inst" form="instruction_request">
     	 		<option value=NULL>Alt Instructor</option>');       
     			  foreach($instructors as $key){ 	
     			  	echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
     	 		 };             
     	 		echo ( '</select></div></div> ');
 			echo('<div class="table-row"><label for="comment" class="table-col">Comment</label>				
 			<div class="table-col" ><textarea id="comment" name="comment" rows="2", cols="55"></textarea></div></div>');

      		echo ('<div id="inst_type"  class="table-row"> <label for="inst_type" style=color:black class="table-col">Instruction Type: </label>
     	 		<div class="table-col" ><select class="event_cal_form" name="inst_type" id="inst_type" form="instruction_request">
     	 		<option value=NULL>Select</option>');       
     			  foreach($instruction_types as $key){ 	
     			  	echo '<option value=' . $key->ID . '>'. $key->request_type . '</option>';
     	 		 };             
     	 		echo ( '</select></div> </div>');
     	 		echo('<div class="table-row"><label for="member_weight" style=color:black class="table-col">Member Weight: </label>
     	 		 <div class="table-col" > <input type="number" id="member_weight" name="member_weight" value='.$user_weight .' ></input></div></div>');
 
     	 		echo('<div class="table-row"><label for="confirmed" style=color:black class="table-col">Confirmed with CFIG?: </label>
     	 		 <div class="table-col" > <input type="checkbox" id="confirmed" name="confirmed" ></input></div></div>');

     	 		echo('<div class="table-row"><label for="scheduling_assistance" style=color:black class="table-col">Scheduling Assistance Requested? </label>
     	 		 <div class="table-col" > <input type="checkbox" id="scheduling_assistance" name="scheduling_assistance" ></input></div></div>');
	echo(' <input type="hidden" id="requested_date" name="requested_date" ></input>');
	echo('<div><input type="button" value="Submit"  onclick="hideassignpopup()" >'); //
	echo('<input type="button" value="Cancel"  onclick="hideinstructionrequest()" >'); //
 	echo ('<input type="hidden" id="dutyday" name="dutyday" value="" ></div>');
	echo('</div></form> </div></div>');
	echo('<div id="cfig_accept"></div>');
	echo('<div>Note: The time slot shown for your instruction is not necessarly the time of your lesson. The Field Manager 
		and instructors will determine flying order. All students are required to be at the field by 8:00 to assist in setting up 
		for the days flying. </div>');

	echo ('<div id="calendar" "></div>');
 }	
	
?> 
 




