function _importLeaveCredits(events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Import Leave Credits';
    var width = 330;
    var height = 'auto';

    if (typeof events.onLoading == "function") {
        events.onLoading();
    }

    $.get(base_url + 'attendance/ajax_import_leave_credit', {}, function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true
        });
        $('#import_leave_credit_form').validationEngine({scroll:false});
        $('#import_leave_credit_form').ajaxForm({
            success:function(o) {
                if (o.is_imported) {
                    if (typeof events.onImported == "function") {
                        events.onImported(o);
                    }
                } else {
                    if (typeof events.onError == "function") {
                        events.onError(o);
                    }
                }
            },
            dataType:'json',
            beforeSubmit: function() {
                if ($('#file').val() == '') {
                    return false;
                }
                if (typeof events.onImporting == "function") {
                    events.onImporting();
                }
                return true;
            }
        });
    });
}

function _addLeaveRequestForm(h_employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Leave Request';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'leave/ajax_quick_add_leave_request', {h_employee_id:h_employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#request_leave_form').validationEngine({scroll:false});		
		$('#request_leave_form').ajaxForm({
			success:function(o) {
				if (o.is_saved = 1) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _editLeaveRequestForm(c_leave_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Leave Request';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	var start_cutoff = $("#from_period").val();
	var end_cutoff   = $("#to_period").val();

	$.post(base_url + 'leave/ajax_edit_leave_request', {c_leave_id:c_leave_id,start_cutoff:start_cutoff,end_cutoff:end_cutoff}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#request_leave_form').validationEngine({scroll:false});		
		$('#request_leave_form').ajaxForm({
			success:function(o) {
				if (o.is_saved = 1) {
					if (typeof events.onSaved == "function") {
						//load_leave_list_dt(o.es_id);
						load_leave_list_dt($("#cmb_dept_id").val());
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _viewLeaveRequestApprovers(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Leave Request Approvers';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	var start_cutoff = $("#from_period").val();
	var end_cutoff   = $("#to_period").val();

	$.get(base_url + 'leave/ajax_view_leave_request_approvers', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});		
	});
}

function _editLeaveType(c_leave_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Leave Type';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'leave/ajax_edit_leave_type', {c_leave_id:c_leave_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#request_leave_form').validationEngine({scroll:false});		
		$('#request_leave_form').ajaxForm({
			success:function(o) {
				if (o.is_saved = 1) {
					if (typeof events.onSaved == "function") {
						//load_leave_list_dt(o.es_id);
						//load_leave_type_list_dt();
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _addLeaveCredit(employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Leave Credit';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'leave/ajax_add_leave_credit', {employee_id:employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#request_leave_form').validationEngine({scroll:false});		
		$('#request_leave_form').ajaxForm({
			success:function(o) {
				if (o.is_success = 1) {
					if (typeof events.onSaved == "function") {
						//load_leave_list_dt(o.es_id);
						load_employee_leave_available_dt(o.e_id);
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _archiveLeaveRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = '<b>Send to archive</b> the selected leave request?';
	
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
				$.post(base_url+'leave/_load_archive_leave_request',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {														
						load_leave_list_dt($("#cmb_dept_id").val());
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _pendingLeaveWithSelectedAction(status,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	if(status == 'approve'){
		var message = '<b>Approve</b> the selected request(s)?';
	}else if(status == 'archive'){
		var message = '<b>Send to archive</b> the selected request(s)?';
	}else if(status == 'disapprove') {
		var message = '<b>Disapprove</b> the selected request(s)?';
	}
	
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
				showLoadingDialog('Validating...');	
				var request_action = $("#chkAction").val();
				if( request_action == "approve" ){
					var url_action = 'leave/_approve_selected_requests';
				}else if( request_action == "archive" ){
					var url_action = 'leave/_pending_leave_with_selected_action';
				}else if( request_action == "disapprove" ){
					var url_action = 'leave/_disapprove_selected_requests';
				}
				$.post(base_url + url_action ,$('#withSelectedAction').serialize(),
					function(o){								
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});	
						$("#chkAction").attr('disabled',true);													
						//load_leave_list_dt($("#cmb_dept_id").val());
						location.reload();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _archiveWithSelectedAction(status,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	if(status == 'restore_leave_type'){
		var form_id = 'leaveTypewithSelectedAction';
		var message = '<b>Restore</b> selected archived leave type(s)?';
	}else if(status == 'restore_leave_request'){
		var form_id = 'leaveRequestWithSelectedAction';
		var message = '<b>Restore</b> selected archived leave request(s)?';
	}
	
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
				showLoadingDialog('Validating...');		
				$.post(base_url+'leave/_archive_with_selected_action',$('#' + form_id).serialize(),
					function(o){								
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});
						if(o.form == 1){	
							$("#chkAction").attr('disabled',true);													
							load_leave_type_archives_dt();
						}else{
							$("#chkActionSub").attr('disabled',true);
							load_leave_list_archives_dt($("#cmb_dept_id").val());
						}
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _leaveTypeWithSelectedAction(status,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	if(status == 'archive'){
		var message = '<b>Send to archive</b> selected leave type(s)?';
	}
	
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
				showLoadingDialog('Validating...');		
				$.post(base_url+'leave/_leave_type_with_selected_action',$('#withSelectedAction').serialize(),
					function(o){								
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});														
						load_leave_type_list_dt();
						$("#chkAction").attr('disabled',true);
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _archiveLeaveRequestWithSelectedAction(status,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	if(status == 'restore'){
		var message = '<b>Restore</b> selected request(s)?';
	}
	
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
				showLoadingDialog('Validating...');		
				$.post(base_url+'leave/archiveLeaveRequestsWithSelectedAction',$('#withSelectedAction').serialize(),
					function(o){								
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});														
						load_leave_list_archives_dt($("#cmb_dept_id").val());
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _approveLeaveRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220;
	var title = 'Notice';
	var message = 'Are you sure you want to approve the selected leave request? <br /><br /> Note : <b>Approving request will set all approvers request status to approved.</b>';
	
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
				//$.post(base_url+'leave/_load_approve_leave_request',{e_id:e_id},
                $.post(base_url+'leave/_approve_request',{e_id:e_id},
					function(o){													
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}									   
				},"json");		
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

function _disApproveLeaveRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220;
	var title = 'Notice';
	var message = 'Are you sure you want to disapprove the selected leave request? <br /><br /> Note : <b>Disapproving request will set all approvers request status to disapproved.</b>';
	
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
				//$.post(base_url+'leave/_load_approve_leave_request',{e_id:e_id},
                $.post(base_url+'leave/_disapprove_request',{e_id:e_id},
					function(o){													
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}									   
				},"json");		
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

function _archiveLeaveType(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected leave type?';
	
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
				$.post(base_url+'leave/_load_archive_leave_type',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {														
						//load_leave_type_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _restoreLeaveRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = '<b>Restore</b> the selected archived leave request?';
	
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
				$.post(base_url+'leave/_load_restore_leave_request',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {								
						//load_leave_list_dt(o.es_id);
						load_leave_list_archives_dt($("#cmb_dept_id").val());
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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

function _restoreLeaveType(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected leave type?';
	
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
				$.post(base_url+'leave/_load_restore_leave_type',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {														
						load_leave_type_archives_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
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


function _revertLeaveRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220;
	var title = 'Notice';	
	var message = 'Are you sure you want to revert the selected leave request? <br /><br /> Note : <b>Reverting request will set all approvers request status to pending.</b>';

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
				$.post(base_url+'leave/_revert_request',{e_id:e_id},
					function(o){													
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}				
															   
				},"json");		
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

function _processIncentiveLeave(month, year, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Process incentive leave?<br /><br />Note : Data cannot be reprocess once saved';
	
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
				$(dialog_id).html(loading_image + " Processing incentive leave...");
				$(".ui-dialog-buttonset").hide();
				$.post(base_url+'leave/process_incentive_leave',{month:month, year:year},
					function(o){													
					events.onYes(o);				
															   
				},"json");		
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