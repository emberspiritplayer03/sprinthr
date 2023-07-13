function _editOvertime(employee_id, date, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Edit Overtime ';
    var width = 300;
    var height = 'auto';

    if (typeof events.onLoading == "function") {
        events.onLoading();
    }

    $.get(base_url + 'overtime/ajax_edit_overtime_form', {employee_id:employee_id, date:date}, function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true,
            form_id: '#edit_overtime_form'
        });

        $('#edit_overtime_form').ajaxForm({
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

function _viewOvertimeRequestApprovers(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Overtime Request Approvers';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	var start_cutoff = $("#from_period").val();
	var end_cutoff   = $("#to_period").val();

	$.get(base_url + 'overtime/ajax_view_overtime_request_approvers', {eid:eid}, function(data) {
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

/*
function _editOvertimeRequestForm(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Overtime Request';
	var width = 550;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'overtime/_load_edit_overtime_request',{h_id:h_id},
	function(o){ 
		$('#edit_request_overtime_form_modal_wrapper').html(o); 
		dialogGeneric('#edit_request_overtime_form_modal_wrapper', {
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
				//closeDialog(dialog_id);
				closeDialogBox('#edit_request_overtime_form_modal_wrapper','#edit_request_overtime_form');
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
*/

function _deleteOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive overtime request?';
	
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
				$.post(base_url + 'overtime/_load_delete_overtime_request',{h_id:h_id},function(o) { });
				//load_overtime_list_dt();
				load_overtime_list_dt_withselectionfilter();
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

function _disApproveOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180;
	var title = 'Notice';
	var message = 'Are you sure you want to disapprove the selected request?';
	
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
				$.post(base_url + 'overtime/_load_disapprove_overtime_request',{h_id:h_id},
				function(o) { 
					//load_overtime_list_dt();
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}
				
				});
				
				
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

function _disApproveCustomOvertime(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180;
	var title = 'Notice';
	var message = 'Are you sure you want to disapprove the selected custom overtime?';
	
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
				$.post(base_url + 'overtime/_ajax_disapprove_custom_overtime',{eid:eid},
				function(o) { 
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}
				
				},'json');
				
				
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

function _approveOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180;
	var title = 'Notice';
	var message = 'Are you sure you want to approve the selected request?';
	
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
				$.post(base_url + 'overtime/_load_approve_overtime_request',{h_id:h_id},
				function(o) { 
					//load_overtime_list_dt();
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}
				
				});
				
				
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

function _approveCustomOvertime(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180;
	var title = 'Notice';
	var message = 'Are you sure you want to approve the selected custom overtime?';
	
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
				$.post(base_url + 'overtime/_ajax_approve_custom_overtime',{eid:eid},
				function(o) { 
					//load_overtime_list_dt();
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}
				
				},'json');
				
				
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

function _setAsPendingOvertime(oid, employee_id, date, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var width = 350 ;
    var height = 180;
    var title = 'Notice';
    var message = 'Are you sure you want to set this overtime as pending?';

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
                $.post(base_url + 'overtime/_set_pending_overtime',{oid:oid,employee_id:employee_id, date:date},
                    function(o) {
                        //load_overtime_list_dt();

                        if (typeof events.onYes == "function") {
                            events.onYes(o);
                        }

                    });


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

function _disapproveOvertime(oid, employee_id, date, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var width = 350 ;
    var height = 220;
    var title = 'Notice';    
    var message = 'Are you sure you want to disapprove the selected overtime request? <br /><br /> Note : <b>Disapproving request will set all approvers request status to disapproved.</b>';

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
                $.post(base_url + 'overtime/_disapprove_overtime',{oid:oid, employee_id:employee_id, date:date},
                    function(o) {
                        //load_overtime_list_dt();

                        if (typeof events.onYes == "function") {
                            events.onYes(o);
                        }

                    });


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

function _approveOvertime(oid, employee_id, date, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var width = 350 ;
    var height = 220;
    var title = 'Notice';
    var message = 'Are you sure you want to approve the selected overtime request? <br /><br /> Note : <b>Approving request will set all approvers request status to approved.</b>';

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
                $.post(base_url + 'overtime/_approve_overtime',{oid:oid, employee_id:employee_id, date:date},
                    function(o) {
                        //load_overtime_list_dt();

                        if (typeof events.onYes == "function") {
                            events.onYes(o);
                        }

                    });


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

function _changeOvertimeRequestStatus(status, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	
	if(status == 'Approved') {
		var message = '<b>Approve</b> the selected request(s)?';
	}else if(status == 'Disapproved') {
		var message = '<b>Disapprove</b> the selected request(s)?';
	}else if(status == 'Archive'){
		var message = '<b>Send to archive</b> the selected request(s)?';
	}else if(status == 'Restore Archive'){
		var message = '<b>Restore</b> the selected archived request?';
	}else {
		status = 'Pending';
		var message = '<b>Set to pending status</b> the selected request(s)?';	
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
				$.post(base_url + 'overtime/load_change_overtime_request_status',$('#withSelectedAction').serialize()+'&status='+status,		
				function(o) { 
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});																				
						datatable_loader(o.load_dt);
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}	
					
					$('.overtime_action_link').hide();
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



/*
	RECONSTRUCTED
*/
function _editOvertimeRequestForm(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Overtime Request';
	var width = 600;
	var height = 'auto';
	
	var from_period = $('#from_period').val();
	var to_period	= $('#to_period').val();
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	

	$.post(base_url + 'overtime/_load_edit_overtime_request', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		
		
		$("#start_date_hideshow").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	: function(o) {
				load_show_specific_schedule();		
			},
			minDate		: from_period,
			maxDate		: to_period,
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
		
		
		$('#edit_request_overtime_form').validationEngine({scroll:false});		
		$('#edit_request_overtime_form').ajaxForm({
			success:function(o) {
				if (o.is_saved == 1) {
					if (typeof events.onSaved == "function") {
						datatable_loader(1);
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

function _archiveOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected overtime request?';
	
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
				$.post(base_url+'overtime/_load_archive_overtime_request',{h_id:h_id},
					function(o){													
					if(o.is_saved==1) {								
						//load_leave_list_dt(o.es_id);
						if (typeof events.onYes == "function") {
							events.onYes();
							datatable_loader(1);
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


function _restoreOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = '<b>Restore</b> the selected archived request?';
	
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
				$.post(base_url+'overtime/_load_restore_overtime_request',{h_id:h_id},
					function(o){													
					if(o.is_saved==1) {								
						//load_leave_list_dt(o.es_id);
						if (typeof events.onYes == "function") {
							events.onYes();
							datatable_loader(4);
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

function _withSelectedAction(status, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220
	var title = 'Notice';
	
	if(status == 'approve') {
		var message = 'Are you sure you want to approve the selected overtime request(s)? <br /><br /> Note : <b>Approving request will set all approvers request status to approved.</b>';
	}else if(status == 'disapprove'){
		var message = 'Are you sure you want to disapprove the selected overtime request(s)? <br /><br /> Note : <b>Disapproving request will set all approvers request status to disapproved.</b>';
	}else if(status == 'pending'){
		var message = 'Are you sure you want to set as pending the selected overtime request(s)? <br /><br /> Note : <b>Setting this as pending request will set all approvers request status to pending.</b>';
	}
	
	$("#action").val(status);
	
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
				$.post(base_url + 'overtime/_with_selected_action',$('#withSelectedAction').serialize(),		
				function(o) { 

					if(o.is_success==1) {							
						dialogOkBox(o.message,{});	
						
						/*if(status == 'archive'){																		
							load_pending_ob_list_dt();
						}else{
							load_archive_ob_list_dt();
						}*/
						
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

function _showEditCustomOvertime(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Custom Overtime';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'overtime/ajax_edit_custom_overtime', {eid:eid}, function(data) {
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

		$('#custom_overtime_start_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
		$('#custom_overtime_end_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});

		$('#edit_custom_overtime_form').validationEngine({scroll:false});		
		$('#edit_custom_overtime_form').ajaxForm({
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


	});
}
