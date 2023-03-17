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

		global $wpdb;
 // 		$table_name =  $wpdb->prefix . 'cloud_base_trades';
//   		$sql = "SELECT * FROM {$table_name} WHERE role = 'cfi_g' ";
//   		$trades = $wpdb->get_results($sql);

		$user = wp_get_current_user();
		$user_meta = get_userdata( $user->ID );
		$display_name = $user_meta->first_name .' '.  $user_meta->last_name;
		$user_weight = $user_meta->weight; 


		echo('<div id=editdate>  </div>');
		echo ('<form id="instruction_request" action="#" ><div >');

			$insturctors = get_users(['role__in' => 'cfi_g' ] );
			
       	 		echo ('<div id="prim_instructor" > <label for="prim_inst" style=color:black>Instructor: </label>
       	 		<select class="event_cal_form" name="prim_inst" id="prim_inst" form="instruction_request">
       	 		<option value=NULL>Instructor</option>');       
     				  foreach($insturctors as $key){ 	
     				  	echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
       	 		 };             
        	 	echo ( '</select></div> ');
       	 		echo ('<div id="alt_instructor" > <label for="alt_inst" style=color:black>Alt Inst: </label>
       	 		<select class="event_cal_form" name="alt_inst" id="alt_inst" form="instruction_request">
       	 		<option value=NULL>Instructor</option>');       
     				  foreach($insturctors as $key){ 	
     				  	echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
       	 		 };             
       	 		echo ( '</select></div> ');
 				echo('<div><label for="comment">Comment</label>
 				
 				<textarea id="comment" name="comment" rows="2", cols="55"></textarea><div>');

     	 		echo ('<div id="inst_type" > <label for="inst_type" style=color:black>Instruction Type: </label>
       	 		<select class="event_cal_form" name="inst_type" id="inst_type" form="instruction_request">
       	 		<option value=NULL>Select</option>');       
     				  foreach($insturctors as $key){ 	
     				  	echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
       	 		 };             
       	 		echo ( '</select></div> ');



       	 		echo('<div><label for="member_weight" style=color:black>Member Weight: </label>
       	 		 <input type="number" id="member_weight" name="member_weight" value='.$user_weight .' ></input></div>');
 
      	 		echo('<div><label for="confirmed" style=color:black>Confirmed with CFIG?: </label>
       	 		 <input type="checkbox" id="confirmed" name="confirmed" ></input></div>');

      	 		echo('<div><label for="scheduling_assistance" style=color:black>Scheduling Assistance Requested? </label>
       	 		 <input type="checkbox" id="scheduling_assistance" name="scheduling_assistance" ></input></div>');
	
		echo('<div><input type="button" value="Submit"  onclick="hideassignpopup()" >'); //
		echo('<input type="button" value="Cancel"  onclick="hideassignpopup()" >'); //
 		echo ('<input type="hidden" id="dutyday" name="dutyday" value="" ></div>');
		echo('</div></form> ');

?> 
 
</div>

<div id="calendar" "></div>

