function _showPayslip(element, employee_id, from, to, events) {	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	$.get(base_url + 'payslip/show_payslip', {employee_id:employee_id, from:from, to:to, ajax:1}, function(data) {
		$(element).html(data);
		if (typeof events.onLoaded == "function") {
			events.onLoaded();
		}
	});	
}

function _showEmployeeList(element, from, to, events) {
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	$.get(base_url + 'payslip/manage', {from:from, to:to, ajax:1}, function(data) {
		$(element).html(data);
		if (typeof events.onLoaded == "function") {
			events.onLoaded();
		}		
	});
}

function _updatePayslips(from, to, events) {
	if (typeof events.onUpdating == "function") {
		events.onUpdating();
	}
	$.get(base_url + 'payslip/_update_payslips', {from:from, to:to}, function(o) {
		if (o.is_updated) {
			if (typeof events.onUpdated == "function") {
				events.onUpdated(o);
			}				
		} else {
			if (typeof events.onError == "function") {
				events.onError(o);
			}		
		}
	},'json');
}

function _updateEmployeePayslip(employee_id, from, to, events) {
	var ans = true;
	if (typeof events.onBeforeUpdate == "function") {
		ans = events.onBeforeUpdate();
	}	
	if (ans) {
		if (typeof events.onUpdating == "function") {
			events.onUpdating();
		}
		$.get(base_url + 'payslip/_update_employee_payslip', {employee_id:employee_id, from:from, to:to}, function(o) {
			if (o.is_updated) {
				if (typeof events.onUpdated == "function") {
					events.onUpdated(o);
				}				
			} else {
				if (typeof events.onError == "function") {
					events.onError(o);
				}		
			}
		},'json');
	}
}

function _generatePayslipByMonthCutoffNumberYear(month, cutoff_number, year, q, events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Generate Payroll';
	var width = 470;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'payroll_register/_load_generate_payroll_option', {month:month, cutoff_number:cutoff_number, year:year, q:q}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#generatePayrollForm').validationEngine({scroll:false});		
		$('#generatePayrollForm').ajaxForm({
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