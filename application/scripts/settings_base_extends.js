function addProjectType() {
	_addProjectType({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function(o) {
			console.log(o);
		},
		onSaved: function(o) {
			load_project_site_dt();	
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}




function _addProjectType(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Project Site Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_project_type', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#addProjectType').validationEngine({scroll:false});		
		$('#addProjectType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {

				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function EditProjectSite(id){
	_EditProjectSite(id,{
         onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function(o) {
			console.log(o);
		},
		onSaved: function(o) {
			load_project_site_dt();	
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	})

}

function EditActivity(id){
	_EditActivity(id,{
         onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function(o) {
			console.log(o);
		},
		onSaved: function(o) {
			load_project_site_dt();	
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	})

}

function _EditProjectSite(id,events){
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Project Site Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/project_site_edit_view', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editProjectType').validationEngine({scroll:false});		
		$('#editProjectType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {

				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function _EditActivity(id,events){
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Project Site Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'settings/project_site_edit_view', {id:id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#editProjectType').validationEngine({scroll:false});		
		$('#editProjectType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {

				} 
				
				if (typeof events.onSaved == "function") {
					events.onSaved(o);					
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if (typeof events.onError == "function") {
					events.onSaving();
				}				
				return true;
			}
		});		
	});
}

function DeleteProjectSite(id){
   var dialogBoxLeave = $('#dialog-confirm').dialog();
     dialogBoxLeave.dialog({
        autoOpen: false,
        title: 'Confirmation',
        width: 410,
			  height: 207,				
			  resizable: false,
			  modal:true,
             buttons:{
                'Yes'   : function (){
                          $.ajax({
                             	  url:base_url + 'settings/delete_project_site',
                               method:'GET',
                                 data:{id:id},
                          contentType:'application/json;charset=utf8',
                              success:function(o){
                              	   var x = JSON.parse(o);
                                   if(x.status == true){
                                   	   dialogBoxLeave.dialog('close');
                                   	   load_project_site_dt();	
                                   }else{
                                   	   dialogBoxLeave.dialog('close');
                                   	   load_project_site_dt();	
                                   }
                               }
                           })
                } ,
                'No' : function () {
                      dialogBoxLeave.dialog('close'); 
                }
             }

         });
         dialogBoxLeave.dialog('open'); 
}

 function DeleteActivity(eid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected activity?';
	
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
				
				$.post(base_url+'settings/_delete_activity',{eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_activities_list_dt();			
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



