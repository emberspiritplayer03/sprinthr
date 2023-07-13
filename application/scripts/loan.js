function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function computeNoOfInstallmentBy(start_date,end_date)
{
	$.post(base_url+'loan/ajax_compute_no_of_installment',{start_date:start_date,end_date:end_date},
	function(o){
		$("#request_loan_form_wrapper").html(o);	
	});	
}

function getEndDate(number_of_installment,deduction_type_id,start_date_id,end_date)
{
	var number_of_installment = $("#" + number_of_installment).val();
	var type_of_deduction     = $("#" + deduction_type_id).val();	
	var start_date 		      = $("#" + start_date_id).val();
	if(type_of_deduction != '' && start_date != ''){
		$.post(base_url+'loan/ajax_get_end_date',{number_of_installment:number_of_installment,type_of_deduction:type_of_deduction,start_date:start_date},
		function(o){
			if(o.end_date){
				$("#" + end_date).val(o.end_date);
			}
			
		},"json");	
	}
}

function archivesEnableDisableWithSelected(form)
{	
	var check = countChecked(form);		
	if(check > 0){
		if(form == 1){
			$("#chkAction").removeAttr('disabled');
		}else if(form == 2){
			$("#chkActionLoanType").removeAttr('disabled');
		}else if(form == 3){
			$("#chkActionLoanDeductionType").removeAttr('disabled');
		}
	}else{
		if(form == 1){
			$("#chkAction").attr('disabled',true);
		}else if(form == 2){
			$("#chkActionLoanType").attr('disabled',true);
		}else if(form == 3){
			$("#chkActionLoanDeductionType").attr('disabled',true);
		}
	}
}

function show_request_loan_form() {
	$('#request_button').hide();
	$('#request_loan_form_wrapper').show();
	$("#request_loan_form_wrapper").html(loading_image);	
	$.get(base_url+'loan/ajax_add_new_loan',{},
	function(o){
		$("#request_loan_form_wrapper").html(o);	
	});	
	
}

function show_add_loan_payment_form(e_id) {
	if(e_id != ''){
		$("#wrapper_add_payment_form").html(loading_image);	
		$.post(base_url+'loan/ajax_show_add_loan_payment_form',{e_id:e_id},
		function(o){
			$("#wrapper_add_payment_form").html(o);	
		});	
	}
	
}

function loan_payment_schedule_notification(loan_id) {
	if( loan_id > 0 ){
		$(".loan-notification-" + loan_id).html(loading_image);	
		$.get(base_url+'loan/ajax_loan_payment_schedule_notification',{loan_id:loan_id},
		function(o){
			if( o.is_with_notification ){
				if( !$(".loan-notification-" + loan_id).hasClass("alert alert-error") ){
					$(".loan-notification-" + loan_id).addClass("alert alert-error");
				}

				$(".loan-notification-" + loan_id).html(o.message);	
			}else{
				$(".loan-notification-" + loan_id).removeClass("alert alert-error");
				$(".loan-notification-" + loan_id).html("");
			}
		},"json");	
	}
	
}

function loan_payment_schedule_balance(loan_id) {
	if( loan_id > 0 ){
		$(".row-loan-balance-" + loan_id).html(loading_image);	
		$.get(base_url+'loan/ajax_loan_balance',{loan_id:loan_id},
		function(o){
			$(".row-loan-balance-" + loan_id).html(o.amount);	
		},"json");	
	}
	
}


function _updateLoanAmount(loan_id, employee_id,total_amount_to_pay,deduction_per_period) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to update the data?';
	
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
				$.post(base_url+'loan/_update_loan_amount',{loan_id:loan_id,employee_id:employee_id,total_amount_to_pay:total_amount_to_pay,deduction_per_period:deduction_per_period},
					function(o){
					if (o.is_success == "true") {
						events.onYes(o);
					}	
					loan_payment_schedule_notification(loan_id);	
    				loan_payment_schedule_balance(loan_id);	
					$("#request_loan_form_wrapper").load(location.href + " #request_loan_form_wrapper");
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

function _updateLoanStatus(loan_id, employee_id) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to stop loan?';
	
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
				$.post(base_url+'loan/_update_loan_status',{loan_id:loan_id,employee_id:employee_id},
					function(o){
					if (o.is_success == "true") {
						events.onYes(o);
					}	
					$("#request_loan_form_wrapper").load(location.href + " #request_loan_form_wrapper");
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






function show_load_add_payment_form() {
	$('#request_button').hide();
	$('#request_loan_form_wrapper').show();
	$("#request_loan_form_wrapper").html(loading_image);	
	var e_id = $("#loan_id").val();
	$.post(base_url+'loan/ajax_add_new_loan_payment',{e_id:e_id},
	function(o){
		$("#request_loan_form_wrapper").html(o);	
	});	
	
}

function show_add_loan_type_form() {
	$('#request_button').hide();
	$('#request_loan_form_wrapper').show();
	$("#request_loan_form_wrapper").html(loading_image);	
	$.get(base_url+'loan/ajax_add_new_loan_type',{},
	function(o){
		$("#request_loan_form_wrapper").html(o);	
	});	
	
}

function show_add_loan_deduction_type_form() {
	$('#request_button').hide();
	$('#request_loan_form_wrapper').show();
	$("#request_loan_form_wrapper").html(loading_image);	
	$.get(base_url+'loan/ajax_add_new_loan_deduction_type',{},
	function(o){
		$("#request_loan_form_wrapper").html(o);	
	});	
	
}

function computeDays(start,end) {	
	var start = new Date(start);	
	var end = new Date(end);
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;  //=> 8.525845775462964
	}else {
		var length = 0;
	}	
	
	return length;
}

function hide_show_loan_form() {	
	$('#request_button').show();
	$('#request_loan_form_wrapper').hide();
	clearFormError($("form").attr("id"));	
}

function hide_loan_payment_breakdown(e_id) {	
	$("#wrapper_breakdown").hide(400);	
	$(".tipsy").remove();	
	$('#loan_payment_breakdown_wrapper').html("");	
	$('#loan_payment_breakdown_wrapper').html("");	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function load_loan_list_dt() {	
	$('#loan_list_dt_wrapper').html(loading_image);	
	$.get(base_url + 'loan/_load_loan_list_dt',{},function(o) {
		$('#loan_list_dt_wrapper').html(o);		
	});	
}

function load_loan_archive_list_dt() {	
	$.get(base_url + 'loan/_load_loan_archive_list_dt',{},function(o) {
		$('#loan_list_dt_wrapper').html(o);		
	});	
}

function load_employee_list_dt(dept_id) {	
	$.post(base_url + 'loan/_load_employee_list_dt',{dept_id:dept_id},function(o) {
		$('#loan_list_dt_wrapper').html(o);		
	});	
}

function load_loan_type_list_dt() {	
	$.get(base_url + 'loan/_load_loan_type_list_dt',{},function(o) {
		$('#loan_type_list_dt_wrapper').html(o);		
	});	
}

function load_loan_payment_breakdown(e_id,hide_show) {	
	$("#wrapper_breakdown").show(2000);
	$('#loan_payment_breakdown_wrapper').html(loading_image);		
	$.post(base_url + 'loan/_load_loan_period_payment_breakdown',{e_id:e_id,hide_show:hide_show},function(o) {
		$('#loan_payment_breakdown_wrapper').html(o);		
	});	
}

function load_loan_type_archive_list_dt() {	
	$.get(base_url + 'loan/_load_loan_type_archive_list_dt',{},function(o) {
		$('#loan_type_list_dt_wrapper').html(o);		
	});	
}

function load_loan_deduction_type_archive_list_dt() {	
	$.get(base_url + 'loan/_load_loan_deduction_type_archive_list_dt',{},function(o) {
		$('#loan_deduction_type_list_dt_wrapper').html(o);		
	});	
}

function load_loan_details_list_dt(e_id) {	
	$.post(base_url + 'loan/_load_loan_details_list_dt',{e_id:e_id},function(o) {
		$('#loan_payment_list_dt_wrapper').html(o);		
	});	
}

function load_employee_loan_list_dt(e_id) {	
	$.post(base_url + 'loan/_load_employee_loan_list_dt',{e_id:e_id},function(o) {
		$('#employee_loan_list_dt_wrapper').html(o);		
	});	
}

function load_e_loan_details_list_dt(e_id) {	
	$.post(base_url + 'loan/_load_employee_loan_details_list_dt',{e_id:e_id},function(o) {
		$('#employee_loan_details_list_dt_wrapper').html(o);		
	});	
}

function load_loan_deduction_type_list_dt() {	
	$.get(base_url + 'loan/_load_loan_deduction_type_list_dt',{},function(o) {
		$('#loan_deduction_type_list_dt_wrapper').html(o);		
	});	
}

function loanTypeWithSelectedAction(status) {
	if(status){	
		_loanTypeWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function loanTypeArchiveWithSelectedAction(status) {
	if(status){	
		_loanTypeArchiveWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkActionLoanType').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkActionLoanType').val('');
			} 
		});
	}
}

function loanDeductionTypeWithSelectedAction(status) {
	if(status){	
		_loanDeductionTypeWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function loanDeductionTypeArchivedWithSelectedAction(status) {
	if(status){	
		_loanDeductionTypeArchivedWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkActionLoanDeductionType').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkActionLoanDeductionType').val('');
			} 
		});
	}
}

function loanWithSelectedAction(status) {
	if(status == 'loan_archive'){	
		_loanWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}else if(status == 'view_details'){
		$('#request_loan_form_wrapper').show();
		$("#request_loan_form_wrapper").html(loading_image);	
		$.get(base_url+'loan/ajax_selected_view_loan_details',$("#withSelectedAction").serialize(),
		function(o){
			$("#request_loan_form_wrapper").html(o);	
		});	
		$('#chkAction').val('');
	}else if(status == 'view_payment_history'){
		$('#request_loan_form_wrapper').show();
		$("#request_loan_form_wrapper").html(loading_image);	
		$.get(base_url+'loan/ajax_selected_view_loan_payment_history',$("#withSelectedAction").serialize(),
		function(o){
			$("#request_loan_form_wrapper").html(o);	
		});
		$('#chkAction').val('');
	}	
}

function loanArchiveWithSelectedAction(status) {
	if(status){	
		_loanArchiveWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function loanPaymentWithSelectedAction(status) {
	if(status){	
		_loanPaymentWithSelectedAction(status, {
			onYes: function() {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function editLoan(e_id) {
	_editLoan(e_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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
			dialogOkBox(o.message,{});
		}
	});	
}

function editLoanTypeForm(e_id) {
	_editLoanTypeForm(e_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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
			dialogOkBox(o.message,{});
		}
	});	
}

function addLoanPaymentForm(e_id) {
	_addLoanPaymentForm(e_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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
			dialogOkBox(o.message,{});
		}
	});	
}

function editLoanPaymentForm(e_id) {
	_editLoanPaymentForm(e_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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
			dialogOkBox(o.message,{});
		}
	});	
}

function editLoanDeductionTypeForm(e_id) {
	_editLoanDeductionTypeForm(e_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
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
			dialogOkBox(o.message,{});
		}
	});	
}

function archiveLoanType(h_id) {
	_archiveLoanType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreArchiveLoanType(h_id) {
	_restoreArchiveLoanType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveLoanDeductionType(h_id) {
	_archiveLoanDeductionType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreLoanDeductionType(h_id) {
	_restoreLoanDeductionType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteLoanPayment(h_id) {
	_deleteLoanPayment(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteLoanPaymentBreakdown(e_id,hide_show) {
	_deleteLoanPaymentBreakdown(e_id,hide_show, {
		onYes: function() {
			closeDialog('#action_modal');			
		}, 
		onNo: function(){
			closeDialog('#action_modal');
		} 
	});	
}

function archiveLoan(h_id) {
	_archiveLoan(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreArchiveLoan(h_id) {
	_restoreArchiveLoan(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteLoanPaymentSchedule(id, obj) {
	var is_success = false;	
	_deleteLoanPaymentSchedule(id, {
		onYes: function(o) {
			if( o.is_success ){
				var loan_id = $(obj).attr("data-key");
				$(obj).closest("tr.loan-payment-row").remove();				
				loan_payment_schedule_notification(loan_id);
				loan_payment_schedule_balance(loan_id);
			}
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function addPaymentSchedule(dynamic_data,obj) {	
	_addPaymentSchedule(dynamic_data, {
		onYes: function(o) {
			if( o.is_success ){
				var loan_schedule_id = o.last_inserted_id;
				var row_id           = dynamic_data['row_id'];
				var loan_id          = dynamic_data['loan_id'];

				var amount_to_pay = parseFloat($(".loan-expected-amount-" + row_id).val());
			    var amount_paid   = parseFloat($(".loan-paid-" + row_id).val());

			    $(".loan-expected-amount-" + row_id).val(amount_to_pay.toFixed(2));
			    $("loan-paid-" + row_id).val(amount_paid.toFixed(2));

				$(".loan-expected-date-" + row_id).removeClass("loan-date-payment");
				$(".loan-expected-date-" + row_id).prop("disabled",true);

				$(".loan-expected-amount-" + row_id).addClass("input-" + loan_schedule_id);
				$(".loan-expected-amount-" + row_id).addClass("loan-amount-to-pay-" + loan_schedule_id);
				$(".loan-expected-amount-" + row_id).prop("disabled",true);

				$(".loan-paid-" + row_id).addClass("input-" + loan_schedule_id);
				$(".loan-paid-" + row_id).addClass("loan-amount-paid-" + loan_schedule_id);
				$(".loan-paid-" + row_id).prop("disabled",true);

				$(".loan-date-paid-" + row_id).addClass("input-" + loan_schedule_id);
				$(".loan-date-paid-" + row_id).addClass("loan-date-payment-" + loan_schedule_id);
				$(".loan-date-paid-" + row_id).attr("disabled",true);

				var new_btn_group = '<td style="width:139px" class="loan-col-' + loan_schedule_id + '"><div class="btn-group action-grp action-grp-' +loan_schedule_id + '"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Action<span class="caret"></span></a><ul class="dropdown-menu"><li><a href="javascript:void(0);" class="btn-grp-edit" data-key="' + loan_id + '" data-index="' + loan_schedule_id +'"><i class="icon-pencil"></i> Edit</a></li><li><a href="javascript:void(0);" data-key="' + loan_id + '" class="btn-grp-remove" data-index="' + loan_schedule_id + '"><i class="icon-trash"></i> Remove</a></li><li><a href="javascript:void(0);" class="btn-grp-set-paid" data-index="' + loan_schedule_id + '" data-key="' + loan_id + '"><i class="icon-check"></i> Set as paid</a></li></ul></div><div class="sub-action-grp sub-action-grp-' + loan_schedule_id + '"><a href="javascript:void(0);" class="btn btn-small btn-grp-update" data-key="' + loan_id + '" data-index="' + loan_schedule_id + '" style="margin-right:5px;">Update</a><a href="javascript:void(0);" data-index="' + loan_schedule_id + '" class="btn btn-small btn-cancel">Cancel</a></div></td>';
				$("table.tbl-" + loan_id + " tr:last").append(new_btn_group);
				$(".sub-action-grp-" + loan_schedule_id).hide();
    			$(".action-grp-" + loan_schedule_id).show();
    			$(obj).closest("td.temp-btn-grp").remove();	

    			loan_payment_schedule_notification(loan_id);	
    			loan_payment_schedule_balance(loan_id);

			}
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function updatePaymentSchedule(dynamic_data) {	
	_updatePaymentSchedule(dynamic_data, {
		onYes: function(o) {
			if( o.is_success ){
				var row_id  = dynamic_data['id'];
				var loan_id = dynamic_data['loan_id'];
				$(".sub-action-grp-" + row_id).hide();
			    $(".action-grp-" + row_id).show();

			    var amount_to_pay = parseFloat($(".loan-amount-to-pay-" + row_id).val());
			    var amount_paid   = parseFloat($(".loan-amount-paid-" + row_id).val());

			    $(".loan-amount-to-pay-" + row_id).val(amount_to_pay.toFixed(2));
			    $(".loan-amount-paid-" + row_id).val(amount_paid.toFixed(2));

			    $(".input-" + row_id).prop("disabled",true);
			    loan_payment_schedule_balance(loan_id);   
			    loan_payment_schedule_notification(loan_id);			    
			}
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function setAsPaidLoanSchedule(loan_schedule_id,loan_id) {	
	_updateLoanScheduleStatus(loan_schedule_id,'paid', {
		onYes: function(o) {
			if( o.is_success ){
				$(".loan-col-" + loan_schedule_id).html("<span class=\"label label-success\">Paid</span> <a class=\"btn btn-small btn-cancel-paid\" href=\"javascript:void(0);\" data-index=\"" + loan_schedule_id + "\" data-key=\"" + loan_id + "\">Cancel</a>");
			}
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function setAsUnPaidLoanSchedule(loan_schedule_id,loan_id) {	
	_updateLoanScheduleStatus(loan_schedule_id,'unpaid', {
		onYes: function(o) {
			if( o.is_success ){				
				var new_btn_group = '<div class="btn-group action-grp action-grp-' +loan_schedule_id + '"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Action<span class="caret"></span></a><ul class="dropdown-menu"><li><a href="javascript:void(0);" class="btn-grp-edit" data-key="' + loan_id + '" data-index="' + loan_schedule_id +'"><i class="icon-pencil"></i> Edit</a></li><li><a href="javascript:void(0);" data-key="' + loan_id + '" class="btn-grp-remove" data-index="' + loan_schedule_id + '"><i class="icon-trash"></i> Remove</a></li><li><a href="javascript:void(0);" class="btn-grp-set-paid" data-index="' + loan_schedule_id + '" data-key="' + loan_id + '"><i class="icon-check"></i> Set as paid</a></li></ul></div><div class="sub-action-grp sub-action-grp-' + loan_schedule_id + '"><a href="javascript:void(0);" class="btn btn-small btn-grp-update" data-key="' + loan_id + '" data-index="' + loan_schedule_id + '" style="margin-right:5px;">Update</a><a href="javascript:void(0);" data-index="' + loan_schedule_id + '" class="btn btn-small btn-cancel">Cancel</a></div>';

				$("td.loan-col-" + loan_schedule_id).html(new_btn_group);
				$(".sub-action-grp-" + loan_schedule_id).hide();
    			$(".action-grp-" + loan_schedule_id).show();				
			}
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function changePayPeriodByYearMonth(selected_year,class_container,selected_frequency,selected_month, is_government = false)
{
	$("#" + class_container).html(loading_image);
	$.get(base_url + 'loan/ajax_load_payroll_period_by_year_and_month',{selected_year:selected_year,selected_frequency:selected_frequency,selected_month:selected_month,is_government:is_government},
		function(o){
			$("#" + class_container).html(o);			
		}
	);
}

function importLoans() {
    _importLoans({
        onLoading: function() {
            showLoadingDialog('Loading...');
        },
        onImported: function(o) {
            closeDialog('#_new_dialog_');  
            load_loan_list_dt();          
            showOkDialog(o.message,{height:'auto'});
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



function importGovtLoans() {
    _importGovtLoans({
        onLoading: function() {
            showLoadingDialog('Loading...');
        },
        onImported: function(o) {
            closeDialog('#_new_dialog_1');  
            load_loan_list_dt();          
            showOkDialog(o.message,{height:'auto'});
        },
        onImporting: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            $("body").append("<div id='_new_dialog_1'></div>");
            dialogGeneric('#_new_dialog_1',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
        },
        onError: function(o) {
            closeDialog('#_new_dialog_1');
            $('#_new_dialog_1').remove();
            showOkDialog(o.message);
        }
    });
}




function closeTheDialog() {
    closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_attendance');
}