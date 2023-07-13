function load_fa_leave_list_dt() {
	$('#fa_leave_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_leave_list_dt',{},function(o) {
		$('#fa_leave_list_dt_wrapper').html(o);
	});
}

function viewForApprovalLeaveRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalLeaveRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_leave_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function viewForApprovalLeaveRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_leave_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	

	});
}

function load_fa_ob_list_dt() {
	$('#fa_ob_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_ob_list_dt',{},function(o) {
		$('#fa_ob_list_dt_wrapper').html(o);
	});
}

function viewForApprovalOBRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalOBRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_ob_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function viewForApprovalOBRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_ob_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	
	});
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function load_fa_change_schedule_list_dt() {
	$('#fa_change_schedule_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_change_schedule_list_dt',{},function(o) {
		$('#fa_change_schedule_list_dt_wrapper').html(o);
	});
}

function viewForApprovalChangeScheduleRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_change_schedule_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	

	});
}

function viewForApprovalChangeScheduleRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalChangeScheduleRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_change_schedule_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function load_fa_make_up_schedule_list_dt() {
	$('#fa_make_up_schedule_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_make_up_schedule_list_dt',{},function(o) {
		$('#fa_make_up_schedule_list_dt_wrapper').html(o);
	});
}

function viewForApprovalMakeUpScheduleRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_make_up_schedule_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	
	});
}

function viewForApprovalMakeUpScheduleRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalMakeUpScheduleRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_make_up_schedule_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function load_fa_undertime_list_dt() {
	$('#fa_undertime_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_undertime_list_dt',{},function(o) {
		$('#fa_undertime_list_dt_wrapper').html(o);
	});
}

function viewForApprovalUndertimeRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_undertime_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	
	});
}

function viewForApprovalUndertimeRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalUndertimeRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_undertime_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function load_fa_rest_day_list_dt() {
	$('#fa_rest_day_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_rest_day_list_dt',{},function(o) {
		$('#fa_rest_day_list_dt_wrapper').html(o);
	});
}

function viewForApprovalRestDayRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_rest_day_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	
	});
}

function viewForApprovalRestDayRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalRestDayRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_rest_day_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function load_fa_ac_list_dt() {
	$('#fa_ac_list_dt_wrapper').html("");
	$.get(base_url + 'for_approval/_load_fa_ac_list_dt',{},function(o) {
		$('#fa_ac_list_dt_wrapper').html(o);
	});
}

function viewForApprovalACRequestApprovers(h_id) {
	$.post(base_url + 'for_approval/_load_show_ac_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	
	});
}

function viewForApprovalACRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalACRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_ac_list_dt();
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}
