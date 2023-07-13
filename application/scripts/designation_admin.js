function load_designations_list_dt() {
	$("#designations_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'activity/_load_designations_list_dt',{},function(o) {
		$('#designations_list_dt_wrapper').html(o);		
	});	
}

function show_add_designation_form() {
	$('#add_designation_button').hide();
	$('#add_designation_form_wrapper').show();
	$("#designation_wrapper").html(loading_image);	
	$.post(base_url+'activity/ajax_add_new_designation',{},
	function(o){
		$("#designation_wrapper").html(o);	
	});	
	
}

function hide_add_designation_form() {	
	$('#add_designation_button').show();
	$('#add_designation_form_wrapper').hide();
	$("#designation_wrapper").html("");	
	clearFormError($("form").attr("id"));	
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function deleteDesignation(eid) {
	_deleteDesignation(eid, {
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Deleting...');
		}
	});	
}

function editDesignation(eid) {
	_editDesignation(eid, {
		onSaved: function(o) {		
			load_designations_list_dt();				
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