function _deleteDesignation(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected designation?';
	
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
	
				if (typeof events.onLoading == "function") {
					events.onLoading();
				}

				$.post(base_url+'activity/_delete_designation',{eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_designations_list_dt();			
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
					events.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function _editDesignation(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Designation';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'activity/ajax_edit_designation', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});						
	});
}