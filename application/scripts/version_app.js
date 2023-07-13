function loadVersionInfo(version){
	$("#version-info-container").html(loading_image);	
	$.get(base_url + 'settings/_load_version_info',{version:version},function(o) {
		$('#version-info-container').html(o);		
	});
}

function updateDatabase(version){

	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 280
	var title  = 'Notice';	
	var message = 'Proceeding will update SprintHR Database.<br /><br /><p><b>Proceed with update?</b></p><br /><span class="red">Note : No interruption must be made while updating. Make sure that all user(s) are logout to ensure complete update.</span>';
		
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
				showLoadingDialog('Updating database...');
				$.post(base_url + 'settings/update_database',{version:version},		
				function(o) { 
					if( o.is_success ) {				
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#version-wrapper").html(o.message);
						$dialog.dialog("close");			
					}else {
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#err-msg").html(o.message);													
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

	/*$("#version-info-container").html(loading_image);	
	$.get(base_url + 'settings/_load_version_info',{version:version},function(o) {
		$('#version-info-container').html(o);		
	});*/
}

function updateTddDatabase(version){

	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width  = 350 ;
	var height = 280
	var title  = 'Notice';	
	var message = 'Proceeding will update SprintHR Database.<br /><br /><p><b>Proceed with update?</b></p><br /><span class="red">Note : No interruption must be made while updating. Make sure that all user(s) are logout to ensure complete update.</span>';
		
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
				showLoadingDialog('Updating database...');
				$.post(base_url + 'settings/update_tdd_database',{version:version},		
				function(o) { 
					if( o.is_success ) {				
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#version-wrapper").html(o.message);
						$dialog.dialog("close");			
					}else {
						$dialog.dialog("destroy");		
						disablePopUp();	
						$("#err-msg").html(o.message);													
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

	/*$("#version-info-container").html(loading_image);	
	$.get(base_url + 'settings/_load_version_info',{version:version},function(o) {
		$('#version-info-container').html(o);		
	});*/
}