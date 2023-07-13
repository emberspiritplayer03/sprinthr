function enableDisableWithSelected(form){
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function obWithSelectedAction(status) {
	if(status){	
		_obWithSelectedAction(status, {
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

function show_request_ac_form() {
	$('#request_button').hide();
	$('#request_ac_form_wrapper').show();
	$("#request_ac_form_wrapper").html(loading_image);	
	$.get(base_url+'attendance_correction/ajax_add_new_ac_request',{},
	function(o){
		$("#request_ac_form_wrapper").html(o);	
	});	
	
}

function load_show_specific_schedule() {
	var attendance_date	= $('#attendance_date').val();
	
	if(attendance_date != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'attendance_correction/load_get_specific_schedule',{attendance_date:attendance_date},function(o) {		
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

function hide_request_ac_form() {	
	$('#request_button').show();
	$('#request_ac_form_wrapper').hide();
	clearFormError("employee_ac_form");
	
}

function clearFormError(form_id)
{
	$("#" + form_id).validationEngine('hide'); 	
}


function load_pending_ac_list_dt() {	
	$.get(base_url + 'attendance_correction/_load_pending_ac_list_dt',{},function(o) {
		$('#ac_list_dt_wrapper').html(o);		
	});	
}

function load_approved_ac_list_dt() {	
	$.get(base_url + 'attendance_correction/_load_approved_ac_list_dt',{},function(o) {
		$('#ac_list_dt_wrapper').html(o);		
	});	
}


function load_archive_ac_list_dt() {	
	$.get(base_url + 'attendance_correction/_load_archive_ac_list_dt',{},function(o) {
		$('#ac_list_dt_wrapper').html(o);		
	});	
}

function restoreAcRequest(h_id) {
	_restoreAcRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editAcRequestForm(ob_id) {
	_editAcRequestForm(ob_id, {
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

function archiveAcRequest(h_id) {
	_archiveAcRequest(h_id, {
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