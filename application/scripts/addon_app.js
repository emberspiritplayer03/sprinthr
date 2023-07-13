function loadAddOnInfo(addon){
	$("#addon-info-container").html(loading_image);	
	$.get(base_url + 'settings/_load_addon_info',{addon:addon},function(o) {
		$('#addon-info-container').html(o);		
	});
}

function activateAddon(addon){

	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 280
	var title  = 'Notice';	
	var message = 'Proceeding will update SprintHR Database and will activate the selected addon.<br /><br /><p><b>Proceed with update?</b></p><br /><span class="red">Note : No interruption must be made while updating. Make sure that all user(s) are logout to ensure complete update.</span>';
		
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
				showLoadingDialog('Activating addon...');
				$.post(base_url + 'settings/activate_addon',{addon:addon},		
				function(o) { 
					if( o.is_success ) {				
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#addon-wrapper").html(o.message);
						$dialog.dialog("close");			
					}else {						
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#addon-err-msg").html(o.message);													
					}						
					
				},"json");				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();				
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();

}

function deactivateAddOn(addon){

	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 280
	var title  = 'Notice';	
	var message = 'Proceeding will deactivate / remove the selected addon to your application.<br /><br /><p><b>Proceed with deactivation?</b></p><br /><span class="red">Note : No interruption must be made while deactivating. Make sure that all user(s) are logout to ensure complete update.</span>';
		
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
				showLoadingDialog('Deactivating addon...');
				$.post(base_url + 'settings/deactivate_addon',{addon:addon},		
				function(o) { 
					if( o.is_success ) {				
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#addon-wrapper").html(o.message);
						$dialog.dialog("close");			
					}else {						
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#addon-err-msg").html(o.message);													
					}						
					
				},"json");				
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();				
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();

}