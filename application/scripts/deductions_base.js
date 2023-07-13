function _editDeduction(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Deduction';
	var width = 700;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'deductions/ajax_edit_deduction', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});						
	});
}

function _importDeductions(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Deductions';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'deductions/ajax_import_deduction', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});	
		
		$('#import_deductions_form').validationEngine({scroll:false});		
		$('#import_deductions_form').ajaxForm({
			success:function(o) {
				if (o.is_imported) {
					if (typeof events.onImported == "function") {
						events.onImported(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if ($('#earning_file').val() == '') {					
					return false;	
				}
				if (typeof events.onImporting == "function") {
					events.onImporting();
				}				
				return true;
			}
		});					
	});
}


function _withSelectedEarnings(status,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 180
	var title  = 'Notice';	
	if(status == 'deduction_approve'){
		var message = '<b>Approve</b> the selected entries?';
	}else if(status == 'deduction_archive'){
		var message = '<b>Archive</b> the selected entries?';
	}else if(status == 'deduction_disapprove'){
		var message = '<b>Disapprove</b> the selected entries?';
	}else if(status == 'deduction_restore'){
		var message = '<b>Restore</b> archived selected entries?';
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
				$.post(base_url + 'deductions/_with_selected_pending_action',$('#withSelectedAction').serialize(),		
				function(o) { 
					if(o.is_success==1) {	
																							
					}					
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
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

function _archivePendingDeduction(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected deduction?';
	
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
				$.post(base_url+'deductions/_archive_deduction',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {	
							
					}					
					
					if (typeof events.onYes == "function") {						
						events.onYes(o);
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

function _restoreArchivedDeduction(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>restore</b> the selected archived deduction?';
	
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
				$.post(base_url+'deductions/_restore_archived_deduction',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {	
							
					}					
					
					if (typeof events.onYes == "function") {						
						events.onYes(o);
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

function _approveDeduction(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 210
	var title = 'Notice';
	var message = 'Are you sure you want to <b>approve</b> the selected deduction?<br><br><b>Note : Approving will add the selected amount to payroll.</b><br>';
	
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
				$.post(base_url+'deductions/_approve_deduction',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {
						load_sum_pending_deductions('"' + o.eid + '"');															
						load_deductions_list_dt('"' + o.eid + '"');						
					}			
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
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

function _disApproveDeduction(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>disapprove</b> the selected deduction?';
	
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
				$.post(base_url+'deductions/_disapprove_deduction',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_sum_approved_deductions('"' + o.eid + '"');
						load_approved_deductions_list_dt('"' + o.eid + '"');
					}
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
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

