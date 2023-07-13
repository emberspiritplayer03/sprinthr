function _empQuickAddRequest(module, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	if(module == 'quick_leave'){
		var title = 'Quick Add Leave';
		var width = 600;
	}else if(module == 'quick_overtime'){
		var title = 'Quick Add Overtime';
		var width = 600;
	}
	
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'dtr/_load_quick_add_form', {module:module}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});		
		
		$("#emp_quick_add").val('');
		$('#quick_add_form').validationEngine({scroll:false});		
		$('#quick_add_form').ajaxForm({
		success:function(o) {
			if (o.is_saved = 1) {
				if(typeof events.onSaved == "function") {					
					
					events.onSaved(o);
				}
			}else{
				if(typeof events.onError == "function") {
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