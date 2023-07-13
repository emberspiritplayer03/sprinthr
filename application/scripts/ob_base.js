function _obWithSelectedAction(status, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	
	if(status == 'archive') {
		var message = '<b>Send to archive</b> the selected request(s)?';
	}else if(status == 'restore'){
		var message = '<b>Restore</b> the selected archived request?';
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
				$.post(base_url + 'ob/ob_with_selected_action',$('#withSelectedAction').serialize(),		
				function(o) { 
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});	
						
						if(status == 'archive'){																		
							load_pending_ob_list_dt();
						}else{
							load_archive_ob_list_dt();
						}
						
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
					}						
					
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

function _editOBRequestForm(ob_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit OB Request';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'ob/ajax_edit_ob_request', {ob_id:ob_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#employee_edit_ob_form').validationEngine({scroll:false});		
		$('#employee_edit_ob_form').ajaxForm({
			success:function(o) {
				if (o.is_saved = 1) {
					if (typeof events.onSaved == "function") {						
						load_pending_ob_list_dt();
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

function _archiveObRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected OB request?';
	
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
				$.post(base_url+'ob/_load_archive_ob_request',{e_id:e_id},
					function(o){													
					if(o.is_success == 1) {														
						load_pending_ob_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
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

function _restoreObRequest(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected ob request?';
	
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
				$.post(base_url+'ob/_load_restore_ob_request',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {														
						load_archive_ob_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
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