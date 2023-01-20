<br>
<script language="JavaScript">
	var cb_admin_tab = "trade_types";
</script>
<div style="display:inline-block"  align:left id="trade_types"  class="trade_type editform" >
<?php 			
if( current_user_can( 'manage_options' ) ) {	
  echo ('   
   <h3>Trade Types</h3><DIV>   
   <form id="addtrade_type" action="#" >
    	<div>
    	<input type = "hidden"
            id = "id"
            size = "2"
            value = ""
            name = "id"/>
        <input type = "text"
            id = "title"
            size = "8"
            title = "trade ." 
            name = "title"/> 
        <input type = "hidden"
            id = "active"
            size = "2"
            value = ""
            name = "active"/> 
        <button id="add" class="view">Add</button>
        <button id="update" class="cb_edit">Update</button>
       </div>
    </form></DIV>');
}    
?>    

<div  class="Table">
    <div class="Title">
        <p>Trade Types</p>
    </div>
    <div class="Heading">
        <div class="Cell"  >
            <p>ID</p>
        </div>
        <div class="Cell"  style="width: 6.1em">
            <p>Trade</p>
        </div>

    </div>
</div>

</div>

    
    <h4>Instructions</h4>
<p>      
    Here is where you add trades such as; Tow Pilot, Instructor, Field Manager for 
    Field Duty.
</p><p>      
    You can not delete a trade type if it is in use. 
</p><p>  
    To edit an existing item double click anywhere in that line. The data will be copied 
    to the form at the top of the page and the button will change to "Update" click on
    Update to save the new values.  All previous assignment to that id will reflect the 
    change to the type. 
</p>    

 
