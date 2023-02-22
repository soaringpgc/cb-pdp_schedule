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
	 	 var app = app || {};
    //models
		//  type model 
			app.Model = Backbone.Model.extend({
			// over ride the sync function to include the Wordpress nonce. 
			// going to need this for everything so do it once.  
			  	sync: function( method, model, options ){
		   		return Backbone.sync(method, this, jQuery.extend( options, {
		     			beforeSend: function (xhr) {
		//      			alert(cp_schedule_admin_vars.nonce);
		       		xhr.setRequestHeader( 'X-WP-NONCE', cp_schedule_admin_vars.nonce );
		     			},
		  			} ));	
		  		},	
			});
			app.Cb_trade_type= app.Model.extend({
				initialize: function(){
				},	
			    defaults: {		
				},
				wait: true	
			} );
			app.cb_field_duty= app.Model.extend({
				initialize: function(){
				},	
			    defaults: {		
				},
				wait: true	
			} );	 
    // collections	
		    app.Collection = Backbone.Collection.extend({	
		    	sync: function( method, model, options ){
		    		return Backbone.sync(method, this, jQuery.extend( options, {
		      			beforeSend: function (xhr) {
		 //     			alert(cp_schedule_admin_vars.nonce);
		        		xhr.setRequestHeader( 'X-WP-NONCE', cp_schedule_admin_vars.nonce );
		      			},
		   			} ));	
		   		},	
		   	 }) ; 
		
		    app.TradeTypeList = app.Collection.extend({
		    	model: app.Cb_trade_type,
		    	url: cp_schedule_admin_vars.root + 'cloud_base/v1/trades',  
		   	 }) ; 
		    app.FieldDutyList = app.Collection.extend({
		    	model: app.cb_field_duty,
		    	url: cp_schedule_admin_vars.root + 'cloud_base/v1/field_duty',			
		    }) ; 
// model view	
	app.ModelView = Backbone.View.extend({
		tagName: 'div',
        className: 'Row',
		render: function(){
			this.$el.html( this.template(this.model.toJSON() ) );
			this.$input = this.$('.edit');
			return this;
		},
		initialize: function(){
    		this.model.on('change', this.render, this);
  		},
		events:{
			'click .delete' : 'deleteItem',
			'dblclick label' : 'update'
		},
		deleteItem: function(){
			this.model.destroy();
			this.remove();
		},
   		update: function(){
			var localmodel = this.model;
 			$("div.editform").addClass('editing');
 			   console.log( "edit" );
             // 			
             // NTFS this requires the form id's to be the same as the model id's.
             // we are looping over the form, picking up the id's and then getting the 
             // value of the same id in the model and then loading it back into the form
             //  someone (probably me) is going to hate me in the future.  -dsj
            $(this.localDivTag).children('input').each(function(i, el ){
 //			   console.log( el.id);
      		   if(el.type === "checkbox" ){
      		   		if (localmodel.get(el.id) === "1" ){
      		   			$('#'+el.id).prop("checked", true);
      		   		} else {
      		   		    $('#'+el.id).prop("checked", false);
      		   		}
      		   } else {
      		      $('#'+el.id).val(localmodel.get(el.id));
      		   }  
      		});     		
      		$(this.localDivTag).children('select').each(function(i, el ){
				$('#'+el.id).val(localmodel.get(el.id));
      		});
      		$(this.localDivTag).children('textarea').each(function(i, el ){
				$('#'+el.id).val(localmodel.get(el.id));
      		});
		},
		deleteItem: function(){
			this.model.destroy(
			{
    			wait: true,			
    			error: function(model, response) {
    				var parsedmessage = JSON.parse(response.responseText);
    				 alert(JSON.stringify(parsedmessage.message));
    				},	
    			success: (function(model, response){
            		this.remove();  
    			 	}).bind(this) //  NTFS: ".bind(this)" makes the right "this" available to the callback. 
    			}	
			)
 		},	   
	});		    
	app.tradeTypeView = app.ModelView.extend({
	    template: tradetypetemplate,     
	});
	app.TowFeeView = app.ModelView.extend({
	    template: feeitemtemplate,
	});		    

	app.CollectionView =  Backbone.View.extend({         
      initialize: function(){
//      	console.log('the view has been initialized. ');
        this.collection.fetch({reset:true});
        this.render();
        this.listenTo(this.collection, 'add', this.renderItem);
        this.listenTo(this.collection, 'reset', this.render);
      },
      render: function(){
      	this.collection.each(function(item){
  			this.renderItem(item);    	
      	}, this );
      },
      events:{
      	'click #add' : 'addItem',
      	'click #update' : 'updateItem'
      },
      addItem: function(e){
      	e.preventDefault();
      	var formData ={};
      	// grab all of the input fields
 		$(this.localDivTag).children('input').each(function(i, el ){
		  if($(el).val() != ''){
		  	if($(el).hasClass('checked_class')){
		  		formData[el.id]=($(el).is(":checked")? true : false );
		  	} else {
        		formData[el.id] = $(el).val();
        	}
      	  } 
      	});
      	//grab all of the <select> fields 
      	$(this.localDivTag).children('select').each(function(i, el ){
      		if($(el).val() != ''){
      			formData[el.id] = $(el).val();
      		}
      	});
      	$(this.localDivTag).children('textarea').each(function(i, el ){
      		if($(el).val() != ''){
      			formData[el.id] = $(el).val();
      		}
      	});
//  alert(JSON.stringify(formData));
      	this.collection.create( formData, {wait: true, error: function(model, response, error){
      				var mresult= JSON.parse(response.responseText);     	
      				alert(mresult["message"]) 
      				} 
      			});
      	// clean out the form:
      		$(this.localDivTag).children('input').each(function(i, el ){
				$('#'+el.id).val('');
      		});       
      		$(this.localDivTag).children('select').each(function(i, el ){
				$('#'+el.id).val('');
      		});  
      		$(this.localDivTag).children('textarea').each(function(i, el ){
				$('#'+el.id).val('');
      		});       
      },
      updateItem: function(e){     	
		e.preventDefault();
 		var formData ={};
		// grab all of the input fields
 		$(this.localDivTag).children('input').each(function(i, el ){
 		 if($(el).val() != ''){
		  	if($(el).hasClass('checked_class')){
		  		formData[el.id]=($(el).is(":checked")? true : false );
		  	} else {
        		formData[el.id] = $(el).val();
        	}
      	  } 		
      	});
      	//grab all of the <select> fields 
      	$(this.localDivTag).children('select').each(function(i, el ){
      	  if($(el).val() != ''){
      		formData[el.id] = $(el).val();
      	  }
      	});
      	$(this.localDivTag).children('textarea').each(function(i, el ){
      	  if($(el).val() != ''){
      		formData[el.id] = $(el).val();
      	  }
      	});
 //    	alert(JSON.stringify(formData));
      	var updateModel = this.collection.get(formData.id);
        updateModel.save(formData, {wait: true, error: function(model, response, error){
      				var mresult= JSON.parse(response.responseText);     	
      				alert(mresult["message"]) 
      				}         
        	});
	// clean out the form:
      		$(this.localDivTag).children('input').each(function(i, el ){
				$('#'+el.id).val('');
      		});       
      		$(this.localDivTag).children('select').each(function(i, el ){
				$('#'+el.id).val('');
      		}); 
      		$(this.localDivTag).children('textarea').each(function(i, el ){
				$('#'+el.id).val('');
      		});           
		$("div.editform").removeClass('editing');	
      	}
	});
	app.TradeTypesView = app.CollectionView.extend({
	 	el: '#trade_types', 
		localDivTag: '#addtradetypes Div',
	 	preinitialize(){
	 	   this.collection = new app.TradeTypeList();
	 	},	
        renderItem: function(item){
            var expandedView = app.tradeTypeView.extend({ localDivTag:this.localDivTag });
            var itemView = new expandedView({
//      		var itemView = new app.AircraftView({
      	  		model: item
      		})
      		this.$el.append( itemView.render().el);   
        }
	 });
  
 $(function(){
   if (typeof cb_admin_tab !== 'undefined' ){
   		switch(cb_admin_tab){
   			case "trade_types" : new app.TradeTypesView();
   			break;
   		}
   	} else {
   	console.log("not defined");}
   });	
   	    
  }) // $(function) close
})( jQuery );


