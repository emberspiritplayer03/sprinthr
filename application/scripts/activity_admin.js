function load_activities_list_dt() {
	$("#activities_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'activity/_load_activities_list_dt',{},function(o) {
		$('#activities_list_dt_wrapper').html(o);		
	});	
}

function show_add_activity_form() {
	$('#add_activity_button').hide();
	$('#add_activity_form_wrapper').show();
	$("#activity_wrapper").html(loading_image);	
	$.post(base_url+'activity/ajax_add_new_activity',{},
	function(o){
		$("#activity_wrapper").html(o);	
	});	
	
}

function hide_add_activity_form() {	
	$('#add_activity_button').show();
	$('#add_activity_form_wrapper').hide();
	$("#activity_wrapper").html("");	
	clearFormError($("form").attr("id"));	
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function deleteActivity(eid) {
	_deleteActivity(eid, {
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

function editActivity(eid) {
	_editActivity(eid, {
		onSaved: function(o) {		
			load_activities_list_dt();				
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