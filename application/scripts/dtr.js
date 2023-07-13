function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
	$("#emp_quick_add").val('');
}

function load_dtr_list_dt() {
	$.get(base_url+'dtr/_load_dtr_list_dt',{},
	function(o){
		$("#dtr_list_dt_wrapper").html(o);
	});	
}

function wrapperComputeDaysWithHalfDay(addHalfDay,deductHalfDay,outputId) {
	var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),addHalfDay,deductHalfDay);					
	$("#" + outputId).val(output);

}


function computeDaysWithHalfDay(start,end,addHalfDay,deductHalfDay) {	
	var start = new Date(start);	
	var end = new Date(end);
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;  //=> 8.525845775462964
	}else {
		var length = 0;
	}
	
	//Halfday	
	var total = 0;
	if($('#' + addHalfDay).is(':checked')){total = total - 0.5;}
	if($('#' + deductHalfDay).is(':checked')){total = total - 0.5;}
	
	length = length + total;
	
	return length;
}

function empQuickAddRequest(module) {
	if(module){
	_empQuickAddRequest(module, {
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
			$("#emp_quick_add").val("");
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
	}
}

function load_show_specific_schedule() {
	var start_date 		= $('#start_date').val();	
	
	if(start_date != ""){
		$('#_schedule_loading_wrapper').html(loading_image);
		$.post(base_url + 'dtr/load_get_specific_schedule',{start_date:start_date},function(o) {
			$('#_schedule_loading_wrapper').html('');
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}

function load_dtr_filter_by_date() {
	var from 	= $('#from').val();
	var to		= $('#to').val();
	
	if(from != "" && to != ""){
		load_dtr_list_dt();
	}
}