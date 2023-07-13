function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function createFormToken() {
	$.post(base_url + 'request/_load_token',{},function(o){
		$('#token').val(o.token);
	},'json');
}

function load_requested_leave_list_dt() {
	$.get(base_url + 'request/_load_requested_leave_list_dt',{},function(o) {
		$('#requested_leave_list_dt_wrapper').html(o);
	});
}

function show_request_leave_form() {
	$('#request_leave_button').hide();
	$('#request_leave_form_wrapper').show();
	createFormToken();
}

function cancel_request_leave_form() {
	$('#request_leave_button').show();
	$('#request_leave_form_wrapper').hide();
	$('#request_leave_form').validationEngine('hide');
}

function editLeaveRequestForm(employee_id, is_approved) {
	if(is_approved == 0 ) {
		_editLeaveRequestForm(employee_id, {
			onSaved: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				load_requested_leave_list_dt();
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
				showOkDialog(o.message,{});
			}
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function deleteLeaveRequest(employee_id, is_approved) {
	if(is_approved == 0 ) {
		_deleteLeaveRequest(employee_id, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				load_requested_leave_list_dt();
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function load_requested_overtime_list_dt() {
	$.get(base_url + 'request/_load_requested_overtime_list_dt',{},function(o) {
		$('#requested_overtime_list_dt_wrapper').html(o);
	});
}

function show_request_overtime_form() {
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	createFormToken();
}

function cancel_request_overtime_form() {
	$('#request_overtime_button').show();
	$('#request_overtime_form_wrapper').hide();
	$('#request_overtime_form').validationEngine("hide");
}

function editOvertimeRequestForm(hid, is_approved) {
	if(is_approved == "Pending" ) {
		_editOvertimeRequestForm(hid, {
			onSaved: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				dialogOkBox(o.message,{});
				load_requested_overtime_list_dt();
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
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function deleteOvertimeRequest(hid, is_approved) {
	if(is_approved == 'Pending' ) {
		_deleteOvertimeRequest(hid, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});	
	} else { dialogOkBox('Selected entry cannot be edited',{}); }
}

function show_request_rest_day_form() {
	$('#request_rest_day_button').hide();
	$('#request_rest_day_form_wrapper').show();
	createFormToken();
}

function cancel_request_rest_day_form() {
	$('#request_rest_day_button').show();
	$('#request_rest_day_form_wrapper').hide();
	$('#request_rest_day_form').validationEngine("hide");
}

function load_requested_rest_day_list_dt() {
	$.get(base_url + 'request/_load_requested_rest_day_list_dt',{},function(o) {
		$('#requested_rest_day_list_dt_wrapper').html(o);
	});
}


function editRestDayRequestForm(hid, is_approved) {
	if(is_approved == 0 ) {
		_editRestDayRequestForm(hid, {
			onSaved: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				load_requested_rest_day_list_dt();
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
				showOkDialog(o.message,{});
			}
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function deleteRestDayRequest(hid, is_approved) {
	if(is_approved == 0 ) {
		_deleteRestDayRequest(hid, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				load_requested_rest_day_list_dt();
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function load_requested_change_schedule_list_dt() {
	$.get(base_url + 'request/_load_requested_change_schedule_list_dt',{},function(o) {
		$('#requested_change_schedule_list_dt_wrapper').html(o);
	});
}

function show_request_change_schedule_form() {	
	$('#request_change_schedule_button').hide();
	$('#request_change_schedule_form_wrapper').show();
	createFormToken();
}

function cancel_request_change_schedule_form() {
	$('#request_change_schedule_button').show();
	$('#request_change_schedule_form_wrapper').hide();
	$('#request_change_schedule_form').validationEngine("hide");
}

function editChangeScheduleForm(hid, is_approved) {
	if(is_approved == 0 ) {
		_editChangeScheduleRequestForm(hid, {
			onSaved: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				load_requested_change_schedule_list_dt();
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
				showOkDialog(o.message,{});
			}
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function deleteChangeScheduleRequest(hid, is_approved) {
	if(is_approved == 0 ) {
		_deleteChangeScheduleRequest(hid, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

function load_fa_leave_list_dt() {
	$.get(base_url + 'request/_load_fa_leave_list_dt',{},function(o) {
		$('#fa_leave_list_dt_wrapper').html(o);
	});
}

function viewForApprovalLeaveRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalLeaveRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
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
			showOkDialog(o.message,{});
		}
	});	
}

function viewForApprovalRequestApprovers(h_id) {
	$.post(base_url + 'request/_load_fa_overtime_request_approvers',{h_id:h_id},function(o) {
		$("#view_fa_request_approvers_wrapper").html(o);
		dialogGeneric("#view_fa_request_approvers_wrapper",{title: 'Approvers', height:'auto',width:'auto'});	

	});
}

function load_fa_overtime_list_dt() {
	$('#fa_overtime_approved_list_dt_wrapper').html("");
	var h_department = $('#department').val();
	$.post(base_url + 'request/_load_fa_overtime_list_dt',{h_department:h_department},function(o) {
		$('#fa_overtime_list_dt_wrapper').html(o);
	});
}

function load_show_specific_schedule() {
	var start_date 		= $('#start_date').val();

	if(start_date != "") {
		$('#_schedule_loading_wrapper').html(loading_image);
		$.post(base_url + 'request/load_get_specific_schedule',{start_date:start_date},function(o) {
			$('#_schedule_loading_wrapper').html('');
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}

function load_show_specific_schedule_edit() {
	var start_date 		= $('#start_date_edit').val();
	
	if(start_date != "")
	$('#_schedule_loading_wrapper_edit').html(loading_image);
	$.post(base_url + 'request/load_get_specific_schedule',{start_date:start_date},function(o) {
		$('#_schedule_loading_wrapper_edit').html('');
		$('#show_specific_schedule_wrapper_edit').html(o);
	});
}

function viewForApprovalOvertimeRequest(hid,employee_name,h_approvers_id) {
	_viewForApprovalOvertimeRequest(hid,employee_name,h_approvers_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_fa_overtime_list_dt();
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


function load_fa_overtime_approved_list_dt() {
	$('#fa_overtime_list_dt_wrapper').html("");
	var h_department = $('#department_2').val();
	$.post(base_url + 'request/_load_fa_overtime_pending_list_dt',{h_department:h_department},function(o) {
		$('#fa_overtime_approved_list_dt_wrapper').html(o);
	});
}

