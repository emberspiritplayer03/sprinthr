function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}


function show_add_earnings_form(eid,frequency_id) {
	
	$("#add_earning_button").hide();
	$(".earnings-dt-container").hide();
	$("#add_earnings_form_wrapper").show();
	$("#earnings_wrapper").html(loading_image);	
	$.post(base_url+'earnings/ajax_add_new_earning',{eid:eid,frequency_id:frequency_id},
	function(o){
		$("#earnings_wrapper").html(o);	
	});	
	
	
}

function hide_add_earnings_form() {	
	$("#add_earnings_form").validationEngine("hide");	
	$('#add_earning_button').show();
	$('#add_earnings_form_wrapper').hide();
	$("#earnings_wrapper").html("");	
	$('.earnings-dt-container').show();
	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function load_earnings_list_dt(eid) {
	$("#earnings_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'earnings/_load_earnings_list_dt',{eid:eid},function(o) {
		$('#earnings_list_dt_wrapper').html(o);		
	});	
}

function load_yearly_bonus_list_dt(year) {
	$("#earnings_list_dt_wrapper").html(loading_image);	
	$.get(base_url + 'earnings/_load_yearly_bonus_list_dt',{year:year},function(o) {
		$('#earnings_list_dt_wrapper').html(o);		
	});	
}

function load_converted_leave_list_by_year_dt(year) {
	$("#leave_converted_list_dt_wrapper").html(loading_image);	
	$.get(base_url + 'earnings/_load_leave_converted_list_dt',{year:year},function(o) {
		$('#leave_converted_list_dt_wrapper').html(o);		
	});	
}

function load_sum_approved_earnings(eid) {
	$("#total_approved").html(loading_image);	
	$.post(base_url + 'earnings/_load_sum_approved_earnings',{eid:eid},function(o) {
		$('#total_approved').html(o);		
	});	
}

function load_sum_pending_earnings(eid) {
	$("#total_pending").html(loading_image);	
	$.post(base_url + 'earnings/_load_sum_pending_earnings',{eid:eid},function(o) {
		$('#total_pending').html(o);		
	});	
}

function load_sum_archived_earnings(eid) {
	$("#total_archived").html(loading_image);	
	$.post(base_url + 'earnings/_load_sum_archived_earnings',{eid:eid},function(o) {
		$('#total_archived').html(o);		
	});	
}

function load_approved_earnings_list_dt(eid,frequency_id=1) {

	$("#earnings_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'earnings/_load_approved_earnings_list_dt',{eid:eid,frequency_id:frequency_id},function(o) {
		$('#earnings_list_dt_wrapper').html(o);		
	});	
}

function load_archived_earnings_list_dt(eid) {
	$("#earnings_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'earnings/_load_archived_earnings_list_dt',{eid:eid},function(o) {
		$('#earnings_list_dt_wrapper').html(o);		
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

function importEarnings(eid) {
	_importEarnings(eid,{
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			load_approved_earnings_list_dt('"' + eid + '"', $('select[name="selected_frequency"]').val());	
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});			
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

function withSelectedYearlyBonus(status) {
	_withSelectedYearlyBonus(status, {
		onYes: function(o) {
			//showLoadingDialog('Processing...');				
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

function withSelectedApproved(status) {
	_withSelectedEarnings(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_sum_approved_earnings('"' + o.eid + '"');																		
			load_approved_earnings_list_dt('"' + o.eid + '"');	
			$("#chkAction").attr('disabled',true);		
		}, 
		onNo: function(){			
			$("#chkAction").val("");
			//$("#chkAction").attr('disabled',true);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function withSelectedArchived(status) {	
	_withSelectedEarnings(status, {
		onYes: function(o) {
			dialogOkBox(o.message,{});							
			load_archived_earnings_list_dt('"' + o.eid + '"');																		
			load_sum_archived_earnings('"' + o.eid + '"');	
			$("#chkAction").attr('disabled',true);		
		}, 
		onNo: function(){			
			$("#chkAction").val("");
			//$("#chkAction").attr('disabled',true);
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


function archivePendingEarning(keid,eid) {
	_archiveEarning(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_sum_pending_earnings('"' + o.eid + '"');													
			load_earnings_list_dt('"' + o.eid + '"');									
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreArchivedEarning(keid,eid) {
	_restoreArchivedEarning(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_archived_earnings_list_dt('"' + o.eid + '"');													
			load_sum_archived_earnings('"' + o.eid + '"');									
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveApprovedEarning(keid,eid) {
	_archiveEarning(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});
			load_approved_earnings_list_dt('"' + o.eid + '"');													
			load_sum_approved_earnings('"' + o.eid + '"');									
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function approveEarning(keid,eid) {
	_approveEarning(keid,eid, {
		onYes: function(o) {				
			dialogOkBox(o.message,{});			
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function disApproveEarning(keid,eid) {
	_disApproveEarning(keid,eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});			
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}



function changePayPeriodByYear(selected_year,class_container,selected_frequency = 0)
{
	$("." + class_container).html(loading_image);
	$.get(base_url + 'earnings/ajax_load_payroll_period_by_year2',{selected_year:selected_year,selected_frequency:selected_frequency},
		function(o){
			$("." + class_container).html(o);			
		}
	);
}