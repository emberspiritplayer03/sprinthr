function deleteHoliday(holiday_id) {
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this holiday?', {
		onYes: function(){
			_deleteHoliday(holiday_id, {
				onDeleted: function(o) {					
					closeDialog('#' + DIALOG_CONTENT_HANDLER);					
					//showHolidayList('#holiday_list');
                    showHolidayList('#holiday_list', $('#selected_year').val());
                    showOkDialog(o.message);
				},
				onLoading: function() {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showLoadingDialog('Deleting...');
				},
				onError: function(o) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(o.message);	
				}
			});	
		}
	});
}

function editHolidayFromList(holiday_id) {
	_editHoliday(holiday_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			//showHolidayList('#holiday_list');
            showHolidayList('#holiday_list', $('#selected_year').val());
			openMessage('Holiday has been saved.');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeMessage();
			closeAddHoliday();
			showLoadingDialog('Loading...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function showHolidayList(element, year) {
	_showHolidayList(element, year, {
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');
		}
	});	
}

function showHolidayCalendar(element,year) {
	$(element).html(loading_image + ' Loading...');
	$.get(base_url + 'holiday/ajax_show_holiday_calendar', {year:year}, function(data) {
		$(element).html(data);
			
	});
}

function checkAllBranches(form_element) {
	if (form_element != '') {
		form_element = form_element + ' ';
	}

	var is_checked = $(form_element + '#_checkbox-all').is(':checked');
	if (is_checked) {
		$(form_element + '._branch_checkbox').attr({'checked':'checked'});
		$(form_element + '#_checkbox-all').validationEngine('hide');
	} else {
		$(form_element + '._branch_checkbox').removeAttr('checked');
		$(form_element + '#_checkbox-all').validationEngine('showPrompt', '* Please select 1 option', 'error');
	}	
}

function holidayNameChanged() {
	var holiday_name = $('#_holiday_name').val();
	holiday_name = $.trim(holiday_name);
	if (holiday_name == '') {
		$('#_holiday_name').validationEngine('showPrompt', '* This field is required', 'error');
	} else {
		$('#_holiday_name').validationEngine('hide');
	}
}

function checkBranch(form_element) {
	if (form_element != '') {
		form_element = form_element + ' ';
	}	
	var n = $(form_element + "input:checked").length;
	var is_checked = $(form_element + '#_checkbox-all').is(':checked');
	if (is_checked) {
		n = n - 1;	
	}
	if (n > 0) {
		$(form_element + '#_checkbox-all').validationEngine('hide');
		$(form_element + '#_checkbox-all').removeAttr('checked');
	} else {
		$(form_element + '#_checkbox-all').validationEngine('showPrompt', '* Please select 1 option', 'error');	
	}
}

function closeEditHolidayDialog() {
	$('.formError').remove();
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_holiday_form');
}

function addHoliday() {
	$('._branch_checkbox').removeAttr('checked');
	$('#add_holiday_form_container').html(loading_image + ' Loading...');
	$('#add_holiday_link').hide();
	$('#add_holiday_form_container').html($('#add_holiday_id').html());
	$("#_holiday_name").focus();
	$('#message_container').hide();
	
	$("#add_holiday_form").validationEngine({scroll: false});	

	$('#add_holiday_form').ajaxForm({
		success:function(o) {
			if (o.is_added) {								
				closeAddHoliday();
				openMessage(o.message);
				showHolidayList('#holiday_list', $('#selected_year').val());
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox("Record saved",{});						
			} else {
				dialogOkBox(o.message,{});			
			}
			$('#add_holiday_form #token').val(o.token);	
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Saving...');
		}
	});		


	/*$('#add_holiday_form').ajaxForm({
		success:function(o) {
			if (o.is_added) {
				$('#add_holiday_form #token').val(o.token);				
				closeAddHoliday();
				openMessage(o.message);
				showHolidayList('#holiday_list', $('#selected_year').val());
			} else {
				alert(o.message);
				closeAddHoliday();
			}
		},
		beforeSubmit:function() {
			var error = 0;
			var n = $("#add_holiday_form input:checked").length;
			var count_checked = n;
			if (count_checked == 0) {
				error++;
				$('#_checkbox-all').validationEngine('showPrompt', '* Please select 1 option', 'error');				
			} else {
				$('#_checkbox-all').validationEngine('hide');
			}
			var holiday_name = $('#_holiday_name').val();
			if ($.trim(holiday_name) == '') {
				error++;
				$('#_holiday_name').validationEngine('showPrompt', '* This field is required', 'error');
			} else {
				$('#_holiday_name').val($.trim(holiday_name));
				$('#_holiday_name').validationEngine('hide');
			}
				
			if (error > 0) {
				return false;
			} else {
				$('#add_holiday_form').validationEngine('hideAll')
				$('#form_submit').html(loading_image + ' Saving data... ');
				$('#form_cancel').hide();
				return true;	
			}
		},
		dataType:'json'
	});	*/	
}

function closeMessage() {
	$('#message_container').hide();
}

function openMessage(message) {
	$('.message').html(message);
	$('#message_container').show();
}

function closeAddHoliday() {
	$('.formError').remove();
	$('#add_holiday_link').show();
	$('#add_holiday_form_container').html('');	
}