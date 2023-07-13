/* Declaration */
var sync_attendance;

/*
 * Activate autoload of notification with 60 seconds interval = 60000ms
 * when the user is active in the browser.
 */
$(window).focus(function(){	
	clearInterval(sync_attendance);
	if( sync_attendance_interval == "" ){
		sync_attendance_interval = 120000;
	}
	sync_attendance = setInterval(function(){_synchronize_attendance()}, sync_attendance_interval);		
});

/*
 * Deactivate autoload of notification when the user leave the browser - Idle
 * This will avoid resource overload. 
 */
$(window).blur(function(){
	clearInterval(sync_attendance);
});

function _synchronize_attendance() {
	//$.active returns the number of active Ajax requests.
	if($.active == 0) {
		$.get(base_url+'attendance_sync/_sync_employee_attendance',{},
			function(o){				
		},"json");	
	}
	
}


function modalSyncAttendanceDataDepre(){
	showLoadingDialog('Synchronizing data...');
	$.get(base_url+'attendance_sync/ajax_sync_attendance',{},
		function(o){				
			if(o.is_success){

			}
			dialogOkBox(o.message,{});
	},"json");	
}

function modalSyncAttendanceData() {
	_modalSyncAttendanceData({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});				
		},
		onError: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});		
		}
	});
}

function _modalSyncAttendanceData(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Synchronize Attendance';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'attendance/ajax_synchronize_attendance', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#attendance_sync').validationEngine({scroll:false});		
		$('#attendance_sync').ajaxForm({
			success:function(o) {
				if (o.is_success) {
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
				showLoadingDialog('Synchronizing Attendance...');
			}
		});		
	});
}
