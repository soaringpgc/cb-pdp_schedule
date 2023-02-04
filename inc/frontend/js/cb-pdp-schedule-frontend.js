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
 
 // Over-ride openForm method to do our bidding. 
 	Calendar.prototype.openForm = function(el) {
 	    var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
		    var day = this.current.clone().date(dayNumber);
 	  		$("#assignself").removeClass('popup-overlay'); 
 	  		$("#dutyday").val(day);
// 	  		alert(day);
 	      return ;            
 	      ;
 	}
let startOfMonth = moment().startOf('month');
let endOfMonth   = moment().endOf('month');
 	
// process and get next month 
 	Calendar.prototype.nextMonth = function() {
    	this.current.add(1, 'months');
    	this.next = true;
    	
    	var response=[];
    	var self = this;
// get the day we need to schedule trades		
	$.ajax({
		type: "GET",
		url: PDP_SCHEDULER.restURL + 'cloud_base/v1/field_duty',
		async: false,
        cache: false,
        timeout: 30000,
		beforeSend: function (xhr){
			xhr.setRequestHeader('X-WP-Nounce',  PDP_SCHEDULER.nonce );
		},
		data:{
			start: startOfMonth.add(1, "month").format('YYYY-MM-DD'),
			stop: endOfMonth.add(1, "month").endOf('month').format('YYYY-MM-DD'),
			ec: true
		},
		success : function (response){
			if(response){
			self.events = response;	
    		self.draw();  
			}
		}			
	});	
  }
// process and get previous month
 	Calendar.prototype.prevMonth = function() {
    	this.current.subtract(1, 'months');
    	this.next = false;   	
    	var response=[];
    	var self = this;
// get the day we need to schedule trades		
	$.ajax({
		type: "GET",
		url: PDP_SCHEDULER.restURL + 'cloud_base/v1/field_duty',
		async: false,
        cache: false,
        timeout: 30000,
		beforeSend: function (xhr){
			xhr.setRequestHeader('X-WP-Nounce',  PDP_SCHEDULER.nonce );
		},
		data:{
			start: startOfMonth.subtract(1, "month").format('YYYY-MM-DD'),
			stop: endOfMonth.subtract(1, "month").endOf('month').format('YYYY-MM-DD'),
			ec: true
		},
		success : function (response){
			if(response){
			self.events = response;	
    		self.draw();  
			}
		}			
	});	
  } 	

	var data = [];	
	
	var response=[];
// get the day we need to schedule trades		
	$.ajax({
		type: "GET",
		url: PDP_SCHEDULER.restURL + 'cloud_base/v1/field_duty',
		async: false,
        cache: false,
        timeout: 30000,
		beforeSend: function (xhr){
			xhr.setRequestHeader('X-WP-Nounce',  PDP_SCHEDULER.nonce );
		},
		data:{
			start: startOfMonth.format('YYYY-MM-DD'),
			stop: endOfMonth.format('YYYY-MM-DD'),
			ec: true
		},
		success : function (response){
			if(response){
				var calendar = new Calendar('#calendar', response); 
			}
		}	
	});

	$('.event_cal_form').change(function() {	
//	$('#instructor').change(function() {
//alert(event.target.id);
	var trade = event.target.id;
	switch(trade){
		case 'towpilot':
			var trade_id = 1;
			var trade_name = "Tow Pilot"
			break;
		case 'instructor':
			var trade_id = 2;
			var trade_name = "Instructor"
			break;
		case 'field_manager':
			var trade_id = 3;
			var trade_name = "Field Manager"
		    break;
		case 'assistant_field_manager':
			var trade_id = 4;
			var trade_name = "Assistant FM"
	
	}
	
	var dutyday = moment($("#dutyday").val()).format('YYYY-MM-DD');
	var member_id = $("#"+trade).val();
	var member_name = $("#"+trade).find('option:selected').text();
  	if(confirm("Are you sure you want to assign " + member_name + " as " + trade_name + " on " + dutyday + "? " )) {

//     let elements = document.querySelector('#calendar');
//     elements.remove;

  
     	$.ajax({
 		type: "PUT",
 		url: PDP_SCHEDULER.restURL + 'cloud_base/v1/field_duty',
 		async: true,
         cache: false,
         timeout: 30000,
 		beforeSend: function (xhr){
 			xhr.setRequestHeader('X-WP-Nounce',  PDP_SCHEDULER.nonce );
 		},
 		data:{
 			date: dutyday,
 			member_id: member_id,
 			trade_id: trade_id
 		},
 		success : function (response){
 			if(response){
 			 this.el = document.querySelector('#calendar');
 			 this.el.removeChild(this.month);
 				//var calendar = new Calendar('#calendar', response); 
   alert(response);
 			}
 		}	
 	});

    
    
  }
});
	
//		console.log(response);	
// 	 var calendar = new Calendar('#calendar', response); 
			 
	 }) // $(function) close	 
	 $( window ).load(function() {

	  });
	 
	 
})( jQuery );


