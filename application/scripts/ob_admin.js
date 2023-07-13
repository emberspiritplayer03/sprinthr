function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}


function show_add_ob_request_form(from,to) {
	$('#add_ob_button').hide();
	$('#request_ob_form_wrapper').show();
	$("#ob_wrapper").html(loading_image);	
	$.post(base_url+'ob/ajax_add_new_request',{from:from,to:to},
	function(o){
		$("#ob_wrapper").html(o);	
	});	
	
}

function hide_add_ob_form() {	
	$('#add_ob_button').show();
	$('#request_ob_form_wrapper').hide();
	$("#ob_wrapper").html("");	
	clearFormError($("form").attr("id"));	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function load_ob_list_dt(from,to,frequency_id = 1) {
	$("#ob_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'ob/_load_ob_list_dt',{from:from,to:to,frequency_id:frequency_id},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}

function load_show_employee_request_approvers() {
	var h_employee_id 	= $('#employee_id').val();	
	if(h_employee_id != ""){
		$('#show_request_approvers_wrapper').html(loading_image);
		$.post(base_url + 'ob/_load_get_employee_request_approvers',{h_employee_id:h_employee_id},function(o) {		
			$('#show_request_approvers_wrapper').html(o);
		});
	}
}

function viewObRequestApprovers(eid) {
	_viewObRequestApprovers(eid, {
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

function load_approved_ob_list_dt(from,to,frequency_id = 1) {
	$("#ob_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'ob/_load_approved_ob_list_dt',{from:from,to:to,frequency_id:frequency_id},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}

function load_disapproved_ob_list_dt(from,to,frequency_id = 1) {
	$("#ob_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'ob/_load_disapproved_ob_list_dt',{from:from,to:to,frequency_id:frequency_id},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}

function load_archived_ob_list_dt(from,to,frequency_id = 1) {
	$("#ob_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'ob/_load_archived_ob_list_dt',{from:from,to:to,frequency_id:frequency_id},function(o) {
		$('#ob_list_dt_wrapper').html(o);		
	});	
}

function importOBRequest(date_from,date_to) {
	_importOBRequest(date_from,date_to,{
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			load_ob_list_dt(date_from,date_to);			
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

function editOBRequest(eid) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_editOBRequest(eid,date_from,date_to, {
		onSaved: function(o) {		
			load_ob_list_dt(date_from,date_to);				
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

function approveOBRequest(eid,sType) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_approveOBRequest(eid, {
		onYes: function(o) {			
        	if( sType == 2 ){
        		load_disapproved_ob_list_dt(date_from,date_to);
        	}else{
				load_ob_list_dt(date_from,date_to);								
			}
			dialogOkBox(o.message,{});							
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function disapproveOBRequest(eid,sType) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_disapproveOBRequest(eid, {
		onYes: function(o) {
			if( sType == 2 ){
				load_approved_ob_list_dt(date_from,date_to);								
			}else{
				load_ob_list_dt(date_from,date_to);		
			}
			dialogOkBox(o.message,{});							
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archivePendingOBRequest(eid) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_archiveOBRequest(eid, {
		onYes: function(o) {
			load_ob_list_dt(date_from,date_to);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveApprovedOBRequest(eid) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_archiveOBRequest(eid, {
		onYes: function(o) {
			load_approved_ob_list_dt(date_from,date_to);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreArchivedOBRequest(eid) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_restoreArchivedOBRequest(eid, {
		onYes: function(o) {
			load_archived_ob_list_dt(date_from,date_to);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function withSelectedApproved(status) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_withSelectedOBRequest(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			//load_approved_ob_list_dt(date_from,date_to);	
			location.reload();	
			$("#chkAction").attr('disabled',true);			
		}, 
		onNo: function(){			
			$("#chkAction").val("");			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function withSelectedPendings(status) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_withSelectedOBRequest(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_ob_list_dt(date_from,date_to);		
			$("#chkAction").attr('disabled',true);			
		}, 
		onNo: function(){			
			$("#chkAction").val("");			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function withSelectedArchived(status) {
	var date_from = $("#cp_date_from").val();
	var date_to   = $("#cp_date_to").val();
	_withSelectedOBRequest(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_archived_ob_list_dt(date_from,date_to);		
			$("#chkAction").attr('disabled',true);			
		}, 
		onNo: function(){			
			$("#chkAction").val("");			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}
