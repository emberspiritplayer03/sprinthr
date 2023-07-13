function _importOBRequest(date_from,date_to,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import OB Request(Period: ' + date_from + ' to ' + date_to + ')';
	var width = 430;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'ob/ajax_import_ob_request', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});	
		
		$('#import_ob_form').validationEngine({scroll:false});		
		$('#import_ob_form').ajaxForm({
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
				if ($('#ob_file').val() == '') {					
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

function _viewObRequestApprovers(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'OB Request Approvers';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	var start_cutoff = $("#from_period").val();
	var end_cutoff   = $("#to_period").val();

	$.get(base_url + 'ob/ajax_view_ob_request_approvers', {eid:eid}, function(data) {
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

function _editOBRequest(eid,date_from,date_to, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Request';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'ob/ajax_edit_ob_request', {eid:eid,date_from:date_from,date_to:date_to}, function(data) {
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

function _approveOBRequest(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220;
	var title = 'Notice';	
	var message = 'Are you sure you want to approve the selected overtime request? <br /><br /> Note : <b>Approving request will set all approvers request status to approved.</b>';
	
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
				$.post(base_url+'ob/_approve_ob_request',{eid:eid},
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

function _disapproveOBRequest(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220;
	var title = 'Notice';	
	var message = 'Are you sure you want to disapprove the selected official business request? <br /><br /> Note : <b>Disapproving request will set all approvers request status to disapproved.</b>';
	
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
				$.post(base_url+'ob/_disapprove_ob_request',{eid:eid},
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

function _archiveOBRequest(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected request?';
	
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
				$.post(base_url+'ob/_archive_ob_request',{eid:eid},
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

function _restoreArchivedOBRequest(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>restore</b> the selected archived request?';
	
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
				$.post(base_url+'ob/_restore_ob_request',{eid:eid},
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

function _withSelectedOBRequest(status,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 180
	var title  = 'Notice';	
	if(status == 'ob_approve'){
		var message = '<b>Approve</b> the selected entries?';
	}else if(status == 'ob_archive'){
		var message = '<b>Archive</b> the selected entries?';
	}else if(status == 'ob_disapprove'){
		var message = '<b>Disapprove</b> the selected entries?';
	}else if(status == 'ob_restore'){
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
				$.post(base_url + 'ob/_with_selected_action',$('#withSelectedAction').serialize(),		
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
