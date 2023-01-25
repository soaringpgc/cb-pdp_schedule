<?php  
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    cb-pdp-schedule
 * @subpackage cb-pdp-schedule/admin/partials
 */
    $date1 =  strtotime('first saturday of april ');
    $date2=   strtotime('last sunday of november ');  
    $rest_request = new WP_REST_REQUEST( 'GET', '/cloud_base/v1/calendar' ) ;  
    $rest_request->set_query_params(array('session_start'=> 1));
    $rest_response = rest_do_request( $rest_request);
    $server = rest_get_server();
    $data = $server->response_to_data( $rest_response, false );

	$sdate = $data[0]->calendar_date ;    
    if($sdate  == null ){
    	$session1Start =  date('Y-m-d', $date1 );
	} else {
		$session1Start = $sdate ;
	}
    $session3End =  date('Y-m-d', $date2 );     
    $sessionduration = ($date2 - $date1)/60/60/24/3 ;
    
    $rest_request->set_query_params(array('session_start'=> 2));
    $rest_response = rest_do_request( $rest_request);
    $server = rest_get_server();
    $data = $server->response_to_data( $rest_response, false );

	$sdate = $data[0]->calendar_date ;       
    if($sdate  == null ){
    	$session2Start =  date('Y-m-d', strtotime( $session1Start. '+' .  (int)$sessionduration . 'days'));  
	} else {
		$session2Start = $sdate ;
	}  
	
    $rest_request->set_query_params(array('session_start'=> 3));
    $rest_response = rest_do_request( $rest_request);
    $server = rest_get_server();
    $data = $server->response_to_data( $rest_response, false );

	$sdate = $data[0]->calendar_date ;       
    if($sdate  == null ){
    	$session3Start =  date('Y-m-d', strtotime( $session1Start. '+' .   (int)$sessionduration*2 . 'days'));  
	} else {
		$session3Start = $sdate ;
	}  	
	
//    $session2Start =  date('Y-m-d', strtotime( $session1Start. '+' .  (int)$sessionduration . 'days'));    
//    $session3Start =  date('Y-m-d', strtotime( $session1Start. '+' .   (int)$sessionduration*2 . 'days'));  

?>

<SCRIPT LANGUAGE="JavaScript" ID="js1">
		var cal = new CalendarPopup();
	 </SCRIPT>
<head>
<br>
 <div style="display:inline-block"  align:left>
<h3 align:left>Setup Sessions</h3>

<p style="max-width:600px;">
If no dates have been set up for this year, Session 1 start will default to the last Saturday in April. 
Session 3 end will default to the last Sunday in November. Session 2 start and session 3 start will be calculated. 
Adjust dates as necessary. Select days of the week to enable scheduling for each trade. 
All dates outside of session 1, 2 or 3 are session 0. </p>
<p  style="max-width:600px;">It is recommended that the days of the week for scheduling Tow Pilots, Instructors and Field Mangers be selected 
before updating the Year. If not you will have to re-run "Update year" NOTE each section of this form is processed
independently of the others. (pressing Update Daily does not Update the Year.) Set up holidays last as they will be
overwritten when "Update Year" is pressed. 

</p>
<p  style="max-width:600px;">Enabled session should be rest each year. This is the flag to 
enable sign up for duty for each session. 
</p>

	
	<form action="admin-post.php" method="post" id="schedule_setup" align ="left">
		<input type="hidden" name=action value="schedule_setup">
    		<?php  			
    		if( current_user_can( 'manage_options' ) ) {	
// Session 1 start date
         		echo '<div class="hform"><label for session1Start>Session 1 Start:</label>';
          		echo '<input type="date" id="session1Start" name="session1Start" value="' . $session1Start . '"></div>';
// Session 2 start date
         		echo '<div class="hform"><label for session2Start>Session 2 Start:</label>';
          		echo '<input type="date" id="session2Start" name="session2Start" value="' . $session2Start . '"></div>';
// Session 3 start date
         		echo '<div class="hform"><label for session2Start>Session 3 Start:</label>';
          		echo '<input type="date" id="session3Start" name="session3Start" value="' . $session3Start . '"></div>';                                          
// Session 3 end date
         		echo '<div class="hform"><label for session1Start>Session 3 End:</label>';
          		echo '<input type="date" id="$session3end" name="session3end" value="' . $session3End  . '"></div><br><br>';          		
// days of week for scheduling 
 				submit_button('Update Year', 'primary', 'selection', true);		
 				echo '<hr>';         		 
				$weekly_options =  get_option('cloudbase_tp_weekly' ) ; 
         		$tp_options = $weekly_options[0];
         		$weekarray = array ('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat' ); 
         		$tradearray = array ('Tow Pilots', 'Instructors', 'Field Manager'); 
         		
				for($j=0; $j<=2; $j++ ){    
				  	$tp_options = $weekly_options[$j]; 		
           			echo '<br><h4>'. $tradearray[$j].' Schedule: </h4>';         		
 					for( $i = 0; $i<= 6; $i++ ){
          				echo '<dd id="rr-element" class="hform2">
   							<label for="tpschedule['.$i.']">';
    						if ( $tp_options[$i] == '1' )	{
       							echo '<input type="checkbox" value="1" id="weekschedule['.$j.'][ '.$i.']" name="weekschedule['.$j.']['.$i.']" checked/>';
       						} else {
       							echo '<input type="checkbox" value="1" id="weekschedule['.$j.'][ '.$i.']" name="weekschedule['.$j.']['.$i.']"/>';
      						}      						
     					echo $weekarray[$i].'</label></dd>';
 					}	  					  
 				     echo '<br>';
 				 }								  				 
				submit_button('Update Daily', 'primary', 'selection', true);						  	         		            		         	          		        		
     		}
     		wp_nonce_field('schedule_page');  

         		echo '<hr><label for Individualdatest>Add Holiday:</label>';
          		echo '<input type="date" id="editdates" name="editdates" value="">';
       		echo '<p>Select the trades to be scheculed for the holiday.';      		    	     		
           		echo '<dd id="rr-element" class="hform">
   						<label for="holtp-">
      						<input type="checkbox" value="1" id="holtp" name="holiday[0]">
     							Tow Pilot
  							 </label>
					  </dd>';
           		echo '<dd id="rr-element" class="hform">
   						<label for="holins-">
      						<input type="checkbox" value="1" id="holins" name="holiday[1]">
     							Instructor
  							 </label>
					  </dd>';
            	echo '<dd id="rr-element" class="hform">
   						<label for="holfm-">
      						<input type="checkbox" value="1" id="holfm" name="holiday[2]">
     							Field Manager
  							 </label>
					  </dd>'; 
        		echo '<dd id="rr-element" class="hform">
						<label for="holsession-">
							<input type="number" id="holsession" name="holsession" min="0" max="3">
  								Session
							</label>
				  	</dd>';  					      		          		
				submit_button('Add Holiday', 'primary', 'selection', true);		         		
				$enabled = get_option('cloudbase_enabled_sessions', $_POST['enablesession'], false );		        		         		
           		echo '<hr><dd id="rr-element" class="hform">
   						<label for="enablesession[0]">';
  						if ( $enabled[0] == '1' )	{
       							echo '<input type="checkbox" value="1" id="enablesession[0]" name="enablesession[0]" checked/>';
       						} else {
       							echo '<input type="checkbox" value="1" id="enablesession[0]" name="enablesession[0]"/>';
      						}   
     						echo '	Session 1
  							 </label>
					  </dd>';
           		echo '<dd id="rr-element" class="hform">
  						<label for="enablesession[1]">';
  						if ( $enabled[1] == '1' )	{
       							echo '<input type="checkbox" value="1" id="enablesession[0]" name="enablesession[1]" checked/>';
       						} else {
       							echo '<input type="checkbox" value="1" id="enablesession[0]" name="enablesession[1]"/>';
      						}   
     						echo '	Session 2
  							 </label>
					  </dd>';
            	echo '<dd id="rr-element" class="hform">
   						<label for="holfm-">
 						<label for="enablesession[2]">';
  						if ( $enabled[2] == '1' )	{
       							echo '<input type="checkbox" value="1" id="enablesession[0]" name="enablesession[2]" checked/>';
       						} else {
       							echo '<input type="checkbox" value="1" id="enablesession[0]" name="enablesession[2]"/>';
      						}   
     						echo '	Session 3
  							 </label>
					  </dd><br>';     		          		
				submit_button('Enable Sessions', 'primary', 'selection', true);				
 	   	?> 		
		</form>  	
			
</div>

