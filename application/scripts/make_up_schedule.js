function enableDisableWithSelected(form){
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function makeUpScheduleWithSelectedAction(status) {
	if(status){	
		_makeUpScheduleWithSelectedAction(status, {
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
function show_request_make_up_schedule_form() {
	$('#request_button').hide();
	$('#request_make_up_schedule_form_wrapper').show();
	$("#request_make_up_schedule_form_wrapper").html(loading_image);	
	$.get(base_url+'make_up_schedule/ajax_add_new_make_up_schedule_request',{},
	function(o){
		$("#request_make_up_schedule_form_wrapper").html(o);	
	});	
	
}

function load_show_specific_schedule() {
	var start_date 		= $('#date_from').val();
	var end_date 		= $('#date_to').val();	
	
	if(start_date != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'make_up_schedule/_load_employee_get_specific_schedule',{start_date:start_date,end_date:end_date},function(o) {		
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}

function computeDays(start,end) {	
	var start = new Date(start);	
	var end = new Date(end);
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;  //=> 8.525845775462964
	}else {
		var length = 0;
	}	
	
	return length;
}

function hide_request_make_up_schedule_form() {	
	$('#request_button').show();
	$('#request_make_up_schedule_form_wrapper').hide();
	clearFormError("employee_make_up_schedule_form");
	
}

function clearFormError(form_id)
{
	$("#" + form_id).validationEngine('hide'); 	
}


function load_pending_make_up_schedule_list_dt() {	
	$.get(base_url + 'make_up_schedule/_load_make_up_schedule_pending_list_dt',{},function(o) {
		$('#make_up_schedule_list_dt_wrapper').html(o);		
	});	
}

function load_approved_make_up_schedule_list_dt() {	
	$.get(base_url + 'make_up_schedule/_load_approved_make_up_schedule_list_dt',{},function(o) {
		$('#make_up_schedule_list_dt_wrapper').html(o);		
	});	
}

function load_archive_make_up_schedule_list_dt() {	
	$.get(base_url + 'make_up_schedule/_load_archive_make_up_schedule_list_dt',{},function(o) {
		$('#make_up_schedule_list_dt_wrapper').html(o);		
	});	
}

function restoreMakeUpScheduleRequest(h_id) {
	_restoreMakeUpScheduleRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editMakeUpScheduleRequestForm(make_up_id) {
	_editMakeUpScheduleRequestForm(make_up_id, {
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

function wrapperComputeDays(outputId) {
	var output  = computeDays($("#edit_date_start").val(),$("#edit_date_end").val());					
	$("#" + outputId).val(output);

}

function archiveMakeUpScheduleRequest(h_id) {
	_archiveMakeUpScheduleRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}