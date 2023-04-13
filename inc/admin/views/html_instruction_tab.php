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
$lessions  = get_option('cloudbase_leason_slots', false );	
?>
<br>
 <div style="display:inline-block"  align:left>
<h3 align:left>Setup Lessions</h3>

<p style="max-width:600px;">
Setup the start time, lessions/hour, number of hours of instruction and length of instruction here. 
 </p>	
	<form action="admin-post.php" method="post" id="instruction_setup" align ="left">
		<input type="hidden" name=action value="instruction_setup">
    		<?php  			
 
     		wp_nonce_field('schedule_page');  
// 	         		array('start'=>"9:00", 'slots'=>3, 'length'=>"1:00", 'count'=>3)
				$lessions = get_option('cloudbase_leason_slots', array('start'=>9, 'slots'=>3, 'length'=>1, 'count'=>3));		
           		echo ('<hr><dd id="rr-element" class="hform"><label for="start">');
       			echo ('<input type="number" value=' .$lessions['start'] .' id="start" name="start"/>') ; 
     			echo ('	Start Time </label></dd>');
           		echo ('<dd id="rr-element" class="hform"><label for="slots">');
				echo ('<input type="number" value=' .$lessions['slots'] .' id="slots" name="slots"/>');   
     			echo ('	Slots/Hour </label> </dd>');		
           		echo ('<hr><dd id="rr-element" class="hform"><label for="length">');
       			echo ('<input type="number" value=' .$lessions['length'].' id="length" name="length"/>');
     			echo ('	Length of Lession </label></dd>');
           		echo ('<dd id="rr-element" class="hform"><label for="count">');
  				echo ('<input type="number" value=' .$lessions['count'] .' id="count" name="count"/>');  
     			echo ('	Number of Hours avaliable</label></dd><br>');     		          		
				submit_button('Enter', 'primary', 'selection', true);				
 	   	?> 		
		</form>  				
</div>

