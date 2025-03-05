(function( $ ) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
         * 
         * The file is enqueued from inc/admin/class-admin.php.
	 */
	 $(function(){
  		$("#assign_date0").on("change", function(event){
  		 	 get_fm($(this).find("option:selected").text());  	 
  		});
 		$("#assign_date1").on("change", function(event){
  		 	get_fm($(this).find("option:selected").text());
  		});
  		$("#assign_date2").on("change", function(event){
  		 	get_fm($(this).find("option:selected").text());
  		});
   		$("#field_manager").on("change", function(event){
   			update_manager($(this).find("option:selected").val(), 3); 
  		});
    	$("#assistant_manager").on("change", function(event){
    		update_manager($(this).find("option:selected").val(), 4); 
  		}); 	

  		function update_manager(id, trade_id){  	
  		   	if($("#fd_date").text() ==""){
   				alert("You must select a date first.");
   			} else {
   				var date = $("#fd_date").text();
   				$.ajax({
					type: "PUT",
					url: passed_vars.restURL + 'cloud_base/v1/field_duty',
					async: true,
				    cache: false,
				    timeout: 30000,
					beforeSend: function (xhr){
						xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
					},
					data:{
						date: date,
						trade_id : trade_id,    // 4 for assistant
						member_id: id
					},
					success : function (response){
// 						alert("manager updated");
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
    	    				alert("Status: " + textStatus); 
    	    				alert("Error: " + errorThrown); 
   					} 
				});	    			
   			}  	
  		}
  		function get_fm(date){
   			$("#fd_date").text(date);
//  				$( ".inserted_row" ).remove();
  			          $.ajax({
    	          	         url: passed_vars.restURL + "cloud_base/v1/field_duty?fc=1&start="+ date + "&end=" + date,
    	          	         type: 'GET',
    	          	         cache: false, 
    	          	         headers: {
    	          	             'X-WP-NONCE':passed_vars.nonce,
//  	           	             'Cache-Control': 'no-cache, no-store, must-revalidate', 
//  	   						 'Pragma': 'no-cache', 
//  	   						 'Expires': '0'
    	          	         }, 
    	          	         success: function (response) {
								  if(response.length < 4){
										$("#field_manager").val("-1");
									$("#assistant_manager").val("-1");
								  }
								  response.forEach((obj) =>{
									if( obj.groupId == "Field Manager"){
										if (_.has( obj, "member_id" )){
											$("#field_manager").val(obj.member_id);
										} else {										
											$("#field_manager").val("0");
										}
									}
									if( obj.groupId == "Assistant Manager"){
										if (_.has( obj, "member_id" )){
											$("#assistant_manager").val(obj.member_id);
										} else {
											$("#assistant_manager").val("0");
										}
									}
									});
    	          	         }, 
    	          	         error : function (response) {
//  	             	        failureCallback(response);
								alert(response);
    	          	         }
    	          	     }); 	
  				}
		});	 	 
})( jQuery );