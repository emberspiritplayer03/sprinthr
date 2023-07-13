function enableDisableWithSelected(form){
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function withSelectedAction(status) {
	if(status){	
		_withSelectedAction(status, {
			onYes: function() {
				//closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
				location.reload();
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function editOvertime(employee_id, date) {
    _editOvertime(employee_id, date, {
        onSaved: function(o) {
            var query = window.location.search;
            $.get(base_url + 'overtime/period'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });

            dialogOkBox(o.message,{});
            closeDialog('#' + DIALOG_CONTENT_HANDLER);	        
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

function viewOvertimeRequestApprovers(eid) {
	_viewOvertimeRequestApprovers(eid, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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

function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function load_overtime_list_dt() {
	$.get(base_url + 'overtime/_load_overtime_list_dt',{},function(o) {
		$('#overtime_list_dt').html(o);
	});
}

function load_show_employee_request_approvers() {
	var h_employee_id 	= $('#h_employee_id').val();	
	if(h_employee_id != ""){
		$('#show_request_approvers_wrapper').html(loading_image);
		$.post(base_url + 'overtime/_load_get_employee_request_approvers',{h_employee_id:h_employee_id},function(o) {		
			$('#show_request_approvers_wrapper').html(o);
		});
	}
}

function load_custom_overtime_list( date_from, date_to, frequency_id = 1 ) {
	$('#show_request_approvers_wrapper').html(loading_image);
	$.get(base_url + 'overtime/_load_custom_overtime_list',{date_from:date_from,date_to:date_to,frequency_id:frequency_id},function(o) {		
		$('#custom_overtime_list_dt_wrapper').html(o);
	});
}

/*
function editOvertimeRequestForm(h_id) {
	_editOvertimeRequestForm(h_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			//load_overtime_list_dt();
			load_overtime_list_dt_withselectionfilter();
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
*/
function deleteOvertimeRequest(h_id,is_approved) {
	if(is_approved == "Pending" ) {
		_deleteOvertimeRequest(h_id, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});	
	} else { showOkDialog('Selected entry cannot be edited',{}); }
}

/*function show_request_overtime_form_clerk() {
	$('#department').hide();
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	createFormToken();
}*/

function disApproveOvertimeRequest(h_id) {
	_disApproveOvertimeRequest(h_id, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_approved_overtime_list_dt();
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function approveOvertimeRequest(h_id) {
	_approveOvertimeRequest(h_id, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_pending_overtime_list_dt();
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function approveOvertime(oid, employee_id, date) {
    _approveOvertime(oid, employee_id, date, {
        onYes: function(o) {

            var query = window.location.search;
            $.get(base_url + 'overtime/period'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });

            dialogOkBox(o.message,{});
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
        },
        onNo: function(){
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
        }
    });
}

function disapproveOvertime(oid, employee_id, date) {
    _disapproveOvertime(oid, employee_id, date, {
        onYes: function(o) {

            var query = window.location.search;
            $.get(base_url + 'overtime/period'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });

            dialogOkBox(o.message,{});
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
        },
        onNo: function(){
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
        }
    });
}

function setAsPendingOvertime(oid,employee_id, date) {
    _setAsPendingOvertime(oid, employee_id, date, {
        onYes: function(o) {
            var query = window.location.search;
            $.get(base_url + 'overtime/period'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });

            dialogOkBox(o.message,{});
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
        },
        onNo: function(){
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
        }
    });
}

function disApproveCustomOvertime(eid) {
	_disApproveCustomOvertime(eid, {
		onYes: function(o) {			
			var start_date = $("#date_from").val();
			var end_date   = $("#date_to").val();

			dialogOkBox(o.message,{});
			load_custom_overtime_list(start_date, end_date);
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function approveCustomOvertime(eid) {
	_approveCustomOvertime(eid, {
		onYes: function(o) {			
			var start_date = $("#date_from").val();
			var end_date   = $("#date_to").val();

			dialogOkBox(o.message,{});
			load_custom_overtime_list(start_date, end_date);
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function createFormToken() {
	$.post(base_url + 'overtime/_load_token',{},function(o){
		$('.form_token').val(o.token);
	},'json');
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

/*function show_request_overtime_form() {
	$('#department').hide();
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	createFormToken();
}*/

function cancel_request_overtime_form() {
	$('#department').show();
	$('#request_overtime_button').show();
	$('#request_overtime_form_wrapper').hide();
	$('#request_overtime_form').validationEngine("hide");
	$('#request_overtime_hideshow_form').validationEngine("hide");
}

function load_show_overtime_details(h_id,h_employee_id) {
	$('#overtime_back').show();
	$('#overtime_details_wrapper').show();
	$('#action_link').hide();
	$('#overtime_list_dt').hide();
	
	$.post(base_url + 'overtime/_load_show_overtime_details',{h_id:h_id,h_employee_id:h_employee_id},function(o) {
		$('#overtime_details_wrapper').html(o);
	});
}

function back_to_list() {
	$('#overtime_back').hide();
	$('#overtime_details_wrapper').hide();
	$('#overtime_list_dt').show();
	

	$('#action_link').show();
	
}

function load_show_specific_schedule() {
	var start_date 		= $('#start_date_hideshow').val();
	var h_employee_id 	= $('#h_employee_id').val();
	
	if(start_date != "" && h_employee_id != "")
	$('#_schedule_loading_wrapper').html(loading_image);
	$.post(base_url + 'overtime/load_get_specific_schedule',{start_date:start_date,h_employee_id:h_employee_id},function(o) {
		$('#_schedule_loading_wrapper').html('');
		$('#show_specific_schedule_wrapper').html(o);
	});
}

function load_show_specific_schedule_edit() {
	var start_date 		= $('#start_date_edit').val();
	var h_employee_id 	= $('#h_employee_id_edit').val();
	
	if(start_date != "" && h_employee_id != "")
	$('#_schedule_loading_wrapper_edit').html(loading_image);
	$.post(base_url + 'overtime/load_get_specific_schedule',{start_date:start_date,h_employee_id:h_employee_id},function(o) {
		$('#_schedule_loading_wrapper_edit').html('');
		$('#show_specific_schedule_wrapper_edit').html(o);
	});
}

function change_overtime_request_status(status) {
	if(status){	
		_changeOvertimeRequestStatus(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function check_ckdt() {
	$('#check_all_overtime').attr('checked',false)
	$('#h_ckdt_id').val(getConcatCkvalue());
	if(getConcatCkvalue()) {
		$('.overtime_action_link').show();
	} else {
		$('.overtime_action_link').hide();
	}
}


// Datatable loader with filter function
function load_overtime_list_dt_withselectionfilter() {
	var h_department_id = $('#department_id').val();
	$.post(base_url + 'overtime/_load_overtime_list_dt_withselectionfilter',{h_department_id:h_department_id},function(o) {
		$('#overtime_list_dt').html(o);
	});
}





/*
	RECONSTRUCTED OVERTIME MODULE (CLERK) :
*/


function datatable_loader(sidebar) {
	if(sidebar == 1) {
		load_pending_overtime_list_dt();
	} else if(sidebar == 2) {
		load_approved_overtime_list_dt();
	} else if( sidebar == 3) {
		load_overtime_history_list_dt();
	} else if(sidebar == 4) {
		load_archived_overtime_list_dt();
	}
}

function load_pending_overtime_list_dt() {	
	var h_department_id = $('#department_id').val();
	$.post(base_url + 'overtime/_load_pending_overtime_list_dt',{h_department_id:h_department_id},function(o) {
		$('#overtime_list_dt').html(o);		
	});	
}

function load_approved_overtime_list_dt() {	
	sidebar = 2;
	var h_department_id = $('#department_id').val();
	$.post(base_url + 'overtime/_load_generic_overtime_list_dt',{h_department_id:h_department_id,sidebar:sidebar},function(o) {
		$('#overtime_list_dt').html(o);		
	});	
}

function load_overtime_history_list_dt() {	
	sidebar = 3;
	var h_department_id = $('#department_id').val();
	$.post(base_url + 'overtime/_load_overtime_history_list_dt',{h_department_id:h_department_id,sidebar:sidebar},function(o) {
		$('#overtime_list_dt').html(o);		
	});	
}

function load_archived_overtime_list_dt() {	
	sidebar = 4;
	var h_department_id = $('#department_id').val();
	$.post(base_url + 'overtime/_load_archived_overtime_list_dt',{h_department_id:h_department_id,sidebar:sidebar},function(o) {
		$('#overtime_list_dt').html(o);		
	});	
}

function show_request_overtime_form() {
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	$("#request_overtime_form_wrapper").html(loading_image);	
	$.get(base_url+'overtime/ajax_add_new_overtime_request',{},
	function(o){
		$("#request_overtime_form_wrapper").html(o);	
	});	
	
}

function hide_request_overtime_form() {	
	$('#request_overtime_button').show();
	$('#request_overtime_form_wrapper').hide();
	clearFormError();
	
}

function clearFormError()
{
	$("form").validationEngine('hide'); 	
}

function editOvertimeRequestForm(h_id) {
	_editOvertimeRequestForm(h_id,{
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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

function archiveOvertimeRequest(h_id,action) {
	_archiveOvertimeRequest(h_id, {
		onYes: function() {
			datatable_loader(action);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


function load_show_overtime_history_details(h_employee_id) {
	$('#overtime_history_details_wrapper').html(loading_image);
	$.post(base_url + 'overtime/load_show_overtime_history_details',{h_employee_id:h_employee_id},function(o){ 
		$('#overtime_history_details_wrapper').html(o);
	});
}

function load_hide_overtime_details() {	
	$('#overtime_history_details_wrapper').html("");
}

function load_employee_overtime_history_list_dt(h_employee_id) {
	$('#employee_overtime_history_dt_wrapper').html(loading_image);
	$.post(base_url + 'overtime/load_employee_overtime_history_list_dt',{h_employee_id:h_employee_id},function(o){ 
		$('#employee_overtime_history_dt_wrapper').html(o);
	});
}

function restoreOvertimeRequest(h_id) {
	_restoreOvertimeRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function clear_import_error_notifs() {
	$('#error_notifs').hide();
	$.post(base_url + 'overtime/_load_clear_import_error',{},function(o){});	
}

function showEditCustomOvertime(eid) {
	_showEditCustomOvertime(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});

			var date_from = $("#date_from").val();
			var date_to   = $("#date_to").val();
          	load_custom_overtime_list(date_from, date_to);			
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