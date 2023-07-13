function load_add_leave_credits() {
	load_leave_credits_form();
	$('#message_container').hide();
	$("#tabs").hide();
	$("#leave_credit_form_wrapper").show();				
}

function load_edit_leave_credits(leave_credit_id) {
	load_leave_credits_edit_form(leave_credit_id);
	$('#message_container').hide();
	$("#tabs").hide();
	$("#leave_credit_form_wrapper").show();	
}

function hide_leave_credits_form() {
	$("#credit_condition_form").validationEngine('hide');
	$("#tabs").show();
	$("#leave_credit_form_wrapper").hide();
}

function load_leave_credits_edit_form(leave_credit_id) {
	$('#leaveCreditFormsAjax').html(loading_image);
	$.post(base_url + 'settings/_load_edit_credit_condition_form',{leave_credit_id:leave_credit_id},
		function(o){			
			$('#leaveCreditFormsAjax').html(o);		
		});			
}

function load_leave_credits_form() {
	$('#leaveCreditFormsAjax').html(loading_image);
	$.post(base_url + 'settings/_load_credit_condition_form',{},
		function(o){			
			$('#leaveCreditFormsAjax').html(o);		
		});		
}

function load_leave_credit_list() {
	$('#AjaxLoadLeaveCreditContainer').html(loading_image);
	$.post(base_url + 'settings/_load_leave_credit_list',{},
		function(o){			
			$('#AjaxLoadLeaveCreditContainer').html(o);		
		});			
}

function deleteLeaveCredit(lc_id) {
	_deleteLeaveCredit(lc_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}
