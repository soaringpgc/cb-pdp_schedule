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
	 var current_user_role = passed_vars.current_user_role;
	 var enabled_sessions = passed_vars.enabled_sessions;
	 var trade_authority = passed_vars.trade_authority;
	 var current_user_role_name =  passed_vars.current_user_role_name;
	 var current_user_can =  passed_vars.user_can;
	 var saturday = nextDay(6);
	 var sunday = new Date();
	 sunday.setDate(saturday.getDate() + 1); 

        $(document).ready (function() {
	      	var calendarEl = document.getElementById('calendar');
        	var calendar = new FullCalendar.Calendar(calendarEl, {
 				headerToolbar: {
//        	  		plugins: [ dayGrid, timeGrid ],
  					left: '',
  					center: 'title',
  					right: '',
//  						ignoreTimezone: false
  					}, 
 					selectable: true,
 					select: this.select, 
         	  		initialView: 'timeGrid',	// agendaDay
					dateClick: function(info){
					$('#editinstruction').removeClass('popup-content');
					$('#calendar').addClass('popup-content');
					$('#requested_date').val(info.dateStr);
					},
					events:{
					  	url: '/wordpress/wp-json/cloud_base/v1/instruction',
					  	method: 'GET',				
					  	extraParams:{ fc: '1' }  // tell rest endpoint we want FullCallendar data format. 
					},

					
// 					$('#cfig_accept').dialog({
// 
//     autoOpen: true,
//     buttons: {
// 
//         Yes: function() {
// 
//             alert("Yes!");
//             $(this).dialog("close");
//         },
//         No: function() {
// 
//             alert("No!");
//             $(this).dialog("close");
// 
//         },
//         Maybe: function() {
// 
//             alert("Maybe!");
//             $(this).dialog("close");
//         }
// 
//     },
//     width: "400px"});
// 
// 					
// 						alert('clicked ' + info.dateStr);
// 						
						

// 					select: function(info){
// 						alert('selected ' + info.startStr + ' to ' + info.endStr);
// 						console.log(info);
// 					},	
// 					firstDat: 1,
					visibleRange: {
 					   start: saturday,
 					   end: sunday
 					 }, 
 					 slotMinTime: "08:00:00",
 					 slotMaxTime: "12:00:00",			
			  });
			calendar.render();    
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
	jQuery('#calendar').removeClass('popup-content');
	
}

	

