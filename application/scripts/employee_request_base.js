function _viewOvertimeApprovers(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'View Approver(s)';
	var width = 555;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'overtime/ajax_view_overtime_approvers', {request_id:request_id}, function(data) {
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

function _editOvertime(eid, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Edit Overtime ';
    var width = 300;
    var height = 'auto';

    if (typeof events.onLoading == "function") {
        events.onLoading();
    }

    $.get(base_url + 'overtime/ajax_edit_overtime_form', {eid:eid}, function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true,
            form_id: '#edit_request_form'
        });

        $('#edit_request_form').ajaxForm({
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
            },
            dataType:'json',
            beforeSubmit: function() {
                if (typeof events.onSaving == "function") {
                    events.onSaving();
                }
                return true;
            }
        });
    });
}

function _approverEditRequest(eid, type, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Edit Request ';
    var width = 300;
    var height = 'auto';

    if (typeof events.onLoading == "function") {
        events.onLoading();
    }

    $.get(base_url + 'requests/ajax_edit_request_form', {eid:eid,type:type}, function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true,
            form_id: '#edit_request_form'
        });

        $('#edit_request_form').ajaxForm({
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
            },
            dataType:'json',
            beforeSubmit: function() {
                if (typeof events.onSaving == "function") {
                    events.onSaving();
                }
                return true;
            }
        });
    });
}

function _viewLeaveApprovers(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'View Approver(s)';
	var width = 555;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'leave/ajax_view_leave_approvers', {request_id:request_id}, function(data) {
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

function _viewObApprovers(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'View Approver(s)';
	var width = 555;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'ob/ajax_view_ob_approvers', {request_id:request_id}, function(data) {
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

function _fileOvertime(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'File Overtime';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'overtime/ajax_file_overtime', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#file_overtime_form').validationEngine({scroll:false});		
		$('#file_overtime_form').ajaxForm({
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
				$("#token").val(o.token);
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}				
				return true;
			}
		});	

		$('#date_of_overtime').datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true
		});
		$('#start_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
		$('#end_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});	
	});
}

function _fileOfficialBusiness(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'File Official Business';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'ob/ajax_file_ob', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#file_ob_form').validationEngine({scroll:false});		
		$('#file_ob_form').ajaxForm({
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
				$("#token").val(o.token);
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}				
				return true;
			}
		});	

		$("#ob_date_from").datepicker({
			dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() { 
				$("#ob_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			}
		});	
		
		$("#ob_date_to").datepicker({
			dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() { 
			
			}
		});	
	});
}

function _fileLeave(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'File Leave';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'leave/ajax_file_leave', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#file_leave_form').validationEngine({scroll:false});		
		$('#file_leave_form').ajaxForm({
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
				$("#token").val(o.token);
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}				
				return true;
			}
		});	

		$("#date_start").datepicker({
		    dateFormat:'yy-mm-dd',
		    changeMonth:true,
		    changeYear:true,
		    showOtherMonths:true,
		    onSelect  :function() {       
		      $("#date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
		      var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),"start_halfday","end_halfday");
		      $("#number_of_days").val(output);
		      load_show_specific_schedule();      
		    }
		});
		    
		$("#date_end").datepicker({
		    dateFormat:'yy-mm-dd',
		    changeMonth:true,
		    changeYear:true,
		    showOtherMonths:true,
		    onSelect  :function() { 
		      var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),"start_halfday","end_halfday");
		      $("#number_of_days").val(output);
		      load_show_specific_schedule();
		    }
		});

		$("#leave_id").change(function(){
			var eid = $(this).val();
			checkAvailableLeaveCredit(eid);
		});

	});
}

function _approveRequest(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Confirmation';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'dashboard/ajax_approve_request', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#approve_request_form').validationEngine({scroll:false});		
		$('#approve_request_form').ajaxForm({
			success:function(o) {
				if (o.is_success) {
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
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}				
				return true;
			}
		});	

		
	});
}

function _disapproveRequest(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Confirmation';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'dashboard/ajax_disapprove_request', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#disapprove_request_form').validationEngine({scroll:false});		
		$('#disapprove_request_form').ajaxForm({
			success:function(o) {
				if (o.is_success) {
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
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}				
				return true;
			}
		});	

		
	});
}

function _eNotificationApproveDisapproveRequest(btnValue,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Proceed with selected action?';
	
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
				showLoadingDialog('Saving...');

				//Create status input - post
				var formClosest = $("#approve-disapprove-request");
			    var tempElement = $("<input name='status' type='hidden'/>");
			    if( btnValue == 'Approve' ){
			      tempElement.val('Approve');
			      tempElement.appendTo(formClosest);
			    }else if(btnValue == 'Disapprove'){
			      tempElement.val('Disapprove');
			      tempElement.appendTo(formClosest);
			    }

				$.post(base_url+'requests/_e_approve_request',$("#approve-disapprove-request").serialize(),
					function(o){	
					$("#token").val(o.token);	
					tempElement.remove();      
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

function _withSelectedAction(status, form, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220
	var title = 'Notice';
	
	if(status == 'approve') {
		var message = 'Are you sure you want to approve the selected request(s)? ';
	}else if(status == 'disapprove'){
		var message = 'Are you sure you want to disapprove the selected request(s)? ';
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
				disablePopUp();
                $dialog.dialog("destroy");
                $dialog.hide();  
                showLoadingDialog('Saving..'); 
				$.post(base_url + 'dashboard/_with_selected_action',$('#'+form).serialize(),		
				function(o) { 

					if(o.is_success==1) {							
						dialogOkBox(o.message,{});	
						_count_new_employee_notifications();
						if(o.request_type == "ot") {
							$('#overtime').trigger('click');
						}else if(o.request_type == "lv") {
							$('#leave').trigger('click');
						}else{
							$('#ob').trigger('click');
						}
						
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {				
						dialogOkBox(o.message,{});									
					}						
					//closeDialog('#' + DIALOG_CONTENT_HANDLER);
				},"json");
				
				//load_overtime_list_dt();				
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
