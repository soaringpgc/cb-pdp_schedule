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
	 var overide = [];
	 var hidded_days = passed_vars.hide_days; //  [ 1, 2, 4, 5 ]; 
	 
 	 trade_authority.forEach(function(trade){
	 	overide.push(trade['overrideauthority']);
	 });
	 overide.push('manage_options');

	 var startdate ='';
  	 var localdata ="";
       $(document).ready (function() {
        	var calendarEl = document.getElementById('calendar');
        	var calendar = new FullCalendar.Calendar(calendarEl, {
// set up calendar
        		headerToolbar: {
//        	  		plugins: [ dayGrid, timeGrid ],
 					left: 'prev, timeGridWeek',
 					center: 'title',
 					right: 'dayGridMonth, next',
 						ignoreTimezone: false
 					},
  					hiddenDays: hidded_days,
 					selectable: true,
 					select: this.select, 
         	  		initialView: window.innerWidth >= 765 ? 'dayGridMonth' : 'listWeek',
        	  		dateClick: function(date, jsEvent, view) {
        	  			calendar.gotoDate(date.dateStr);
         	  			calendar.changeView('dayGridDay');
        	  		
//         	  			if (current_user_role == 'tow_pilot'){
//         	  			startdate= date.dateStr;
//         	  				if(confirm("You are signing up to Tow on " + startdate + "? " )) {								
// 						   		$.ajax({
// 						   			type: "PUT",
// 						   			url: passed_vars.restURL + 'cloud_base/v1/field_duty',
// 						   			async: true,
// 						   		   cache: false,
// 						   		   timeout: 30000,
// 						   			beforeSend: function (xhr){
// 						   				xhr.setRequestHeader('X-WP-NONCE',  passed_vars.nonce );
// 						   			},
// 						   			data:{
// 						   				date: startdate,
// 						   				trade_id : "1",
// 						   				member_id: passed_vars.current_user_id
// 						   			},
// 						   			success : function (response){
// 						   				calendar.refetchEvents();
// 						   				hideassignpopup( );				
// 						   			},
// 						   			error: function(XMLHttpRequest, textStatus, errorThrown) { 
//         				   					alert("Status: " + textStatus); 
//         				   					alert("Error: " + errorThrown); 
//    						   			} 
// 						   		});	 		 
//   	 		  			    } 	        	  			
//          	  			}						
					  },
		// fetch the existing events and display 			  
					 events: function (info, successCallback, failureCallback) {	
               	     let start = moment(info.start.valueOf()).format('YYYY-MM-DD');
               	     let end = moment(info.end.valueOf()).format('YYYY-MM-DD');
               	     $.ajax({
               	         url: passed_vars.restURL + "cloud_base/v1/field_duty?fc=1&start="+ start + "&end=" + end,
               	         type: 'GET',
               	         headers: {
               	             'X-WP-NONCE':passed_vars.nonce
               	         }, success: function (response) {
               	              successCallback(response);
               	         }
               	     });
               	 },					  					  
// It is absurd that FullCalendar has no way to include headers. 					  
// 					events:{
// 						headers: {  'X-WP-NONCE': passed_vars.nonce}, 
// 					  	url: '/wordpress/wp-json/cloud_base/v1/field_duty',
// 					  	method: 'GET',				
// 					  	extraParams:{ fc: '1' }  // tell rest endpoint we want FullCallendar data format. 
// 					},
	    // If an event is click on decide what to do. 
				eventClick: function(info) {
					var assigned_member = info.event.title.split(': ')[1] ;				  
					info.jsEvent.preventDefault(); 
					hideassignpopup( );	
					startdate= moment(info.event.start).format('YYYY-MM-DD'); 
					var trade_clicked = info.event.groupId;	
					var trade_select = trade_clicked.replace(/ /g, '_');
					let i= 0;
					for( i; i < trade_authority.length; ++i){
						if( trade_authority[i].trade == trade_clicked  ){
							var trade_id = trade_authority[i].id;
						break;
						}
					};
					// create a list of trade members and their user Id (tradelist)
					var tradelist = {};

					$("#" + trade_select + " option").each(function(){
							 	tradelist[($(this).text())] =($(this).val());
					})
					// now use the tradelist to look up the pilot's user id to set the default 
					// in the select block.  
					$("#" + trade_select + "").val( tradelist[assigned_member]);
					if(overide.includes(current_user_can)){												
						$('#editdate').text(startdate);	
						let i= 0;
						for( i; i < trade_authority.length; ++i){
							if( trade_authority[i].trade == trade_clicked && (current_user_can == trade_authority[i].overrideauthority ||  current_user_can == 'manage_options' )){
								$("#"+trade_authority[i].trade.replace(/ /g, '')+'_').removeClass('popup-content'); 
 								$("#assign_trade_popup").show(); 
								var trade_name = trade_authority[i].trade;
							break;
							}
						}
	  				} else if( info.event.groupId.includes(current_user_role_name) ){ 
	  					var taken = info.event.title.substring(0,2);
						if (taken == 'No' ){ // || current_user_role_name == 'Tow Pilot' ){	
  							if(confirm("You are signing up for " + info.event.groupId  + " on " + startdate + "? " )) {								
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
										date: startdate,
										trade_id : trade_id,
										member_id: passed_vars.current_user_id
									},
									success : function (response){
										calendar.refetchEvents();
										hideassignpopup( );				
									},
									error: function(XMLHttpRequest, textStatus, errorThrown) { 
        									alert("Status: " + textStatus); 
        									alert("Error: " + errorThrown); 
   									} 
								});	 		 
  	 		  			 	} 	
						} else if ( (info.event.extendedProps.member_id == passed_vars.current_user_id)  && current_user_role_name == 'Tow Pilot' ) {
							  	if(confirm("You are canceling duty for " + info.event.groupId  + " on " + startdate + "? " )) {								
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
										date: startdate,
										trade_id : trade_id,
										member_id: 0
									},
									success : function (response){
										calendar.refetchEvents();
										hideassignpopup( );				
									},
									error: function(XMLHttpRequest, textStatus, errorThrown) { 
        									alert("Status: " + textStatus); 
        									alert("Error: " + errorThrown); 
   									} 
								});	 		 
  	 		  			 	} 
						}
						else {
							alert( "That day is taken, contact Ops Manager to switch.");
						}				  								
	  				}
   				},				
        	});
	// render the calendar
     		calendar.render();  
    // if a trade is changed, ask for confirmation and assign.             
     		$('.event_cal_form').change(function() {
		 		var trade = event.target.id;
				var member_id =   $("#"+trade).val();
				var member_name = $("#"+trade).find('option:selected').text();		
				var trade_name = event.target.id.replace(/_/g, ' '); 		
		 	
				for( let i=0; i < trade_authority.length; ++i){
					if( trade_authority[i].trade == trade_name  ){						
						$("#"+trade_authority[i].trade.replace(/ /g, '')+'_').removeClass('popup-content'); 
						var trade_id = trade_authority[i].id;					
						break;
					}
				}		 				
  				if(confirm("Are you sure you want to assign " + member_name + " as " + trade_name + " on " + startdate + "? " )) {					
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
							date: startdate,
							trade_id : trade_id,
							member_id: member_id
						},
						success : function (response){
							calendar.refetchEvents();
							hideassignpopup( );				
						},
						error : function(response){
							alert(response);
							hideassignpopup( );		
						}
					});	 		 
  	 		   }     		   	
     		});       
     	});	  	 
	 }) // $(function) close	 
	 $( window ).load(function() {

	 });
	 	 
})( jQuery );
	
function hideassignpopup( ) {
	var trade_authority = passed_vars.trade_authority;
		for(let i=0; i < trade_authority.length; ++i){
			jQuery("#"+trade_authority[i].trade.replace(/ /g, '')+'_').addClass('popup-content'); 
		}				
 	jQuery("#assign_trade_popup").hide();  	
}

