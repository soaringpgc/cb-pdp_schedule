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
    $session1Start =  date('Y-m-d', $date1 );
    $session3End =  date('Y-m-d', $date2 );     

    $sessionduration = ($date2 - $date1)/60/60/24/3 ;

    $session2Start =  date('Y-m-d', strtotime( $session1Start. '+' .  (int)$sessionduration . 'days'));  
    $session3Start =  date('Y-m-d', strtotime( $session1Start. '+' .   (int)$sessionduration*2 . 'days'));  

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
Adjust dates as necessary. Select days of the week to enable scheduling. (Even if the only trade to be scheduled is tow pilot.) 
All dates outside of session 1, 2 or 3 are session 0. 
</p>
	
	<form action="admin-post.php" method="post" id="config_page" align ="left">
		<input type="hidden" name=action value="session_page">
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
          		echo '<input type="date" id="$session3end" name="$session3end" value="' . $session3End  . '"></div><br>';          		
// days of week for scheduling 

          		echo '<br><br><h4>Days to Schedule</h4>';

          		echo '<div class="hform"><label for monday >Monday:</label>';
           		echo '<input type="checkbox" id="monday" name="monday">';   
	       		
          		echo '<label for tuesday >Tuesday:</label>';
           		echo '<input type="checkbox" id="tuesday" name="tuesday">';            		         		
	          		       		          		       		
          		echo '<label for wednesday >Wednesday:</label>';
           		echo '<input type="checkbox" id="wednesday" name="wednesday"></div><br>';          		
           	          		       		
            	echo '<div class="hform"><label for thursday >Thursday:</label>';
             	echo '<input type="checkbox" id="thursday" name="thursday">'; 
            		         		
          		echo '<label for friday >Friday:</label>';
           		echo '<input type="checkbox" id="friday" name="friday">';          		
           		       		
          		echo '<label for saturday >Saturday:</label>';
           		echo '<input type="checkbox" id="saturday" name="saturday"></div><br>';          		
           		       		
          		echo '<div class="hform"><label for sunday >Sunday:</label>';
           		echo '<input type="checkbox" id="sunday" name="sunday"></div>';          		
     		}
     		wp_nonce_field('session_page' );  
  	   		submit_button();	
  	   	echo '</form>' ;
  	   	?> 
 	<form action="admin-post.php" method="post" id="config_page" align ="left">
		<input type="hidden" name=action value="session_date"> 	
		<?php  	 
			if( current_user_can( 'manage_options' ) ) {	
         		echo '<label for Individualdatest>Edit dates:</label>';
          		echo '<input type="date" id="editdates" name="editdates" value="">';
          		
          		echo '<label for isession>Session:</label>';
          		echo '<input type="number" id="isession" name="number" min="0" max="3">'; 
          		
              	echo '<label for scheduling >Schedule:</label>';
           		echo '<input type="radio" id="scheduling" name="scheduling"><br>';          		
      		          		
			  submit_button('update');	
			}
 	   	?> 		
		</form>  	
			
</div>

