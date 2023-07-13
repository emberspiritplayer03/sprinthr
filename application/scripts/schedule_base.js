function _editSpecificSchedule(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 400;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'schedule/ajax_edit_specific_schedule_form', {schedule_id:schedule_id}, function(data) {
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
		$.post(base_url + 'schedule/_delete_specific_schedule', {schedule_id:schedule_id}, function(o) {
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

	$.get(base_url + 'schedule/ajax_add_specific_schedule', {employee_id:employee_id}, function(data) {
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
	$.post(base_url + 'schedule/_assign_group_schedule_to_employee', {schedule_group_id:schedule_group_id, employee_id:employee_id}, function(o) {
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
	
	$.get(base_url + 'schedule/ajax_import_schedule_specific', {}, function(data) {
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
	
	$.get(base_url + 'schedule/ajax_import_schedule', {}, function(data) {
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
	
	$.get(base_url + 'schedule/ajax_import_employees_in_schedule', {public_id:public_id}, function(data) {
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

	$.get(base_url + 'schedule/ajax_add_weekly_schedule_form', {}, function(data) {
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

	$.get(base_url + 'schedule/ajax_group_add_weekly_schedule_form', {eid:eid}, function(data) {
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

	$.get(base_url + 'schedule/ajax_edit_weekly_schedule_form', {public_id:public_id}, function(data) {
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

function _editSchedule(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'schedule/ajax_edit_schedule_form', {schedule_id:schedule_id}, function(data) {
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
	$.ajax(base_url + 'schedule/_count_members', {
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
		$.post(base_url + 'schedule/_delete_schedule', {schedule_id:schedule_id}, function(o) {
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

		$.post(base_url + 'schedule/_remove_group_schedule', {group_eid:group_eid,group_schedule_eid:group_schedule_eid}, function(o) {
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
		$.post(base_url + 'schedule/_remove_schedule_member', {employee_group_id:employee_group_id, schedule_id:schedule_id, employee_or_group:employee_or_group}, function(o) {
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
		$.post(base_url + 'schedule/_remove_all_schedule_member_employees', {schedule_group_public_id:schedule_group_public_id}, function(o) {
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
	$.get(base_url + 'schedule/ajax_show_schedule_members_list', {schedule_id:schedule_id}, function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
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
	
	$.get(base_url + 'schedule/ajax_assign_schedule_groups', {schedule_id:schedule_id}, function(data) {
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
	
	$.get(base_url + 'schedule/ajax_assign_schedule_employees', {schedule_id:schedule_id}, function(data) {
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
	
	$.get(base_url + 'schedule/ajax_assign_schedule_groups_employees', {schedule_id:schedule_id}, function(data) {
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