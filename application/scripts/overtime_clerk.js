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

/*function show_request_overtime_form_clerk() {
	$('#department').hide();
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	createFormToken();
}*/


function createFormToken() {
	$.post(base_url + 'overtime/_load_token',{},function(o){
		$('.form_token').val(o.token);
	},'json');
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function show_request_overtime_form() {
	$('#department').hide();
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	createFormToken();
}

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
	var start_date 		= $('#start_date').val();
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

function change_overtime_request_status() {
	var a 	 	= $('#change_status_ck').val();
	var h_id 	= $('#h_ckdt_id').val();
	var status 	= 'Pending';
	switch(a) {
		case 'Approve': { status = 'Approve'; break; }
		case 'Disapproved'	: { status = 'Disapproved'; break; }
		default: {
			status = 'Pending'; break;
		}
	}

	_changeOvertimeRequestStatus(h_id,status, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});
	
	$('#change_status_ck').val('');
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



/*
	RECONSTRUCTED OVERTIME MODULE (CLERK) :
*/


function datatable_loader(sidebar) {
	if(sidebar == 1) {
		load_pending_overtime_list_dt();
	} else if(sidebar == 2) {
		load_approved_overtime_list_dt();
	} else if(sidebar == 3) {
		load_archived_overtime_list_dt();
	}
}

function load_pending_overtime_list_dt() {	
	$.get(base_url + 'overtime/_load_pending_overtime_list_dt',{},function(o) {
		$('#overtime_list_dt').html(o);		
	});	
}

function load_approved_overtime_list_dt() {	
	$.get(base_url + 'overtime/_load_approved_overtime_list_dt',{},function(o) {
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
	$.get(base_url + 'overtime/_load_archived_overtime_list_dt',{},function(o) {
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

function archiveOvertimeRequest(h_id) {
	_archiveOvertimeRequest(h_id, {
		onYes: function() {
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
















