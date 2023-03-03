<br>
<script language="JavaScript">
	var cb_admin_tab = "trade_types";
</script>


<div style="display:inline-block"  align:left id="trade_types"  class="trade_type editform" >
<?php 			
if( current_user_can( 'manage_options' ) ) {	
  echo ('   <h3>Trade Types</h3>
  <DIV id="addtradetypes">   
   <form id="addtradetypes" action="#" >
  <div>    
    	<input type = "hidden"
            id = "id"
            size = "2"
            value = ""
            name = "id"/>
        <div class="hform">   
        <label for="trade" style=color:black>Trade: </label>                  		   
        <input type = "text"
            id = "trade"
            size = "20"
            title = "trade ." 
            name = "trade"/> 
           </div> 
		<div class="hform">	
        <label for="role" style=color:black>role: </label>      		
		<select name ="role" id="role" name="role" >');
			
		wp_dropdown_roles( $selected = 'inactive');	
		
		echo ('</select>  </div>

        <div class="hform">   
        <label for="authority" style=color:black>Authority: </label>          		
		<select name ="authority" id="authority" name="authority" >
			<option value="0"> Select Authority </option>
			');
			// authority array is stored in WP options, It is created/updated on activation 
			$value_label_authority = get_option('cloud_base_authoritys');

			foreach ($value_label_authority  as $key => $authority ){
				echo ('<option value="' . $key . '">' . $authority . '</option>');
			}	
		echo ('</select>         </div>
        <div class="hform"> 
        <label for="overrideauthority" style=color:black>Over Ride Authority: </label>       
		<select name ="overrideauthority" id="overrideauthority"  >
			<option value="0"> Select Over Ride Authority </option>
			');
			// authority array is stored in WP options, It is created/updated on activation 
			$value_label_authority = get_option('cloud_base_authoritys');

			foreach ($value_label_authority  as $key => $authority ){
				echo ('<option value="' . $key . '">' . $authority . '</option>');
			}	
		echo ('</select>        </div>
        <div class="hform">   
        <label for="sessionmax" style=color:black>Max per Session: </label>    
		 <input type = "number"
            id = "sessionmax"
            title = "sessionmax ." 
            name = "sessionmax"
            min = "0" max = "15" /> 
               </div>
        <div class="hform">
        <label for="yearmin" style=color:black>Min per Year: </label>            
		<input type = "number"
            id = "yearmin"
            title = "yearmin ." 
            name = "yearmin"
            min = "0" max = "25"/> 	
        </div>
        <div class="hform">         	
        <input type = "hidden"
            id = "active"
            size = "2"
            value = ""
            name = "active"/> 
        <label for="add" style=color:black>Add</label>      
        <button id="add" class="view">Submit</button>
        <button id="update" class="cb_edit">Update</button>
        </div>
       
    </form></div>');
}    
?>   
</div>
<div  class="Table">
    <div class="Title">
        <p>Trade Types</p>
    </div>
    <div class="Heading">
        <div class="Cell0"  >
            <p>ID</p>
        </div>
        <div class="Cell2" >
            <p>Trade</p>
        </div>
        <div class="Cell2" >
            <p>Role</p>
        </div>
        <div class="Cell" >
            <p>Authority</p>
        </div>
        <div class="Cell" >
            <p>Over Ride Authority</p>
        </div> 
        <div class="Cell"  >
            <p>Max per Session</p>
        </div>  
        <div class="Cell"  >
            <p>Min per Year</p>
        </div>                       
    </div>
</div>
</div>
    
    <h3>Instructions</h3>
    <p>Here is where you add trades such as; Tow Pilot, Instructor, Field Manager for 
    Field Duty.     
    You can not delete a trade type if it is in use.  
    To edit an existing item double click anywhere in that line. The data will be copied 
    to the form at the top of the page and the button will change to "Update" click on
    Update to save the new values.  All previous assignment to that id will reflect the 
    change to the type. 
</p>    

 
