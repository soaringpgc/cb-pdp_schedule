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
	 var overide = [];
 console.log(current_user_role)	; 
 
 	 trade_authority.forEach(function(trade){
	 	overide.push(trade['overrideauthority']);
	 });
	overide.push('administrator');
	 
 console.log(overide)	; 
	 var startdate ='';
//	 var cal_date_id =""; 
  	 var localdata ="";
       $(document).ready (function() {
        	var calendarEl = document.getElementById('calendar');
        	var calendar = new FullCalendar.Calendar(calendarEl, {
        		headerToolbar: {
//      	  		plugins: [ 'dayGrid', 'timeGrid' ],
 					left: 'prev, next, today',
 					center: 'title',
 					right: 'dayGridMonth,timeGridWeek, timeGridDay,listMonth',
 						ignoreTimezone: false
 					},
 					selectable: true,
 //					editable: true,
 					select: this.select, 			       
        	  		initialView: 'dayGridMonth',
        	  		dateClick: function() {
					    alert('a day has been clicked!');
					  },
					events:{
					  	url: '/wordpress/wp-json/cloud_base/v1/field_duty',
					  	method: 'GET',
					  	extraParams:{ fc: '1'}  // tell rest endpoint we want FullCallendar data format. 
					},
	
				eventClick: function(info) {
				hideassignpopup();		
//     		   	console.log(info);
				if(overide.includes(current_user_role)){
					startdate= moment(info.event.start).format('YYYY-MM-DD'); 
     		   		switch(info.event.groupId){
     		   			case 'Tow Pilot': 
     		   				$("#assigntp").removeClass('popup-content'); 
     		   				break;
     		   			case 'Instructor': 
     		   				$("#assignins").removeClass('popup-content'); 
     		   				break;
     		   			case 'Field Manager': 
     		   				$("#assignfm").removeClass('popup-content'); 
     		   				break;
     		   			case 'Assistant Field Manager': 
     		   				$("#assignafm").removeClass('popup-content'); 
     		   				break;     		   		
     		   		}					
					$('#editdate').text(startdate);
	  				$("#assignself").removeClass('popup-overlay'); 
	  				} else { 				
 						switch(current_user_role){
							case 'tow_pilot':
								var trade_id = 1;
								var trade_name = "Tow Pilot";
								break;
							case 'cfi_g':
								var trade_id = 2;
								var trade_name = "Instructor";
								break;
							case 'field_manager':
								var trade_id = 3;
								var trade_name = "Field Manager";
							    break;
							case 'assistant_field_manager':
								var trade_id = 4;
								var trade_name = "Assistant FM";
						}	
  				if(confirm("You are signing up for " + trade_name + " on " + startdate + "? " )) {
					
					$.ajax({
						type: "PUT",
						url: passed_vars.restURL + 'cloud_base/v1/field_duty',
						async: true,
					     cache: false,
					     timeout: 30000,
						beforeSend: function (xhr){
							xhr.setRequestHeader('X-WP-Nounce',  passed_vars.nonce );
						},
						data:{
							date: startdate,
							trade_id : trade_id,
							member_id: member_id
						},
						success : function (response){
							calendar.refetchEvents();
							hideassignpopup();				
						}	
					});	 		 
  	 		   } 						
						  				
	  				
	  				}
//	  				cal_date_id = info.event.id;	
   				},				
        	});
     		calendar.render();
              
     		$('.event_cal_form').change(function() {
		
//      		   	console.log(startdate);
//      		   	console.log(cal_date_id);
 				var trade = event.target.id;
 				switch(trade){
					case 'towpilot':
						var trade_id = 1;
						var trade_name = "Tow Pilot";
						break;
					case 'instructor':
						var trade_id = 2;
						var trade_name = "Instructor";
						break;
					case 'field_manager':
						var trade_id = 3;
						var trade_name = "Field Manager";
					    break;
					case 'assistant_field_manager':
						var trade_id = 4;
						var trade_name = "Assistant FM";
				}
				var member_id = $("#"+trade).val();
				var member_name = $("#"+trade).find('option:selected').text();
  				if(confirm("Are you sure you want to assign " + member_name + " as " + trade_name + " on " + startdate + "? " )) {
					
					$.ajax({
						type: "PUT",
						url: passed_vars.restURL + 'cloud_base/v1/field_duty',
						async: true,
					     cache: false,
					     timeout: 30000,
						beforeSend: function (xhr){
							xhr.setRequestHeader('X-WP-Nounce',  passed_vars.nonce );
						},
						data:{
							date: startdate,
							trade_id : trade_id,
							member_id: member_id
						},
						success : function (response){
							calendar.refetchEvents();
							hideassignpopup();				
						}	
					});	 		 
  	 		   }     		   	
     		});       
     });	 
	 	 	 
	 }) // $(function) close	 
	 $( window ).load(function() {

	 });
	 	 
})( jQuery );

		function hideassignpopup() {
			jQuery("#assignself").addClass("popup-overlay");
			jQuery("#assignins").addClass("popup-content");
			jQuery("#assigntp").addClass("popup-content");
			jQuery("#assignfm").addClass("popup-content");
			jQuery("#assignafm").addClass("popup-content");
		}

