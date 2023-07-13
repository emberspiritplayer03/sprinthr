function _showEditOtAllowance(eid,events) {
	var dialog_id = '#overtime-allowance-edit-container';
	var title     = 'Edit OT Allowance';
	var width 	  = 455;
	var height    = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'overtime_settings/ajax_edit_ot_allowance', {eid:eid}, function(data) {
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#edit_ot_allowance_form').validationEngine({scroll:false});		
		$('#edit_ot_allowance_form').ajaxForm({
			success:function(o) {
				if (o.is_success) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
				$("#token").val(o.token);
			},
			dataType:'json',
			beforeSubmit: function() {				
				showLoadingDialog('Saving...');		        	
		        return true;
		       /* var total_selected_day_type = $("#edit_ot_allowance_form").find('input.chk_day_type:checked').length;  
		        if( total_selected_day_type <= 0 ){
		          closeDialog('#' + DIALOG_CONTENT_HANDLER);      
		          dialogOkBox("Please select atleast 1 day type",{});
		          return false;
		        }else{
		        	showLoadingDialog('Saving...');		        	
		        	return true;
		        }*/
			}
		});	

		$("#date_start").datepicker({
		    dateFormat:"yy-mm-dd"
		  });

	});
}

function _showEditOtRate(eid,events) {
	var dialog_id = '#overtime-rate-edit-container';
	var title     = 'Edit OT Rate';
	var width 	  = 455;
	var height    = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'overtime_settings/ajax_edit_ot_rate', {eid:eid}, function(data) {
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('input').blur();
		$('#edit_ot_rate_form').validationEngine({scroll:false});		
		$('#edit_ot_rate_form').ajaxForm({
			success:function(o) {				
				events.onSaved(o);
				$("#token").val(o.token);
			},
			dataType:'json',
			beforeSubmit: function() {				
				showLoadingDialog('Saving...');		        	
		        return true;		      
			}
		});	
	});
}

function _showDeleteOtAllowance(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Delete the selected Overtime Allowance?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url+'overtime_settings/delete_ot_allowance',{eid:eid},
					function(o){													
					if(o.is_success) {								
													
					}	
					
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}				
															   
				},"json");		
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _showDeleteOtRate(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Delete the selected Overtime Rate?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url+'overtime_settings/delete_ot_rate',{eid:eid},
					function(o){													
					if (typeof events.onYes == "function") {
						events.onYes(o);
					}				
															   
				},"json");		
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof events.onNo == "function") {
					events.onNo(o);
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}