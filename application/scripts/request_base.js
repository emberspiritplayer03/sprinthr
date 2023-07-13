function _editLeaveRequestForm(employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Leave Request';
	var width = 550;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'request/_load_edit_leave_request',{employee_id:employee_id},
	function(o){ 
		
		$('#edit_request_leave_form_wrapper').html(o); 
		dialogGeneric('#edit_request_leave_form_wrapper', {
			title: 'Edit Leave Request',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_request_leave_form11'
		});
	
		$("#edit_request_leave_form11").validationEngine({scroll:false});
		$('#edit_request_leave_form11').ajaxForm({
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
				closeDialog(dialog_id);
				closeDialogBox('#edit_request_leave_form_wrapper','#edit_request_leave_form11');
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


function _deleteLeaveRequest(employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to cancel your leave request?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url + 'request/_load_delete_leave_request',{employee_id:employee_id},function(o) { });
				if (typeof events.onYes == "function") {
					events.onYes();
				}
				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _editOvertimeRequestForm(hid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Overtime Request';
	var width = 550;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'request/_load_edit_overtime_request',{hid:hid},
	function(o){ 
		$('#edit_request_overtime_form_wrapper').html(o); 
		dialogGeneric('#edit_request_overtime_form_wrapper', {
			title: 'Edit Overtime Request',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_request_overtime_form'
		});
	
		$("#edit_request_overtime_form").validationEngine({scroll:false});
		$('#edit_request_overtime_form').ajaxForm({
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
				closeDialogBox('#edit_request_overtime_form_wrapper','#edit_request_overtime_form');
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

function _deleteOvertimeRequest(hid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to cancel your overtime request?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url + 'request/_load_delete_overtime_request',{hid:hid},function(o) { });
				load_requested_overtime_list_dt();
				if (typeof events.onYes == "function") {
					events.onYes();
				}
				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _editRestDayRequestForm(hid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Rest Day Request';
	var width = 550;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'request/_load_edit_rest_day_request',{hid:hid},
	function(o){ 
		
		$('#edit_request_rest_day_form_wrapper').html(o); 
		dialogGeneric('#edit_request_rest_day_form_wrapper', {
			title: 'Edit Rest Day Request',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_request_rest_day_form'
		});
	
		$("#edit_request_rest_day_form").validationEngine({scroll:false});
		$('#edit_request_rest_day_form').ajaxForm({
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
				closeDialog(dialog_id);
				closeDialogBox('#edit_request_rest_day_form_wrapper','#edit_request_rest_day_form');
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


function _deleteRestDayRequest(hid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to cancel your rest day  request?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url + 'request/_load_delete_rest_day_request',{hid:hid},function(o) { });
				if (typeof events.onYes == "function") {
					events.onYes();
				}
				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _editChangeScheduleRequestForm(hid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Overtime Request';
	var width = 550;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'request/_load_edit_change_schedule_request',{hid:hid},
	function(o){ 
		$('#edit_request_change_schedule_form_wrapper').html(o); 
		dialogGeneric('#edit_request_change_schedule_form_wrapper', {
			title: 'Edit Change Schedule Request',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_request_change_schedule_form'
		});
	
		$("#edit_request_change_schedule_form").validationEngine({scroll:false});
		$('#edit_request_change_schedule_form').ajaxForm({
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
				closeDialog(dialog_id);
				closeDialogBox('#edit_request_change_schedule_form_wrapper','#edit_request_change_schedule_form');
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

function _deleteChangeScheduleRequest(hid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 400;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to cancel your change schedule request?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url + 'request/_load_delete_change_schedule_request',{hid:hid},function(o) { });
				load_requested_change_schedule_list_dt();
				if (typeof events.onYes == "function") {
					events.onYes();
				}
				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
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
	
	$.post(base_url + 'request/_load_view_fa_leave_request',{hid:hid,h_approvers_id:h_approvers_id},
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
				closeDialog(dialog_id);
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

function _viewForApprovalOvertimeRequest(hid,employee_name,h_approvers_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Leave';
	var width = 650;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'request/_load_view_fa_overtime_request',{hid:hid,h_approvers_id:h_approvers_id},
	function(o){ 
		$('#view_fa_request_form_wrapper').html(o); 
		dialogGeneric('#view_fa_request_form_wrapper', {
			title: 'Overtime Request ('+employee_name+')',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#view_leave_request_form'
		});
	
		$("#view_overtime_request_form").validationEngine({scroll:false});
		$('#view_overtime_request_form').ajaxForm({
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
				closeDialogBox('#view_fa_request_form_wrapper','#view_overtime_request_form');
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
