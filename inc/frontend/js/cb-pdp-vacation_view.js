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
	 
 
	 var startdate ='';
  	 var localdata ="";
        $(document).ready (function() {
	      	var calendarEl = document.getElementById('calendar');
        	var calendar = new FullCalendar.Calendar(calendarEl, {
				headerToolbar: {
//        	  		plugins: [ dayGrid, timeGrid ],
 					left: 'prev, timeGridWeek',
 					center: 'title',
 					right: 'dayGridMonth, next',
 						ignoreTimezone: false
 					}, 
 					selectable: true,
 					select: this.select, 
         	  		initialView: window.innerWidth >= 765 ? 'dayGridMonth' : 'listWeek',	
				dateClick: function(info){
					alert('clicked ' + info.dateStr);
				},
				select: function(info){
					alert('selected ' + info.startStr + ' to ' + info.endStr);
					console.log(info);
				}	

			  });
			calendar.render();    
 		});

 
	 }) // $(function) close	 
	 $( window ).load(function() {

	 });
	 	 
})( jQuery );
	

