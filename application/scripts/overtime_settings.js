function load_overtime_allowance_list_dt() {
	$('#overtime-allowance-wrapper').html(loading_image);
	$.get(base_url + 'overtime_settings/load_overtime_allowance_list',{},function(o) {
		$('#overtime-allowance-wrapper').html(o);
	});
}

function load_overtime_rate_list_dt() {
	$('#overtime-rate-wrapper').html(loading_image);
	$.get(base_url + 'overtime_settings/load_overtime_rate_list',{},function(o) {
		$('#overtime-rate-wrapper').html(o);
	});
}

function showAddOtAllowance(){
	$("#overtime-settings-container").hide();
	$("#overtime-settings-form-container").show();
	$('#overtime-settings-form-container').html(loading_image);

	$.get(base_url + 'overtime_settings/_load_add_ot_allowance_form',{},function(o) {
		$('#overtime-settings-form-container').html(o);		
	});	
		
}

function showAddOtRate(){
	$("#overtime-settings-container").hide();
	$("#overtime-settings-form-container").show();
	$('#overtime-settings-form-container').html(loading_image);

	$.get(base_url + 'overtime_settings/_load_add_ot_rate_form',{},function(o) {
		$('#overtime-settings-form-container').html(o);		
	});	
		
}

function showEditOtAllowance(eid) {
	_showEditOtAllowance(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			closeDialog('#overtime-allowance-edit-container');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
          	load_overtime_allowance_list_dt();
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#overtime-allowance-edit-container');
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function showEditOtRate(eid) {
	_showEditOtRate(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			if(o.is_success){
				closeTheDialog();
				closeDialog('#_new_dialog_');
				closeDialog('#overtime-rate-edit-container');	
				$('#_new_dialog_').remove();
				load_overtime_rate_list_dt();	
			}
			dialogOkBox(o.message,{});          			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#overtime-rate-edit-container');
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Saving...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function showDeleteOtAllowance(eid) {
	_showDeleteOtAllowance(eid, {
		onYes: function(o) {
			load_overtime_allowance_list_dt();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function showDeleteOtRate(eid) {
	_showDeleteOtRate(eid, {
		onYes: function(o) {
			load_overtime_rate_list_dt();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

