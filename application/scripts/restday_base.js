function _copyDefaultRestdayToGroup(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to apply default restday to this group/department?<p>Note : Default Restday set in <b>default schedule</b></p>';
	
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
				$.get(base_url+'schedule/_copy_default_restday',{eid:eid},
					function(o){													
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

function _copyDefaultRestdayToEmployee(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180;
	var title = 'Notice';
	var message = 'Are you sure you want to apply default restday to this employee?<p>Note : Default Restday set in <b>default schedule</b></p>';
	
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
				$.get(base_url+'schedule/_copy_default_restday_to_employee',{eid:eid},
					function(o){													
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

function _copyDefaultRestdayToAllEmployee(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180;
	var title = 'Notice';
	var message = 'Are you sure you want to apply default restday to all employees?';
	
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

				if (typeof events.onLoading == "function") {
					events.onLoading();
				}
					
				$.get(base_url+'schedule/_copy_default_restday_to_all_employee',{},
					function(o){													
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

function _importRestday(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Rest Day';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	
	$.get(base_url + 'attendance/ajax_import_restday', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#import_restday_form').validationEngine({scroll:false});		
		$('#import_restday_form').ajaxForm({
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
				if ($('#restday_file').val() == '') {
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

function _deleteRestdayByEmployeeAndDate(employee_id, date, events) {
	var ans = true;
	if (typeof events.onBeforeDelete == "function") {
		ans = events.onBeforeDelete();
	}	
	if (ans) {
		if (typeof events.onDeleting == "function") {
			events.onDeleting();
		}
		$.post(base_url + 'attendance/_delete_restday_by_employee_and_date', {employee_id:employee_id, date:date}, function(o) {
			if (o.is_deleted) {
				if (typeof events.onDeleted == "function") {
					events.onDeleted(o);
				}				
			} else {
				if (typeof events.onError == "function") {
					events.onError(o);
				}		
			}
		},'json');
	}
}

function _deleteRestday(restday_id, events) {
	var ans = true;
	if (typeof events.onBeforeDelete == "function") {
		ans = events.onBeforeDelete();
	}
	if (ans) {
		if (typeof events.onDeleting == "function") {
			events.onDeleting();
		}
		$.post(base_url + 'attendance/_delete_restday', {restday_id:restday_id}, function(o) {
			if (o.is_deleted) {
				if (typeof events.onDeleted == "function") {
					events.onDeleted(o);
				}
			} else {
				if (typeof events.onError == "function") {
					events.onError(o);
				}
			}
		},'json');
	}
}

function _deleteGroupRestday(month, day, year, group_id, events) {
	var ans = true;
	if (typeof events.onBeforeDelete == "function") {
		ans = events.onBeforeDelete();
	}
	if (ans) {
		if (typeof events.onDeleting == "function") {
			events.onDeleting();
		}
		$.post(base_url + 'attendance/_delete_group_restday', {month:month, day:day, year:year, group_id:group_id}, function(o) {
			if (o.is_success) {
				if (typeof events.onDeleted == "function") {
					events.onDeleted(o);
				}
			} else {
				if (typeof events.onError == "function") {
					events.onError(o);
				}
			}
		},'json');
	}
}

function _addRestday(month, day, year, employee_id, events) {
	if (typeof events.onAdding == "function") {
		events.onAdding();
	}
    $.post(base_url + 'attendance/_add_restday', {month:month, day:day, year:year, employee_id:employee_id}, function(o) {
    	if (o.is_added) {
    		if (typeof events.onAdded == "function") {
    			events.onAdded(o);
    		}
    	} else {
    		if (typeof events.onError == "function") {
    			events.onError(o);
    		}
    	}
    },'json');
}

function _addGroupRestday(month, day, year, group_id, events) {
	if (typeof events.onAdding == "function") {
		events.onAdding();
	}
    $.post(base_url + 'attendance/_add_group_restday', {month:month, day:day, year:year, group_id:group_id}, function(o) {
    	if (o.is_success) {
    		if (typeof events.onAdded == "function") {
    			events.onAdded(o);
    		}
    	} else {
    		if (typeof events.onError == "function") {
    			events.onError(o);
    		}
    	}
    },'json');
}