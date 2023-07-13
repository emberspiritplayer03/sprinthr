function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function load_annualized_tax_by_year(year) {
	$("#annualized_tax_list_dt_wrapper").html(loading_image);	
	$.get(base_url + 'annualize_tax/_load_annualize_tax_by_year',{year:year},function(o) {
		$('#annualized_tax_list_dt_wrapper').html(o);		
	});	
}

function editEarning(eid) {
	_editEarning(eid, {
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

function withSelectedPendings(status) {
	_withSelectedEarnings(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_sum_pending_earnings('"' + o.eid + '"');																		
			load_earnings_list_dt('"' + o.eid + '"');
			$("#chkAction").attr('disabled',true);			
		}, 
		onNo: function(){			
			$("#chkAction").val("");
			//$("#chkAction").attr('disabled',true);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function processYearlyBonus() {
	_processYearlyBonus({
		onYes: function(o) {
			var year_selected = $("#year").val();
						
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
			load_yearly_bonus_list_dt(year_selected);						
		}, 
		onNo: function(){						
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}