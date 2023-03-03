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
 <div style="text-align: center;" id="assign_trade_popup"> 
<?php
// $roles  = array ('tow_pilot', 'inactive', 'board_member', 'operations', 'cfi_g' , 'chief_of_ops', 'flight_edit', 'maintenance_editor', 'administrator');
// $capabiliteis = array('cb_edit_cfig', 'cb_edit_instruction',  'cb_edit_operations', 'cb_edit_towpilot', 'flight_edit' )	;					

//  			$rest_request = new \WP_REST_REQUEST( 'GET', '/cloud_base/v1/trades' ) ;  
//    			$rest_request->set_query_params(array('session_start'=> 1));
//   			$rest_response = rest_do_request( $rest_request);      		
//  			$server = rest_get_server();
//   			$trade_authorities = $server->response_to_data( $rest_response, false );


// 		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
// 		$request->set_param( 'role', 'tow_pilot' );
//         $response = rest_do_request($request);
// 		$towpilots = $response->get_data();
	
// 		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
// 		$request->set_param( 'role', 'assistant_field_manager' );
//         $response = rest_do_request($request);
// 		$afm = $response->get_data();

// 		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
// 		$request->set_param( 'role', 'field_manager' );
//         $response = rest_do_request($request);
// 		$fm = $response->get_data();
		
// 		$request = new WP_REST_Request('GET', '/cloud_base/v1/pilots');
// 		$request->set_param( 'role', 'cfi_g' );
//         $response = rest_do_request($request);
// 		$instructors = $response->get_data();

		global $wpdb;
 		$table_name =  $wpdb->prefix . 'cloud_base_trades';
//  		$cloud_base_authoritys = get_option('cloud_base_authoritys');
  		$sql = "SELECT * FROM {$table_name}";
  		$trades = $wpdb->get_results($sql);
		
//  		$towpilots = get_users(['role__in' => ['tow_pilot' ]] );
//  		$instructors = get_users(['role__in' => ['cfi_g' ]] );
// 		$fm = get_users(['role__in' => ['field_manager' ]] );	
// 		$afm =get_users(['role__in' => ['assistant_field_manager' ]] );		

		echo('<div id=editdate>  </div>');
		echo ('<form id="editdutyday" action="#" ><div >');

  		foreach($trades as $trade ){	
  			$tl =	str_replace(' ', '_', $trade->trade);	
			if( current_user_can( 'manage_options') || current_user_can($trade->overrideauthority) ) {	
 				$duty_trade = get_users(['role__in' => [$trade->role] ] );
       	 		echo ('<div id="'.str_replace(' ','',$trade->trade).'_" class="popup-content"> <label for="'.$tl.'" style=color:black>'.$trade->trade.': </label>
       	 		<select class="event_cal_form" name="'.$tl.'" id="'.str_replace(' ', '_', $trade->trade).'" form="editdutyday">
       	 		<option value="" selected>'.$trade->trade.'</option>');       
     				  foreach($duty_trade as $key){ 	
     				  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
       	 		 };             
       	 		echo ( '</select></div> ');
			}		
		
 		}		

//      	if( current_user_can( 'manage_options') || current_user_can('cb_edit_cfig' ) ) {	
//           echo ('<div id="assignins" class="popup-content"> <label for="instructor" style=color:black>Instructor: </label>
//           <select class="event_cal_form" name="instructor" id="instructor" form="editdutyday">
//           <option value="" selected>Instructor</option>');       
//      	  foreach($instructors as $key){ 	
//      	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
//            };             
//           echo ( '</select></div> ');
// 		}
//      	if( current_user_can( 'manage_options') || current_user_can('cb_edit_towpilot' ) ) {	
//           echo ('<div id="chief_tow" class="popup-content"> <label for="towpilot" style=color:black>Tow Pilot: </label>
//           <select class="event_cal_form" name="towpilot" id="towpilot" form="editdutyday">
//           <option value="" selected>Tow Pilot</option>');       
//      	  foreach($towpilots as $key){ 	
//      	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
//            };             
//           echo ( '</select></div> ');
// 		}
//      	if( current_user_can( 'manage_options') || current_user_can('cb_edit_operations' ) ) {	
// 
//           echo ('<div id="assignfm" class="popup-content"> <label for="field_manager" style=color:black>Field Manager: </label>
//           <select class="event_cal_form" name="field_manager" id="field_manager" form="editdutyday">
//           <option value="" selected>Field Manager</option>');       
//      	  foreach($fm as $key){ 	
//      	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
//            };             
//           echo ( '</select></div> ');
//           echo ('<div id="assignafm" class="popup-content"> <label for="assistant_field_manager" style=color:black>Assistant Field Manager: </label>
//           <select class="event_cal_form" name="assistant_field_manager" id="assistant_field_manager" form="editdutyday">
//           <option value="" selected>Assistant Field Manager</option>');       
//      	  foreach($afm as $key){ 	
//      	  	echo '<option value=' . $key->ID . '>'. $key->last_name . ', '. $key->first_name . '</option>';
//            };             
//           echo ( '</select></div> ');
// 			}
//		echo('<input type="button" value="Cancel"  onclick= jQuery("#assign_trade_popup").addClass("popup-overlay") >'); //

		echo('<input type="button" value="Cancel"  onclick="hideassignpopup()" >'); //
 		echo ('<input type="hidden" id="dutyday" name="dutyday" value="" >');
		echo('</div></form> ');

?> 
 
</div>

<!-- 
<div id="fullCalModal" style="display:none; text-align: center; ">
  <div>ID: <span id="modalID"></span></div>
 <div>Title: <span id="modalTitle"></span></div>
 <div>Location: <span id="modalLocation"></span></div>
 <div>Start Date: <span id="modalStartDate"></span></div>
 <div>End Date: <span id="modalEndDate"></span></div>
</div>
 -->

<div id="calendar"></div>

<script>


</script>
<style>

.fc-event {
    font-size: .85em;} 
/* 
#calendar {
    width: 200px;
    margin: 0 auto;
    font-size: 10px;
}
.fc-header-title h2 {
    font-size: .9em;
    white-space: normal !important;
}
.fc-view-month .fc-event, .fc-view-agendaWeek .fc-event {
    font-size: 0;
    overflow: hidden;
    height: 2px;
}
.fc-view-agendaWeek .fc-event-vert {
    font-size: 0;
    overflow: hidden;
    width: 2px !important;
}
.fc-agenda-axis {
    width: 20px !important;
    font-size: .7em;
}

.fc-button-content {
    padding: 0;
}

https://stackoverflow.com/questions/5372328/tiny-version-of-fullcalendar
 */
 
 /* 
#calendar {
    width: 200px;
    margin: 0 auto;
    font-size: 10px;
}
.fc-toolbar {
    font-size: .9em;
}
.fc-toolbar h2 {
    font-size: 12px;
    white-space: normal !important;
}
/* click +2 more for popup */
/* 
.fc-more-cell a {
    display: block;
    width: 85%;
    margin: 1px auto 0 auto;
    border-radius: 3px;
    background: grey;
    color: transparent;
    overflow: hidden;
    height: 4px;
}
.fc-more-popover {
    width: 100px;
}
.fc-view-month .fc-event, .fc-view-agendaWeek .fc-event, .fc-content {
    font-size: 0;
    overflow: hidden;
    height: 2px;
}
.fc-view-agendaWeek .fc-event-vert {
    font-size: 0;
    overflow: hidden;
    width: 2px !important;
}
.fc-agenda-axis {
    width: 20px !important;
    font-size: .7em;
}

.fc-button-content {
    padding: 0;
}
 
 */
</style>



 

