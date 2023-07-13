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


function show_request_ob_form() {
	$('#request_button').hide();
	$('#request_ob_form_wrapper').show();
	$("#request_ob_form_wrapper").html(loading_image);	
	$.get(base_url+'ob/ajax_add_new_ob_request',{},
	function(o){
		$("#request_ob_form_wrapper").html(o);	
	});	
	
}

function load_show_specific_schedule() {
	var start_date 		= $('#date_start').val();
	var end_date 		= $('#date_end').val();	
	
	if(start_date != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'ob/load_ob_get_specific_schedule',{start_date:start_date,end_date:end_date},function(o) {		
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

function hide_request_ob_form() {	
	$('#request_button').show();
	$('#request_ob_form_wrapper').hide();
	clearFormError("employee_ob_form");
	
}

function clearFormError(form_id)
{
	$("#" + form_id).validationEngine('hide'); 	
}


function load_pending_ob_list_dt() {	
	$.get(base_url + 'ob/_load_pending_ob_list_dt',{},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}

function load_approved_ob_list_dt() {	
	$.get(base_url + 'ob/_load_approved_ob_list_dt',{},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}


function load_archive_ob_list_dt() {	
	$.get(base_url + 'ob/_load_archive_ob_list_dt',{},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}

function restoreObRequest(h_id) {
	_restoreObRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editOBRequestForm(ob_id) {
	_editOBRequestForm(ob_id, {
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

function archiveObRequest(h_id) {
	_archiveObRequest(h_id, {
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