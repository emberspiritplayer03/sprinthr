function getFormToken() {
	var token;
	$.ajax(base_url + 'global/ajax_get_form_token', {
		type:'POST',
		data: '',
		async: false,
		success: function(o){
    		token = o;
		}
	});
	return token;	
}

function _showHolidayList(element, year, events) {
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}
	$.get(base_url + 'holiday/ajax_show_holiday_list', {year:year}, function(data) {
		$(element).html(data);
		if (typeof events.onLoaded == "function") {
			events.onLoaded();
		}		
	});
}

function _deleteHoliday(holiday_id, events) {
	var ans = true;
	if (typeof events.onBeforeDelete == "function") {
		ans = events.onBeforeDelete();
	}	
	if (ans) {
		if (typeof events.onLoading == "function") {
			events.onLoading();
		}		
		$.post(base_url + 'holiday/_delete_holiday', {holiday_id:holiday_id}, function(o) {
			if (o.is_deleted) {
				if (typeof events.onDeleted == "function") {
					events.onDeleted(o);
				}				
			} else {
				if (typeof events.onError == "function") {
					events.onError(o);
				}							
			}
		},'json');
	}
}

function _editHoliday(holiday_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Holiday';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'holiday/ajax_edit_holiday', {holiday_id:holiday_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_holiday_form'
		});
		
		$('#edit_holiday_form #holiday_name_').select();
		$('#edit_holiday_form').validationEngine({scroll:false});		
		$('#edit_holiday_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}					
			},
			dataType:'json',
			beforeSubmit: function() {
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#edit_holiday_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_holiday_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}