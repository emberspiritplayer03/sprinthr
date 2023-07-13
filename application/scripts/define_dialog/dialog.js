function blockPopUp() {
		var windowHeight = document.documentElement.clientHeight;
		
		
		$("#backgroundPopup").css({
		"height": windowHeight
		});
		$("#backgroundPopup").css({
			"opacity": "0.2"
		});
		
		$("#backgroundPopup").fadeIn("slow");	
}


function disablePopUp() {
	$("#backgroundPopup").fadeOut("slow");
}

//THIS IS FOR FORM SUBMISSION
//sample dialogYesNoForm('#test','#form_id','hr/appliccant',title,height,width,modal)
function dialogYesNoForm(dialog_id,form_id,yes_url, title,height,width,modal,message) {
	
	var dialog_id = (dialog_id == undefined || dialog_id=='') ? 'content' :  dialog_id;
	var title	  = (title == undefined || title=='') ? 'Message' :  title;
	var height	  = (height == undefined || height=='') ? 170:  height;
	var width	  = (width == undefined || width=='') ? 400:  width;
	var modal	  = (modal == undefined || modal=='') ? true:  false ;
	var yes_url	  = (yes_url == undefined || yes_url=='') ? '':  yes_url ;
	
	var form_id	  = (form_id == undefined || form_id =='') ? '':  form_id ;
	var message   = (message == undefined || message=='') ? '':  message ;
	
	blockPopUp();
	if(message!='') {
		$(dialog_id).html(message);
	}
	blockPopUp();
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: modal,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
					
					'Yes' : function(){
						$dialog.dialog("close");
						disablePopUp();
						$(form_id).ajaxSubmit({
							success:function(o) {
								if(yes_url!='') {
									location.href = base_url + yes_url;
								}
							}
						});	
					},
					'No' : function(){
						$dialog.dialog("close");
						disablePopUp();	
							
					}
				}
			}).show();	

}

// this is NOT FOR FORM DIALOG BOX
// this is for data table
function dialogYesNoBox(dialog_id,yes_url,go_url,title,height,width,modal,message) {
	
	var dialog_id = (dialog_id == undefined) ? 'content' :  dialog_id;
	var title	  = (title == undefined) ? 'Message' :  title;
	var height	  = (height == undefined) ? 170:  height;
	var width	  = (width == undefined) ? 400:  width;
	var modal	  = (modal == undefined) ? true:  false ;
	var yes_url	  = (yes_url == undefined) ? '':  yes_url ;
	var go_url	  = (go_url == undefined) ? '':  go_url ;
	var message   = (message == undefined || message=='') ? '':  message ;
	
	blockPopUp();
	if(message!='') {
		$(dialog_id).html(message);
	}
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: modal,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
							'Yes' : function(){
								$dialog.dialog("close");
								disablePopUp();
								
								$.post(base_url + yes_url,{},function(){});
								if(go_url!='') {
									location.href = base_url + go_url;	
								}
								
								
							},
							'No' : function(){
								$dialog.dialog("close");
								disablePopUp();
								
							}
						}
					}).show();	

}


function dialogOkBox(dialog_id,title,height,width,modal,ok_url,message) {
	
	var dialog_id = (dialog_id == undefined) ? 'content' :  dialog_id;
	var title	  = (title == undefined) ? 'Message' :  title;
	var height	  = (height == undefined) ? 170:  height;
	var width	  = (width == undefined) ? 400:  width;
	var modal	  = (modal == undefined) ? true:  false ;
	var ok_url 	  = (ok_url == undefined) ? '':  ok_url ;
	var message   = (message == undefined || message=='') ? '':  message ;
	
	blockPopUp();
	if(message!='') {
		$(dialog_id).html(message);
	}
	
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: modal,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
							'Ok' : function(){
								$dialog.dialog("close");
								disablePopUp();
								if(ok_url!='') {
									location.href = base_url + ok_url;	
								}
							}
						}
					}).show();	
}

