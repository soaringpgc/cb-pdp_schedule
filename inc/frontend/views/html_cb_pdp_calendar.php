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
 */
?>

 <div style="text-align: center;" id="assignself" class="popup-overlay center"> 
<?php
// $roles  = array ('tow_pilot', 'inactive', 'board_member', 'operations', 'cfi_g' , 'chief_of_ops', 'flight_edit', 'maintenance_editor', 'administrator');
// $capabiliteis = array('cb_edit_cfig', 'cb_edit_instruction',  'cb_edit_operations', 'cb_edit_towpilot', 'flight_edit' )	;					

//  			$rest_request = new \WP_REST_REQUEST( 'GET', '/cloud_base/v1/trades' ) ;  
//    			$rest_request->set_query_params(array('session_start'=> 1));
//   			$rest_response = rest_do_request( $rest_request);      		
//  			$server = rest_get_server();
//   			$trade_authorities = $server->response_to_data( $rest_response, false );

// 		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
// 		$request->set_param( 'role', 'subscriber' );
//      $response = rest_do_request($request);
// 		$pilots = $response->get_data();

		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
		$request->set_param( 'role', 'tow_pilot' );
        $response = rest_do_request($request);
		$towpilots = $response->get_data();
	
		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
		$request->set_param( 'role', 'assistant_field_manager' );
        $response = rest_do_request($request);
		$afm = $response->get_data();

		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
		$request->set_param( 'role', 'field_manager' );
        $response = rest_do_request($request);
		$fm = $response->get_data();
		
		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
		$request->set_param( 'role', 'cfi_g' );
        $response = rest_do_request($request);
		$instructors = $response->get_data();
		echo('<div id=editdate>  </div>');
		
		echo ('<form id="editdutyday" action="#" ><div >');
     	if( current_user_can( 'manage_options') || current_user_can('cb_edit_cfig' ) ) {	
          echo ('<div id="assignins" class="popup-content"> <label for="instructor" style=color:black>Instructor: </label>
          <select class="event_cal_form" name="instructor" id="instructor" form="editdutyday">
          <option value="" selected>Instructor</option>');       
     	  foreach($instructors as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
		}
     	if( current_user_can( 'manage_options') || current_user_can('cb_edit_towpilot' ) ) {	
          echo ('<div id="assigntp" class="popup-content"> <label for="towpilot" style=color:black>Tow Pilot: </label>
          <select class="event_cal_form" name="towpilot" id="towpilot" form="editdutyday">
          <option value="" selected>Tow Pilot</option>');       
     	  foreach($towpilots as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
		}
     	if( current_user_can( 'manage_options') || current_user_can('operations' ) ) {	

          echo ('<div id="assignfm" class="popup-content"> <label for="field_manager" style=color:black>Field Manager: </label>
          <select class="event_cal_form" name="field_manager" id="field_manager" form="editdutyday">
          <option value="" selected>Field Manager</option>');       
     	  foreach($fm as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
          echo ('<div id="assignafm" class="popup-content"> <label for="assistant_field_manager" style=color:black>Assistant Field Manager: </label>
          <select class="event_cal_form" name="assistant_field_manager" id="assistant_field_manager" form="editdutyday">
          <option value="" selected>Assistant Field Manager</option>');       
     	  foreach($afm as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
			}
//		echo('<input type="button" value="Cancel"  onclick= jQuery("#assignself").addClass("popup-overlay") >'); //

		echo('<input type="button" value="Cancel"  onclick="hideassignpopup()" >'); //
 		echo ('<input type="hidden" id="dutyday" name="dutyday" value="" >');
		echo('</div></form> ');

?> 
 
 </div>

<div id="fullCalModal" style="display:none; text-align: center; ">
  <div>ID: <span id="modalID"></span></div>
 <div>Title: <span id="modalTitle"></span></div>
 <div>Location: <span id="modalLocation"></span></div>
 <div>Start Date: <span id="modalStartDate"></span></div>
 <div>End Date: <span id="modalEndDate"></span></div>
</div>

<div id="calendar"></div>

<script>


</script>


 

