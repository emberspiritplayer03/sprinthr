function _viewForApprovalOBRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_ob_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Offical Business Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_ob_request_form'
		});
	
		$("#view_ob_request_form").validationEngine({scroll:false});
		$('#view_ob_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_ob_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}

function _viewForApprovalLeaveRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_leave_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Leave Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_leave_request_form'
		});
	
		$("#view_leave_request_form").validationEngine({scroll:false});
		$('#view_leave_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_leave_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}


function _viewForApprovalChangeScheduleRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_change_schedule_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Change Schedule Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_change_schedule_request_form'
		});
	
		$("#view_change_schedule_request_form").validationEngine({scroll:false});
		$('#view_change_schedule_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_change_schedule_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}

function _viewForApprovalMakeUpScheduleRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_make_up_schedule_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Make Up Schedule Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_make_up_schedule_request_form'
		});
	
		$("#view_make_up_schedule_request_form").validationEngine({scroll:false});
		$('#view_make_up_schedule_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_make_up_schedule_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}


function _viewForApprovalUndertimeRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_undertime_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Undertime Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_undertime_request_form'
		});
	
		$("#view_undertime_request_form").validationEngine({scroll:false});
		$('#view_undertime_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_undertime_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}

function _viewForApprovalRestDayRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_rest_day_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Rest Day Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_rest_day_request_form'
		});
	
		$("#view_rest_day_request_form").validationEngine({scroll:false});
		$('#view_rest_day_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_rest_day_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}

function _viewForApprovalACRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'for_approval/_load_view_fa_ac_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Attendance Correction Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_ac_request_form'
		});
	
		$("#view_ac_request_form").validationEngine({scroll:false});
		$('#view_ac_request_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#view_fa_request_form_wrapper','#view_ac_request_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}

