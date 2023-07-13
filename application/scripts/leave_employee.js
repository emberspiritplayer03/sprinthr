
function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function hide_request_leave_form() {	
	$('#request_leave_button').show();
	$('#request_leave_form_wrapper').hide();
	clearFormError();
	
}

function wrapperEditComputeDaysWithHalfDay(addHalfDay,deductHalfDay,outputId) {
	var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),addHalfDay,deductHalfDay);					
	$("#" + outputId).val(output);

}

function wrapperComputeDaysWithHalfDay(addHalfDay,deductHalfDay,outputId) {
	var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),addHalfDay,deductHalfDay);					
	$("#" + outputId).val(output);

}

function load_leave_list_dt() {	
	$.get(base_url + 'leave/_load_leave_list_dt',{},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_leave_list_archives_dt() {	
	$.post(base_url + 'leave/_load_leave_list_archives_dt',{},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_approved_leave_list_dt() {	
	$.get(base_url + 'leave/_load_approved_leave_list_dt',{},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_show_specific_schedule() {
	var start_date 		= $('#date_start').val();
	var end_date 		= $('#date_end').val();
	var h_employee_id 	= $('#employee_id').val();
	
	if(start_date != "" && h_employee_id != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'leave/load_get_specific_schedule',{start_date:start_date,end_date:end_date,h_employee_id:h_employee_id},function(o) {		
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}

function load_show_employee_leave_available() {	
	$('#show_leave_available_wrapper').html(loading_image);
		$.get(base_url + 'leave/_load_get_employee_leave_available',{},function(o) {		
			$('#show_leave_available_wrapper').html(o);
		});
}

function show_request_leave_form() {
	$('#request_leave_button').hide();
	$('#request_leave_form_wrapper').show();
	$("#request_leave_form_wrapper").html(loading_image);	
	$.get(base_url+'leave/ajax_add_new_leave_request',{},
	function(o){
		$("#request_leave_form_wrapper").html(o);	
	});	
	
}

function restoreLeaveRequest(h_id) {
	_restoreLeaveRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveWithSelectedAction() {
	_archiveWithSelectedAction({
		onYes: function() {					
			$("#chkActionSub").val("");							
		}, 
		onNo: function(){				
			$("#chkActionSub").val("");				
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});		
}

function pendingLeaveWithSelectedAction() {
	_pendingLeaveWithSelectedAction({
		onYes: function() {	
			$("#chkAction").val("");							
		}, 
		onNo: function(){
			$("#chkAction").val("");				
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


function archiveLeaveRequest(h_id) {
	_archiveLeaveRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editLeaveRequestForm(c_leave_id) {
	_editLeaveRequestForm(c_leave_id, {
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

function computeDaysWithHalfDay(start,end,addHalfDay,deductHalfDay) {	
	var start = new Date(start);	
	var end = new Date(end);
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;  //=> 8.525845775462964
	}else {
		var length = 0;
	}
	
	//Halfday	
	var total = 0;
	if($('#' + addHalfDay).is(':checked')){total = total - 0.5;}
	if($('#' + deductHalfDay).is(':checked')){total = total - 0.5;}
	
	length = length + total;
	
	return length;
}