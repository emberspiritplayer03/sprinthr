function _editEarning(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Earning';
	var width = 700;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'earnings/ajax_edit_earning', {eid:eid}, function(data) {
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

function _importEarnings(eid,events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Earnings';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'earnings/ajax_import_earning', {eid:eid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});	
		
		$('#import_earnings_form').validationEngine({scroll:false});		
		$('#import_earnings_form').ajaxForm({
			success:function(o) {
				if (o.is_imported) {
					if (typeof events.onImported == "function") {
						events.onImported(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if ($('#earning_file').val() == '') {					
					return false;	
				}
				if (typeof events.onImporting == "function") {
					events.onImporting();
				}				
				return true;
			}
		});					
	});
}


function _withSelectedEarnings(status,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 180
	var title  = 'Notice';	
	if(status == 'earning_approve'){
		var message = '<b>Approve</b> the selected entries?';
	}else if(status == 'earning_archive'){
		var message = '<b>Archive</b> the selected entries?';
	}else if(status == 'earning_disapprove'){
		var message = '<b>Disapprove</b> the selected entries?';
	}else if(status == 'earning_restore'){
		var message = '<b>Restore</b> archived selected entries?';
	}
	
		
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
				$.post(base_url + 'earnings/_with_selected_pending_action',$('#withSelectedAction').serialize(),		
				function(o) { 
					if(o.is_success==1) {	
																							
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

function _processYearlyBonus(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 180
	var title  = 'Notice';	
	var message = 'Process yearly bonus?';
		
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
				$(dialog_id).html(loading_image + " Processing yearly bonus...");
				$(".ui-dialog-buttonset").hide();
				$.post(base_url + 'earnings/_process_yearly_bonus',$('#withSelectedAction').serialize(),			
				function(o) { 
					events.onYes(o);	
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

function _withSelectedYearlyBonus(status,events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 180
	var title  = 'Notice';	
	if(status == 'add_selected'){
		var message = 'Add selected to earnings?';
	}else if(status == 'add_all'){
		var message = 'Add all to earnings?';
	}
	
		
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
				$.post(base_url + 'earnings/_with_selected_earnings_action',$('#withSelectedAction').serialize(),		
				function(o) { 
					if(o.is_success==1) {	
																							
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

function _archiveEarning(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected earning?';
	
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
				$.post(base_url+'earnings/_archive_earning',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {	
							
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

function _restoreArchivedEarning(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>restore</b> the selected archived earning?';
	
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
				$.post(base_url+'earnings/_restore_archived_earning',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {	
							
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

function _approveEarning(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 210
	var title = 'Notice';
	var message = 'Are you sure you want to <b>approve</b> the selected earning?<br><br><b>Note : Approving will add the selected amount to payroll.</b><br>';
	
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
				$.post(base_url+'earnings/_approve_earning',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {
						load_sum_pending_earnings('"' + o.eid + '"');															
						load_earnings_list_dt('"' + o.eid + '"');						
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

function _disApproveEarning(keid,eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>disapprove</b> the selected earning?';
	
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
				$.post(base_url+'earnings/_disapprove_earning',{keid:keid,eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_sum_approved_earnings('"' + o.eid + '"');													
						load_approved_earnings_list_dt('"' + o.eid + '"');
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

