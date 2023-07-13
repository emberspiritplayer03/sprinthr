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

function load_loan_list_dt(eid) {	
	$.post(base_url + 'employee/_load_loan_list_dt',{eid:eid},function(o) {
		$('#loan_list_dt_wrapper').html(o);		
	});	
}

function editLoan(e_id) {
	var url = 'employee/ajax_edit_loan';
	_editLoan(e_id,url, {
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

