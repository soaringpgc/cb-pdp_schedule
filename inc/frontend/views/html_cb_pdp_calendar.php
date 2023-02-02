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

// 		$date1 =  strtotime('first day of this month');
//         $date2 =  strtotime('last day of this month');     
//         $s_date1 = date('Y-m-d', $date1 );
//         $s_date2 = date('Y-m-d', $date2 );  
//         $fist_day_month =  new \DateTime( $s_date1 );
//         $last_day_month =  new \DateTime( $s_date2 );   
//         
// 		$request = new WP_REST_Request('GET', '/cloud_base/v1/calendar');
// 		$request->set_param( 'start', $fist_day_month->format("Y-m-d"));
// 		$request->set_param( 'stop', $last_day_month->format("Y-m-d"));
//         $response = rest_do_request($request);
// 		$duty_days = $response->get_data();

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
          <select name="instructor" id="instructor" form="editdutyday">
          <option value="" selected>Instructor</option>');       
     	  foreach($instructors as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
		}
     	if( current_user_can( 'manage_options' || 'cb_edit_towpilot' ) ) {	
          echo ('<div > <label for="towpilot" style=color:black>Tow Pilot: </label>
          <select name="towpilots" id="towpilots" form="editdutyday">
          <option value="" selected>Tow Pilot</option>');       
     	  foreach($towpilots as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
		}
     	if( current_user_can( 'manage_options' || 'operations' ) ) {	

          echo ('<div > <label for="field_manager" style=color:black>Field Manager: </label>
          <select name="field_manager" id="field_manager" form="editdutyday">
          <option value="" selected>Field Manager</option>');       
     	  foreach($fm as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
          echo ('<div > <label for="assistant_field_manager" style=color:black>Assistant Field Manager: </label>
          <select name="assistant_field_manager" id="assistant_field_manager" form="editdutyday">
          <option value="" selected>Assistant Field Manager</option>');       
     	  foreach($afm as $key){ 	
     	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
           };             
          echo ( '</select></div> ');
			}

		echo('<input type="button" value="Cancel"  onclick=  jQuery("#assignself").addClass("popup-overlay") >');

		echo('</div></form> ');


echo ('</form>');

// $dataToBePasssed = array (
//  	 
//   	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'1'),
//   	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'7' ),
//  	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'8' ),
// 	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'11' ),
//  	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'14' ),
// 	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'15' ),
// 	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'18' ),
// 	array ( eventName=>'No Tow Pilot Assigned', calendar=>'Tow Pilot', color=>'red', day=>'22' ),
// 
//     array ( eventName=>'Peter Hoey', calendar=>'Tow Pilot', color=>'green', day=>'21' ),
// 
// 	array ( eventName=>'No Instructor Assigned', calendar=>'Instructors', color=>'red', day=>'22' ),
// 	array ( eventName=>'No Field Manager', calendar=>'Field Manager', color=>'red', day=>'22' ),
// 	array ( eventName=>'No AFM', calendar=>'Field Manager', color=>'orange', day=>'22' ),
// 
//     array ( eventName=>'Interview - Jr. Web Developer', calendar=>'Instructors', color=>'orange', day=>'3' ),
//     array ( eventName=>'Demo New App to the Board', calendar=>'Tow Pilot', color=>'orange', day=>'1' ),
//     array ( eventName=>'Dinner w/ Marketing', calendar=>'Tow Pilot', color=>'orange', day=> '14' ),
// 
//     array ( eventName=>'Game vs Portalnd', calendar=>'Instructors', color=>'blue', day=>'21' ),
//     array ( eventName=>'Game vs Houston', calendar=>'Instructors', color=>'blue', day=>'31' ),
//     array ( eventName=>'Game vs Denver', calendar=>'Instructors', color=>'blue', day=>'14' ),
//     array ( eventName=>'Game vs San Degio', calendar=>'Instructors', color=>'blue', day=>'12' ),
// 
//     array ( eventName=>'School Play', calendar=>'Field Manager', color=>'yellow', day=>'5' ),
//     array ( eventName=>'Parent/Teacher Conference', calendar=>'Field Manager', color=>'yellow', day=>'15' ),
//     array ( eventName=>'Pick up from Soccer Practice', calendar=>'Field Manager', color=>'yellow', day=>'13' ),
//     array ( eventName=>'Ice Cream Night', calendar=>'Field Manager', color=>'yellow', day=>'17' ),
// 
//     array ( eventName=>'Free Tamale Night', calendar=>'AFM', color=>'green', day=>'27' ),
//     array ( eventName=>'Bowling Team', calendar=>'AFM', color=>'green', day=>'9' ),
//     array ( eventName=>'Teach Kids to Code', calendar=>'AFM', color=>'green', day=>'23' ),
//     array ( eventName=>'Startup Weekend', calendar=>'AFM', color=>'green', day=>'7' )
// 
// );
// wp_add_inline_script(  $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cb-pdp-schedule-frontend.js', 'const php_vars = ' . json_encode( $dataToBePassed ), 'before' );

?>
<!-- 
<p style=color:black> You are selecting Field duty for:</p>
<form method="post" action="submit.php">
	
	<div style=color:black id="cb_cal_date_p"> </div>
	<input type="checkbox" id="checkbox1" name="check1" title="check to confirm.">
	<p style=color:black> check the box to verify:</p>
	<input type="submit" value="Submit">
	<input type="button" value="Cancel" onClick="jQuery('#assignself').addClass('popup-overlay');">
</form>
 -->
</div>

<div id="calendar"></div>
<p style=color:black >Click a date for details, Double Click to select or assign duty days. </p>

<script>


</script>


 

