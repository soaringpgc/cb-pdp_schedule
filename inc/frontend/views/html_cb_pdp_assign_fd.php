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
/*
*  
*	
*
*/	
function field_duty_assignments(){

	if( isset($_POST['member_id'])){
		return;
	}
	if(current_user_can( 'cb_edit_operations')){
		global $wpdb;
		$user = wp_get_current_user();
		$user_meta = get_userdata( $user->ID );
		$display_name = $user_meta->first_name .' '.  $user_meta->last_name;
		$user_roles=$user_meta->roles; 		
		$enabled_sessions = get_option('cloudbase_enabled_sessions'); 
		
		$label_text = array('1st', '2nd', '3rd');
		$table_calendar =  $wpdb->prefix . 'cloud_base_calendar';
 		$table_field_duty =  $wpdb->prefix . 'cloud_base_field_duty';	
		list( $role_id, $role_name) = fd_user_role($user_roles);
		$session_dates = array(); 
		$session_start_dates = array(); 
		$session_end_dates = array(); 
				
		function cmp($a, $b) {
    		return strcmp($a->last_name, $b->last_name);
		}
		$fieldmanagers = array();
		$args = array('role'=> 'field_manager', 'role__not_in'=>'inactive', 'orderby'=>'user_nice_name', 'order'=> 'ASC');
		$field_manager = get_users( $args );
 		foreach($field_manager as $pilot ){
			$pilot_user = get_userdata( $pilot->id );
			$a = new stdClass();
			$a->name = $pilot_user->last_name .', '. $pilot_user->first_name ;
			$a->last_name = $pilot_user->last_name;
			$a->first_name =  $pilot_user->first_name ;
			$a->id = $pilot->id;
			$fieldmanagers[] = $a;
		}
		usort($fieldmanagers, "cmp"); 		
				
		// select assistant Managers from Wordpress user database where role = 'assistant_field_manager'
		$assistantmanagers = array();
		$args = array('role'=> 'assistant_field_manager', 'role__not_in'=>'inactive', 'orderby'=>'user_nice_name', 'order'=> 'ASC');
		$assistant_manager = get_users( $args );
 		foreach($assistant_manager as $pilot ){
			$pilot_user = get_userdata( $pilot->id );
			$a = new stdClass();
			$a->name = $pilot_user->last_name .', '. $pilot_user->first_name ;
			$a->last_name = $pilot_user->last_name;
			$a->first_name =  $pilot_user->first_name ;
			$a->id = $pilot->id;
			$assistantmanagers[] = $a;
		}
		usort($assistantmanagers, "cmp"); 				

		
		echo('<div style="text-align: center; " id="select_fd_days" > ');

		echo ('<form id="selectdutyday"  name="selectdutyday" method="post" >');
// 		if(current_user_can( 'cb_edit_operations')){
//  			echo('<input type="submit" value="Enable Selected" id="submit" name="submit" >'); 
//  		}
 		echo("<table><tr><th>Session 1</th><th>Session 2</th><th>Session 3</th></tr><tr>");
 		$first_year = date('Y-m-d', strtotime('first day of january this year'));
		for ($i = 0; $i <3; $i++ )	{	

  			$sql = "SELECT c.id, c.calendar_date FROM {$table_calendar} c INNER JOIN {$table_field_duty} f ON  c.id=f.calendar_id WHERE f.trade = 1 AND  f.member_id IS NULL AND c.session =" . ($i+1) . " AND c.calendar_date >'" . $first_year ."'"  ;
			$session_dates[$i] =  $wpdb->get_results($sql);
			$name = "assign_date" . $i;
			echo("<td><select  name=".$name." id=". $name ." form='assigndutyday'>");	
			 	echo(' <option value="0" >Select Date</option>');   					
     				foreach($session_dates[$i] as $key){ 	
     					echo '<option value=' . $key->id . '>'. $key->calendar_date. '</option>';
        			};   
			echo("</select></td>");
		}
		echo("</tr></table>");		
		echo("<div id=fd_date></div>");
		 echo("<table id='managers'><tr>");
         echo(' <td class="label" ><label for="Field_Managers" align="left">Field Manager:</label></td>
                <td class="detail" colspan="2"><select id="field_manager" name="field_manager">	<option value="0" >No Manager Assigned </option>');                
                    foreach($fieldmanagers as $pilot ){
                     		echo(' <option value="'.$pilot->id.'" >'.$pilot->name.'</option>');    
                    } 	
          echo("<option value='-1' >No Manager Required</option>");	          		
          echo ("</select></td></tr>");
       	  echo('<tr> <td class="label" ><label for="Assistant_Managers" align="left">Assistant Manager:</label></td>
                <td class="detail" colspan="2"><select id="assistant_manager" name="assistant_manager">	<option value="0" >No Assistant Assigned</option>');             
                    foreach($assistantmanagers as $pilot ){
                     		echo(' <option value="'.$pilot->id.'" >'.$pilot->name.'</option>');    
                    } 	
          echo("<option value='-1' >No Assistant Required</option>");	
          echo ("</select></td></tr></table>");
	}	
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


