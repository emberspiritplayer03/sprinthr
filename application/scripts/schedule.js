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
	}else if(hash=='#philhealth') {
		displayPage({canvass:'#philhealth_wrapper',parameter:'reports/_load_philhealth'});
	}
}

function hide_all_canvass() {
	$("#shift_schedule_wrapper").hide();
	$("#bir_2316_wrapper").hide();
}

function editSpecificSchedule(schedule_id) {
	_editSpecificSchedule(schedule_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			var query = window.location.search;
			$.get(base_url + 'schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
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
			$.get(base_url + 'schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
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

function createAndAssignWeeklySchedule(employee_id) {
	_createWeeklySchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);		
			$('#schedule_'+ employee_id).html(loading_image + ' Updating...');	
			_directAssignGroupScheduleToEmployee(o.schedule_group_id, employee_id,{
				onAssigned: function(data) {					
					var query = window.location.search;
					$.get(base_url + 'schedule/show_employee_schedule'+ query, {ajax:1}, function(html_data){
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
				dialogOkBox(o.message,{ok_url: "schedule/show_department_schedule?eid=" + o.eid});
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

function removeGroupSchedule(group_eid, group_schedule_eid) {
	showLoadingDialog('Loading...');	
	$('#status_message').html('');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to remove the schedule in this group?', {
		onYes: function(){
			_removeScheduleToGroup(group_eid, group_schedule_eid, {
				onRemoved: function(o) {
					$dialog.dialog('destroy');
					dialogOkBox(o.message,{ok_url: "schedule/show_department_schedule?eid=" + o.eid});						
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
	$.get(base_url + 'schedule/ajax_show_schedule_list', function(data) {
		$(element).html(data);
	});		
}

function showWeeklyScheduleList(element) {
	$(element).html(loading_image + ' Loading...');
	$.get(base_url + 'schedule/ajax_show_weekly_schedule_list', function(data) {
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

function cancelCreateSchedule() {
	$('.formError').remove();
	$('#create_schedule_link').show();
	$('#create_schedule_handler').html('');	
}

function assignSchedule(schedule_id) {
	_assignScheduleGroupsAndEmployees(schedule_id, {
		onSave: function(o) {
			location.href = base_url + 'schedule/show_schedule?id=' + o.public_id;
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



