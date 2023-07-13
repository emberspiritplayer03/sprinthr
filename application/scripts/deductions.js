function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}


function show_add_deductions_form(eid,frequency_id) {
	$('#add_deduction_button').hide();
	$('#add_deductions_form_wrapper').show();
	$("#deductions_wrapper").html(loading_image);	
	$.post(base_url+'deductions/ajax_add_new_deduction',{eid:eid,frequency_id:frequency_id},
	function(o){
		$("#deductions_wrapper").html(o);	
	});	
	
}


function hide_add_deductions_form() {	
	$("#add_deductions_form").validationEngine("hide");	
	$('#add_deduction_button').show();
	$('#add_deductions_form_wrapper').hide();
	$("#deductions_wrapper").html("");	
	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function load_deductions_list_dt(eid) {
	$("#deductions_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'deductions/_load_deductions_list_dt',{eid:eid},function(o) {
		$('#deductions_list_dt_wrapper').html(o);		
	});	
}

function load_sum_approved_deductions(eid) {
	$("#total_approved").html(loading_image);	
	$.post(base_url + 'deductions/_load_sum_approved_deductions',{eid:eid},function(o) {
		$('#total_approved').html(o);		
	});	
}

function load_sum_pending_deductions(eid) {
	$("#total_pending").html(loading_image);	
	$.post(base_url + 'deductions/_load_sum_pending_deductions',{eid:eid},function(o) {
		$('#total_pending').html(o);		
	});	
}

function load_sum_archived_deductions(eid) {
	$("#total_archived").html(loading_image);	
	$.post(base_url + 'deductions/_load_sum_archived_deductions',{eid:eid},function(o) {
		$('#total_archived').html(o);		
	});	
}

function load_approved_deductions_list_dt(eid,frequency_id=1) {
	
	$("#deductions_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'deductions/_load_approved_deductions_list_dt',{eid:eid,frequency_id:frequency_id},function(o) {
		$('#deductions_list_dt_wrapper').html(o);		
	});	
}

function load_hold_deductions_list_dt(eid,from,to) {
	$("#hold_deductions_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'deductions/_load_hold_deductions_list_dt',{eid:eid,from:from,to:to},function(o) {
		$('#hold_deductions_list_dt_wrapper').html(o);		
	});	
}

function load_archived_deductions_list_dt(eid) {
	$("#deductions_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'deductions/_load_archived_deductions_list_dt',{eid:eid},function(o) {
		$('#deductions_list_dt_wrapper').html(o);		
	});	
}

function editDeduction(eid) {
	_editDeduction(eid, {
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

function importDeductions(eid) {
	_importDeductions(eid,{
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			load_approved_deductions_list_dt('"' + eid + '"');	
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

function withSelectedPendings(status) {
	_withSelectedEarnings(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_sum_pending_deductions('"' + o.eid + '"');																		
			load_deductions_list_dt('"' + o.eid + '"');
			$("#chkAction").attr('disabled',true);			
		}, 
		onNo: function(){			
			$("#chkAction").val("");
			//$("#chkAction").attr('disabled',true);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function withSelectedApproved(status) {
	_withSelectedEarnings(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_sum_approved_deductions('"' + o.eid + '"');																		
			load_approved_deductions_list_dt('"' + o.eid + '"');	
			$("#chkAction").attr('disabled',true);		
		}, 
		onNo: function(){			
			$("#chkAction").val("");
			//$("#chkAction").attr('disabled',true);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function withSelectedArchived(status) {	
	_withSelectedEarnings(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_archived_deductions_list_dt('"' + o.eid + '"');																		
			load_sum_archived_deductions('"' + o.eid + '"');	
			$("#chkAction").attr('disabled',true);		
		}, 
		onNo: function(){			
			$("#chkAction").val("");
			//$("#chkAction").attr('disabled',true);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


function archivePendingDeduction(keid,eid) {
	_archivePendingDeduction(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_sum_pending_deductions('"' + o.eid + '"');													
			load_deductions_list_dt('"' + o.eid + '"');									
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreArchivedDeduction(keid,eid) {
	_restoreArchivedDeduction(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_archived_deductions_list_dt('"' + o.eid + '"');													
			load_sum_archived_deductions('"' + o.eid + '"');									
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveApprovedDeduction(keid,eid) {
	_archivePendingDeduction(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_approved_deductions_list_dt('"' + o.eid + '"');													
			load_sum_approved_deductions('"' + o.eid + '"');									
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function approveDeduction(keid,eid) {
	_approveDeduction(keid,eid, {
		onYes: function(o) {				
			dialogOkBox(o.message,{});			
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function disApproveDeduction(keid,eid) {
	_disApproveDeduction(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});			
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}
