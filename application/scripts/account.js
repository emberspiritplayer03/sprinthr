function cancelRegistration(eid,hid) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width   = 350 ;
	var height  = 180
	var title   = 'Notice';	
	var message = 'Are you sure you want to cancel your registration?';
	
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
				$.post(base_url + 'account/_cancel_registration',{eid:eid,hid:hid},		
				function(o) { 
					if(o.is_success==1) {
						window.location.href = o.url;
					}else {
						dialogOkBox(o.message,{});	
						$dialog.dialog("close");								
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

function resendEmail(eid,hid) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width   = 350 ;
	var height  = 180
	var title   = 'Notice';	
	var message = 'Resend confirmation link?';
	
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
				$.post(base_url + 'account/_resend_confirmation',{eid:eid,hid:hid},		
				function(o) { 
					if(o.is_success==1) {
						window.location.href = o.url;
					}else {
						dialogOkBox(o.message,{});	
						$dialog.dialog("close");								
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