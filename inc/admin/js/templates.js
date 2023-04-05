// Trade Types 
  var tradetypetemplate = _.template(`
   <div class="Cell0"  id = "id" > <%= id %></div>
     <div >
     <label class="Cell2" > <%= trade %></label>
      <div class="hiding" > <%= role %></div>
      <label class="Cell2" > <%= role_label %></label>
      <div class="hiding" > <%= authority %></div>
     <label class="Cell"><%=  authority_label  %> </label>
    <div class="hiding" > <%= overrideauthority %></div>
     <label class="Cell" > <%= overrideauthoritylabel %>  </label>
     <label class="Cell" > <%= sessionmax %>  </label>
     <label class="Cell" > <%= yearmin %>  </label>
     <div class="Cell"> <button class="delete" ">Delete</button></div>
   </div>      
`);
// 

// Instruction Types 
  var instructiontypetemplate = _.template(`
   <div class="hiding"  id = "id" > <%= id %></div>
     <div >
     <label class="Cell2" > <%= request_type %></label>
     <div class="Cell"> <button class="delete" ">Delete</button></div>
   </div>
      
`);

