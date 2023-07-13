function datatable_loader(sidebar) {
	if(sidebar == 1) {
		load_pending_overtime_list_dt();
	}
}

function load_pending_overtime_list_dt() {	
	var h_department_id = $('#department_id').val();
	$.post(base_url + 'reconstructed_overtime/_load_pending_overtime_list_dt',{h_department_id:h_department_id},function(o) {
		$('#overtime_list_dt').html(o);		
	});	
}

function show_request_overtime_form_clerk() {
	$('#request_overtime_button').hide();
	$('#request_overtime_form_wrapper').show();
	$("#request_overtime_form_wrapper").html(loading_image);	
	$.get(base_url+'reconstructed_overtime/ajax_add_new_overtime_request',{},
	function(o){
		$("#request_overtime_form_wrapper").html(o);	
	});	
	
}

function hide_request_overtime_form_clerk() {	
	$('#request_overtime_button').show();
	$('#request_overtime_form_wrapper').hide();
	clearFormError();
	
}

function clearFormError()
{
	$("form").validationEngine('hide'); 	
}
