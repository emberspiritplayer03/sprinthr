/*
* TODO NOT YET FINISHED. TO BE CONTINUED
 */
function _resignEmployee(employee_id, date, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Edit Overtime ';
    var width = 300;
    var height = 'auto';

    if (typeof events.onLoading == "function") {
        events.onLoading();
    }

    $.get(base_url + 'overtime/ajax_edit_overtime_form', {employee_id:employee_id, date:date}, function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true,
            form_id: '#edit_overtime_form'
        });

        $('#edit_overtime_form').ajaxForm({
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
            },
            dataType:'json',
            beforeSubmit: function() {
                if (typeof events.onSaving == "function") {
                    events.onSaving();
                }
                return true;
            }
        });
    });
}

function _deleteEmployeeBenefit(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected benefit?';
	
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
				$.post(base_url+'employee/_delete_employee_benefit',{eid:eid},
					function(o){													
					if(o.is_success) {								
						loadEmployeeBenefits(o.eid);						
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {						
						$dialog.dialog("close");								
					}
					dialogOkBox(o.message,{});					
															   
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

function _editEmployeeHistoryDialog(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Employee History';
	var width = 600;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	

	$.post(base_url + 'employee/_load_edit_history_form', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		
		$('#edit_history_form').validationEngine({scroll:false});		
		$('#edit_history_form').ajaxForm({
			success:function(o) {
				if (o.is_saved == 1) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_employee_history_list_dt();
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

function _deleteSpecificSchedule(schedule_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected schedule?';
	
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
				$.post(base_url+'schedule/_delete_specific_schedule',{schedule_id:schedule_id},
					function(o){													
					if(o.is_deleted) {							
					
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

function _deleteEmployeeHistory(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected history?';
	
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
				$.post(base_url+'employee/_archive_employee_history',{eid:eid},
					function(o){													
					if(o.is_success) {							
					
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

function _archiveEmployee(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width   = 350 ;
	var height  = 180
	var title   = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected employee?';
	
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
				$.post(base_url+'employee/_archive_employee',{eid:eid},
					function(o){													
					if(o.is_success) {							
					
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

function _restoreEmployee(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width   = 350 ;
	var height  = 180
	var title   = 'Notice';
	var message = 'Are you sure you want to <b>restore</b> the selected archived employee?';
	
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
				$.post(base_url+'employee/_restore_employee',{eid:eid},
					function(o){													
					if(o.is_success) {							
					
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
