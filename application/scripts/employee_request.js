function closeTheDialog() {
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_attendance');
}

function employeeOvertimeRequestScripts() {
	$("#tabs").tabs();
	$(".load-data").click(function(){
		var action = $(this).attr("id");
		load_overtime_list_dt(action);
	});

	$("#btn-file-overtime").click(function(){
		fileOvertime();
	});

	load_overtime_list_dt("pending");
}

function employeeOfficialBusinessRequestScripts() {
	$("#tabs").tabs();

	$(".load-data").click(function(){
		var action = $(this).attr("id");
		load_ob_list_dt(action);
	});

	$("#btn-file-ob").click(function(){
		fileOfficialBusiness();
	});

	load_ob_list_dt("pending");
}

function load_department_sections(eid) {
	$.get(base_url + '',{eid:eid},function(o){
		$('#li-sections').html(o);	
	});

}

function employeeLeaveRequestScripts() {
	$("#tabs").tabs();

	$(".load-data").click(function(){
		var action = $(this).attr("id");
		load_leave_list_dt(action);
	});

	$("#btn-file-leave").click(function(){
		fileLeave();
	});

	load_leave_list_dt("pending");
}

function requestForApprovalScripts() {
	$("#tabs").tabs();
	$(".load-data").click(function(){
		var action = $(this).attr("id");
		load_request_for_approval_list_dt(action);
	});

	load_request_for_approval_list_dt("overtime");
}

function load_overtime_list_dt(action) {
	if(action == "approved") {
		var obj_wrapper = $(".overtime-approved-list-dt");
	}else if(action == "disapproved") {
		var obj_wrapper = $(".overtime-disapproved-list-dt");
	}else{
		var obj_wrapper = $(".overtime-pending-list-dt");
	}

	obj_wrapper.html(loading_image);	
	$.get(base_url + 'overtime/_load_data',{action:action},function(o) {
		obj_wrapper.html(o).hide();	
		obj_wrapper.fadeIn(1000);
	});	
}

function load_request_for_approval_list_dt(action) {
	if(action == "ob") {
		var obj_wrapper = $(".ob-pending-list-dt");
	}else if(action == "leave") {
		var obj_wrapper = $(".leave-pending-list-dt");
	}else{
		var obj_wrapper = $(".overtime-pending-list-dt");
	}

	obj_wrapper.html(loading_image);	
	$.get(base_url + 'dashboard/_load_for_approval_data',{action:action},function(o) {
		obj_wrapper.html(o).hide();	
		obj_wrapper.fadeIn(1000);
	});	
}

function load_ob_list_dt(action) {
	if(action == "approved") {
		var obj_wrapper = $(".ob-approved-list-dt");
	}else if(action == "disapproved") {
		var obj_wrapper = $(".ob-disapproved-list-dt");
	}else{
		var obj_wrapper = $(".ob-pending-list-dt");
	}

	obj_wrapper.html(loading_image);	
	$.get(base_url + 'ob/_load_data',{action:action},function(o) {
		obj_wrapper.html(o).hide();	
		obj_wrapper.fadeIn(1000);
	});	
}

function load_leave_list_dt(action) {
	if(action == "approved") {
		var obj_wrapper = $(".leave-approved-list-dt");
	}else if(action == "disapproved") {
		var obj_wrapper = $(".leave-disapproved-list-dt");
	}else{
		var obj_wrapper = $(".leave-pending-list-dt");
	}

	obj_wrapper.html(loading_image);	
	$.get(base_url + 'leave/_load_data',{action:action},function(o) {
		obj_wrapper.html(o).hide();	
		obj_wrapper.fadeIn(1000);
	});	
}

function checkAvailableLeaveCredit(eid) {
	$("#is_paid_wrapper").html(loading_image);
	$.post(base_url + 'leave/_ajax_load_available_leave_credit',{eid:eid},function(o) {
		$("#is_paid_wrapper").html(o).hide();	
		$("#is_paid_wrapper").fadeIn(1000);
	});	
}

function viewOvertimeApprovers(request_id) {
	_viewOvertimeApprovers(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			$('#pending').trigger('click');
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function viewLeaveApprovers(request_id) {
	_viewLeaveApprovers(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			$('#pending').trigger('click');
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function viewObApprovers(request_id) {
	_viewObApprovers(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			$('#pending').trigger('click');
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function fileOvertime() {
	_fileOvertime({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			$('#pending').trigger('click');
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function fileOfficialBusiness() {
	_fileOfficialBusiness({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			$('#pending').trigger('click');
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function fileLeave() {
	_fileLeave({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			$('#pending').trigger('click');
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function approveRequest(eid) {
	_approveRequest(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			_count_new_employee_notifications();
			if(o.request_type == "ot") {
				$('#overtime').trigger('click');
			}else if(o.request_type == "lv") {
				$('#leave').trigger('click');
			}else{
				$('#ob').trigger('click');
			}
			
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function disapproveRequest(eid) {
	_disapproveRequest(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			_count_new_employee_notifications();
			if(o.request_type == "ot") {
				$('#overtime').trigger('click');
			}else if(o.request_type == "lv") {
				$('#leave').trigger('click');
			}else{
				$('#ob').trigger('click');
			}
			
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function eNotificationApproveDisapproveRequest(btnValue) {
	_eNotificationApproveDisapproveRequest(btnValue,{
		onYes: function(o) {	
			if(o.is_success) {																				
				closeDialog('#' + DIALOG_CONTENT_HANDLER);					
				$(".approvers-request-details").html("<div class='alert alert-success'>" + o.message + "</div>");	
			}else {
				$(".errContainer").html("<br /><div class='alert alert-box alert-error'>" + o.message + "</div>");							   
				closeDialog('#' + DIALOG_CONTENT_HANDLER);		
			}		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editOvertime(eid) {
    _editOvertime(eid, {
        onSaved: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);  
	        if(o.is_saved){
	        	showOkDialog(o.message);
	        	load_overtime_list_dt("pending");
	        }else{
	        	 showOkDialog(o.message);
	        }              
        },
        onSaving: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Saving...');
        },
        onLoading: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Loading...');
        },
        onBeforeSave: function(o) {

        },
        onError: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showOkDialog(o.message);
        }
    });
}

function approverEditRequest(eid,type) {
    _approverEditRequest(eid,type, {
        onSaved: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);  
	        if(o.is_saved){
	        	if( o.request == 'ot' ){
	        		showOkDialog(o.message);
	        		_count_new_employee_notifications();
					$('#overtime').trigger('click');
				}
	        }else{
	        	 showOkDialog(o.message);
	        }              
        },
        onSaving: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Saving...');
        },
        onLoading: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Loading...');
        },
        onBeforeSave: function(o) {

        },
        onError: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showOkDialog(o.message);
        }
    });
}

function withSelectedAction(status,form) {
	if(status){	
		_withSelectedAction(status,form, {
			onYes: function() {
				//closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
				
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}



