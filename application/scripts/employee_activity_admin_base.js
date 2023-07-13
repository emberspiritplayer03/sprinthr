function _importEmployeeActivities(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Schedule Activities';
	var width = 430;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'activity/ajax_import_employee_activities', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});	
		
		$('#import_activity_form').validationEngine({scroll:false});		
		$('#import_activity_form').ajaxForm({
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
				if ($('#employee_activity_file').val() == '') {					
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




function _generateEmployeeActivities(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Generate Activities';
	var width = 430;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'activity/ajax_generate_employee_activities', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});	
		
		$('#generate_activity_form').validationEngine({scroll:false});		
		$('#generate_activity_form').ajaxForm({
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
				
				if (typeof events.onImporting == "function") {
					events.onImporting();
				}				
				return true;
			}
		});					
	});
}



function _deleteEmployeeActivity(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected employee activity?';
	
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
				$.post(base_url+'activity/_delete_employee_activity',{eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_employee_activities_list_dt();			
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

function _editEmployeeActivity(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule Activity';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'activity/ajax_edit_employee_activity', {eid:eid}, function(data) {
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