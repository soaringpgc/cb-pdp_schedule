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
 <div style="text-align: center; " id="assign_trade_popup" > 
<?php

		global $wpdb;
 		$table_name =  $wpdb->prefix . 'cloud_base_trades';
  		$sql = "SELECT * FROM {$table_name}";
  		$trades = $wpdb->get_results($sql);

		echo('<div id=editdate>  </div>');
		echo ('<form id="editdutyday" action="#" ><div >');

  		foreach($trades as $trade ){	
  			$tl =	str_replace(' ', '_', $trade->trade);	
			if( current_user_can( 'manage_options') || current_user_can($trade->overrideauthority) ) {	
 				$duty_trade = get_users(['role__in' => [$trade->role] ] );
       	 		echo ('<div id="'.str_replace(' ','',$trade->trade).'_" class="popup-content"> <label for="'.$tl.'" style=color:black>'.$trade->trade.': </label>
       	 		<select class="event_cal_form" name="'.$tl.'" id="'.str_replace(' ', '_', $trade->trade).'" form="editdutyday">
       	 		<option value=NULL>'.$trade->trade.'</option>');       
     				  foreach($duty_trade as $key){ 	
     				  	echo '<option value=' . $key->ID . '>'. $key->first_name . ' '. $key->last_name . '</option>';
       	 		 };             
       	 		echo ( '</select></div> ');
			}				
 		}		
		echo('<input type="button" value="Cancel"  onclick="hideassignpopup()" >'); //
 		echo ('<input type="hidden" id="dutyday" name="dutyday" value="" >');
		echo('</div></form> ');

?>  
</div>
<div id="calendar" "></div>
<script>


</script>
<style>

.fc-event {
    font-size: .85em;} 
/* 
style="transform:scale(.5)
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



 

