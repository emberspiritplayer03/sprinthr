/*
function _editOvertimeRequestForm(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Overtime Request';
	var width = 550;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	
	$.post(base_url + 'overtime/_load_edit_overtime_request',{h_id:h_id},
	function(o){ 
		$('#edit_request_overtime_form_modal_wrapper').html(o); 
		dialogGeneric('#edit_request_overtime_form_modal_wrapper', {
			title: 'Edit Overtime Request',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_request_overtime_form'
		});
	
		$("#edit_request_overtime_form").validationEngine({scroll:false});
		$('#edit_request_overtime_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				//closeDialog(dialog_id);
				closeDialogBox('#edit_request_overtime_form_modal_wrapper','#edit_request_overtime_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});

	});
}
*/

function _changeOvertimeRequestStatus(status, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	
	if(status == 'Approved') {
		var message = '<b>Approve</b> the selected request(s)?';
	}else if(status == 'Disapproved') {
		var message = '<b>Disapprove</b> the selected request(s)?';
	}else if(status == 'Archive'){
		var message = '<b>Send to archive</b> the selected request(s)?';
	}else if(status == 'Restore Archive'){
		var message = '<b>Restore</b> the selected archived request?';
	}else {
		var message = '<b>Set to pending status</b> the selected request(s)?';	
	}
	
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url + 'overtime/load_change_overtime_request_status',$('#withSelectedAction').serialize(),		
				function(o) { 
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});																				
						datatable_loader(o.load_dt);
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}	
					
					$('.overtime_action_link').hide();
				},"json");
				
				//load_overtime_list_dt();				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}



/*
	RECONSTRUCTED
*/
function _editOvertimeRequestForm(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Overtime Request';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	

	$.post(base_url + 'overtime/_load_edit_overtime_request', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		
		
		$("#start_date_hideshow").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	: function(o) {
				load_show_specific_schedule();		
			}
		});	
		
		$('#start_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
		$('#end_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
		
		
		$('#edit_request_overtime_form').validationEngine({scroll:false});		
		$('#edit_request_overtime_form').ajaxForm({
			success:function(o) {
				if (o.is_saved == 1) {
					if (typeof events.onSaved == "function") {
						datatable_loader(1);
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _archiveOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected overtime request?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url+'overtime/_load_archive_overtime_request',{h_id:h_id},
					function(o){													
					if(o.is_saved==1) {								
						//load_leave_list_dt(o.es_id);
						if (typeof events.onYes == "function") {
							events.onYes();
							datatable_loader(1);
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}


function _restoreOvertimeRequest(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected overtime request?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url+'overtime/_load_restore_overtime_request',{h_id:h_id},
					function(o){													
					if(o.is_saved==1) {								
						//load_leave_list_dt(o.es_id);
						
						if (typeof events.onYes == "function") {
							events.onYes();
							datatable_loader(3);
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}					
															   
				},"json");		
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}


