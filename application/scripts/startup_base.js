function _editDefaultLeaveCredits(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Default Leave Credits';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'startup/_load_edit_default_leave_form_startup', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editDefaultLeaveForm').validationEngine({scroll:false});		
		$('#editDefaultLeaveForm').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
						load_leave_default_startup();
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
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'startup/_load_add_branch', {company_structure_id:company_structure_id}, function(data) {
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
	
	$.post(base_url + 'startup/_load_edit_company_branch', {eid:eid}, function(data) {
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

function _addNewDepartment(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Department';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'startup/_load_add_new_department', {eid:eid}, function(data) {
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

function _addNewGracePeriod(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Grace Period';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'startup/_load_add_new_grace_period', {}, function(data) {
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

function _editGracePeriod(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Grace Period';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'startup/_load_edit_grace_period', {eid:eid}, function(data) {
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

function _load_edit_pay_period(eid,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Payroll Period';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'startup/_load_edit_pay_period', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editPayPeriod').validationEngine({scroll:false});		
		$('#editPayPeriod').ajaxForm({
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

function _load_add_new_pay_period(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Pay Period';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'startup/_load_add_new_pay_period', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addPayPeriod').validationEngine({scroll:false});		
		$('#addPayPeriod').ajaxForm({
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

function _addNewLeaveType(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add New Leave Type1';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'startup/_load_add_new_leave_type', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addDefaultLeave').validationEngine({scroll:false});		
		$('#addDefaultLeave').ajaxForm({
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
	
	$.post(base_url + 'startup/_load_edit_department', {eid:eid}, function(data) {
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
				$.post(base_url+'startup/set_default_grace_period',{eid:eid},
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

function _setDefaultPayPeriod(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Set as <b>default</b> the selected Pay Period?';
	
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
				$.post(base_url+'startup/set_default_pay_period',{eid:eid},
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

function _updateStartupXml(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Done setting up your company?';
	
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
				$.get(base_url+'startup/disAbleStartup',{},
					function(o){													
					if(o.is_success==1) {
						dialogOkBox(o.message,{});								
						window.location = o.url;							
					}else{
						dialogOkBox(o.message,{});		
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