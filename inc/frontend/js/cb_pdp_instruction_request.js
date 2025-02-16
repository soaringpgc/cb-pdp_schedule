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
		 var current_user_roles = passed_vars.current_user_role_array;
//  		 var result = current_user_roles.findIndex(ele => ele === "subscriber");
 		 var current_user_can =  passed_vars.current_user_caps;
	 	 var saturday =nextDay(6, new Date()); 
	 	 var sunday = nextDay(0, new Date());

 		 if (sessionStorage['workingDate']){
 		 	var sunday = new Date(sessionStorage.getItem('workingDate'+'T00:00:00'));
 		 }    		 
    	saturday = subtractDays(sunday, 1)	 
//      sessionStorage.clear(); 	 	 		 
		 var record_id ='';
// console.log(passed_vars)	;	 
 		if(current_user_can['cfi_g'] || current_user_can['schedule_assist']){
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
        $(document).ready (function() {
	      	var calendarEl = document.getElementById('calendar'); 
        	var calendar = new FullCalendar.Calendar(calendarEl, { // set up calendar
//         		schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives', // demo key
        		customButtons :{
        			cb_next :{ 
        				text: 'Next',
        				click: function(){          
        			 		sunday = getNextDayOfWeek(new Date( sunday.setDate(sunday.getDate()+1)), 0 );
        			 		saturday.setDate(saturday.getDate()+7); 
        					sessionStorage.setItem('workingDate', sunday);        			            				
							calendar.changeView('timeGrid', {
						      start: saturday,
						      end: sunday
						    }) ;
						    calendar.refetchEvents();              				
        				}
        			},
					cb_prev :{ 
        				text: 'Prev',
        				click: function(){  
        			 		sunday = getNextDayOfWeek(new Date( sunday.setDate(sunday.getDate()-7)), 0 );
        			 		saturday.setDate(saturday.getDate()-7); 
        					sessionStorage.setItem('workingDate', sunday);
        					saturday = new Date(saturday.setDate(sunday.getDate() -1));     					
        					calendar.changeView('timeGrid', {
						      start: saturday,
						      end: sunday
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
					$("html, body").animate({ scrollTop: 20 }, "slow");
//  					$('#calendar').addClass('popup-content');
					$('#request_date').val(info.dateStr);  // set the date field 
					$('#display_date').text(info.dateStr.substring(0,10));
					},
				eventTextColor: 'black',
				events:{
					url: passed_vars.restURL + 'cloud_base/v1/instruction',
				  	method: 'GET',				
				  	extraParams:  function(){ 
				  		return {
					        cachebuster: new Date().valueOf()
					    };
				  	 },  
					 failure: function(){
						alert('there was an error while fetching events!');
					 }
				},
				visibleRange: {
 				   start:  saturday,
 				   end: sunday
 				 },
 // I think I should be passin men max from options?? dsj  
 	   			 slotMinTime: "08:00:00",
				 slotMaxTime: "14:00:00",	
				 eventClick: function(info) {
					// member wishes to cancel 
					if( current_user_id ==  info.event.extendedProps.member_id){  // Student clicks on event., possible to cancel. 
						 pop_up_dialog(info, calendar, 'Do you wish to cancel instruction?', "No, keep appointment", "Yes, cancel Instruction", "DELETE", current_user_id);				 	
  	 		  		} else if( current_user_id ==  info.event.extendedProps.cfiga){	  // assigned CFI clicks, possible cancel 
   					    pop_up_dialog( info, calendar, 'Instructor Cancel', "No, keep appointment", "Yes, cancel Instruction", "DELETE", current_user_id);													  	 		  									
 					} else if(((current_user_id == info.event.extendedProps.cfig1) ||(current_user_id == info.event.extendedProps.cfig2)) && (info.event.extendedProps.cfiga === null ) ){										
  						// cfi1 or cfi2 accepts. 					
						 pop_up_dialog( info, calendar, 'Instructor Accept', "Return", "Yes, schedule", "PUT", current_user_id);				     		
//  					} else if(((current_user_id == info.event.extendedProps.cfig2) ||  (current_user_id == info.event.extendedProps.cfig1)) && (info.event.extendedProps.cfiga !== null )){										
//						// Other instructor takes over from accepted cfi  			
//  					} else if(current_user_role == 'cfi_g'){	
 					} else if( current_user_roles.findIndex(ele => ele === "cfi_g") >= 0){	 														
  						// allow any instructor to take over appointment. 				
						 pop_up_dialog( info, calendar, 'Instructor OverRide', "Return", "Yes, OverRide Instructor", "PUT", current_user_id);				     		
 					} else if( current_user_roles.findIndex(ele => ele === "schedule_assist") >= 0){	 	
//  					} else if( current_user_role == 'schedule_assist' ){
 						// If the user is schedual assistant administrator. 
//  						console.log( info.event.extendedProps);
 				 		jQuery("#assigned_instructor").prepend('<div id="event_info">Instruction: ' + info.event.extendedProps.request_type + '<br>Member weight: ' +  info.event.extendedProps.member_weight + 
  					 			'<br>Comment: ' +  info.event.extendedProps.comment + '<br>Alt Inst: ' +  info.event.extendedProps.cfig2 +'</div>');	
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
  	      $('#showInstruction').on('click', function(){ 
    	     jQuery('#instructions').removeClass('popup-content');
          }); 		 		
  	      $('#cancel').on('click', function(){ 
//     	restore_page_settings();
    	     jQuery("#assigned_instructor").empty();   
          }); 
	 }) // $(function) close	 
	 $(".confirmed_check").click( function(){
	 	if( $('#cfig1').val() == -1){			
	 		alert(" You must select an instructor first. ");
	 		$('#confirmed').prop("checked", false);
	 		}
	 }); 
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
	var saturday = getNextDayOfWeek(now, 6);
	var sunday = getNextDayOfWeek(now, 0);

	jQuery.ajax({
        url: passed_vars.restURL + "cloud_base/v1/instruction?&start="+ saturday.toISOString().substr(0,10) + "&end=" + sunday.toISOString().substr(0,10),
        type: 'GET',
        cache: false, 
        success: function (response) {
        	response.sort(function(a,b){ return new Date(a.start) - new Date(b.start)});  // sort by date time 
        	var str = '<table width="60%"  border="1"><tr><th width="10%">Date/Time</th><th width="20%">Student</th><th width="20%">Instructor</th><th width="5%">Conf</th><th width="20%">Alt Instructor</th><th width="15%">Instruction type</th><th width="10%">Comment</th><tr>';
           		response.forEach((item) => {
           		console.log(item.istatus); 
          			str += '<tr><td>' + item.start + '</td><td>' + item.student + '</td><td>' + (item.cfia_name != "none" ?  item.cfia_name : item.cfi1_name   ) + '</td><td style="text-align:center;">' + (item.istatus > 1 ?  "Y" : "N"  ) + '</td><td>' +
          			item.cfi2_name + '</td><td> ' + item.request_type +'</td><td> ' + item.comment + '</td></tr>' ;	  
          		});
       str += '</table><br><p>The time slot shown for your instruction is not necessarly the time of your lesson. The Field Manager and instructors will determine flying order.</p>';        	
		var html = "<!DOCTYP HTM>";
		html += '<html lang="en-us">';
		html += '<head><style>Student Schedule</style>';
		html += "<body>";
		html += str;
		html += "</body>";
		var w =window.open('about:blank' );
		w.document.write(html);
        }
    });		
}

function getNextDayOfWeek(date, dayOfWeek) {
    // Code to check that date and dayOfWeek are valid left as an exercise ;)
    var resultDate = new Date(date.getTime());
    resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getDay()) % 7);
    return resultDate;
}

function nextDay(x, workingDate){    
	workingDate.setDate(workingDate.getDate() + (x+(7-workingDate.getDay())) % 7);
	return workingDate;
}
function subtractDays(date, days) {  
  const dateCopy = new Date(date);
  dateCopy.setDate(dateCopy.getDate() - days);
  return dateCopy;   
}

function pop_up_dialog(info, calendar, title, return_text, cancel_text, ajax_type, cfig_id){
		//  ntfs: using the cfix(info.event.extendedProps.cfig) id to look up the cfi name from the drop down select menu list(info.event.extendedProps.cfig1 +']').text()). 
	var inst_string = (info.event.extendedProps.cfiga === null) ?  '<br>Requested CFI-G: ' + jQuery('#cfig1 option[value=' + info.event.extendedProps.cfig1 +']').text() : '<br>Assigned CFI-G: ' + jQuery('#cfig1 option[value=' + info.event.extendedProps.cfiga +']').text();

	 jQuery('#dialogText').html('Date: ' + moment(info.event.start).format('YYYY-MM-DD') + 
	 	inst_string +	 
	 	'<br>Instruction: ' + info.event.extendedProps.request_type + 
	 	'<br>Member weight: ' + info.event.extendedProps.member_weight + 
	 	'<br>Comment: ' +  info.event.extendedProps.comment + 
	 	'<br>Alt Inst: ' + jQuery('#cfig1 option[value=' + info.event.extendedProps.cfig2 +']').text() );
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
							id: info.event.id,
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
}	
function contains(a, obj) {
    for (var i = 0; i < a.length; i++) {
        if (a[i] === obj) {
            return true;
        }
    }
    return false;
}								





	

