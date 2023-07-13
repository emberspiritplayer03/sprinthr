function _editSpecificSchedule(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 400;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_edit_specific_schedule_form', {schedule_id:schedule_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_specific_schedule_form'
		});

		$('#edit_specific_schedule_form').ajaxForm({
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

function _deleteSpecificSchedule(schedule_id, events) {
	var ans = true;
	if (typeof events.onBeforeDelete == "function") {
		ans = events.onBeforeDelete();
	}
	if (ans) {
		if (typeof events.onDeleting == "function") {
			events.onDeleting();
		}
		$.post(base_url + 'new_schedule/_delete_specific_schedule', {schedule_id:schedule_id}, function(o) {
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

function _createSpecificSchedule(employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Schedule';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_add_specific_schedule', {employee_id:employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_specific_schedule_form'
		});

		$('#add_specific_schedule_form').ajaxForm({
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

function _directAssignGroupScheduleToEmployee(schedule_group_id, employee_id, events) {
	if (typeof events.onSaving == "function") {
		events.onSaving();
	}
	$.post(base_url + 'new_schedule/_assign_group_schedule_to_employee', {schedule_group_id:schedule_group_id, employee_id:employee_id}, function(o) {
		if (o.is_assigned) {
			if (typeof events.onAssigned == "function") {		
				events.onAssigned(o);
			}
		} else {
			if (typeof events.onError == "function") {			
				events.onError(o);
			}
		}
	}, 'json');	
}

function _importScheduleSpecific(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Changed Schedule';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'new_schedule/ajax_import_schedule_specific', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#import_schedule_specific_form'
		});
		$('#import_schedule_specific_form').validationEngine({scroll:false});		
		$('#import_schedule_specific_form').ajaxForm({
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
				if ($('#import_schedule_specific_file').val() == '') {
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

function _importSchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Schedule';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'new_schedule/ajax_import_schedule', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#import_schedule_form'
		});
		$('#import_schedule_form').validationEngine({scroll:false});		
		$('#import_schedule_form').ajaxForm({
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
				if ($('#import_schedule_file').val() == '') {
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

function _importEmployeesInSchedule(public_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Employees';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'new_schedule/ajax_import_employees_in_schedule', {public_id:public_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#import_employees_in_schedule'
		});
		$('#import_employees_in_schedule').validationEngine({scroll:false});		
		$('#import_employees_in_schedule').ajaxForm({
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
				if ($('#import_employees').val() == '') {
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

function _createWeeklySchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_add_weekly_schedule_form', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function _createShiftSchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Flexible Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_create_shift_schedule', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function _createFlexibleSchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Flexible Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_create_flexible_schedule', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}


function _createStaggeredSchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Staggered Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_create_staggered_schedule', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function _createCompressSchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Compress Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_create_compress_schedule', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function _createGroupWeeklySchedule(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_group_add_weekly_schedule_form', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function _editWeeklySchedule(public_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 500;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_edit_weekly_schedule_form', {public_id:public_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_schedule_form'
		});
		
		$('#name').select();
		$('#edit_schedule_form').validationEngine({scroll:false});
		$('#edit_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#edit_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;
				}
			}
		});
	});
}

function _editStaggeredSchedule(public_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Staggered Schedule';
	var width = 500;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'new_schedule/ajax_edit_staggered_schedule_form', {public_id:public_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_schedule_form'
		});
		
		$('#name').select();
		$('#edit_schedule_form').validationEngine({scroll:false});
		$('#edit_schedule_form').ajaxForm({
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
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#edit_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;
				}
			}
		});
	});
}

function _countMembers(schedule_id, events) {
	var count;
	$.ajax(base_url + 'new_schedule/_count_members', {
		type:'POST',
		data: 'schedule_id='+ schedule_id,
		async: false,
		success: function(o){
    		count = o.count;
		},
		dataType: 'json'
	});
	return count;
}

function _deleteSchedule(schedule_id, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeDelete();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}
		$.post(base_url + 'new_schedule/_delete_schedule', {schedule_id:schedule_id}, function(o) {
			if (o.is_deleted) {
				if (events) { events.onDeleted(o.message);}
			} else {
				if (events) { events.onError(o.message);}
			}
		},'json');
	}
}

function _deleteStaggeredSchedule(schedule_id, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeDelete();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}
		$.post(base_url + 'new_schedule/_delete_staggered_schedule', {schedule_id:schedule_id}, function(o) {
			if (o.is_deleted) {
				if (events) { events.onDeleted(o.message);}
			} else {
				if (events) { events.onError(o.message);}
			}
		},'json');
	}
}

function _removeScheduleToGroup(group_eid, group_schedule_eid, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeRemove();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}

		$.post(base_url + 'new_schedule/_remove_group_schedule', {group_eid:group_eid,group_schedule_eid:group_schedule_eid}, function(o) {
			if (o.is_success) {
				events.onRemoved(o);
			} else {
				events.onError(o);
			}			
		},'json');
	}
}

function _removeScheduleMember(employee_group_id, schedule_id, employee_or_group, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeRemove();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}
		$.post(base_url + 'new_schedule/_remove_schedule_member', {employee_group_id:employee_group_id, schedule_id:schedule_id, employee_or_group:employee_or_group}, function(o) {
			if (o.is_removed) {
				if (events) { events.onRemoved(o.message);}
			} else {
				if (events) { events.onError(o.message);}
			}
		},'json');
	}
}

function _removeStaggeredScheduleMember(employee_group_id, schedule_id, employee_or_group, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeRemove();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}
		$.post(base_url + 'new_schedule/_remove_staggered_schedule_member', {employee_group_id:employee_group_id, schedule_id:schedule_id, employee_or_group:employee_or_group}, function(o) {
			if (o.is_removed) {
				if (events) { events.onRemoved(o.message);}
			} else {
				if (events) { events.onError(o.message);}
			}
		},'json');
	}
}

function _removeAllScheduleMemberEmployees(schedule_group_public_id, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeRemove();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}
		$.post(base_url + 'new_schedule/_remove_all_schedule_member_employees', {schedule_group_public_id:schedule_group_public_id}, function(o) {
			if (o.is_removed) {
				if (events) { events.onRemoved(o.message);}
			} else {
				if (events) { events.onError(o.message);}
			}
		},'json');
	}
}

function _removeAllStaggeredScheduleMemberEmployees(schedule_group_public_id, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeRemove();
	}
	if (ans) {
		if (events) {
			events.onLoading();
		}
		$.post(base_url + 'new_schedule/_remove_all_staggered_schedule_member_employees', {schedule_group_public_id:schedule_group_public_id}, function(o) {
			if (o.is_removed) {
				if (events) { events.onRemoved(o.message);}
			} else {
				if (events) { events.onError(o.message);}
			}
		},'json');
	}
}

function _showScheduleMembersList(element, schedule_id, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/ajax_show_schedule_members_list', {schedule_id:schedule_id}, function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _showStaggeredScheduleMembersList(element, schedule_id, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/ajax_show_staggered_schedule_members_list', {schedule_id:schedule_id}, function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

//Dashboard
//Schedule Main - All
function _showAllScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/schedule_main_all_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//shift
function _showDashboardShiftScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_shift_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardShiftLeaveScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_shift_leave_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardShiftOBScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_shift_ob_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardShiftNoScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_shift_no_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//compress
function _showDashboardCompressScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_compress_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardCompressLeaveScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_compress_leave_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardCompressOBScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_compress_ob_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardCompressNoScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_compress_no_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//staggered
function _showDashboardStaggeredScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_staggered_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardStaggeredLeaveScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_staggered_leave_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardStaggeredOBScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_staggered_ob_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardStaggeredNoScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_staggered_no_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//flextime
function _showDashboardFlextimeScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_flextime_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardFlextimeLeaveScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_flextime_leave_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardFlextimeOBScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_flextime_ob_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showDashboardFlextimeNoScheduleMembersList(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/dashboard_ajax_show_flextime_no_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

//set employee
function _showSetEmployeeShiftSchedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/set_employee_ajax_show_shift_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _showSetEmployeeCompressSchedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/set_employee_ajax_show_compress_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _showSetEmployeeStaggeredSchedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/set_employee_ajax_show_staggered_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _showSetEmployeeFlextimeSchedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/set_employee_ajax_show_flextime_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _showSetEmployeeNoSchedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/set_employee_ajax_show_no_schedule_members_list', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
function _showAttendance(element, employee_id, start_date, end_date, frequency_id,events) {	
	// if (typeof events.onLoading == "function") {
	// 	events.onLoading();
	// }
	$.get(base_url + 'new_schedule/ajax_show_attendance', {employee_id:employee_id, start_date:start_date, end_date:end_date,frequency_id:frequency_id }, function(data) {
		$(element).html(data);
		if (typeof events.onLoaded == "function") {
			events.onLoaded();
		}
	});	
}

function _assignScheduleGroups(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Groups or Department';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_schedule_groups', {schedule_id:schedule_id}, function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}			
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
		
		$('#groups_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'group/ajax_get_groups_autocomplete'}
		}}});
		
		$('#groups_autocomplete').focus();	
	});	
}

function _assignScheduleEmployees(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employees';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_schedule_employees', {schedule_id:schedule_id}, function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}			
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#employees_autocomplete').focus();	
	});	
}

function _assignStaggeredScheduleEmployees(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employees';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_staggered_schedule_employees', {schedule_id:schedule_id}, function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}			
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#employees_autocomplete').focus();	
	});	
}

function _assignScheduleGroupsAndEmployees(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Groups or Employees';
	var width = 350;
	var height = 300;
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_schedule_groups_employees', {schedule_id:schedule_id}, function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o);
									}
								} else {
									if (events) {
										events.onError(o);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}			
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#groups_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'group/ajax_get_groups_autocomplete'}
		}}});
						
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#groups_autocomplete').focus();	
	});		
}

//Staggered Schedule
function _load_staggered_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_staggered_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_no_staggered_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_no_staggered_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_leave_staggered_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_leave_staggered_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_rest_day_staggered_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_rest_day_staggered_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_ob_staggered_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_ob_staggered_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//Compress Schedule
function _load_compress_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_compress_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_no_compress_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_no_compress_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_leave_compress_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_leave_compress_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_rest_day_compress_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_rest_day_compress_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_ob_compress_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_ob_compress_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//Shift Schedule
function _load_shift_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_shift_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_no_shift_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_no_shift_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_leave_shift_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_leave_shift_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_rest_day_shift_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_rest_day_shift_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_ob_shift_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_ob_shift_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}
//Flextime Schedule
function _load_flextime_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_flextime_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_no_flextime_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_no_flextime_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_leave_flextime_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_leave_flextime_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_rest_day_flextime_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_rest_day_flextime_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function _load_ob_flextime_schedule(element, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'new_schedule/load_ob_flextime_schedule', function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

//Edit Schedule
function _editEmployeeSchedule(eid_in, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 390;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'new_schedule/ajax_edit_employee_schedule', {eid_in:eid_in}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_attendance_log').validationEngine({scroll:false});		
		$('#edit_attendance_log').ajaxForm({
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
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _editSchedule(id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 390;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'new_schedule/ajax_edit_schedule', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_attendance_log').validationEngine({scroll:false});		
		$('#edit_attendance_log').ajaxForm({
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
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}
function _editEmployeeNoSchedule(eid_in, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 390;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'new_schedule/ajax_edit_no_schedule', {eid_in:eid_in}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_attendance_log').validationEngine({scroll:false});		
		$('#edit_attendance_log').ajaxForm({
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
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}
//Batch Update
function _withSelectedLogsAll(logs, action, events) {
	if (action == 'update') {
		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var title = 'Batch Update';
		var width = 'auto';
		var height = 'auto';
		
		if (typeof events.onLoading == "function") {
			events.onLoading();
		}	
		
		$.post(base_url + 'new_schedule/ajax_batch_edit_site_attendance_logs', {logs:logs}, function(data) {
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true
			});
			$('#batch_edit_attendance_log').validationEngine({scroll:false});		
			$('#batch_edit_attendance_log').ajaxForm({
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
					if (typeof events.onError == "function") {
						events.onSaving();
					}				
					return true;
				}
			});		
		});
	}
	else if(action == 'delete'){
		

		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var width = 350 ;
		var height = 180
		var title = 'Notice';
		var message = 'Delete selected logs?';
	
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
						$.post(base_url+'project_site/ajax_batch_delete_attendance_log', {logs:logs},
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
							events.onNo(o);
						}
					}				
				}
			}).show().parent().find('.ui-dialog-titlebar-close').hide();


	}
}

function _withSelectedLogsNoSchedule(logs, action, events) {
	if (action == 'update') {
		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var title = 'Batch Update';
		var width = 'auto';
		var height = 'auto';
		
		if (typeof events.onLoading == "function") {
			events.onLoading();
		}	
		
		$.post(base_url + 'new_schedule/ajax_batch_edit_no_schedule', {logs:logs}, function(data) {
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true
			});
			$('#batch_edit_attendance_log').validationEngine({scroll:false});		
			$('#batch_edit_attendance_log').ajaxForm({
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
					if (typeof events.onError == "function") {
						events.onSaving();
					}				
					return true;
				}
			});		
		});
	}
	else if(action == 'delete'){
		

		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var width = 350 ;
		var height = 180
		var title = 'Notice';
		var message = 'Delete selected logs?';
	
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
						$.post(base_url+'project_site/ajax_batch_delete_attendance_log', {logs:logs},
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
							events.onNo(o);
						}
					}				
				}
			}).show().parent().find('.ui-dialog-titlebar-close').hide();


	}
}

function _withSelectedLogsLeave(logs, action, events) {
	if (action == 'update') {
		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var title = 'Batch Update';
		var width = 'auto';
		var height = 'auto';
		
		if (typeof events.onLoading == "function") {
			events.onLoading();
		}	
		
		$.post(base_url + 'new_schedule/ajax_batch_edit_site_attendance_logs', {logs:logs}, function(data) {
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true
			});
			$('#batch_edit_attendance_log').validationEngine({scroll:false});		
			$('#batch_edit_attendance_log').ajaxForm({
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
					if (typeof events.onError == "function") {
						events.onSaving();
					}				
					return true;
				}
			});		
		});
	}
	else if(action == 'delete'){
		

		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var width = 350 ;
		var height = 180
		var title = 'Notice';
		var message = 'Delete selected logs?';
	
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
						$.post(base_url+'project_site/ajax_batch_delete_attendance_log', {logs:logs},
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
							events.onNo(o);
						}
					}				
				}
			}).show().parent().find('.ui-dialog-titlebar-close').hide();


	}
}

function _withSelectedLogsRestDay(logs, action, events) {
	if (action == 'update') {
		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var title = 'Batch Update';
		var width = 'auto';
		var height = 'auto';
		
		if (typeof events.onLoading == "function") {
			events.onLoading();
		}	
		
		$.post(base_url + 'new_schedule/ajax_batch_edit_site_attendance_logs', {logs:logs}, function(data) {
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true
			});
			$('#batch_edit_attendance_log').validationEngine({scroll:false});		
			$('#batch_edit_attendance_log').ajaxForm({
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
					if (typeof events.onError == "function") {
						events.onSaving();
					}				
					return true;
				}
			});		
		});
	}
	else if(action == 'delete'){
		

		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var width = 350 ;
		var height = 180
		var title = 'Notice';
		var message = 'Delete selected logs?';
	
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
						$.post(base_url+'project_site/ajax_batch_delete_attendance_log', {logs:logs},
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
							events.onNo(o);
						}
					}				
				}
			}).show().parent().find('.ui-dialog-titlebar-close').hide();


	}
}

function _withSelectedLogsOB(logs, action, events) {
	if (action == 'update') {
		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var title = 'Batch Update';
		var width = 'auto';
		var height = 'auto';
		
		if (typeof events.onLoading == "function") {
			events.onLoading();
		}	
		
		$.post(base_url + 'new_schedule/ajax_batch_edit_site_attendance_logs', {logs:logs}, function(data) {
			closeDialog(dialog_id);
			$(dialog_id).html(data);
			dialogGeneric(dialog_id, {
				title: title,
				resizable: false,
				width: width,
				height: height,
				modal: true
			});
			$('#batch_edit_attendance_log').validationEngine({scroll:false});		
			$('#batch_edit_attendance_log').ajaxForm({
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
					if (typeof events.onError == "function") {
						events.onSaving();
					}				
					return true;
				}
			});		
		});
	}
	else if(action == 'delete'){
		

		var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
		var width = 350 ;
		var height = 180
		var title = 'Notice';
		var message = 'Delete selected logs?';
	
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
						$.post(base_url+'project_site/ajax_batch_delete_attendance_log', {logs:logs},
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
							events.onNo(o);
						}
					}				
				}
			}).show().parent().find('.ui-dialog-titlebar-close').hide();


	}
}

//Add Employee
function _assignStaggeredScheduleEmployeeList(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employees';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_staggered_schedule_employee_list', function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}	
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#employees_autocomplete').focus();	
	});	
}

function _assignCompressScheduleEmployeeList(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employees';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_compress_schedule_employee_list', function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}	
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#employees_autocomplete').focus();	
	});	
}

function _assignFlexibleScheduleEmployeeList(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employees';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_flexible_schedule_employee_list', function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}	
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#employees_autocomplete').focus();	
	});	
}

function _assignShiftScheduleEmployeeList(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employees';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'new_schedule/ajax_assign_shift_schedule_employee_list', function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}	
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
				
		$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
		}}});
		
		$('#employees_autocomplete').focus();	
	});	
}