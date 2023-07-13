var function_executing_message = 'Please wait while your existing request is processed.';
var _isExecuting = 0;
var loading_message = '<img src="'+ base_folder + 'themes/' + theme + '/assets/loading.gif"> Loading...';
var process_message = '<img src="'+ base_folder + 'themes/' + theme + '/assets/loading.gif"> Processing...';
var saving_message = '<img src="'+ base_folder + 'themes/' + theme + '/assets/loading.gif"> Saving...';
var loading_image = '<img src="'+ base_folder + 'themes/' + theme + '/assets/loading_fb.gif">';
var $dialog;


function displayPage(options) {
	var canvass = options.canvass || '';
	var parameter = options.parameter || '';
	var animate = options.animate || 'show';
	if(canvass=='' || parameter=='') {alert('Error Page Occured');}
	if (_isExecuting != 0) {//alert(function_executing_message);return;
	}
	_isExecuting += 1;
	
	if(animate=='show') {
		$(canvass).show();	
	}else {
		$(canvass).animate({width:'toggle'},1000);
	}
	
	if($(canvass).html()=='') {
		$(canvass).html(loading_message);
		$(canvass).load(base_url + parameter, {},function(){_isExecuting -= 1;});	 
	}else {	_isExecuting -= 1;}
}


/* Locks screen then if the document is already loaded unlock it. this is put inside template.php */
function lockScreen() {
	blockFullScreen();
	$(document).ready(function() {
		$.unblockUI();
	});
}

function blockFullScreen() {
	var loader_image = base_folder + 'themes/' + theme + "/assets/loader.gif";
	$.blockUI({ message: '<div style="background-color:#FFFFFF; border:1px solid #CCCCCC;"><div style="font:verdana; font-weight:bold; font-size:12px ">Loading...</div><img src="'+ loader_image +'"></div>', overlayCSS: { backgroundColor: '#CCCCCC' }, css: { border: '0px' } });	
}

function blockPopUp() {
	var windowHeight = document.documentElement.clientHeight;	
	$("#backgroundPopup").css({"height": windowHeight});
	$("#backgroundPopup").css({"opacity": "0.2"});
	$("#backgroundPopup").fadeIn("slow");	
}

function disablePopUp() {
	$("#backgroundPopup").fadeOut("slow");
}

//usage dialogGeneric('#test',{width:300});
function dialogGeneric(dialog_id,options) 
{	
	var width  = options.width || 400;
	var height = options.height || 250;
	var resizable = options.resizable || false;
	var modal = options.modal || true;
	var message = options.message || '';
	var title = options.title || 'Message';
	var form_id = options.form_id || '';
		
	blockPopUp();
	if(message!='') {$(dialog_id).html(message)};
	 	var $dialog = $(dialog_id);
		$dialog.dialog({
                title: title,
                width: width,
				height: height,				
				resizable: resizable,
				modal:modal,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();
					if(form_id!='') {
						$(form_id).validationEngine('hide');    
					} 				   
                }       
        }).show();	
}

function closeDialog(dialog_id, form_id)
{
	if(form_id!='') {
		$(form_id).validationEngine('hide');    
	}
	$dialog = $(dialog_id);	
	disablePopUp();
	$dialog.dialog("destroy");
	$dialog.hide();		
}

function closeDialog2(dialog_id,options)
{
	var form_id = options.form_id || '';
	$(form_id).validationEngine('hide');
	$dialog = $(dialog_id);
	disablePopUp();
	$dialog.dialog("destroy");
	$dialog.hide();	  
}


function dialogOkBox(message,options) { //ok_url,title,height,width,modal,dialog_id) {	
	var dialog_id = options.dialog_id || '#' + DIALOG_CONTENT_HANDLER;
	var title	  = options.title || 'Message';
	var height	  = options.height || 'auto';
	var width	  = options.width || 'auto';//390;
	var modal	  = options.modal || true;
	var ok_url 	  = options.ok_url || '';
	var icon 	  = options.icon || 'alert';
	var button 	  = options.button || 'Ok';

	if(icon=='trash') {icon = '<br><div class="confirmation-trash"><div> '}
	else if(icon=='alert') {icon = '<br><div class="confirmation-alert"><div> '}
	else if(icon=='question') {icon = '<br><div class="confirmation-box"><div> '}
	else {icon = ''}
	
	blockPopUp();
	if(message!='') {$(dialog_id).html(icon+message);}
	
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

function subDialogOkBox(message,options) { //ok_url,title,height,width,modal,dialog_id) {	
	var dialog_id 		 = options.dialog_id || '#' + DIALOG_CONTENT_HANDLER;
	var title	  		 = options.title || 'Message';
	var height	  		 = options.height || 'auto';
	var width	  		 = options.width || 'auto';//390;
	var modal	  		 = options.modal || true;
	var ok_url 	       = options.ok_url || '';
	var icon 	  		 = options.icon || 'alert';
	var button_caption = options.button_caption || 'Ok';
	
	if(icon=='trash') {icon = '<br><div class="confirmation-trash"><div> '}
	else if(icon=='alert') {icon = '<br><div class="confirmation-alert"><div> '}
	else if(icon=='question') {icon = '<br><div class="confirmation-box"><div> '}
	else {icon = ''}
	
	blockPopUp();
	if(message!='') {$(dialog_id).html(icon+message);}
	
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
		buttons:[{
				text : button_caption,
				click : function(){
					$dialog.dialog("close");
					disablePopUp();
					if(ok_url!='') {
						location.href = base_url + ok_url;	
					}
				}
			}]
		}).show();	
}



// this is NOT FOR FORM DIALOG BOX
// this is for data table
function dialogYesNoBox(message,yes_process,jump_url,title,height,width,modal,dialog_id) {
	
	var dialog_id = (dialog_id == undefined) ? '#' + DIALOG_CONTENT_HANDLER :  dialog_id;
	var title	  = (title == undefined) ? 'Message' :  title;
	var height	  = (height == undefined) ? 170:  height;
	var width	  = (width == undefined) ? 400:  width;
	var modal	  = (modal == undefined) ? true:  false ;
	var yes_url	  = (yes_process == undefined) ? '':  yes_process ;
	var go_url	  = (jump_url == undefined) ? '':  jump_url ;
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



//THIS IS FOR FORM SUBMISSION
//sample dialogYesNoForm('#test','#form_id','hr/appliccant',title,height,width,modal)
function dialogYesNoForm(form_id,message,yes_url, title,height,width,modal,dialog_id) {

	var dialog_id = (dialog_id == undefined) ? '#' + DIALOG_CONTENT_HANDLER :  dialog_id;
	var title	  = (title == undefined) ? 'Message' :  title;
	var height	  = (height == undefined) ? 170:  height;
	var width	  = (width == undefined) ? 400:  width;
	var modal	  = (modal == undefined) ? true:  false ;
	var yes_url	  = (yes_url == undefined) ? '':  yes_url ;
	
	var form_id	  = (form_id == undefined) ? alert('No Form ID selected'):  form_id ;
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

function showLoadingDialog(message, params) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 200 ;
	var height = 120
	var title = 'Status';
	if (params) {
		width = (params.width) ? params.width : width ;
		height = (params.height) ? params.height : height ;
		title = (params.title) ? params.title : title ;
	}	
		
	blockPopUp();
	$(dialog_id).html('<div align="center" style="margin-top:20px">' + loading_image + ' ' + message + '</center>');
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();	
}

function showYesNoDialog(message, params) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Confirmation';
	if (params) {
		width = (params.width) ? params.width : width ;
		height = (params.height) ? params.height : height ;
		title = (params.title) ? params.title : title ;
	}
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
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
				if (typeof params.onYes == "function") {
					params.onYes();
				}
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof params.onNo == "function") {
					params.onNo();
				}
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

function showOkDialog(message, params) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	if (params) {
		width = (params.width) ? params.width : width ;
		height = (params.height) ? params.height : height ;
		title = (params.title) ? params.title : title ;
	}
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
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
			'Ok' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				if (typeof params.onOk == "function") {
					params.onOk();
				}				
			}			
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();;
}
