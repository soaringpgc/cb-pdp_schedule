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
 
 Quck and I hope not to dirty form to allow members to select duty day choices. For the 
 logged in member member it will display avaliable dates for enabled session. (If a date
 has already been assighend to a member it will not be avaliable.) An email will to send
 to the operations team members so they can use this information to assign field duty days.
 A record of the choices is also saved to the database. At the time of this writing nothing
 is done with the saved data. But perhaps in the future we can all additionl functions to 
 help automate this process. - dsj 6 march 2023 

 */
?>
<?php
// process form. quick and ditry, form submitted back to itself. 
	function field_duty_submit_request(){
		$label_text = array('First', 'Second', 'Third');
		global $wpdb;		
		$table_calendar =  $wpdb->prefix . 'cloud_base_calendar';
		$table_preferences =  $wpdb->prefix . 'cloud_base_duty_day_member_preferences';
		$choices = array();
		if( !isset($_POST['member_id'])){
			return;
		}
		// Check that the nonce was set and valid
   		if( !wp_verify_nonce($_POST['_wpnonce'], 'submit_field_duty') ) {
   		    echo 'Did not save because your form seemed to be invalid. Sorry';
   		    return;
   		}
   		$user = get_user_by('ID', $_POST['member_id'] );

 		$user_meta = get_userdata($_POST['member_id']  );
		$display_name = $user_meta->first_name .' '.  $user_meta->last_name;
		$user_roles=$user_meta->roles; 
		list( $role_id, $role_name) = fd_user_role($user_roles);

   		$enabled_sessions = get_option('cloudbase_enabled_sessions'); 
//  $enabled_sessions= array( '1', '1', '0'); 	
 		
   		foreach( $enabled_sessions as $k=>$v){
   			
   			if($v == '1'){
   				$session = $k+1;
   				$selected = array();	
   				for($i=1; $i<4; $i++){
   					$choice = 'choice' . $session . '_' . $i ; 
   					$sql = $wpdb->prepare("Select calendar_date from {$table_calendar} where id = %d", $_POST[$choice]);   	
   					$selected['session_'.$i]= $_POST[$choice] ;			
   					$choices[$k][$i] = $wpdb->get_var($sql);;     						
   				}   		
   			}
   				$pref_array = array( 'member_id'=>$user->id , 'trade'=>$role_id, 'session'=>$session   , 'year'=>date('Y') );
//   			$wpdb->insert($table_preferences, array_merge($pref_array , $selected));

//    			   		var_dump(array_merge ($pref_array , $selected) );
//    					die();
   		}   		
   		$msg = 'Member ' . $display_name . ' is requesting the following dates for field duty as ' . $role_name . '\r\n'; 
		if( $enabled_sessions[0] == '1' ){
   			$msg .=  'for Session 1; First Choice: ' . $choices[0][1] . ' Second Choice: ' . $choices[0][2] . ' Third Choice: ' . $choices[0][3] .'\r\n';
		}	
		if( $enabled_sessions[1] == '1' ){
			$msg .=  'and is requesting the following dates for session 2 \r\n'; 
   			$msg .=  'First Choice: ' . $choices[1][1] . ' Second Choice: ' . $choices[1][2] . ' Third Choice: ' . $choices[1][3] .'\r\n';
		}
		if( $enabled_sessions[2] == '1' ){
			$msg .=  'and is requesting the following dates session 3 \r\n'; 
   			$msg .=  'First Choice: ' . $choices[2][1] . ' Second Choice: ' . $choices[2][2] . ' Third Choice: ' . $choices[2][3] .'\r\n';
		}
		$subject = "Field Duty Selection for: " . $display_name ;

		$sql = "SELECT wp_users.user_email FROM wp_users INNER JOIN wp_usermeta ON wp_users.ID = wp_usermeta.user_id WHERE wp_usermeta.meta_value like '%operations%' "; 
		$ops_emails = $wpdb->get_results($sql);
		$to = ""; 
		foreach ( $ops_emails as $m ){
			$to .= $m->user_email .', ';
		};
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <webmaster@pgcsoaring.com>' . "\r\n";

 		mail($to,$subject,$msg,$headers);
	}
	
	function display_fd_choices(){
		global $wpdb;
		$user = wp_get_current_user();
		$user_meta = get_userdata( $user->id );
		$display_name = $user_meta->first_name .' '.  $user_meta->last_name;
		$user_roles=$user_meta->roles; 		
		$enabled_sessions = get_option('cloudbase_enabled_sessions'); 
// $enabled_sessions= array( '1', '1', '0');
		$label_text = array('First', 'Second', 'Third');
		$table_calendar =  $wpdb->prefix . 'cloud_base_calendar';
 		$table_field_duty =  $wpdb->prefix . 'cloud_base_field_duty';	
		
		list( $role_id, $role_name) = fd_user_role($user_roles);
		$session_dates = array(); 
		echo('<div style="text-align: center; " id="select_fd_days" > ');
		for ($i = 0; $i <3; $i++ )	{	
 			$sql = "SELECT c.id, c.calendar_date FROM {$table_calendar} c INNER JOIN {$table_field_duty} f ON  c.id=f.calendar_id WHERE f.trade = " . $role_id . " AND  f.member_id IS NULL AND c.session =" . ($i+1) . ' AND c.calendar_date >= CURDATE()';
			$session_dates[$i] = $wpdb->get_results($sql);
		}
		echo (' <div>Member: ' .$display_name . '</div>');
		echo (' <div>Profession: ' .$role_name. '</div>');
		echo (' <div>Select your prefered Duty days:</div><br>');	
		echo ('<form id="selectdutyday"  name="selectdutyday" method="post" >');
		echo ('<input type="hidden" id="member_id" name="member_id" value="'. $user->id . '"</input> ');
		echo ('<input type="hidden" id="member_role" name="member_role" value="'. $role_name . '"</input> ');
		echo('<table>');

   		foreach( $enabled_sessions as $k=>$v){
   			if($v == '1'){
   				$session = $k+1;
   				echo('<tr><td>Session'. $session. ': </td>');   				
   				for($i=1; $i<4; $i++){ 			
   					$choice = 'choice' . $session . '_' . $i ; 	
       				echo ('<td><div 2 id="assignins"> <label for="' . $session . '" style=color:black>' . $label_text[$i-1] . ' Choice: </label>
          				<select  name="' . $choice . '" id="' . $choice . '" form="selectdutyday">  
          				<option value="" selected>Select</option>');      
     	  			foreach($session_dates[$k] as $key){ 	
     	  				echo '<option value=' . $key->id . '>'. $key->calendar_date. '</option>';
           			};   
   				}   	// choice1_2	
   				echo('</select></div></td></tr>');		
   			}
   		}
		echo('</table>');
		    wp_nonce_field( 'submit_field_duty' ); 
			if (in_array("field_manager", $user_roles)){
			
			}  elseif (in_array("field_manager", $user_roles)){
			
			} else {
				$message = "this is for Fild Mananges and Assistant Field Managers."; 
			}
		echo('<input type="submit" value="Submit Request" id="submit" name="submit" >'); 
		echo('</form> ');			
}
	function fd_user_role( $user_roles ){
		global $wpdb;
		$table_trades =  $wpdb->prefix . 'cloud_base_trades';
		$sql = "SELECT * FROM {$table_trades}";
		$trades = $wpdb->get_results($sql);
		
		$role_id =0;
		foreach( $trades as $v){ // find out what trade our user is. 
			if (in_array($v->role, $user_roles)){
				$role_id = $v->id;
				$role_name = $v->trade;
				break;
			}
		}	
		return array($role_id, $role_name);	
	}
?> 


