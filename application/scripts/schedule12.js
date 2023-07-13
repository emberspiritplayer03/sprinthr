$(function(){
	function hashCheck(){
        var hash = window.location.hash;
		loadPage(hash);
        $(".left_nav").removeClass("selected");
	   $(hash+"_nav").addClass("selected");  
    }
    hashCheck();
});
function hashClick(hash) {	
	var hash = hash;
	loadPage(hash); 
	
    $(".left_nav").removeClass("selected");
    $(hash+"_nav").addClass("selected");  
}

function loadPage(hash) 
{
	hide_all_canvass();
	
	if(hash=='#shift_schedule') {	
		displayPage({canvass:'#shift_schedule_wrapper',parameter:'new_schedule/_load_shift_schedule'});
	}
}

function hide_all_canvass() {
	$("#shift_schedule_wrapper").hide();
}

function editSpecificSchedule(schedule_id) {
	_editSpecificSchedule(schedule_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			var query = window.location.search;
			$.get(base_url + 'new_schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
				$('#main').html(html_data)
			});
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


function deleteSpecificSchedule(schedule_id) {
	//$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('Loading...');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this schedule?', {
		onYes: function(){
			_deleteSpecificSchedule(schedule_id, {
				onDeleted: function(o) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					var query = window.location.search;
					$.get(base_url + 'new_schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
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

function removeAllScheduleMemberEmployees(schedule_group_public_id) {
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove employees under this schedule?', {
	  onYes: function(){
        	_removeAllScheduleMemberEmployees(schedule_group_public_id, {
        		onRemoved: function(message) {
        			showScheduleMembersList('#schedule_members_list', schedule_group_public_id);
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

function removeAllStaggeredScheduleMemberEmployees(schedule_group_public_id) {
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove employees under this schedule?', {
	  onYes: function(){
        	_removeAllStaggeredScheduleMemberEmployees(schedule_group_public_id, {
        		onRemoved: function(message) {
        			showStaggeredScheduleMembersList('#schedule_members_list', schedule_group_public_id);
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

function deleteScheduleList(schedule_id) {
	//$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('Loading...');
	//var count = _countMembers(schedule_id);
	//if (count > 0) {
	//	$('#status_message').html('');
	//	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	//	showOkDialog('You have to remove first all groups and employees before you can delete this schedule.');
	//} else {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showYesNoDialog('Are you sure you want to delete this schedule?', {
			onYes: function(){
				_deleteSchedule(schedule_id, {
					onDeleted: function(message) {
						$dialog.dialog('destroy');	
						disablePopUp();
						showWeeklyScheduleList('#schedule_list');
					},
					onLoading: function() {
						disablePopUp();
						showLoadingDialog('Deleting...');
					},
					onBeforeDelete: function() {
						return true;
					},
					onError: function(message) {
						alert(message);	
					}
				});	
			}
		});		
	//}
}


function createSpecificSchedule(employee_id) {
	_createSpecificSchedule(employee_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			var query = window.location.search;
			$.get(base_url + 'new_schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
				$('#main').html(html_data)	
			});				
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function importScheduleSpecific() {
	_importScheduleSpecific({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onImported: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();				
			showOkDialog(o.message, {
				onOk: function() {
					showWeeklyScheduleList('#schedule_list');
				}
			});
		},
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function importSchedule() {
	_importSchedule({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onImported: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();				
			showOkDialog(o.message, {
				onOk: function() {
					showWeeklyScheduleList('#schedule_list');
				}
			});
		},
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function importEmployeesInSchedule(public_id) {
	_importEmployeesInSchedule(public_id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onImported: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();				
			showOkDialog(o.message, {
				onOk: function() {
					showScheduleMembersList('#schedule_members_list', public_id);					
				}
			});
		},
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function editWeeklyScheduleFromList(public_id) {
	_editWeeklySchedule(public_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showWeeklyScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function editWeeklySchedule(public_id) {
	_editWeeklySchedule(public_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$('.payslip_period_container h2 b').html(o.title_string);
			$('.payslip_period_container h2 span').html(o.schedule_string);
            $('.view_schedule .styled_items_holder ul').html(o.schedule_string);
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;
			}
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function editStaggeredScheduleFromList(public_id) {
	_editStaggeredSchedule(public_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showStaggeredScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function editStaggeredSchedule(public_id) {
	_editStaggeredSchedule(public_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$('.payslip_period_container h2 b').html(o.title_string);
			$('.payslip_period_container h2 span').html(o.schedule_string);
            $('.view_schedule .styled_items_holder ul').html(o.schedule_string);
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;
			}
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function changePayPeriodByYear(selected_year,selected_cutoff,class_container,selected_frequency) {
	$("." + class_container).html(loading_image);
	$.get(base_url + 'new_schedule/ajax_load_payroll_period_by_year',{selected_year:selected_year,selected_cutoff:selected_cutoff,selected_frequency:selected_frequency},
	  function(o){
	    $("." + class_container).html(o);     
	  }
	);
}  

function createAndAssignWeeklySchedule(employee_id) {
	_createWeeklySchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);		
			$('#schedule_'+ employee_id).html(loading_image + ' Updating...');	
			_directAssignGroupScheduleToEmployee(o.schedule_group_id, employee_id,{
				onAssigned: function(data) {					
					var query = window.location.search;
					$.get(base_url + 'new_schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
						$('#main').html(html_data)
					});	
				},
				onError: function(data) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(data.message);
				}
			});
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			//$('#message_container').hide();
			//cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function createAndAssignWeeklyScheduleToGroup(group_id) {
	_createGroupWeeklySchedule(group_id,{
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);		
			if( o.is_success ){				
				dialogOkBox(o.message,{ok_url: "new_schedule/show_department_schedule?eid=" + o.eid});
			}else{
				showOkDialog(o.message);
			}
			$("#token").val(o.token);
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {		
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function createWeeklySchedule() {
	_createWeeklySchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showWeeklyScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function createShiftSchedule() {
	_createShiftSchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showWeeklyScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function createFlexibleSchedule() {
	_createFlexibleSchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showWeeklyScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function createStaggeredSchedule() {
	_createStaggeredSchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showStaggeredScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function createCompressSchedule() {
	_createCompressSchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showStaggeredScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function editScheduleFromList(schedule_id) {
	_editSchedule(schedule_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('Loading...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function editSchedule(schedule_id) {
	_editSchedule(schedule_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$('h2').html(o.schedule_name +' '+ '<small>('+ o.working_days +' - '+ o.time_in +' - '+ o.time_out +')</small>');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {			
			showLoadingDialog('Loading...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function closeTheDialog() {
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_schedule_form');
	$('.formError').remove();
}

function deleteSchedule(schedule_id) {
	$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('Loading...');
	var count = _countMembers(schedule_id);
	if (count > 0) {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showOkDialog('You have to remove first all groups and employees before you can delete this schedule.');
	} else {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showYesNoDialog('Are you sure you want to delete this schedule?', {
			onYes: function(){
				_deleteSchedule(schedule_id, {
					onDeleted: function(message) {
						$dialog.dialog('destroy');	
						disablePopUp();
						location.href = base_url + 'schedule';	
					},
					onLoading: function() {
						disablePopUp();
						showLoadingDialog('Deleting...');
					},
					onBeforeDelete: function() {
						return true;
					},
					onError: function(message) {
						alert(message);	
					}
				});	
			}
		});		
	}
}

function deleteStaggeredSchedule(schedule_id) {
	$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('Loading...');
	var count = _countMembers(schedule_id);
	if (count > 0) {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showOkDialog('You have to remove first all groups and employees before you can delete this schedule.');
	} else {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showYesNoDialog('Are you sure you want to delete this schedule?', {
			onYes: function(){
				_deleteStaggeredSchedule(schedule_id, {
					onDeleted: function(message) {
						$dialog.dialog('destroy');	
						disablePopUp();
						location.href = base_url + 'new_schedule';	
					},
					onLoading: function() {
						disablePopUp();
						showLoadingDialog('Deleting...');
					},
					onBeforeDelete: function() {
						return true;
					},
					onError: function(message) {
						alert(message);	
					}
				});	
			}
		});		
	}
}

function removeScheduleMember(employee_group_id, schedule_id, employee_or_group) { // employee_or_group (employee, group)
	_removeScheduleMember(employee_group_id, schedule_id, employee_or_group, {
		onRemoved: function(message) {
			//showScheduleMembersList('#schedule_members_list', schedule_id);
		},
		onLoading: function() {
			var row_id = employee_group_id +'-'+ schedule_id +'-'+ employee_or_group;
			$('#'+ row_id).remove();
			$('.tipsy-inner').remove();
		},
		onBeforeRemove: function() {
			return true;
		},
		onError: function(message) {
			alert(message);
		}
	});
}

function removeStaggeredScheduleMember(employee_group_id, schedule_id, employee_or_group) { // employee_or_group (employee, group)
	_removeStaggeredScheduleMember(employee_group_id, schedule_id, employee_or_group, {
		onRemoved: function(message) {
			showStaggeredScheduleMembersList('#schedule_members_list', schedule_id);
		},
		onLoading: function() {
			var row_id = employee_group_id +'-'+ schedule_id +'-'+ employee_or_group;
			$('#'+ row_id).remove();
			$('.tipsy-inner').remove();
		},
		onBeforeRemove: function() {
			return true;
		},
		onError: function(message) {
			alert(message);
		}
	});
}

function removeGroupSchedule(group_eid, group_schedule_eid) {
	showLoadingDialog('Loading...');	
	$('#status_message').html('');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove the schedule in this group?', {
		onYes: function(){
			_removeScheduleToGroup(group_eid, group_schedule_eid, {
				onRemoved: function(o) {
					$dialog.dialog('destroy');
					dialogOkBox(o.message,{ok_url: "new_schedule/show_department_schedule?eid=" + o.eid});						
				},
				onLoading: function() {
					disablePopUp();
					showLoadingDialog('Removing schedule to group...',{width:'250px'});
				},
				onBeforeRemove: function() {
					return true;
				},
				onError: function(o) {
					showOkDialog(o.message);
				}
			});	
		}
	});		
}

function createSchedule() {
	var create_schedule_form = $('#create_schedule_id').html();
	$('#create_schedule_handler').html(create_schedule_form);
	$("#schedule_name").focus();
	$('#schedule_name').select();
	$('#create_schedule_link').hide();
	$('#message_container').hide();
	$('.message').html('');
	
	$("#create_schedule_form").validationEngine({scroll: false});	
	$('#create_schedule_form').ajaxForm({
		success:function(o) {	
			//$('.formError').remove();
			$('#create_schedule_link').show();
			$('#create_schedule_handler').html('');
			$('.message').html(o.message);
			$('#message_container').show();
			showScheduleList('#schedule_list');
		},
		beforeSubmit:function() {
			var error = 0;
			var n = $("input:checked").length;
			var count_checked = (n - 5);
			if (count_checked == 0) {
				error++;
				$('#maxcheck1').validationEngine('showPrompt', '* Please select 1 option', 'error');				
			} else {
				$('#maxcheck1').validationEngine('hide');
			}
			var schedule_name = $('#schedule_name').val();
			if ($.trim(schedule_name) == '') {
				error++;
				$('#schedule_name').validationEngine('showPrompt', '* This field is required', 'error');
			} else {
				$('#schedule_name').val($.trim(schedule_name));
				$('#schedule_name').validationEngine('hide');
			}
				
			if (error > 0) {
				return false;
			} else {
				$('#create_schedule_form').validationEngine('hideAll')
				$('#form_submit').html(loading_image + ' Creating... ');
				$('#form_cancel').hide();
				return true;	
			}
		},
		dataType:'json'
	});	
}

function showScheduleList(element) {
	$.get(base_url + 'new_schedule/ajax_show_schedule_list', function(data) {
		$(element).html(data);
	});		
}

function showWeeklyScheduleList(element) {
	$(element).html(loading_image + ' Loading...');
	$.get(base_url + 'new_schedule/ajax_show_weekly_schedule_list', function(data) {
		$(element).html(data);
	});
}

function showStaggeredScheduleList(element) {
	$(element).html(loading_image + ' Loading...');
	$.get(base_url + 'new_schedule/ajax_show_staggered_schedule_list', function(data) {
		$(element).html(data);
	});
}

function showScheduleMembersList(element, schedule_id) {
	_showScheduleMembersList(element, schedule_id, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function showStaggeredScheduleMembersList(element, schedule_id) {
	_showStaggeredScheduleMembersList(element, schedule_id, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
//Dashboard
//shift
function showDashboardShiftScheduleMembersList(element) {
	_showDashboardShiftScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardShiftLeaveScheduleMembersList(element) {
	_showDashboardShiftLeaveScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardShiftOBScheduleMembersList(element) {
	_showDashboardShiftOBScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardShiftNoScheduleMembersList(element) {
	_showDashboardShiftNoScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
//compress
function showDashboardCompressScheduleMembersList(element) {
	_showDashboardCompressScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardCompressLeaveScheduleMembersList(element) {
	_showDashboardCompressLeaveScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardCompressOBScheduleMembersList(element) {
	_showDashboardCompressOBScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardCompressNoScheduleMembersList(element) {
	_showDashboardCompressNoScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
//staggered
function showDashboardStaggeredScheduleMembersList(element) {
	_showDashboardStaggeredScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardStaggeredLeaveScheduleMembersList(element) {
	_showDashboardStaggeredLeaveScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardStaggeredOBScheduleMembersList(element) {
	_showDashboardStaggeredOBScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardStaggeredNoScheduleMembersList(element) {
	_showDashboardStaggeredNoScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
//flextime
function showDashboardFlextimeScheduleMembersList(element) {
	_showDashboardFlextimeScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardFlextimeLeaveScheduleMembersList(element) {
	_showDashboardFlextimeLeaveScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardFlextimeOBScheduleMembersList(element) {
	_showDashboardFlextimeOBScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function showDashboardFlextimeNoScheduleMembersList(element) {
	_showDashboardFlextimeNoScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

//Load Dashboard
//shift
function load_dashboard_shift_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_shift_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_shift_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_shift_leave_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_shift_leave_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_shift_leave_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_shift_ob_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_shift_ob_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_shift_ob_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_shift_no_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_shift_no_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_shift_no_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_shift_schedule_list_dt() {
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
	$.post(base_url + 'new_schedule/load_shift_schedule_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#schedule_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
//compress
function load_dashboard_compress_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_compress_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_compress_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_compress_leave_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_compress_leave_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_compress_leave_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_compress_ob_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_compress_ob_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_compress_ob_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_compress_no_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_compress_no_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_compress_no_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_compressed_schedule_list_dt() {
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
	$.post(base_url + 'new_schedule/load_compressed_schedule_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#schedule_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
//staggered
function load_dashboard_staggered_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_staggered_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_staggered_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_staggered_leave_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_staggered_leave_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_staggered_leave_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_staggered_ob_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_staggered_ob_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_staggered_ob_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_staggered_no_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_staggered_no_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_staggered_no_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_staggered_schedule_list_dt() {
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
	$.post(base_url + 'new_schedule/load_staggered_schedule_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#schedule_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
//flextime
function load_dashboard_flextime_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_flextime_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_flextime_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_flextime_leave_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_flextime_leave_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_flextime_leave_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_flextime_ob_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_flextime_ob_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_flextime_ob_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_dashboard_flextime_no_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_dashboard_flextime_no_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#dashboard_flextime_no_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}
function load_flexible_schedule_list_dt() {
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
	$.post(base_url + 'new_schedule/load_flexible_schedule_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#schedule_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

//Set Employee Schedule
function showSetEmployeeShiftSchedule(element) {
	_showSetEmployeeShiftSchedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function showSetEmployeeCompressSchedule(element) {
	_showSetEmployeeCompressSchedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function showSetEmployeeStaggeredSchedule(element) {
	_showSetEmployeeStaggeredSchedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function showSetEmployeeFlextimeSchedule(element) {
	_showSetEmployeeFlextimeSchedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function showSetEmployeeNoSchedule(element) {
	_showSetEmployeeNoSchedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function load_set_employee_shift_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_set_employee_shift_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#set_employee_shift_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_set_employee_compress_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_set_employee_compress_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#set_employee_compress_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_set_employee_staggered_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_set_employee_staggered_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#set_employee_staggered_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_set_employee_flextime_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_set_employee_flextime_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#set_employee_flextime_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_set_employee_no_schedule_dt() {
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
	$.post(base_url + 'new_schedule/load_set_employee_no_schedule_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#set_employee_no_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
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

function cancelCreateSchedule() {
	$('.formError').remove();
	$('#create_schedule_link').show();
	$('#create_schedule_handler').html('');	
}

function assignSchedule(schedule_id) {
	_assignScheduleGroupsAndEmployees(schedule_id, {
		onSave: function(o) {
			location.href = base_url + 'new_schedule/show_schedule?id=' + o.public_id;
		},
		onError: function(message) {
			alert(message);
		},		
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if (n == 0) {
				showOkDialog('You have to select at least 1 group or employee', {
					onOk: function() {
						assignSchedule(schedule_id);
					}
				});
				return false;
			} else {				
				return true;	
			}
		}
	});
}

function assignScheduleGroups(schedule_id) {
	_assignScheduleGroups(schedule_id, {
		onSave: function(message) {
			_showScheduleMembersList('#schedule_members_list', schedule_id, {
				onSuccess: function() {
					$('#status_message').html('');
				},
				onLoading: function() {
					$('#status_message').html(' ' + loading_image + ' Updating...');
				}
			});			
		},
		onError: function(message) {
			alert(message);
		},		
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if (n == 0) {
				showOkDialog('You have to select at least 1 group or department', {
					onOk: function() {
						assignScheduleEmployees(schedule_id);
					}
				});
				return false;
			} else {				
				return true;	
			}
		}
	});
}

function assignScheduleEmployees(schedule_id) {
	_assignScheduleEmployees(schedule_id, {
		onSave: function(message) {
			_showScheduleMembersList('#schedule_members_list', schedule_id, {
				onSuccess: function() {
					$('#status_message').html('');
				},
				onLoading: function() {
					$('#status_message').html(' ' + loading_image + ' Updating...');
				}
			});			
		},
		onError: function(message) {
			alert(message);
		},		
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignScheduleEmployees(schedule_id);
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}

function assignStaggeredScheduleEmployees(schedule_id) {
	_assignStaggeredScheduleEmployees(schedule_id, {
		onSave: function(message) {
			_showStaggeredScheduleMembersList('#schedule_members_list', schedule_id, {
				onSuccess: function() {
					$('#status_message').html('');
				},
				onLoading: function() {
					$('#status_message').html(' ' + loading_image + ' Updating...');
				}
			});			
		},
		onError: function(message) {
			alert(message);
		},		
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignStaggeredScheduleEmployees(schedule_id);
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}

function assignCompressScheduleEmployees(schedule_id) {
	_assignStaggeredScheduleEmployees(schedule_id, {
		onSave: function(message) {
			_showStaggeredScheduleMembersList('#schedule_members_list', schedule_id, {
				onSuccess: function() {
					$('#status_message').html('');
				},
				onLoading: function() {
					$('#status_message').html(' ' + loading_image + ' Updating...');
				}
			});			
		},
		onError: function(message) {
			alert(message);
		},		
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignStaggeredScheduleEmployees(schedule_id);
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}
//All employee
function chkEmployee(chk) {
	if(chk.checked){		
		$("#autocomplete").hide();
		$("#all_emp").show();
	}else{
		$("#autocomplete").show();
		$("#all_emp").hide();
	}
}

function chkEmployeeNoStaggeredSchedule(chk) {
	if(chk.checked){		
		$("#autocomplete_no_staggered").hide();
		$("#all_emp_no_staggered").show();
	}else{
		$("#autocomplete_no_staggered").show();
		$("#all_emp_no_staggered").hide();
	}
}

function chkEmployeeLeaveStaggeredSchedule(chk) {
	if(chk.checked){		
		$("#autocomplete_leave_staggered").hide();
		$("#all_emp_leave_staggered").show();
	}else{
		$("#autocomplete_leave_staggered").show();
		$("#all_emp_leave_staggered").hide();
	}
}

function chkEmployeeRestDayStaggeredSchedule(chk) {
	if(chk.checked){		
		$("#autocomplete_rd_staggered").hide();
		$("#all_emp_rd_staggered").show();
	}else{
		$("#autocomplete_rd_staggered").show();
		$("#all_emp_rd_staggered").hide();
	}
}

function chkEmployeeOfficialBusinessStaggeredSchedule(chk) {
	if(chk.checked){		
		$("#autocomplete_ob_staggered").hide();
		$("#all_emp_ob_staggered").show();
	}else{
		$("#autocomplete_ob_staggered").show();
		$("#all_emp_ob_staggered").hide();
	}
}
//Schedule Main
function showAllScheduleMembersList(element) {
	_showAllScheduleMembersList(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}
function load_all_schedule_dt() {
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
		var emp_sel	   = $("#emp_selected_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_schedule_main_all_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#all_schedule_main_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

//Staggered Schedule
function load_staggered_schedule(element) {
	_load_staggered_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_staggered_schedule_dt() {
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
		var emp_sel	   = $("#emp_selected_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_staggered_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#staggered_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_no_staggered_schedule(element) {
	_load_no_staggered_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_no_staggered_schedule_dt() {
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

	if($("#chk_employee_no_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_no_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_no_staggered_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#no_staggered_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_leave_staggered_schedule(element) {
	_load_leave_staggered_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_leave_staggered_schedule_dt() {
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

	if($("#chk_employee_leave_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_leave_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_leave_staggered_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#leave_staggered_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_rest_day_staggered_schedule(element) {
	_load_rest_day_staggered_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_rest_day_staggered_schedule_dt() {
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

	if($("#chk_employee_rd_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_rd_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_rest_day_staggered_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#rest_day_staggered_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_ob_staggered_schedule(element) {
	_load_ob_staggered_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_ob_staggered_schedule_dt() {
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

	if($("#chk_employee_ob_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_ob_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_ob_staggered_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#ob_staggered_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

//Compress Schedule
function load_compress_schedule(element) {
	_load_compress_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_compress_schedule_dt() {
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
		var emp_sel	   = $("#emp_selected_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_compress_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#compress_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_no_compress_schedule(element) {
	_load_no_compress_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_no_compress_schedule_dt() {
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

	if($("#chk_employee_no_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_no_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_no_compress_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#no_compress_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_leave_compress_schedule(element) {
	_load_leave_compress_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_leave_compress_schedule_dt() {
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

	if($("#chk_employee_leave_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_leave_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_leave_compress_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#leave_compress_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_rest_day_compress_schedule(element) {
	_load_rest_day_compress_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_rest_day_compress_schedule_dt() {
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

	if($("#chk_employee_rd_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_rd_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_rest_day_compress_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#rest_day_compress_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_ob_compress_schedule(element) {
	_load_ob_compress_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_ob_compress_schedule_dt() {
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

	if($("#chk_employee_ob_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_ob_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_ob_compress_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#ob_compress_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}
//Shift Schedule
function load_shift_schedule(element) {
	_load_shift_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_shift_schedule_dt() {
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
		var emp_sel	   = $("#emp_selected_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_shift_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#shift_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_no_shift_schedule(element) {
	_load_no_shift_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_no_shift_schedule_dt() {
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

	if($("#chk_employee_no_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_no_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_no_shift_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#no_shift_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_leave_shift_schedule(element) {
	_load_leave_shift_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_leave_shift_schedule_dt() {
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

	if($("#chk_employee_leave_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_leave_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_leave_shift_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#leave_shift_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_rest_day_shift_schedule(element) {
	_load_rest_day_shift_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_rest_day_shift_schedule_dt() {
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

	if($("#chk_employee_rd_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_rd_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_rest_day_shift_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#rest_day_shift_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_ob_shift_schedule(element) {
	_load_ob_shift_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_ob_shift_schedule_dt() {
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

	if($("#chk_employee_ob_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_ob_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_ob_shift_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#ob_shift_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}
//Flextime Schedule
function load_flextime_schedule(element) {
	_load_flextime_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_flextime_schedule_dt() {
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
		var emp_sel	   = $("#emp_selected_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_flextime_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#flextime_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_no_flextime_schedule(element) {
	_load_no_flextime_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_no_flextime_schedule_dt() {
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

	if($("#chk_employee_no_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_no_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_no_flextime_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#no_flextime_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_leave_flextime_schedule(element) {
	_load_leave_flextime_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_leave_flextime_schedule_dt() {
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

	if($("#chk_employee_leave_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_leave_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_leave_flextime_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#leave_flextime_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_rest_day_flextime_schedule(element) {
	_load_rest_day_flextime_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_rest_day_flextime_schedule_dt() {
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

	if($("#chk_employee_rd_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_rd_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_rest_day_flextime_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#rest_day_flextime_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}

function load_ob_flextime_schedule(element) {
	_load_ob_flextime_schedule(element, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});	
}

function load_ob_flextime_schedule_dt() {
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

	if($("#chk_employee_ob_staggered").is(':checked')){	
		var emp_sel	   = "";				
	}else{
		var emp_sel	   = $("#emp_selected_ob_staggered").val();	
	}
	
	$("#s_emp_selected").val(emp_sel);	

	if(hpid != '' && emp_sel == '') {
		emp_sel = hpid;
	}
	
	$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
	$.post(base_url + 'new_schedule/_load_ob_flextime_schedule_dt',{limit:limit,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter, device_id},
	function(o) {
		$('#loading_wrapper').html('');
		$('#ob_flextime_schedule_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");
}
//Edit Schedule
function editEmployeeSchedule(eid_in) {
	_editEmployeeSchedule(eid_in, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			location.reload();
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editSchedule(id) {
	_editSchedule(id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			location.reload();
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function editEmployeeNoSchedule(eid_in) {
	_editEmployeeNoSchedule(eid_in, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			location.reload();
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

//Batch Update
function withSelectedLogsAll(action) {
	$('#chkAction').val('');
	
	var logs = [];

	$('input[name="dtrChk[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsAll(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				location.reload();
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

function withSelectedLogsNoSchedule(action) {
	$('#chkActionNoSchedule').val('');
	
	var logs = [];

	$('input[name="dtrChkNoSchedule[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsNoSchedule(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				location.reload();
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

function withSelectedLogsLeave(action) {
	$('#chkActionLeave').val('');
	
	var logs = [];

	$('input[name="dtrChkLeave[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsLeave(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				location.reload();
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

function withSelectedLogsRestDay(action) {
	$('#chkActionRestDay').val('');
	
	var logs = [];

	$('input[name="dtrChkRestDay[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsRestDay(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				location.reload();
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

function withSelectedLogsOB(action) {
	$('#chkActionOB').val('');
	
	var logs = [];

	$('input[name="dtrChkOB[]"]:checked').each(function(){
		logs.push({
			id: $(this).val(),
			type: $(this).data('type') ? $(this).data('type') : ''
		});
	});

	if (logs.length) {
		_withSelectedLogsOB(logs, action, {
			onLoading: function() {
				showLoadingDialog('Loading...');
			},
			onSaving: function() {
				closeTheDialog();
				showLoadingDialog('Saving...');
			},
			onSaved: function(o) {
				closeTheDialog();
				location.reload();
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

// Employee List
function load_staggered_schedule_employee_list_dt() {
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
	$.post(base_url + 'new_schedule/load_staggered_schedule_employee_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#employee_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_compressed_schedule_employee_list_dt() {
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
	$.post(base_url + 'new_schedule/load_compressed_schedule_employee_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#employee_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_shift_schedule_employee_list_dt() {
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
	$.post(base_url + 'new_schedule/load_shift_schedule_employee_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#employee_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

function load_flexible_schedule_employee_list_dt() {
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
	$.post(base_url + 'new_schedule/load_flexible_schedule_employee_list_dt',
	function(o) {
		$('#loading_wrapper').html('');
		$('#employee_list_dt_wrapper').html(o.table);
		$('.paginator').html(o.paginator)
	},"json");	
}

//Add Employee
function assignStaggeredScheduleEmployeeList() {
	_assignStaggeredScheduleEmployeeList( {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSave: function(o) {
			closeTheDialog();
			load_staggered_schedule_employee_list_dt();	
		},
		onError: function(o) {
			alert(o.message);	
		},
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignStaggeredScheduleEmployeeList();
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}

function assignCompressScheduleEmployeeList() {
	_assignCompressScheduleEmployeeList( {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSave: function(o) {
			closeTheDialog();
			load_employee_list_compress_dt();	
		},
		onError: function(o) {
			alert(o.message);	
		},
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignCompressScheduleEmployees();
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}

function assignFlexibleScheduleEmployeeList() {
	_assignFlexibleScheduleEmployeeList( {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSave: function(o) {
			closeTheDialog();
			load_employee_list_compress_dt();	
		},
		onError: function(o) {
			alert(o.message);	
		},
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignFlexibleScheduleEmployeeList();
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}

function assignShiftScheduleEmployeeList() {
	_assignShiftScheduleEmployeeList( {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSave: function(o) {
			closeTheDialog();
			load_employee_list_compress_dt();	
		},
		onError: function(o) {
			alert(o.message);	
		},
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if($(".all-employees").is(':checked')){
				return true
			}else if(n == 0){
				showOkDialog('You have to select at least 1 employee', {
					onOk: function() {
						assignShiftScheduleEmployeeList();
					}
				});
				return false;
			}else{
				return true;
			}
		}
	});
}