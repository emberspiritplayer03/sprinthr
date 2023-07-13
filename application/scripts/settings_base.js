function _importTimesheet(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Timesheet';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'attendance/ajax_import_timesheet', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#import_timesheet_form').validationEngine({scroll:false});		
		$('#import_timesheet_form').ajaxForm({
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
				if ($('#timesheet_file').val() == '') {
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

function _addNewGracePeriod(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Grace Period';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_grace_period', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addGracePeriod').validationEngine({scroll:false});		
		$('#addGracePeriod').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _setDefaultGracePeriod(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Set as <b>default</b> the selected Grace Period?';
	
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
				$.post(base_url+'settings/set_default_grace_period',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _archiveGracePeriod(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Delete the selected Grace Period?';
	
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
				$.post(base_url+'settings/delete_grace_period',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _showTimesheet(date, employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Details - ' + date;
	var width = 'auto';
	var height = 450;
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'attendance/ajax_show_timesheet', {date:date, employee_id:employee_id}, function(data) {
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

function _editTimesheet(date, employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Details - ' + date;
	var width = 300;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'attendance/ajax_edit_timesheet', {date:date, employee_id:employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_timesheet').validationEngine({scroll:false});		
		$('#edit_timesheet').ajaxForm({
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

function _editGracePeriod(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Grace Period';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_grace_period', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editGracePeriod').validationEngine({scroll:false});		
		$('#editGracePeriod').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editCompanyBenefit(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Company Benefit';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_company_benefit', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editCompanyBenefit').validationEngine({scroll:false});		
		$('#editCompanyBenefit').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewRequest(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New';
	var width = 420;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_add_new_request', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#add_request').validationEngine({scroll:false});		
		$('#add_request').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_request_dt();
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

function _copyRequestSettings(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Copy Request Settings';
	var width = 420;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/ajax_copy_request_settings', {request_id:request_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#copy_request_settings').validationEngine({scroll:false});		
		$('#copy_request_settings').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_request_dt();
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

function _addApprovers(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Request Approvers';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/ajax_add_request_approvers', {request_id:request_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#add_request_approvers').validationEngine({scroll:false});		
		$('#add_request_approvers').ajaxForm({
			success:function(o) {
				if (o.is_success) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_request_approvers_dt(o.request_id);
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

function _addBranch(company_structure_id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Branch';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_branch', {company_structure_id:company_structure_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			form_id : '#addSubdivision',
			modal: true
		});
		$('#addSubdivision').validationEngine({scroll:false});		
		$('#addSubdivision').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_company_structure();
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

function _editBranch(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Branch';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_company_branch', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editSubdivision').validationEngine({scroll:false});		
		$('#editSubdivision').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addSubBranch(company_structure_id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Branch';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_branch',{company_structure_id:company_structure_id},
	function(o){ 
		$('#sub_action_form').html(o); 
		dialogGeneric('#sub_action_form', {
			title: 'Add New Branch',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#addSubdivision'
		});
						
		$('#addSubdivision').validationEngine({scroll:false});		
		$('#addSubdivision').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_company_structure();
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				
				closeDialogBox('#sub_action_form','#addSubdivision');
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

function _addNewCompanyStructure(company_structure_id,branch_id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Company Structure';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_structure', {company_structure_id:company_structure_id,branch_id:branch_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			form_id : '#addCompanyStructure',
			modal: true
		});
		$('#addDepartment').validationEngine({scroll:false});		
		$('#addDepartment').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_company_structure();
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

function _addNewGroupTeam(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Group / Team';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_new_group_team', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addGroupTeam').validationEngine({scroll:false});		
		$('#addGroupTeam').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewSection(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Section';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_new_section', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addSection').validationEngine({scroll:false});		
		$('#addSection').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewDepartment(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Department';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_new_department', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addDepartment').validationEngine({scroll:false});		
		$('#addDepartment').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addLeaveType(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Leave Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_leave_type', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addLeaveType').validationEngine({scroll:false});		
		$('#addLeaveType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {

				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addDeductionType(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Deduction Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_deduction_type', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addDeductionType').validationEngine({scroll:false});		
		$('#addDeductionType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _assignBenefit(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Assign Company Benefit';
	var width = 480;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_assign_company_benefit', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#assignCompanyBenefit').validationEngine({scroll:false});		
		$('#assignCompanyBenefit').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editDeductionType(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Deduction Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_deduction_type', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editDeductionType').validationEngine({scroll:false});		
		$('#editDeductionType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewLocation(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Location';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_location', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addLocation').validationEngine({scroll:false});		
		$('#addLocation').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewLicense(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New License';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_license', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addLicense').validationEngine({scroll:false});		
		$('#addLicense').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editLicense(license_id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit License';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_license', {license_id:license_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editLicense').validationEngine({scroll:false});		
		$('#editLicense').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewRelationship(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Dependent Relationship';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_relationship', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addRelationship').validationEngine({scroll:false});		
		$('#addRelationship').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addEeoJobCategory(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New EEO (Equal Employment Opportunity) Job Category';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/eeo_job_category', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addEeoCategory').validationEngine({scroll:false});		
		$('#addEeoCategory').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editLocation(id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Location';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_location', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editLocation').validationEngine({scroll:false});		
		$('#editLocation').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editEeoJobCategory(id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit EEO (Equal Employment Opportunity) Job Category';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/edit_eeo_job_category', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editEeoCategory').validationEngine({scroll:false});		
		$('#editEeoCategory').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editEmploymentStatus(id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Employment Status';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_employment_status', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editEmploymentStatus').validationEngine({scroll:false});		
		$('#editEmploymentStatus').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editMembershipType(id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Membership Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_membership_type',{id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editMembership').validationEngine({scroll:false});		
		$('#editMembership').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewEmploymentStatus(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Employment Status';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_employment_status', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addEmploymentStatus').validationEngine({scroll:false});		
		$('#addEmploymentStatus').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addJobSalaryRate(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Job Salary Rate';
	var width = 450;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	

	$.get(base_url + 'settings/job_salary_rate', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addJobRate').validationEngine({scroll:false});		
		$('#addJobRate').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addNewMembership(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Membership Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_membership', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addMembership').validationEngine({scroll:false});		
		$('#addMembership').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editJobSalaryRate(id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Job Salary Rate';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/edit_job_salary_rate', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editJobRate').validationEngine({scroll:false});		
		$('#editJobRate').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editDependent(id,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Dependent Relationship';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_dependent', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editRelationship').validationEngine({scroll:false});		
		$('#editRelationship').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addUserAccount(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add User Account';
	var width = 450;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.post(base_url + 'settings/_load_add_user_account', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#add_account_form').validationEngine({scroll:false});
		$('#add_account_form').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {

				}

				if (typeof events.onSaved == "function") {
					events.onSaved(o);
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

function _editUserAccount(id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit User Account';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	

	$.post(base_url + 'settings/_load_edit_user_account', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#update_account_form').validationEngine({scroll:false});		
		$('#update_account_form').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {

				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addPayrollPeriod(selected_year,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Payroll Period';
	var width = 350;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_payroll_period', {selected_year:selected_year}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addPayrollPeriod').validationEngine({scroll:false});		
		$('#addPayrollPeriod').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editDepartment(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Department';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_department', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editDepartment').validationEngine({scroll:false});		
		$('#editDepartment').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editTeamGroup(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Team / Group';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_team_group', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editGroupTeam').validationEngine({scroll:false});		
		$('#editGroupTeam').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editSection(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Section';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_section', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editSection').validationEngine({scroll:false});		
		$('#editSection').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editContribution(eid,type,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Contribution : ' + type.toUpperCase();
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_contribution', {eid:eid,type:type}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('.frmEditContribution').validationEngine({scroll:false});		
		$('.frmEditContribution').ajaxForm({
			success:function(o) {
				if (o.is_success) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _sortApproversLevel(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Sort Level of Approvers';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/ajax_sort_approvers_level', {request_id:request_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#add_request_approvers').validationEngine({scroll:false});		
		$('#add_request_approvers').ajaxForm({
			success:function(o) {
				if (o.is_success) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_request_approvers_dt(o.request_id);
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

function _deleteRequestApprovers(approver_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected approver?';
	
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
				$.post(base_url+'settings/_load_delete_request_approver',{approver_id:approver_id},
					function(o){													
					if(o.is_success==1) {								
						load_request_approvers_dt(o.request_id);
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _deleteBranch(branch_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected branch?';
	
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
				$.post(base_url+'settings/delete_branch',{branch_id:branch_id},
					function(o){													
					if(o.is_success==1) {								
						load_company_structure();
						dialogOkBox(o.message,{});		
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _assignOverrideLevel(approver_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to assign override level to selected approver?';
	
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
				$.post(base_url+'settings/_load_assign_override_level',{approver_id:approver_id},
					function(o){													
					if(o.is_success==1) {								
						load_request_approvers_dt(o.request_id);
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _archiveRequestSettings(request_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected request settings?';
	
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
				$.post(base_url+'settings/_load_archive_request_settings',{request_id:request_id},
					function(o){													
					if(o.is_success==1) {								
						load_request_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _archiveGroup(group_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected group?';
	
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
				$.post(base_url+'settings/_load_archive_group',{group_id:group_id},
					function(o){													
					if(o.is_success==1) {								
						load_child_group_list(o.parent_id);
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _deleteGroupMember(employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected employee in this group?';
	
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
				$.post(base_url+'settings/_load_delete_group_member',{employee_id:employee_id},
					function(o){													
					if(o.is_success==1) {								
						load_employee_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _deleteRequestApprovers(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected request approvers?';
	
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
				$.post(base_url+'settings/_load_delete_request_approvers',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
						load_request_approvers_dt();
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

function _deleteRole(role_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected role?';
	
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
				$.post(base_url+'settings/_load_delete_role',{role_id:role_id},
					function(o){													
					if(o.is_success) {								
						load_roles_dt();						
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

function _removeEnrollee(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected entry?';
	
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
				$.post(base_url+'settings/_load_delete_benefit_enrollee',{eid:eid},
					function(o){													
					if(o.is_success) {								
						load_employees_enrolled_to_benefit(o.eid);						
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

function _deleteBenefit(benefit_id, events) {
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
				$.post(base_url+'settings/_load_delete_benefit',{benefit_id:benefit_id},
					function(o){													
					if(o.is_success) {								
						load_benefits_dt();						
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

function _deleteUser(user_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected user?';
	
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
				$.post(base_url+'settings/_load_delete_user',{user_id:user_id},
					function(o){													
					if(o.is_success) {								
						load_user_management_dt();						
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

function _deleteBreakTimeSchedule(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to delete the selected schedule?';
	
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
				$.post(base_url+'settings/_load_delete_breaktime_schedule',{eid:eid},
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

function _editRequest(request_id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Request';
	var width = 420;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/ajax_edit_request', {request_id:request_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_request').validationEngine({scroll:false});		
		$('#edit_request').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_request_dt();
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

function _editRequestApprovers(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Request';
	var width = 420;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_edit_request_approvers', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_request').validationEngine({scroll:false});		
		$('#edit_request').ajaxForm({
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

function _showAttendance(element, employee_id, start_date, end_date, events) {	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	$.get(base_url + 'attendance/ajax_show_attendance', {employee_id:employee_id, start_date:start_date, end_date:end_date}, function(data) {
		$(element).html(data);
		if (typeof events.onLoaded == "function") {
			events.onLoaded();
		}
	});	
}

function _editTimeInOut(date, employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Time In/Out';
	var width = 300;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'attendance/ajax_edit_time_in_out', {date:date, employee_id:employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_time_in_out').validationEngine({scroll:false});		
		$('#edit_time_in_out').ajaxForm({
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

function _editOvertimeInOut(date, employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Overtime In/Out';
	var width = 300;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'attendance/ajax_edit_overtime_in_out', {date:date, employee_id:employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_overtime_in_out').validationEngine({scroll:false});		
		$('#edit_overtime_in_out').ajaxForm({
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

function _addDepartment(h_company_structure, events) {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var width = 'auto';
    var height = 'auto';

    if (typeof events.onLoading == "function") {
        events.onLoading();
    }

    closeDialog(dialog_id);
    dialogGeneric('#add_group_form_modal_wrapper', {
        title: 'Add Department',
        resizable: false,
        width: width,
        height: height,
        modal: true,
        form_id: '#add_group_form'
    });

    $("#add_group_form").validationEngine({scroll:false});
    $('#add_group_form').ajaxForm({
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
            //closeDialog(dialog_id);
            closeDialogBox('#add_group_form_modal_wrapper','#add_group_form');
        },
        dataType:'json',
        clearForm: true,
        beforeSubmit: function() {
            if (typeof events.onSaving == "function") {
                events.onSaving();
            }
            return true;
        }
    });

}

function _addGroup(h_company_structure, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var width = 'auto';
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	dialogGeneric('#add_group_form_modal_wrapper', {
		title: 'Add New Group',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		form_id: '#add_group_form'
	});

	$("#add_group_form").validationEngine({scroll:false});
	$('#add_group_form').ajaxForm({
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
			//closeDialog(dialog_id);
			closeDialogBox('#add_group_form_modal_wrapper','#add_group_form');
		},
		dataType:'json',
		clearForm: true,
		beforeSubmit: function() {
			if (typeof events.onSaving == "function") {
				events.onSaving();
			}
			return true;
		}
	});

}

function _addEmployee(h_company_structure, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var width = 'auto';
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	closeDialog(dialog_id);
	$.post(base_url + 'settings/_load_add_employee_togroup',{h_company_structure:h_company_structure},function(o) {
		$('#add_employee_form_modal_wrapper').html(o);	
		dialogGeneric('#add_employee_form_modal_wrapper', {
			title: 'Add New Group',
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_employee_form'
		});
	
		$("#add_employee_form").validationEngine({scroll:false});
		$('#add_employee_form').ajaxForm({
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
				//closeDialog(dialog_id);
				closeDialogBox('#add_employee_form_modal_wrapper','#add_employee_form');
			},
			dataType:'json',
			clearForm: true,
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});
	
	});

}

function _lockPayrollPeriod(e_id,frequecy, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220
	var title = 'Notice';
	var message = 'Are you sure you want to lock the selected period?<br><br><b>Note: Locking the period will prohibit editing of data related to this period.</b>';
	
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
                showLoadingDialog('Locking...');
				$.post(base_url+'settings/_load_lock_payroll_period',{e_id:e_id,frequecy:frequecy},
					function(o){													
					if(o.is_success == 1) {
                        if (typeof events.onYes == "function") {
                            events.onYes();
                        }
						if (typeof events.onLocked == "function") {
							events.onLocked(o);
						}
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _lockAllPayrollPeriodBySelectedYear(selected_year, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220
	var title = 'Notice';
	var message = "Note : <b>This will lock all cutoff periods for the selected year.</b><br /><br />Do you wish to proceed?";
	
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
				$.post(base_url+'settings/_load_lock_all_cutoff_period_by_selected_year',{selected_year:selected_year},
					function(o){													
					if(o.is_success == 1) {																				
						if (typeof events.onYes == "function") {
							events.onYes(o);
						}
					}else {						
						dialogOkBox(o.message,{});		
						$dialog.dialog("close");								
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

function _unlockPayrollPeriod(e_id,frequecy, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 220
	var title = 'Notice';
	var message = 'Are you sure you want to unlock the selected period?<br><br><b>Note: Unlocking the period will enable editing of data related to this period.</b>';
	
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
                showLoadingDialog('Unlocking...');
				$.post(base_url+'settings/_load_unlock_payroll_period',{e_id:e_id,frequecy:frequecy},
					function(o){													
					if(o.is_success == 1) {																				
						if (typeof events.onYes == "function") {
							events.onYes();
						}
                        if (typeof events.onUnlocked == "function") {
                            events.onUnlocked(o);
                        }
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _payrollPeriodWithSelectedAction(status,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 220;
	var title  = 'Notice';
	
	if(status == 'lock_period') {
		var message = 'Are you sure you want to lock the selected period(s)?<br><br><b>Note: Locking the period will prohibit editing of data related to this period.</b>';
	}else if(status == 'unlock_period'){
		var message = 'Are you sure you want to unlock the selected period(s)?<br><br><b>Note: Unlocking the period will enable editing of data related to this period.</b>';
	}
	
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
				$.post(base_url + 'settings/payroll_period_with_selected_action',$('#withSelectedAction').serialize(),		
				function(o) { 
					if(o.is_success==1) {							
						dialogOkBox(o.message,{});	
						$("#chkAction").attr('disabled',true);						
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _addNewSubDivisionType(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add new Subdivision Type';
	var width = 300;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_add_new_subdivision_type', {}, function(data) {
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

function _editSubDivisionType(id,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Subdivision Type';
	var width = 300;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_subdivision', {id:id}, function(data) {
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

function _editDeductionBreakdown(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Deduction Breakdown';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'settings/ajax_edit_deduction_breakdown', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_deduction_breakdown_form'
		});
		
		$('#edit_deduction_breakdown_form').validationEngine({scroll:false});		
		$('#edit_deduction_breakdown_form').ajaxForm({
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
					var is_form_valid = $('#edit_deduction_breakdown_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_deduction_breakdown_form');
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

function _editWeeklyDeductionBreakdown(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Weekly Deduction Breakdown';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'settings/ajax_edit_weekly_deduction_breakdown', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_weekly_deduction_breakdown_form'
		});
		
		$('#edit_weekly_deduction_breakdown_form').validationEngine({scroll:false});		
		$('#edit_weekly_deduction_breakdown_form').ajaxForm({
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
					var is_form_valid = $('#edit_weekly_deduction_breakdown_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_weekly_deduction_breakdown_form');
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

function _deleteExam(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected exam?';
	
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
				$.post(base_url+'settings/_load_delete_exam',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _archivePerformanceTemplate(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected performance template?';
	
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
				$.post(base_url+'settings/_load_archive_performance_template',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _archiveCompanyBranch(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected branch?';
	
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
				$.post(base_url+'settings/_load_archive_company_branch',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _archiveCompanyDepartment(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected department?';
	
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
				$.post(base_url+'settings/_load_archive_company_department',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _restorePerformanceTemplate(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>restore</b> the selected performance template?';
	
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
				$.post(base_url+'settings/_load_restore_performance_template',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _editLeaveType(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Leave Type';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_leave_type', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editLeaveType').validationEngine({scroll:false});		
		$('#editLeaveType').ajaxForm({
			success:function(o) {
				if (o.is_success = 1) {
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

function _archiveLeaveType(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected leave type?';
	
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
				$.post(base_url+'leave/_load_archive_leave_type',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {														
						load_leave_type_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _archiveEmployeeStatus(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected employee status?';
	
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
				$.post(base_url+'settings/_load_archive_employee_status',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
						load_employee_status_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _archiveRequirement(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected requirement?';
	
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
				$.post(base_url+'settings/_load_archive_requirement',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
						load_requirements_list_dt();  	
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _archiveBenefit(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected benefit?';
	
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
				$.post(base_url+'settings/_load_archive_benefit',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
															
					}else {
						
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

function _restoreBenefit(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected benefit?';
	
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
				$.post(base_url+'settings/_load_restore_benefit',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
															
					}else {
						
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

function _archiveMemo(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected memo?';
	
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
				$.post(base_url+'settings/_load_archive_memo',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
						load_memo_template_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _restoreEmployeeStatus(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected employee status?';
	
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
				$.post(base_url+'settings/_load_restore_employee_status',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
						load_archive_employee_status_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _restoreRequirement(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected requirement?';
	
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
				$.post(base_url+'settings/_load_restore_requirement',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
						load_archive_requirement_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _restoreMemo(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected memo?';
	
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
				$.post(base_url+'settings/_load_restore_memo',{eid:eid},
					function(o){													
					if(o.is_success==1) {														
						load_archive_memo_template_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _restoreLeaveType(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected leave type?';
	
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
				$.post(base_url+'leave/_load_restore_leave_type',{e_id:e_id},
					function(o){													
					if(o.is_success==1) {														
						load_archive_leave_type_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _archiveLoanType(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected entry?';
	
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
				$.post(base_url+'loan/_load_archive_loan_type',{e_id:e_id},
					function(o){													
					if(o.is_success == 1) {														
						load_loan_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _generatePayrollPeriod(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Generate Current Cutoff	 Period?';
	
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
				$.post(base_url+'settings/generate_payroll_period',{},
					function(o){													
					if(o.is_success == 1) {														
						load_payroll_period_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes(o);
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _restoreArchiveLoanType(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to restore the selected entry?';
	
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
				$.post(base_url+'loan/_load_restore_archive_loan_type',{e_id:e_id},
					function(o){													
					if(o.is_success == 1) {														
						load_archive_loan_list_dt();
						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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

function _addEmployeeStatus(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Employee Status';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_employee_status', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addEmployeeStatus').validationEngine({scroll:false});		
		$('#addEmployeeStatus').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addRequirement(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Requirement';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_requirement', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addRequirement').validationEngine({scroll:false});		
		$('#addRequirement').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _addCompanyBenefit(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Company Benefit';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_company_benefit', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addCompanyBenefit').validationEngine({scroll:false});		
		$('#addCompanyBenefit').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					
				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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

function _editEmployeeStatus(eid, events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Employee Status';
	var width = 300;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_employee_status', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editEmployeeStatus').validationEngine({scroll:false});		
		$('#editEmployeeStatus').ajaxForm({
			success:function(o) {
				if (o.is_success = 1) {
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

function _editRequirement(eid, events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Requirement';
	var width = 400;
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/_load_edit_requirement', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editRequirement').validationEngine({scroll:false});		
		$('#editRequirement').ajaxForm({
			success:function(o) {
				if (o.is_success = 1) {
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

function _editPayrollSettings(field, events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Payroll Settings';	
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_edit_payroll_settings', {field:field}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editSetting').validationEngine({scroll:false});		
		$('#editSetting').ajaxForm({
			success:function(o) {
				if (o.is_success = 1) {
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

function _showEditIpAddress(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit IP Address';
	var width = 455;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_edit_ip_address', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#edit_ip_address_form').validationEngine({scroll:false});		
		$('#edit_ip_address_form').ajaxForm({
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
				$("#token").val(o.token);
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

function _showDeleteIpAddress(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Delete the selected IP Address?';
	
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
				$.post(base_url+'settings/delete_ip_address',{eid:eid},
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}


function _importSSSTable(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import SSS Table';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_import_sss_table', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#import_sss_table_form').validationEngine({scroll:false});		
		$('#import_sss_table_form').ajaxForm({
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
				if ($('#file').val() == '') {
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

function _importEmployeeBenefits(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Employee Benefits';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_import_employee_benefits', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#import_employee_benefits_form').validationEngine({scroll:false});		
		$('#import_employee_benefits_form').ajaxForm({
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
				if ($('#file').val() == '') {
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

function _importPhilHealthTable(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import PhilHealth Table';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_import_philhealth_table', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#import_philhealth_table_form').validationEngine({scroll:false});		
		$('#import_philhealth_table_form').ajaxForm({
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
				if ($('#file').val() == '') {
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

function _importPagibigTable(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import PhilHealth Table';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/ajax_import_pagibig_table', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#import_philhealth_table_form').validationEngine({scroll:false});		
		$('#import_philhealth_table_form').ajaxForm({
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
				if ($('#file').val() == '') {
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



function _addGPExmptedEmployees(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Exempted Employess';
	var width = 350;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_gp_exempted_employees', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addGPExemptedEmployees').validationEngine({scroll:false});		
		$('#addGPExemptedEmployees').ajaxForm({
			success:function(o) {
				//if (o.is_success == 1) {
					
				//} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
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


function _archiveGracePeriodExempted(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Delete the selected Employee?';
	
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
				$.post(base_url+'settings/delete_grace_period_exempted',{eid:eid},
					function(o){													
					if(o.is_success==1) {								
													
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
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

//monthly

function _editMonthlyDeductionBreakdown(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Monthly Deduction Breakdown';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'settings/ajax_edit_monthly_deduction_breakdown', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_monthly_deduction_breakdown_form'
		});
		
		$('#edit_monthly_deduction_breakdown_form').validationEngine({scroll:false});		
		$('#edit_monthly_deduction_breakdown_form').ajaxForm({
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
					var is_form_valid = $('#edit_monthly_deduction_breakdown_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_monthly_deduction_breakdown_form');
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
