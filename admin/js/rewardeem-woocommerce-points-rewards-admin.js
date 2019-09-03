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
	 */

	$(document).ready(function() {

		$(document).find('#mwb_wpr_restrictions_for_purchasing_cat').select2();
		/* Update user Points in the points Table*/
		$('.mwb_points_update').click(function(){
			var user_id = $(this).data('id');
			var user_points = $(document).find("#add_sub_points"+user_id).val();
			var sign = $(document).find("#mwb_sign"+user_id).val();
			var reason = $(document).find("#mwb_remark"+user_id).val();
			user_points = Number(user_points);
			if(user_points > 0 && user_points === parseInt(user_points, 10)){
				if( reason != '' ){
					jQuery("#mwb_wpr_loader").show();
					var data = {
						action:'mwb_wpr_points_update',
						points:user_points,
						user_id:user_id,
						sign:sign,
						reason:reason,
						mwb_nonce:mwb_wpr_object.mwb_wpr_nonce,
					};
					$.ajax({
			  			url: mwb_wpr_object.ajaxurl, 
			  			type: "POST",  
			  			data: data,
			  			success: function(response) 
			  			{
			  				jQuery("#mwb_wpr_loader").hide();
			  				location.reload();
			  			}
			  		});
				}
				else {
					alert(mwb_wpr_object.reason);
				}
			}
			else {
				alert(mwb_wpr_object.validpoint);
			}
		});

		$(document).on('click','.mwb_wpr_email_wrapper_text',function(){
			$(this).siblings('.mwb_wpr_email_wrapper_content').slideToggle();
		});
		/*This will add new setting*/
    	$(document).on("change",".mwb_wpr_common_class_categ",function(){
    		var count = $(this).data('id');
    		var mwb_wpr_categ_list = $('#mwb_wpr_membership_category_list_'+count).val();
    		jQuery("#mwb_wpr_loader").show();
    		var data = {
    			action:'mwb_wpr_select_category',
    			mwb_wpr_categ_list:mwb_wpr_categ_list,
    			mwb_nonce:mwb_wpr_object.mwb_wpr_nonce,
    		};
    		$.ajax({
    			url: mwb_wpr_object.ajaxurl, 
    			type: "POST",  
    			data: data,
    			dataType :'json',
    			success: function(response) 
    			{	

    				if(response.result == 'success')
    				{
    					var product = response.data;	                        
    					var option = '';
    					for(var key in product)
    					{
    						option += '<option value="'+key+'">'+product[key]+'</option>';
    					} 
    					jQuery("#mwb_wpr_membership_product_list_"+count).html(option);
    					jQuery("#mwb_wpr_membership_product_list_"+count).select2();
    					jQuery("#mwb_wpr_loader").hide();
    				}
    			}
    		});	

    	});
		var count = $('.mwb_wpr_repeat:last').data('id');
			for(var i=0; i<=count; i++) {
				//var mwb_wpr_categ_list = $('#mwb_wpr_membership_category_list_'+i).val();
				 $(document).find('#mwb_wpr_membership_category_list_'+i).select2();
				 $(document).find('#mwb_wpr_membership_product_list_'+i).select2();
		}

		/*Add a label for purchasing the paid plan*/
		if(mwb_wpr_object.check_pro_activate) {
			jQuery(document).on('click','.mwb_wpr_repeat_button',function(){
				var html = '';
				$(document).find('.mwb_wpr_object_purchase').remove();
				html = '<div class="mwb_wpr_object_purchase"><p>'+mwb_wpr_object.pro_text+'</p></div>';
				$('.parent_of_div').append(html);
			});
		}

		/*Add a label for purchasing the paid plan*/
		if(mwb_wpr_object.check_pro_activate) {
			$(document).on('click','#mwb_wpr_add_more',function() {
				var html = '';
				$(document).find('.mwb_wpr_object_purchase').remove();
				html = '<div class="mwb_wpr_object_purchase"><p>'+mwb_wpr_object.pro_text+'</p></div>';
				$(html).insertAfter('.wp-list-table');
			});
		}
		jQuery(document).on('click','.mwb_wpr_remove_button',function(){
    		//$('.parent_of_div .mwb_wpr_repeat:last').remove();
    		var curr_div = $(this).attr('id');
    		if(curr_div == 0) {
    			$(document).find('.mwb_wpr_repeat_button').hide();
    			$('#mwb_wpr_membership_setting_enable').attr('checked',false);
    		}
    		$('#mwb_wpr_parent_repeatable_'+curr_div).remove();
    		
    	});

	});

})( jQuery );