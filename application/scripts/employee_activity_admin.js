function load_employee_activities_list_dt() {
	$("#employee_activities_list_dt_wrapper").html(loading_image);	
	$.post(base_url + 'activity/_load_employee_activities_list_dt',{},function(o) {
		$('#employee_activities_list_dt_wrapper').html(o);		
	});	
}

function show_add_employee_activity_form() {
	$('#add_activity_button').hide();
	$('#add_activity_form_wrapper').show();
	$("#activity_wrapper").html(loading_image);	
	$.post(base_url+'activity/ajax_add_new_employee_activity',{},
	function(o){
		$("#activity_wrapper").html(o);	
	});	
	
}

function hide_add_activity_form() {	
	$('#add_activity_button').show();
	$('#add_activity_form_wrapper').hide();
	$("#activity_wrapper").html("");	
	clearFormError($("form").attr("id"));	
}

function addEmployeeActivityActionScripts() {
	$("#category_id").change(function(){
		var did = $(this).val();
		
	    if(did == "add") {
	      	checkForAddCategory();
	    }
	});

	$("#activity_id").change(function(){
		var did = $(this).val();
		
	    if(did == "add") {
	      	checkForAddActivity();
	    }
	});

}

function checkForAddCategory()
{
	clearFormError();
	
	category_id = $("#category_id").val();	

	if(category_id=='add') {
			load_category_dropdown();
			$.post(base_url+"activity/_load_add_category_form",{},
				function(o){
					$("#category_wrapper_form").html(o);
					dialogGeneric("#category_wrapper_form",{height:'auto', width:330});		
				});
	}
}

function load_category_dropdown()
{
	$("#category_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'activity/_load_category_dropdown',{},
	function(o){
		$("#category_dropdown_wrapper").html(o);
	});
}

function checkForAddActivity()
{
	clearFormError();
	
	activity_id = $("#activity_id").val();	

	if(activity_id=='add') {
			load_activity_dropdown();
			$.post(base_url+"activity/_load_add_activity_form",{},
				function(o){
					$("#activity_wrapper_form").html(o);
					dialogGeneric("#activity_wrapper_form",{height:'auto', width:330});		
				});
	}
}

function load_activity_dropdown()
{
	$("#activity_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'activity/_load_activity_dropdown',{},
	function(o){
		$("#activity_dropdown_wrapper").html(o);
	});
}

function clearFormError(form_id) {
	$("#" + form_id).validationEngine('hide'); 	
}

function importEmployeeActivities() {
	_importEmployeeActivities({
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			load_employee_activities_list_dt();
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});			
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();

			showOkDialog('<br><div class="confirmation-alert"><div>' + o.message, {
				height: 'auto',
				onOk: function(o) {
					importEmployeeActivities();
				}
			});
		}
	});
}


//generate activity
function generateEmployeeActivities() {
	_generateEmployeeActivities({
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
			load_employee_activities_list_dt();
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Generating...</center></div>', title:'Status'});			
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();

			showOkDialog('<br><div class="confirmation-alert"><div>' + o.message, {
				height: 'auto',
				onOk: function(o) {
					generateEmployeeActivities();
				}
			});
		}
	});
}



function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? '' : decodeURIComponent(sParameterName[1]);
        }
	}
	
	return '';
};

function deleteEmployeeActivity(eid) {
	_deleteEmployeeActivity(eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


//delete employee activity log
function deleteEmployeeActivityLog(eid) {
	_deleteEmployeeActivity(eid, {
		onYes: function(o) {
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}





function editEmployeeActivity(eid) {
	_editEmployeeActivity(eid, {
		onSaved: function(o) {		
			load_activities_list_dt();				
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}



function changePayPeriodByYear(selected_year,class_container,selected_frequency = 0)
{
	$("." + class_container).html(loading_image);
	$.get(base_url + 'reports/ajax_load_payroll_period_by_year',{selected_year:selected_year,selected_frequency:selected_frequency},
		function(o){
			$("." + class_container).html(o);			
		}
	);
}