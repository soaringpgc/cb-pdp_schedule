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
		 var current_user_role = passed_vars.current_user_role;
// 		 var enabled_sessions = passed_vars.enabled_sessions;
// 		 var trade_authority = passed_vars.trade_authority;
		 var current_user_role_name =  passed_vars.current_user_role_name;
 		 var current_user_can =  passed_vars.current_user_caps;
		 var saturday = nextDay(6);
		 var sunday = nextDay(0);
		 var record_id ='';
		 
 		if(current_user_can['cfi_g'] ){
// Allow CFIs to move to other weekends to schedule students. 
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
// 
        $(document).ready (function() {
	      	var calendarEl = document.getElementById('calendar'); 
        	var calendar = new FullCalendar.Calendar(calendarEl, { // set up calendar
//         		schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives', // demo key
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
  				initialView: 'timeGrid',
//          	  	initialView: 'dayGridWeekend',	// agendaDay
//          	  	views:{
//          	  		dayGridWeekend:{
//          	  			type: 'timeGrid',
//          	  			allDaySlot: false,
//          	  			slotEventOverlap: false,
// //          	  			duration: { days: 2 },
//  				 		eventShortHeight: "30",
//  				 		eventMaxStack: "1",	
//          	  			}
//          	  		},
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
 				   start:  saturday,
 				   end: sunday
 				 },
 	   			 slotMinTime: "08:00:00",
				 slotMaxTime: "14:00:00",	
				 eventClick: function(info) {
					var instructiondate= moment(info.event.start).format('YYYY-MM-DD'); 
					// member wishes to cancel 
					if( current_user_id ==  info.event.extendedProps.member_id){  // Student clicks on event., possible to cancel. 
// 					  	jQuery("#pop_up_dialog").html('Do you wish to cancel instructionon on ' + instructiondate + '"? ' );	
// 						$('#pop_up_dialog').removeClass('popup-content');	
  	 		  			jQuery("#cfig_accept").html('Date: ' + instructiondate + '<br>Instruction: ' + info.event.extendedProps.request_type + '<br>Member weight: ' + 
  	 		  			    info.event.extendedProps.member_weight + '<br>Comment: ' +  info.event.extendedProps.comment + '<br>Alt Inst: ' +  
  	 		  			    info.event.extendedProps.alt_ins );	
						 pop_up_dialog(info.event.id, calendar, 'Do you wish to cancel instructionon?', "No, keep appointment", "Yes, cancel Instruction", "DELETE", current_user_id);				 	
  	 		  		} else if( current_user_id ==  info.event.extendedProps.cfiga){	  // assigned CFI clicks, possible cancel 
   	 		  		console.log( info.event.extendedProps);
  	 		  			jQuery("#cfig_accept").html( info.event.title + '<br>Date: ' + instructiondate +'<br>Instruction: ' + 
  	 		  				info.event.extendedProps.request_type + '<br>Member weight: ' +  info.event.extendedProps.member_weight + 
  						 	'<br>Comment: ' +  info.event.extendedProps.comment + '<br>Alt Inst: ' +  info.event.extendedProps.alt_ins );	
//   	 		  			jQuery("#pop_up_dialog").html('Instruction: ' + info.event.extendedProps.request_type + '<br>Member weight: ' +  info.event.extendedProps.member_weight + 
//   					 '<br>Comment: ' +  info.event.extendedProps.comment + '<br>Alt Inst: ' +  info.event.extendedProps.alt_ins );	

   					    pop_up_dialog( info.event.id, calendar, 'Instructor Cancel', "No, keep appointment", "Yes, cancel Instruction", "DELETE", current_user_id);													  	 		  									
 					} else if((current_user_id == info.event.extendedProps.cfig1) ||(current_user_id == info.event.extendedProps.cfig2)){										
  						// Instructor accepts. 					
 	 		  			 jQuery("#cfig_accept").html('Date: ' + instructiondate + '<br>Instruction: ' + info.event.extendedProps.request_type + 
 	 		  					'<br>Member weight: ' +  info.event.extendedProps.member_weight + '<br>Comment: ' +  info.event.extendedProps.comment + 
 	 		  					'<br>Alt Inst: ' +  info.event.extendedProps.alt_ins );	
// 								$('#cfig_accept').removeClass('popup-content');	

						 pop_up_dialog( info.event.id, calendar, 'Instructor Accept', "Return", "Yes, schedule", "PUT", current_user_id);				     		
 					} else if( current_user_role == 'schedule_assist' ){
 						console.log( info.event.extendedProps);
 				 		jQuery("#assigned_instructor").prepend('<div id="event_info">Instruction: ' + info.event.extendedProps.request_type + '<br>Member weight: ' +  info.event.extendedProps.member_weight + 
  					 			'<br>Comment: ' +  info.event.extendedProps.comment + '<br>Alt Inst: ' +  info.event.extendedProps.cfig2 +'</div>');	
//  					alert('schedule assist');
 						record_id = info.event.id;
  						$("#assigned_instructor").show(); 	
					}	 									
			  }
		  });
		  calendar.render();    		  
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
							hideassigninstuctor( );		
							calendar.refetchEvents();		
						},
						error : function(response){
// 						console.log(response);
							hideassigninstuctor( );		
							calendar.refetchEvents();
						}
					});	 		 
  	 			} 
     		});     
 		  });
		 		
  	      $('#cancel').on('click', function(){ 
//     	restore_page_settings();
    	     jQuery("#assigned_instructor").empty();   
          }); 
	 }) // $(function) close	 
// 	 $( window ).load(function() {
// 
// 	 });
	 	 
})( jQuery );

function hideinstructionrequest( ) {
	jQuery('#editinstruction').addClass('popup-content'); 		
}
function hideassigninstuctor( ) {
		jQuery("#assigned_instructor").hide();  
		jQuery("#event_info").remove();   	
}
function dumpweekendschedule(){
	var now = new Date();   
	var saturday = getNextDayOfWeek(now, 5);
	var sunday = getNextDayOfWeek(now, 6);
	jQuery.ajax({
        url: passed_vars.restURL + "cloud_base/v1/instruction?&start="+ saturday.toISOString().substr(0,10) + "&end=" + sunday.toISOString().substr(0,10),
        type: 'GET',
        cache: false, 
        success: function (response) {
        	response.sort(function(a,b){ return new Date(a.start) - new Date(b.start)});  // sort by date time 
        	var str = '<table width="60%"  border="1"><tr><th width="20%x">Date/Time</th><th width="30%x">Student/Instructor</th><th width="25%">Instruction type</th><th width="25%">Comment</th><tr>';
           		response.forEach((item) => {
          			str += '<tr><td>' + item.start + '</td><td>' + item.title + '</td><td> ' + item.request_type +'</td><td> ' + item.comment + '</td></tr>' ;	  
          		});
       str += '</table><br><p>The time slot shown for your instruction is not necessarly the time of your lesson. The Field Manager and instructors will determine flying order.</p>';        
//          console.log(response);
          		print_schedule(str);
//           		jQuery("#dumpschedule").html(str );	  
        }
    });		
}
function getNextDayOfWeek(date, dayOfWeek) {
    // Code to check that date and dayOfWeek are valid left as an exercise ;)

    var resultDate = new Date(date.getTime());
    resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getDay()) % 7);
    return resultDate;
}

function nextDay(x){
	var now = new Date();    
	now.setDate(now.getDate() + (x+(7-now.getDay())) % 7);
	return now;
}

function print_schedule(str){
	var w = window.open();
// 	var headers = jQuery("#headers").html();
// 	var field1 = jQuery("#field1").html();
// 	var field2 = jQuery("#field2").html();
	
	var html = "<!DOCTYP HTM>";
	html += '<html lang="en-us">';
	html += '<head><style>Student Schedule</style>';
	html += "<body>";
	html += str;
	html += "</body>";
	w.document.write(html);
//  	w.window.print();
// 	w.document.close(); 
}

function pop_up_dialog(event_id, calendar, title, return_text, cancel_text, ajax_type, cfig_id){
	 jQuery('#cfig_accept').removeClass('popup-content');	
	 jQuery('#cfig_accept').dialog({	 
	   	 autoOpen: true,
	   	 title: title,
	     buttons: [
	     	{
	     		text : return_text,
	     		click:  function() {
	         			jQuery(this).dialog("close");
	      		}	
	      	},
	      	{
	     		text : cancel_text,
	     		click:  function() {
	     			jQuery.ajax({
						type: ajax_type,
						url: passed_vars.restURL + 'cloud_base/v1/instruction',
						async: true,
					   cache: false,
					   timeout: 30000,
						beforeSend: function (xhr){
							xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
						},
						data:{
							id: event_id,
							cfig : cfig_id
						},
						dataType: 'text json',
						success : function (response){
 							calendar.refetchEvents();		
						},
						error: function(xhr){
  							calendar.refetchEvents();
// 							alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);    							
						}								
					});	
	         		jQuery(this).dialog("close");
	      		}	
	      	},
	    ],
	    width: "400px"});	
	    jQuery('#cfig_accept').addClass('popup-content');				
}									





	

