function addRemoveRestday(month, day, year, restday_id, employee_id) {
    closeDialog('#' + DIALOG_CONTENT_HANDLER);
    if (restday_id) {
        deleteRestday(restday_id);
    } else if(employee_id) {
        _addRestday(month, day, year, employee_id, {
            onAdding: function() {
    			closeDialog('#' + DIALOG_CONTENT_HANDLER);
    			showLoadingDialog('Adding Rest Day...');
            },
            onAdded: function(o) {
                closeDialog('#' + DIALOG_CONTENT_HANDLER);
    			var query = window.location.search;
    			$.get(base_url + 'schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
    				$('#main').html(html_data)
    			});
            },
            onError: function(o) {
                closeDialog('#' + DIALOG_CONTENT_HANDLER);
                showOkDialog(o.message);
            }
        });
    }
}

function copyDefaultRestdayToGroup(eid) {	
	_copyDefaultRestdayToGroup(eid,{
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			if( o.is_success ){
				var query = window.location.search;	    
				$.get(base_url + 'schedule/show_department_schedule'+ query, {ajax:1}, function(html_data){
					$('#main').html(html_data)
				});
			}
			showOkDialog(o.message);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});
}

function copyDefaultRestdayToEmployee(eid) {	
	_copyDefaultRestdayToEmployee(eid,{
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			if( o.is_success ){
				var query = window.location.search;	    
				$.get(base_url + 'schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
					$('#main').html(html_data)
				});
			}
			showOkDialog(o.message);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});
}

function copyDefaultRestdayToAllEmployees() {

	_copyDefaultRestdayToAllEmployee({		
		onLoading: function() {
			showLoadingDialog('Loading...');
		},		
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			//showLoadingDialog('Loading...');
			if( o.is_success ) {    	
				showOkDialog(o.message);
			} else {
				showOkDialog(o.message);
			}
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	

}

function addRemoveGroupRestday(month, day, year, restday_id, group_id) {
    closeDialog('#' + DIALOG_CONTENT_HANDLER);
    if (restday_id) {
        deleteGroupRestday(month, day, year, group_id);
    } else if(group_id) {
        _addGroupRestday(month, day, year, group_id, {
            onAdding: function() {
    			closeDialog('#' + DIALOG_CONTENT_HANDLER);
    			showLoadingDialog('Adding Rest Day...');
            },
            onAdded: function(o) {
                closeDialog('#' + DIALOG_CONTENT_HANDLER);
                if( o.is_success ){
	    			var query = window.location.search;	    			
	    			if( group_id == 1 ){
	    				$.get(base_url + 'schedule/show_schedule'+ query, {ajax:1}, function(html_data){
		    				$('#main').html(html_data)
		    			});
	    			}else{
		    			$.get(base_url + 'schedule/show_department_schedule'+ query, {ajax:1}, function(html_data){
		    				$('#main').html(html_data)
		    			});
	    			}
    			}
            },
            onError: function(o) {
                closeDialog('#' + DIALOG_CONTENT_HANDLER);
                showOkDialog(o.message);
            }
        });
    }
}

function deleteRestday(restday_id) {
	//$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('Loading...');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this rest day?', {
		onYes: function(){
			_deleteRestday(restday_id, {
				onDeleted: function(o) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					var query = window.location.search;					
					$.get(base_url + 'schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
						$('#main').html(html_data)
					});
				},
				onDeleting: function() {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showLoadingDialog('Deleting...');
				},
				onBeforeDelete: function() {
					return true;
				},
				onError: function(message) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(o.message);
				}
			});	
		}
	});
}

function deleteGroupRestday(month, day, year, group_id) {	
	showLoadingDialog('Loading...');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this rest day?', {
		onYes: function(){
			_deleteGroupRestday(month, day, year, group_id, {
				onDeleted: function(o) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					if( o.is_success ){
						var query = window.location.search;
						if( group_id == 1 ){
							$.get(base_url + 'schedule/show_schedule'+ query, {ajax:1}, function(html_data){
								$('#main').html(html_data)
							});
						}else{
							$.get(base_url + 'schedule/show_department_schedule'+ query, {ajax:1}, function(html_data){
								$('#main').html(html_data)
							});
						}						
					}
				},
				onDeleting: function() {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showLoadingDialog('Deleting...');
				},
				onBeforeDelete: function() {
					return true;
				},
				onError: function(message) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(o.message);
				}
			});	
		}
	});
}

function deleteRestdayByEmployeeAndDate(employee_id, date, start_date, end_date) {
	showLoadingDialog('Loading...');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this Rest Day?', {
		onYes: function(){
			_deleteRestdayByEmployeeAndDate(employee_id, date, {
				onDeleted: function(o) {
					closeTheDialog();
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
				},
				onDeleting: function() {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showLoadingDialog('Deleting...');
				},
				onBeforeDelete: function() {
					return true;
				},
				onError: function(message) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(o.message);
				}
			});	
		}
	});
}

function importRestday() {
	_importRestday({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);			
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}