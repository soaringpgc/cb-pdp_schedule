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

<!-- 
  <meta charset="UTF-8">

  <title>CodePen - Event Calendar Widget</title>
 -->

<div style="text-align: center;" id="assignself" class="popup-overlay">
<?php
$roles  = array ('tow_pilot', 'inactive', 'board_member', 'operations', 'cfi_g' , 'chief_of_ops', 'flight_edit', 'maintenance_editor', 'administrator');
$capabiliteis = array('cb_edit_cfig', 'cb_edit_instruction',  'cb_edit_operations', 'cb_edit_towpilot', 'flight_edit' )	;					

		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
		$request->set_param( 'role', 'subscriber' );
        $response = rest_do_request($request);
		$pilots = $response->get_data();

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
		
		echo ('<form id="editdutyday" action="#" ><div >');
     	if( current_user_can( 'manage_options' || 'cb_edit_cfig' ) ) {	
          echo ('<div > <label for="instructor" style=color:black>Instructor: </label>
          <select class="event_cal_form" name="instructor" id="instructor" form="editdutyday">
          <option value="" selected>Instructor</option>');       
     	  foreach($instructors as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
		}
     	if( current_user_can( 'manage_options' || 'cb_edit_towpilot' ) ) {	
          echo ('<div > <label for="towpilot" style=color:black>Tow Pilot: </label>
          <select class="event_cal_form" name="towpilot" id="towpilot" form="editdutyday">
          <option value="" selected>Tow Pilot</option>');       
     	  foreach($towpilots as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
		}
     	if( current_user_can( 'manage_options' || 'operations' ) ) {	

          echo ('<div > <label for="field_manager" style=color:black>Field Manager: </label>
          <select class="event_cal_form" name="field_manager" id="field_manager" form="editdutyday">
          <option value="" selected>Field Manager</option>');       
     	  foreach($fm as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
          echo ('<div > <label for="assistant_field_manager" style=color:black>Assistant Field Manager: </label>
          <select class="event_cal_form" name="assistant_field_manager" id="assistant_field_manager" form="editdutyday">
          <option value="" selected>Assistant Field Manager</option>');       
     	  foreach($afm as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
			}

		echo('<input type="button" value="Cancel"  onclick=  jQuery("#assignself").addClass("popup-overlay") >');
 		echo ('<input type="hidden" id="dutyday" name="dutyday" value="" >');
		echo('</div></form> ');

?>

</div>

<div id="calendar"></div>
<p style=color:black >Click a date for details, Double Click to select or assign duty days. </p>

<script>


</script>


 

