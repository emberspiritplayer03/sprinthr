function load_error_tab(from, to) {
	$('#error').html(loading_image);	
	$.get(base_url + 'project_site/load_error_tab',{from:from,to:to},
		function(o){				
			$('#error').html(o);		
		});	
}

function load_no_error_tab(element) {
	_load_no_error_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_incomplete_logs_tab(element) {
	_load_incomplete_logs_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function load_multiple_in_tab(element) {
	_load_multiple_in_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function load_multiple_out_tab(element) {
	_load_multiple_out_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_no_sched_tab(element) {
	_load_no_sched_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_conflict_sched_tab(element) {
	_load_conflict_sched_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_leave_tab(element) {
	_load_leave_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_rd_tab(element) {
	_load_rd_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_ob_tab(element) {
	_load_ob_tab(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function loadNewAttendanceSelectedTab(){

	$(".aload-data").click(function(){
		var action = $(this).attr("id");
		load_new_attendance_selected_tab(action);
	});

	load_new_attendance_selected_tab("all_errors");
}

function load_new_attendance_selected_tab(action) {
    if(action == "incomplete_logs") {
		var obj_wrapper = $(".attendance-incomplete-wrapper");
	}else if(action == "multiple_in") {
		var obj_wrapper = $(".attendance-multiple-in-wrapper");
	}else if(action == "multiple_out"){
		var obj_wrapper = $(".attendance-multiple-out-wrapper");
	}
	else{
		var obj_wrapper = $(".attendance-all-wrapper");
	}

	obj_wrapper.html(loading_image);	
	$.get(base_url + 'project_site/_load_new_attendance_selected_data',{action:action},function(o) {
		obj_wrapper.html(o).hide();	
		obj_wrapper.fadeIn(1000);
	});	
}

function editActualTime(employee_id, date) {
    _editActualTime(employee_id, date, {
        onSaved: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            var query = window.location.search;
            $.post(base_url + 'overtime/check_overtime_error', {employee_id:employee_id, date:date}, function(oo){
                if (oo.has_error) {
                    showOkDialog(oo.message);
                } else {
                    showOkDialog(oo.message);
                    $.get(base_url + 'overtime/period'+ query, {ajax:1}, function(html_data){
                        $('#main').html(html_data)
                    });
                }
            }, 'json');
        },
        onSaving: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Saving...');
        },
        onLoading: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Loading...');
        },
        onBeforeSave: function(o) {

        },
        onError: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showOkDialog(o.message);
        }
    });
}

function attendanceAction(action){
	if(action == 'timesheet'){
		importTimesheet();
	}else if(action == 'change_schedule'){
		importScheduleSpecific();
	}else if(action == 'leave'){
		importLeave();
	}else if(action == 'ot'){
		importOvertime();
	}else if(action == 'rest_day'){
		importRestday();
	}
	
	$("#attendance_action").val("selected");
}


function updateSiteAttendance(from, to, frequency_id = 1) {
	_updateSiteAttendance(from, to, {
		onUpdated: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialogProgressBarFull('Updating Attendance...');
			setTimeout(function(){
			    showOkDialog(o.message);
			  }, 1000);				
			//showOkDialog(o.message);
		},
		onUpdating: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			//showLoadingDialog('Updating Attendance...');
			showLoadingDialogProgressBar('Updating Attendance...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showOkDialog(o.message);
		}
	}, frequency_id);
}

function updateAttendanceByEmployee(employee_id, from, to) {
	_updateAttendanceByEmployee(employee_id, from, to, {
		onUpdated: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			_showAttendance('#attendance_container', employee_id, from, to, {});
		},
		onUpdating: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Updating Attendance...');
		},
		onError: function(message) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showOkDialog(o.message);
		}
	});
}

function deleteOvertimeByEmployeeAndDate(employee_id, date, start_date, end_date) {
	showLoadingDialog('Loading...');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this Overtime?', {
		onYes: function(){
			_deleteOvertimeByEmployeeAndDate(employee_id, date, {
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

function downloadTimesheet(from, to) {
	//showLoadingDialog('Loading...');
	location.href = base_url + 'project_site/download_timesheet?from='+ from +'&to='+ to;	
	//location.href = base_url + 'attendance';
}

function importOvertime() {
	_importOvertime({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			var current_location = window.location.search;
			dialogOkBox(o.message,{ok_url: "overtime/period"+current_location});
			//showOkDialog(o.message);			
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

function importOvertimePending(h_employee_id) {
	_importOvertimePending(h_employee_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);
			
			
			if(typeof load_overtime_list_dt_clerk == 'function') {
				load_overtime_list_dt_clerk();
			} else {
				load_overtime_list_dt();	
			}
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

function importTimesheet() {
	_importTimesheet({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);	
			//dialogOkBox(o.message,{ok_url: "project_site/attendance_logs"});
			load_attendance_logs_list_dt();		
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

function addAttendanceLog() {
	_addAttendanceLog({
		onSaved: function(o) {
            closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);			
			load_attendance_logs_list_dt();
        },
		onSaving: function() {
        	$(".attendanceLogErr").html('<div class="alert alert-success">Saving...</div>');
            //closeDialog('#' + DIALOG_CONTENT_HANDLER);
            //showLoadingDialog('Saving...');
        },
        onLoading: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Loading...');
        },
        onBeforeSave: function(o) {

        },
        onError: function(o) {
            //closeDialog('#' + DIALOG_CONTENT_HANDLER);
            $(".attendanceLogErr").html('<div class="alert alert-error">' + o.message + '</div>');
            //showOkDialog(o.message);
        }
	});
}

function employeeListButton () {
	location.href = base_url + 'project_site/employee_list';
}

function payrollButton () {
	location.href = base_url + 'project_site/payroll_register';
}

function editTimesheet(date, employee_id, start_date, end_date,is_period_lock) {
	_editTimesheet(date, employee_id,is_period_lock, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		}
	});
}

function filterTimeSheetBreakDown(date_from,date_to) {
	_filterTimeSheetBreakDown(date_from,date_to, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		}
	});
}

function showTimesheet(date, employee_id) {
	_showTimesheet(date, employee_id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		}
	});
}

function editAttendanceLogErrorTab(eid, incomplete = '') {
	_editAttendanceLogErrorTab(eid, incomplete, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			load_new_attendance_selected_tab("all_errors");
			//_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editAttendanceLogErrorTabIncompleteLogs(eid, type = '') {
	_editAttendanceLogErrorTab(eid, type, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			load_new_attendance_selected_tab("all_errors");
			//_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editAttendanceLog(eid, type = '') {
	_editAttendanceLog(eid, type, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			load_attendance_logs_list_dt();
			//_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editAttendanceLogV2(eid_in) {
	_editAttendanceLogV2(eid_in, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			load_attendance_logs_list_dt();
			//_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editAttendanceLogTimeInTimeOut(eid_in, eid_out, type_in = '', type_out = '') {
	_editAttendanceLogTimeInTimeOut(eid_in, eid_out, type_in, type_out, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			load_attendance_logs_list_dt();
			//_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editAttendance(date, employee_id, start_date, end_date) {
	_editAttendance(date, employee_id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function showAttendanceOtherDetails(date, eid) {
	_showAttendanceOtherDetails(date, eid, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		}			
	});
}

function editTimesheetInOut(date, eid) {
	_editTimesheetInOut(date, eid,{
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			if( o.is_success){				
				var from = getUrlParameter('from');
				var to   = getUrlParameter('to');				
				showAttendanceFromNavigation('#attendance_container',eid,from,to)	
			}
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}  

function editTimeInOut(date, employee_id, start_date, end_date) {
	_editTimeInOut(date, employee_id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editOvertimeInOut(date, employee_id, start_date, end_date) {
	_editOvertimeInOut(date, employee_id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function showAttendance(element, employee_id, start_date, end_date, frequency_id) {
	
	_showAttendance(element, employee_id, start_date, end_date,  frequency_id,{
		onLoading: function() {			
			$('#attendance_container').html(loading_image + ' Loading...');
		},
		onLoaded: function() {

		}
	});
}

function showAttendanceFromNavigation(element, employee_id, start_date, end_date, employee_name) {
	$('.tipsy').hide();
	_showAttendance(element, employee_id, start_date, end_date, {
		onLoading: function() {			
			$('#temp_loading_container').html(loading_image + ' Loading...');
		},
		onLoaded: function() {
			$('.mplynm').html(employee_name);
		}
	});
}

function closeTheDialog() {
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_attendance');
}

function importLeave()
{

	 var $dialog = $("#import_leave_wrapper");
		$dialog.dialog({
                title: 'Import Leave',
                width: 350,
				height: 'auto',				
				resizable: false,
				modal:true
            }).show();
}

function closeImportDialog()
{
	$("#import_leave_wrapper").dialog('destroy');
}

function lockPayrollPeriod(e_id) {
	_lockPayrollPeriod(e_id, {
		onYes: function() {
			location.href = base_url + 'attendance';
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteDTRLog(id, type = '') {
	_deleteDTRLog(id, type, {
		onYes: function(o) {
			if(o.is_success) {								
				load_new_attendance_selected_tab("all_errors");
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteDTRLogTimeInTimeOut(eid_in, eid_out, type_in = '', type_out = '') {
	_deleteDTRLogTimeInTimeOut(eid_in, eid_out, type_in, type_out, {
		onYes: function(o) {
			load_attendance_logs_list_dt();		
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function lockPeriod(period_id,$frequency) {
	
    _lockPayrollPeriod(period_id,$frequency, {
        onLocked: function(o) {
            $dialog.dialog("destroy");
            $dialog.hide();
            disablePopUp();
            showOkDialog(o.message);
            var query = window.location.search;
            $.get(base_url + 'payroll_register/generation'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });
        }
    });
}

function unlockPeriod(period_id,$frequency) {
    _unlockPayrollPeriod(period_id,$frequency, {
        onUnlocked: function(o) {
            $dialog.dialog("destroy");
            $dialog.hide();
            disablePopUp();

            showOkDialog(o.message);
            var query = window.location.search;
            $.get(base_url + 'payroll_register/generation'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });
        }
    });
}

/**old
function load_attendance_logs_list_dt() {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	
	$("#s_from").val(date_from);
	$("#s_to").val(date_to);
	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'project_site/_load_attendance_logs_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter},
	function(o) {
		$('#loading_wrapper').html('');
		$('#attendance_logs_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

*/

function load_attendance_logs_list_dt() {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();

	$("#s_from").val(date_from);
	$("#s_to").val(date_to);
	$("#s_error_type").val(error_type);	

	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'project_site/_load_attendance_logs_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#attendance_logs_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
// Employee List
function load_employee_list_dt() {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	
	$("#s_from").val(date_from);
	$("#s_to").val(date_to);
	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'project_site/load_employee_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#employee_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_employee_list_compress_dt() {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	
	$("#s_from").val(date_from);
	$("#s_to").val(date_to);
	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'project_site/load_employee_list_compress_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#employee_list_compress_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_all_errors_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_all_errors_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#all_errors_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_no_error_logs_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit_no_error").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_no_error_logs_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#no_error_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_incomplete_logs_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_incomplete_logs_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#incomplete_logs_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_multiple_in_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_multiple_in_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#multiple_in_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_multiple_out_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_multiple_out_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#multiple_out_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_no_sched_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_no_sched_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#no_sched_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_conflict_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit_conflict").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_conflict_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#conflict_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_leave_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit_leave").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_leave_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#leave_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_rd_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit_rd").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_rd_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#rd_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_ob_dt(from, to) {
	var date_from  = $("#from").val();
	var date_to    = $("#to").val();
	var error_type = $("#error_type").val();	
	var limit      = $("#dt_limit_ob").val();
	var hpid       = $("#hpid_n").val();
	var filter     = $("#error_filter").val();
	let device_id   = $("#filterByDevice").val();
	

	$("#s_error_type").val(error_type);	
	
	if($("#chk_employee").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.get(base_url + 'project_site/_load_ob_dt',{limit:limit,from:from,to:to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#ob_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function download_attendance_log() {
	var date_from  = $("#s_from").val();
	var date_to    = $("#s_to").val();
	var error_type = $("#s_error_type").val();
	var emp_sel	   = $("#s_emp_selected").val();
	let device_id   = $("#filterByDevice").val();

	if(error_type == "Incomplete Swipe"){
		error_type = "no_time_in_out";
	}else if(error_type == "Multiple Swipe"){
		error_type = "multiple_swipes";
	}else{
		error_type = "logs";
	}
	
	if(emp_sel != ""){
		$.post(base_url + 'project_site/convert_employee_id',{emp_sel:emp_sel},
		function(o) {
			location.href = base_url + 'project_site/download_logs?from=' + date_from + '&to=' + date_to + '&error_type=' + error_type + '&emp=' + o.did +'&device_id='+device_id;				
		},"json");		
	}else{
		location.href = base_url + 'project_site/download_logs?from=' + date_from + '&to=' + date_to + '&error_type=' + error_type  +'&device_id='+device_id;				
	}
	
}

function chkEmployee(chk) {
	if(chk.checked){		
		$("#autocomplete").hide();
		$("#all_emp").show();
	}else{
		$("#autocomplete").show();
		$("#all_emp").hide();
	}
}

function chkEmployeeIncomplete(chk) {
	if(chk.checked){		
		$("#autocomplete_incomplete").hide();
		$("#all_emp_incomplete").show();
	}else{
		$("#autocomplete_incomplete").show();
		$("#all_emp_incomplete").hide();
	}
}

function chkEmployeeMultipleIn(chk) {
	if(chk.checked){		
		$("#autocomplete_multiple_in").hide();
		$("#all_emp_multiple_in").show();
	}else{
		$("#autocomplete_multiple_in").show();
		$("#all_emp_multiple_in").hide();
	}
}

function chkEmployeeMultipleOut(chk) {
	if(chk.checked){		
		$("#autocomplete_multiple_out").hide();
		$("#all_emp_multiple_out").show();
	}else{
		$("#autocomplete_multiple_out").show();
		$("#all_emp_multiple_out").hide();
	}
}

function chkEmployeeNoError(chk) {
	if(chk.checked){		
		$("#autocomplete_no_error").hide();
		$("#all_emp_no_error").show();
	}else{
		$("#autocomplete_no_error").show();
		$("#all_emp_no_error").hide();
	}
}

function chkEmployeeNoSched(chk) {
	if(chk.checked){		
		$("#autocomplete_no_sched").hide();
		$("#all_emp_no_sched").show();
	}else{
		$("#autocomplete_no_sched").show();
		$("#all_emp_no_sched").hide();
	}
}

function changePayPeriodByYear(selected_year,selected_cutoff,class_container,selected_frequency) {
	$("." + class_container).html(loading_image);
	$.get(base_url + 'project_site/ajax_load_payroll_period_by_year',{selected_year:selected_year,selected_cutoff:selected_cutoff,selected_frequency:selected_frequency},
	  function(o){
	    $("." + class_container).html(o);     
	  }
	);
}  
//Project Site
function withSelectedLogs(action) {
	$('#chkAction').val('');
	
	var logs = [];

	$('input[name="dtrChk[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogs(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				load_attendance_logs_list_dt();
			},
			onError: function(o) {
				alert(o.message);	
			},
			onYes: function(o) {
			if(o.is_success) {								
				load_attendance_logs_list_dt();			
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
			}, 
			onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}

		});
	}
}

function withSelectedLogsAllErrors(action) {
	$('#chkActionAllErrors').val('');
	
	var logs = [];

	$('input[name="dtrChkAllErrors[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsAllErrors(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				load_attendance_logs_list_dt();
			},
			onError: function(o) {
				alert(o.message);	
			},
			onYes: function(o) {
			if(o.is_success) {								
				load_attendance_logs_list_dt();			
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
			}, 
			onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}

		});
	}
}

function withSelectedLogsIncompleteLogs(action) {
	$('#chkActionIncompleteLogs').val('');
	
	var logs = [];

	$('input[name="dtrChkIncompleteLogs[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsAllErrors(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				load_attendance_logs_list_dt();
			},
			onError: function(o) {
				alert(o.message);	
			},
			onYes: function(o) {
			if(o.is_success) {								
				load_attendance_logs_list_dt();			
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
			}, 
			onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}

		});
	}
}

function withSelectedLogsMultipleIn(action) {
	$('#chkActionMultipleIn').val('');
	
	var logs = [];

	$('input[name="dtrChkMultipleIn[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsAllErrors(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				load_attendance_logs_list_dt();
			},
			onError: function(o) {
				alert(o.message);	
			},
			onYes: function(o) {
			if(o.is_success) {								
				load_attendance_logs_list_dt();			
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
			}, 
			onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}

		});
	}
}

function withSelectedLogsMultipleOut(action) {
	$('#chkActionMultipleOut').val('');
	
	var logs = [];

	$('input[name="dtrChkMultipleOut[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsAllErrors(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				load_attendance_logs_list_dt();
			},
			onError: function(o) {
				alert(o.message);	
			},
			onYes: function(o) {
			if(o.is_success) {								
				load_attendance_logs_list_dt();			
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
			}, 
			onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}

		});
	}
}

function withSelectedLogsNoSched(action) {
	$('#chkActionMultipleOut').val('');
	
	var logs = [];

	$('input[name="dtrChkNoSched[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsAllErrors(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				load_attendance_logs_list_dt();
			},
			onError: function(o) {
				alert(o.message);	
			},
			onYes: function(o) {
			if(o.is_success) {								
				load_attendance_logs_list_dt();			
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
			}, 
			onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			}

		});
	}
}

function removeAllStaggeredScheduleMemberEmployees(schedule_group_public_id) {
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove employee from the list under their schedule/s?', {
	  onYes: function(){
        	_removeAllStaggeredScheduleMemberEmployees(schedule_group_public_id, {
        		onRemoved: function(message) {
        			//_showStaggeredScheduleMembersList('#schedule_members_list', schedule_group_public_id);
					load_employee_list_dt();
        		},
        		onLoading: function() {
        		    $('.remove_all_employees_link').html(' ' + loading_image + ' Removing...');
        			//$('#'+ row_id).remove();
        			//$('.tipsy-inner').remove();
        		},
        		onBeforeRemove: function() {
        			return true;
        		},
        		onError: function(message) {
        			alert(message);
        		}
        	});
		}
	});
}

function removeSchedule(schedule_template) {
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove selected schedule?', {
	  onYes: function(){
			_removeSchedule(schedule_template, {
        		onRemoved: function(message) {
					//load_staggered_schedule_list_dt();
					location.reload();
        		},
        		onLoading: function() {
        		    $('.remove_all_employees_link').html(' ' + loading_image + ' Removing...');
        			//$('#'+ row_id).remove();
        			//$('.tipsy-inner').remove();
        		},
        		onBeforeRemove: function() {
        			return true;
        		},
        		onError: function(message) {
        			alert(message);
        		}
        	});
		}
	});
}

function removeAllCompressScheduleMemberEmployees(schedule_group_public_id) {
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove employee from the list under their schedule/s?', {
	  onYes: function(){
        	_removeAllCompressScheduleMemberEmployees(schedule_group_public_id, {
        		onRemoved: function(message) {
        			//_showStaggeredScheduleMembersList('#schedule_members_list', schedule_group_public_id);
					load_employee_list_compress_dt();
        		},
        		onLoading: function() {
        		    $('.remove_all_employees_link').html(' ' + loading_image + ' Removing...');
        			//$('#'+ row_id).remove();
        			//$('.tipsy-inner').remove();
        		},
        		onBeforeRemove: function() {
        			return true;
        		},
        		onError: function(message) {
        			alert(message);
        		}
        	});
		}
	});
}

function modalSyncSiteAttendanceData() {
	_modalSyncSiteAttendanceData({
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

function _modalSyncSiteAttendanceData(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Synchronize Attendance';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'project_site/ajax_synchronize_attendance', {}, function(data) {
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