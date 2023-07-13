
function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function show_request_change_schedule_form() {
	$('#request_button').hide();
	$('#request_change_schedule_form_wrapper').show();
	$("#request_change_schedule_form_wrapper").html(loading_image);	
	$.get(base_url+'change_schedule/ajax_add_new_change_schedule_request',{},
	function(o){
		$("#request_change_schedule_form_wrapper").html(o);	
	});	
	
}

function hide_request_change_schedule_form() {	
	$('#request_button').show();
	$('#request_change_schedule_form_wrapper').hide();
	clearFormError("employee_change_schedule_form");
	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function load_approved_change_schedule_list_dt() {	
	$.get(base_url + 'change_schedule/_load_approved_make_up_schedule_list_dt',{},function(o) {
		$('#change_schedule_list_dt_wrapper').html(o);		
	});	
}

function load_archive_change_schedule_list_dt() {	
	$.get(base_url + 'change_schedule/_load_archive_change_schedule_list_dt',{},function(o) {
		$('#change_schedule_list_dt_wrapper').html(o);		
	});	
}

function changeScheduleWithSelectedAction(status) {
	if(status){	
		_changeScheduleWithSelectedAction(status, {
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

function restoreChangeScheduleRequest(h_id) {
	_restoreChangeScheduleRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveChangeScheduleRequest(h_id) {
	_archiveChangeScheduleRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editChangeScheduleRequestForm(e_id) {
	_editChangeScheduleRequestForm(e_id, {
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

function load_pending_change_schedule_list_dt() {	
	$.get(base_url + 'change_schedule/_load_change_schedule_pending_list_dt',{},function(o) {
		$('#change_schedule_list_dt_wrapper').html(o);		
	});	
}
function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function load_show_specific_schedule() {
	var start_date 		= $('#date_from').val();
	var end_date 		= $('#date_to').val();	
	
	if(start_date != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'change_schedule/_load_employee_get_specific_schedule',{start_date:start_date,end_date:end_date},function(o) {		
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}