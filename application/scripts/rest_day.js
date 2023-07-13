function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function show_request_rest_day_form() {
	$('#request_button').hide();
	$('#request_rest_day_form_wrapper').show();
	$("#request_rest_day_form_wrapper").html(loading_image);	
	$.get(base_url+'rest_day/ajax_add_new_rest_day_request',{},
	function(o){
		$("#request_rest_day_form_wrapper").html(o);	
	});	
	
}

function hide_request_rest_day_form() {	
	$('#request_button').show();
	$('#request_rest_day_form_wrapper').hide();
	clearFormError("employee_rest_day_form");
}

function load_show_specific_schedule() {
	if($('#date_from').val()){
		var start_date 		= $('#date_from').val();
		var end_date 		= $('#date_to').val();	
	}else{
		var start_date 		= $('#edit_date_from').val();
		var end_date 		= $('#edit_date_to').val();	
	}
	if(start_date != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'rest_day/_load_employee_get_specific_schedule',{start_date:start_date,end_date:end_date},function(o) {		
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}

function load_pending_rest_day_list_dt() {	
	$.get(base_url + 'rest_day/_load_rest_day_pending_list_dt',{},function(o) {
		$('#rest_day_list_dt_wrapper').html(o);		
	});	
}

function load_archive_rest_day_list_dt() {	
	$.get(base_url + 'rest_day/_load_archive_rest_day_list_dt',{},function(o) {
		$('#rest_day_list_dt_wrapper').html(o);		
	});	
}

function load_approved_rest_day_list_dt() {	
	$.get(base_url + 'rest_day/_load_rest_day_approved_list_dt',{},function(o) {
		$('#rest_day_list_dt_wrapper').html(o);		
	});	
}

function editRestDayRequestForm(e_id) {
	_editRestDayRequestForm(e_id, {
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

function restDayWithSelectedAction(status) {
	if(status){	
		_restDayWithSelectedAction(status, {
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

function archiveRestDayRequest(h_id) {
	_archiveRestDayRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreRestDayRequest(h_id) {
	_restoreRestDayRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}