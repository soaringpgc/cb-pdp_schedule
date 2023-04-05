<br>
<script language="JavaScript">
	var cb_admin_tab = "instruction_types";
</script>


<div style="display:inline-block"  align:left id="instruction_types"  class="editform" >
<?php 			
if( current_user_can( 'manage_options' ) ) {	
  echo ('   <h3>Instruction Types</h3>
  <DIV id="addinstructiontypes">   
   <form id="addinstructiontypes" action="#" >
  <div>    
    	<input type = "hidden"
            id = "id"
            size = "2"
            value = ""
            name = "id"/>
        <div class="hform">   
        <label for="request_type" style=color:black>Instruction: </label>                  		   
        <input type = "text"
            id = "request_type"
            size = "30"
            name = "request_type"/> 
           </div> 
        <div class="hform">         	
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
        <p>Instruction Types</p>
    </div>
    <div class="Heading">
<!-- 
        <div class="Cell0"  >
            <p>ID</p>
        </div>
 -->
        <div class="Cell2" >
            <p>Instruction</p>
        </div>
                    
    </div>
</div>
</div>
    
    <h3>Instructions</h3>
    <p>Here is where you add instruction types such as; Aero tow, Box Wake etc. 
  
    To edit an existing item double click anywhere in that line. The data will be copied 
    to the form at the top of the page and the button will change to "Update" click on
    Update to save the new values.  All previous assignment to that id will reflect the 
    change to the type. 
</p>    

 
