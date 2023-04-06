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
		 var current_user_id = passed_vars.current_user_id;
// 		 var current_user_caps = passed_vars.current_user_caps;
// 		 console.log(current_user_caps);
		 var current_user_role = passed_vars.current_user_role;
		 var enabled_sessions = passed_vars.enabled_sessions;
		 var trade_authority = passed_vars.trade_authority;
		 var current_user_role_name =  passed_vars.current_user_role_name;
		 var current_user_can =  passed_vars.user_can;
		 var saturday = nextDay(6);
		 var sunday = nextDay(0);
		 var record_id ='';

  		 if(current_user_role == 'cfi_g' ){
  			var header = {
  				left: 'cb_prev',
  				center: 'title',
  				right: 'cb_next',
  					}; 
		  } else {
			 	var header = {
  				left: '',
  				center: 'title',
  				right: '',
  					}; 
		  }

        $(document).ready (function() {
	      	var calendarEl = document.getElementById('calendar');
        	var calendar = new FullCalendar.Calendar(calendarEl, {
        		customButtons :{
        			cb_next :{ 
        				text: 'Next',
        				click: function(){       					
        					calendar.changeView('timeGrid', {
						      start: saturday.setDate(saturday.getDate() + 7 ),
						      end: sunday.setDate(sunday.getDate() + 7 )
						    }) ;
						    calendar.refetchEvents();              				
        				}
        			},
					cb_prev :{ 
        				text: 'Prev',
        				click: function(){       					
        					calendar.changeView('timeGrid', {
						      start: saturday.setDate(saturday.getDate() - 7 ),
						      end: sunday.setDate(sunday.getDate() - 7 )
						    });
						    calendar.refetchEvents();       				
        				}
        			},
        			position:{
        				my: "left",
        				
        			}
        		},        	
 				headerToolbar: header, 
 				selectable: true,
 				select: this.select, 
         	  	initialView: 'timeGrid',	// agendaDay
				dateClick: function(info){
					$('#editinstruction').removeClass('popup-content');
// 					$('#calendar').addClass('popup-content');
					$('#request_date').val(info.dateStr);
					$('#display_date').text(info.dateStr.substring(0,10));
					},
				eventTextColor: 'black',
				events:{
					url: passed_vars.restURL + 'cloud_base/v1/instruction',
				  	method: 'GET',				
// 				  	extraParams:{ fc: '1' }  // tell rest endpoint we want FullCallendar data format. 
				},
				visibleRange: {
 				   start: saturday,
 				   end: sunday
 				 }, 
 				 slotMinTime: "08:00:00",
 				 slotMaxTime: "12:00:00",		
				 eventClick: function(info) {
//  			console.log(info.event.id);
					var instructiondate= moment(info.event.start).format('YYYY-MM-DD'); 
					// member wishes to cancel 
					if( current_user_id ==  info.event.extendedProps.member_id){
					 	if(confirm("Do you wish to cancel instructionon? on " + instructiondate + "? " )) {								
							$.ajax({
								type: "DELETE",
								url: passed_vars.restURL + 'cloud_base/v1/instruction',
								async: true,
							   cache: false,
							   timeout: 30000,
								beforeSend: function (xhr){
									xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
								},
								data:{
									id: info.event.id,
								},
								success : function (response){
									calendar.refetchEvents();
 									hideinstructionrequest();				
								},
								error: function(xhr){
									calendar.refetchEvents();
        							alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    							}								
 							});	 	
  	 		  			} 						
 				} else if((current_user_id == info.event.extendedProps.cfig1) ||(current_user_id == info.event.extendedProps.cfig2) ){										
					if( current_user_id ==  info.event.extendedProps.cfiga){
						// Instructor wishes to cancel
					 	if(confirm("Do you wish to cancel instructionon? on " + instructiondate + "? " )) {								
							$.ajax({
								type: "DELETE",
								url: passed_vars.restURL + 'cloud_base/v1/instruction',
								async: true,
							   cache: false,
							   timeout: 30000,
								beforeSend: function (xhr){
									xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
								},
								data:{
									id: info.event.id,
								},
								success : function (response){
									calendar.refetchEvents();
 									hideinstructionrequest();				
								},
								error: function(xhr){
        							calendar.refetchEvents();
        							alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);    							
    							}								
 							});	 	
  	 		  			} 						
 				} else {	
 					// Instructor accetps. 				
 					$('#cfig_accept').dialog({									
 					    autoOpen: true,
 					    buttons: {
 					        Cancel: function() {
 					            $(this).dialog("close");
 					        },
 					        Accept: function() {
 								$.ajax({
 									type: "PUT",
 									url: passed_vars.restURL + 'cloud_base/v1/instruction',
 									async: true,
 								   cache: false,
 								   timeout: 30000,
 									beforeSend: function (xhr){
 										xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
 									},
 									data:{
 										id: info.event.id,
 										cfig : current_user_id
 									},
 									success : function (response){
 										calendar.refetchEvents();
  										hideinstructionrequest();				
 									},
 									error: function(xhr){
         								alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
     								}									
  								});	 
 					            $(this).dialog("close");	
   	 		  				}												
 					    },
 					    width: "400px"});							    
					  }  		
 				} else if( current_user_role == 'schedule_assist' ){
 					record_id = info.event.id;
//  				alert("Hi schedule assistant");
 					$("#assigned_instructor").show(); 
// 					$("#assigned_instructor").removeClass('popup-content'); 	
				}	
			  }
		  });
		  calendar.render();    		  
		    $('.assigned_instructor').change(function() {
				var cfig_id =   $("#assigned_cfig").val();
				var cfig_name = $("#assigned_cfig").find('option:selected').text();		
  				if(confirm("Are you sure you want to assign " + member_name + " as " + instructor + " on " + startdate + "? " )) {					
					$.ajax({
						type: "PUT",
						url: passed_vars.restURL + 'cloud_base/v1/instruction',
						async: true,
					     cache: false,
					     timeout: 30000,
						beforeSend: function (xhr){ 
							xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
						},
						data:{
							id: info.event.id,
							cfig : cfig_id							
						},
						success : function (response){
							calendar.refetchEvents();
							hideassigninstuctor( );				
						},
						error : function(response){
							alert(response);
							hideassigninstuctor( );		
						}
					});	 		 
  	 		   }     		   	
     		}); 
     		
     		$('.instructor_select').change(function() {
		 		
				var member_id =   $("#assigned_cfig").val();
				var member_name = $("#assigned_cfig").find('option:selected').text();			
		 	 				
  				if(confirm("Are you sure you want to assign " + member_name + "? " )) {					
					$.ajax({
						type: "PUT",
						url: passed_vars.restURL + 'cloud_base/v1/instruction',
						async: true,
					     cache: false,
					     timeout: 30000,
						beforeSend: function (xhr){ 
							xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
						},
						data:{
							id: record_id,					
							cfig: member_id
						},
						success : function (response){
							calendar.refetchEvents();
							hideassigninstuctor( );		
							calendar.refetchEvents();		
						},
						error : function(response){
						console.log(response);
// 							alert(response);
							hideassigninstuctor( );		
							calendar.refetchEvents();
						}
					});	 		 
  	 		   }     		   	
     		});   
		  
 		});
		 		
  	$('#cancel').on('click', function(){ 
     alert("button is clicked");
    restore_page_settings();
    });
 
	 }) // $(function) close	 
	 $( window ).load(function() {

	 });
	 function nextDay(x){
    	var now = new Date();    
    	now.setDate(now.getDate() + (x+(7-now.getDay())) % 7);
    	return now;
		}
	 	 
})( jQuery );

function hideinstructionrequest( ) {
	jQuery('#editinstruction').addClass('popup-content'); 	
// 	jQuery('#calendar').removeClass('popup-content');
	
}
function hideassigninstuctor( ) {
// 	var trade_authority = passed_vars.trade_authority;
// 		for(let i=0; i < trade_authority.length; ++i){
			jQuery("#assigned_instructor").hide(); 
//  			jQuery("#assigned_instructor").addClass('popup-content'); 
// 		}				
//  	jQuery("#popup-content").hide();  	
}


	

