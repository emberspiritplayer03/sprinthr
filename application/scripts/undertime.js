function datatable_loader(sidebar) {
	if(sidebar == 1) {
		load_pending_undertime_list_dt();
	} else if(sidebar == 2) {
		load_approved_undertime_list_dt();
	} else if(sidebar == 3) {
		load_archive_undertime_list_dt();
	}
}

function load_pending_undertime_list_dt() {	
	$('#undertime_list_dt_wrapper').html(loading_image);
	$.get(base_url + 'undertime/_load_undertime_list_dt',{},function(o) {
		$('#undertime_list_dt_wrapper').html(o);		
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

function undertimeWithSelectedAction(status,mode) {
	if(status){	
		_undertimeWithSelectedAction(status, {
			onYes: function(o) {
				dialogOkBox(o.message,{});	
				$("#chkAction").attr('disabled',true);				
				$('#chkAction').val('');
				$('.overtime_action_link').hide();
				datatable_loader(mode);
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function show_request_undertime_form(date_from,date_to) {
	$('#request_undertime_button').hide();
	$('#request_undertime_form_wrapper').show();
	$("#request_undertime_form_wrapper").html(loading_image);	
	$.post(base_url+'undertime/ajax_add_new_undertime_request',{date_from:date_from,date_to:date_to},
	function(o){
		$("#request_undertime_form_wrapper").html(o);	
	});	
	
}

function load_show_specific_schedule() {
	var h_employee_id	= $('#h_employee_id').val();
	var start_date 		= $('#date_of_undertime').val();
	var end_date 		= $('#date_end').val();	
	
	if(start_date != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'undertime/_load_employee_get_specific_schedule',{start_date:start_date,h_employee_id:h_employee_id},function(o) {		
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

function hide_request_undertime_form() {	
	$('#request_undertime_button').show();
	$('#request_undertime_form_wrapper').hide();
	clearFormError("employee_undertime_form");
	
}

function clearFormError(form_id)
{
	$("#" + form_id).validationEngine('hide'); 	
}




function load_approved_undertime_list_dt() {	
	$.get(base_url + 'undertime/_load_approved_undertime_list_dt',{},function(o) {
		$('#undertime_list_dt_wrapper').html(o);		
	});	
}


function load_archive_undertime_list_dt() {	
	$.get(base_url + 'undertime/_load_archive_undertime_list_dt',{},function(o) {
		$('#undertime_list_dt_wrapper').html(o);		
	});	
}

function restoreUndertimeRequest(h_id) {
	_restoreUndertimeRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editUndertimeRequestForm(undertime_id) {
	_editUndertimeRequestForm(undertime_id, {
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

function approveUndertime(eid) {
	_approveUndertime(eid, {
		onYes: function(o) {
			load_pending_undertime_list_dt();
			dialogOkBox(o.message,{});				
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			//dialogOkBox(o.message,{});			
		} 
	});	
}

function disApproveUndertime(eid) {
	_disApproveUndertime(eid, {
		onYes: function(o) {
			load_approved_undertime_list_dt();
			dialogOkBox(o.message,{});				
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			//dialogOkBox(o.message,{});			
		} 
	});	
}

function archiveUndertimeRequest(h_id,mod) {
	_archiveUndertimeRequest(h_id, {
		onYes: function() {
			datatable_loader(mod);
			dialogOkBox(o.message,{});		
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
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

function importUndertime() {
	_importUndertime({
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});			
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}


