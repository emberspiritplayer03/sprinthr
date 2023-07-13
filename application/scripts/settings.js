//company info
function chkUnchk()
{
	var check_uncheck = document.withSelectedAction.elements['check_uncheck'];
	if(check_uncheck.checked == 1) {	
		$('#check_uncheck').attr('title', 'Uncheck All');									
		$("#chkAction").removeAttr('disabled');
		var status = 1; 
	} else { 
		$('#check_uncheck').attr('title', 'Check All');									
		$("#chkAction").attr('disabled',true);
		var status = 0;
	}
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

function enableDisAbleObject(obj_id,chk_id)
{		
	if(document.getElementById(chk_id).checked) {			
		$("#" + obj_id).attr('disabled',true);	
	} else { 
		$("#" + obj_id).removeAttr('disabled');
	}
}

function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function load_company_info() {
	$('#c-info').html(loading_image);	
	$.post(base_url + 'settings/_load_company_info',{},
		function(o){			
			$('#c-info').html(o);		
		});		
}

function load_sss_table() {
	$("#sss_table").html(loading_image);	
	$.get(base_url + 'settings/_load_sss_table',{},function(o) {
		$('#sss_table').html(o);		
	});	
}

function load_payroll_settings() {
	$("#payroll_settings_datatable").html(loading_image);	
	$.get(base_url + 'settings/_load_payroll_settings',{},function(o) {
		$('#payroll_settings_datatable').html(o);		
	});	
}

function load_notification_settings() {
	$("#payroll_notifications_datatable").html(loading_image);	
	$.get(base_url + 'settings/_load_notification_settings',{},function(o) {
		$('#payroll_notifications_datatable').html(o);		
	});	
}


function load_payroll_period_list_selected_year(selected_year) {	
	$.post(base_url + 'settings/_load_payroll_period_list_selected_year',{selected_year:selected_year},function(o) {
		$('#payroll_period_selected_year').html(o);		
	});	
}

function load_philhealth_table() {
	$("#philhealth_table").html(loading_image);	
	$.get(base_url + 'settings/_load_philhealth_table',{},function(o) {
		$('#philhealth_table').html(o);		
	});	
}

function load_pagibig_table() {
	$("#pagibig_table").html(loading_image);	
	$.get(base_url + 'settings/_load_pagibig_table',{},function(o) {
		$('#pagibig_table').html(o);		
	});	
}

function load_tax_table() {
	$("#tax_table").html(loading_image);	
	$.get(base_url + 'settings/_load_tax_table',{},function(o) {
		$('#tax_table').html(o);		
	});	
}

function load_payroll_period_list_dt(selected_year) {	

	$(".tipsy").remove();
	$.post(base_url + 'settings/_load_payroll_period_list_dt',{selected_year:selected_year},function(o) {
		$('#payroll_period_list_dt_wrapper').html(o);		
	});	
}

function load_payroll_year() {	
	$.get(base_url + 'settings/_load_payroll_year_list_dt',{},function(o) {
		$('#payroll_period_list_dt_wrapper').html(o);		
	});	
}

function load_company_structure() {
	$('#c-structure').html(loading_image);	
	$.get(base_url + 'settings/_load_company_structure',{},
		function(o){				
			$('#c-structure').html(o);		
		});		
}

function load_department_teams_groups(eid) {
	$(".tipsy").remove();
	$('#c-structure').html(loading_image);	
	$.post(base_url + 'settings/_load_department_teams_groups',{eid:eid},
		function(o){				
			$('#c-structure').html(o);		
		});		
}

function load_sub_teams_groups(eid,branch_id) {
	$(".tipsy").remove();
	$('#c-structure').html(loading_image);	
	$.post(base_url + 'settings/_load_sub_teams_groups',{eid:eid,branch_id:branch_id},
		function(o){				
			$('#c-structure').html(o);		
		});		
}

function load_branch_departments(eid) {	
	$(".tipsy").remove();
	$('#c-structure').html(loading_image);	
	$.post(base_url + 'settings/_load_branch_departments',{eid:eid},
		function(o){				
			$('#c-structure').html(o);		
		});				
}

function load_edit_company_info() {
	$("#action_form").html(loading_image);
	$.post(base_url + 'settings/_load_edit_company_info',{},function(o) {
		$("#action_form").html(o);							 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Company Information',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: false,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
					
		}).dialogExtend({
        "maximize" : false,
		"minimize"  : true,
        "dblclick" : "maximize",
        "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
      }).show();			
	

}

function load_add_branch(parent_id) {
	blockPopUp(); 
	$("#sub_action_form").html(loading_image);
	$.post(base_url + 'settings/_load_add_branch',{parent_id:parent_id},function(o) {
		$("#sub_action_form").html(o);								 
	})
	
	var $dialog = $('#sub_action_form');
	$dialog.dialog("destroy");
	
	
	var $dialog = $('#sub_action_form');
	$dialog.dialog({
		title: 'Add Company Branch',
		resizable: false,
		position: [480,70],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();			
				}	
					
		}).dialogExtend({
        "maximize" : false,
		"minimize"  : false       
      }).show();			
	

}

function load_add_structure(parent_id) {	
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_structure',{parent_id:parent_id},function(o) {
		$("#action_form").html(o);						 
	})	
	var $dialog = $('#action_form');
    $dialog.dialog("destroy");	
	var $dialog = $('#action_form');	
	$dialog.dialog({
		title: 'Add Company Structure',
		resizable: false,
		position: [480,120],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}).dialogExtend({
        "maximize" : false,
		"minimize"  : false   
      }).show();			
	

}

function load_delete_branch(branch_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_branch_confirmation',{branch_id:branch_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_branch',{branch_id:branch_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_branch_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this branch because it has active members");							
							}else {
								load_branch_list_dt();	
								//alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function load_delete_branch_in_structure(branch_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_branch_confirmation',{branch_id:branch_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_branch',{branch_id:branch_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_company_structure();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this branch because it has active members");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();	
}

function load_delete_structure(structure_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_confirmation',{structure_id:structure_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_structure',{structure_id:structure_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_company_structure();	
								disablePopUp();
							}else if(o==2){
								load_confirmation("You cannot remove this group because it has active members");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						},"json");		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}


function load_confirmation(msg) {
	var $dialog = $('#confirmation');
	$dialog.dialog("close");
	
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_confirmation',{msg:msg},function(o) {
		$("#confirmation").html(o);							 
	})
	blockPopUp();
	var $dialog = $('#confirmation');

	$dialog.dialog({
		title: 'Confirmation',
		resizable: false,		
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
   			       disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Close' : function(){	
					 disablePopUp();					
					 $dialog.dialog("destroy");
                   	 $dialog.hide();	   
                    }
                }
            }).show();
}


//end of company info

//branch list

function load_branch_list_dt(){
	var el = 'branch_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_branch(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_branch(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"id",label:"ID", width:20,resizeable:true, sortable:true},
						{key:"name", label:"Branch Name", width:202, resizeable:true, sortable:true},
						/*{key:"province", label:"Province", width:110, resizeable:true, sortable:true},
						{key:"city", label:"City", width:80, resizeable:true, sortable:true},
						{key:"address", label:"Address", width:80, resizeable:true, sortable:true},
						{key:"zip_code", label:"Zip Code", width:80, resizeable:true, sortable:true},*/
						{key:"phone", label:"Phone", width:80, resizeable:true, sortable:true},
						{key:"fax", label:"Fax", width:80, resizeable:true, sortable:true}
						
						];		
		var fields =	['id','name','province','city','address','zip_code','phone','fax'];
		var height = 	'auto';
		var width = 	'100%';
		
		var controller = 'settings/_load_branch_dt?';		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
}

function load_add_new_branch() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_branch',{},function(o) {
		$("#action_form").html(o);							 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Branch',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();				   
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_edit_branch(branch_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_branch',{branch_id:branch_id},function(o) {
		$("#action_form").html(o);						 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Branch',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
  				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}


//end of branch list

//job 
function load_job_list_dt(){
	var el = 'job_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_title(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_title(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"title", label:"Job Title", width:180, resizeable:true, sortable:true},
						{key:"name", label:"Job Specification", width:150, resizeable:true, sortable:true},
						{key:"description", label:"Specification Description", width:180, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','job_specification_id','title','is_active','name','description'];
		var height = 	'auto';
		var width = 	'100%';
		
		//alert(fields)
		//if(searched==undefined)
//		{	
			var controller = 'settings/_load_job_dt?';		
		//}else
//		{
//			var controller = 'settings/_load_job_dt?search='+ searched + '&fieldname='+field_name +'&' ;
//		}
		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
	

}

function load_job_specification_list_dt(){
	var el = 'job_specification_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_specification(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_specification(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"name", label:"Job Specification", width:150, resizeable:true, sortable:true},
						{key:"description", label:"Job Description", width:150, resizeable:true, sortable:true},
						{key:"duties", label:"Duties", width:150, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','name','description','duties'];
		var height = 	'auto';
		var width = 	'100%';
		
		//alert(fields)
		//if(searched==undefined)
//		{	
			var controller = 'settings/_load_job_specification_dt?';		
		//}else
//		{
//			var controller = 'settings/_load_job_dt?search='+ searched + '&fieldname='+field_name +'&' ;
//		}
		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
	

}

function load_job_employment_status_list_dt(){
	var el = 'job_employment_status_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id     = oRecord.getData("id");
		var job_id = oRecord.getData("job_id");
		
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_employment_status(" + job_id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_employment_status(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"title", label:"Job Title", width:220, resizeable:true, sortable:true},
						{key:"employment_status", label:"Status", width:200, resizeable:true, sortable:true}						
						];		
		var fields =	['id','job_id','company_structure_id','title','status','employment_status'];
		var height = 	'auto';
		var width = 	'100%';
		
		//alert(fields)
		//if(searched==undefined)
//		{	
			var controller = 'settings/_load_job_employment_status_dt?';		
		//}else
//		{
//			var controller = 'settings/_load_job_dt?search='+ searched + '&fieldname='+field_name +'&' ;
//		}
		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
	

}

function load_user_management_dt(){
	var el = 'user_management_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");		
		elCell.innerHTML = "<a href='javascript:void(0);' onclick=\"javascript:editUser('" + id + "');\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick=\"javascript:deleteUser('" + id + "');\" style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[
			    		{key:"", label:"", width:35, resizeable:true, sortable:false, formatter:action },						
						{key:"employee_name", label:"User", width:150, resizeable:true, sortable:true},
						{key:"position", label:"Position", width:150, resizeable:true, sortable:true},
						{key:"username", label:"Username", width:80, resizeable:true, sortable:true},
						{key:"role_name", label:"Role", width:80, resizeable:true, sortable:true}									
						];		
		var fields =	['id','employee_name','position','username','role_name'];
		var height = 	'auto';
		var width = 	'100%';
		

			var controller = 'settings/_load_user_management_dt?';

		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
	

}

function load_roles_dt(){
	var el = 'roles_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");		
		if( id == 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg' ){
			elCell.innerHTML = "<a href='javascript:void(0);' onclick=\"javascript:editRole('" + id + "');\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a>";
		}else{
			elCell.innerHTML = "<a href='javascript:void(0);' onclick=\"javascript:editRole('" + id + "');\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick=\"javascript:deleteRole('" + id + "');\" style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
		}
	};
			
		var columns = 	[
			    		{key:"", label:"", width:50, resizeable:true, sortable:false, formatter:action },						
						{key:"name", label:"Role Name", width:100, resizeable:true, sortable:true},
						{key:"description", label:"Role Description", width:100, resizeable:true, sortable:true}						
						];		
		var fields =	['id','name','description'];
		var height = 	'auto';
		var width = 	'100%';
		
			var controller = 'settings/_load_roles_dt?';
		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
}

function load_breaktime_schedules_dt(){
	var el = 'breaktime_schedules_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 				
		var id  = oRecord.getData("id");		
		var eid = oRecord.getData("eid");			
		/*if( id == 1 ){
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='editBreakTimeSchedule(this)' data-index=\"" + eid + "\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a>";
		}else{
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='editBreakTimeSchedule(this)' data-index=\"" + eid + "\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='deleteBreakTimeSchedule(this)' data-index=\"" + eid + "\" style='display:inline-block;' class='btn-delete-breaktime-schedule'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
		}*/
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='editBreakTimeSchedule(this)' data-index=\"" + eid + "\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='deleteBreakTimeSchedule(this)' data-index=\"" + eid + "\" style='display:inline-block;' class='btn-delete-breaktime-schedule'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
	var columns = 	[
		    		{key:"", label:"", width:40, resizeable:true, sortable:false, formatter:action },						
					{key:"schedule", label:"Schedule", width:120, resizeable:true, sortable:true},
					{key:"break_time_schedules", label:"Break Time", width:190, resizeable:true, sortable:true},						
					{key:"applied_to", label:"Applied to", width:100, resizeable:true, sortable:true},
					{key:"starts_on", label:"Starts On", width:100, resizeable:true, sortable:true}
					];		

	var fields =	['id','eid','schedule','break_time_schedules','applied_to','starts_on'];
	var height = 	'auto';
	var width = 	'100%';		

	var controller = 'settings/_load_breaktime_schedules_dt?';
	var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
	datatableDT.rowPerPage(15);
	datatableDT.pageLinkLabel('First','Previous','Next','Last');		
	datatableDT.show();
}

function load_employees_enrolled_to_benefit(eid){	
	var el = 'employees-enrolled-to-benefit-dt';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id     = oRecord.getData("id");
		var job_id = oRecord.getData("job_id");
		
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:removeEnrollee(\"" + id + "\");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[
							{key:"action", label:"", width:15, resizeable:true, sortable:false, formatter:action },
							{key:"description", label:"Applied to", width:220, resizeable:true, sortable:true},
							{key:"criteria", label:"Criteria 1", width:220, resizeable:true, sortable:true},
							{key:"custom_criteria", label:"Criteria 2", width:220, resizeable:true, sortable:true}									
						];		
		var fields =	['id','description','criteria', 'custom_criteria'];
		var height = 	'auto';
		var width = 	'100%';
	
		var controller  = 'settings/_load_employees_enrolled_to_benefit_dt?eid=' + eid + '&';		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
}

	const load_employees_exclude_to_benefit = async (eid) => {	
		await $.get(base_url + 'settings/_load_employees_exclude_to_benefit_dt',{eid},
        (data)=>{
          var employees = data
          if(employees!==null){
            if(employees.length > 0){
              let output = "";
              employees.forEach(employee=>{
                output += "<tr>"+
				"<td>"+employee.employee_code+"</td>"+
				"<td>"+employee.name+"</td>"+
				"</tr>";
              })
              $('#employee-container').html(output);
            }
          }else{
            $('#employee-container').html("No Data Found");
          }
          
        }
      );

	  

	  return true;
	}

function editRole(eid){
	hideUserRoleContainer();
	$("#user-role-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_edit_role_form',{eid:eid},function(o) {
		$('#user-role-form-container').html(o);		
	});	
}

function editBreakTimeSchedule(obj){		
	showEditBreaktimeSchedule(eid);	
	var eid = $(obj).attr("data-index");	
	$.get(base_url + 'settings/_load_edit_breaktime_schedule_form',{eid:eid},function(o) {
		$('.breaktime-schedule-form-container').html(o);		
	});	
}

function editBenefit(eid){
	hideBenefitsContainer();
	$("#benefits-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_edit_benefit_form',{eid:eid},function(o) {
		$('#benefits-form-container').html(o);		
	});	
}

function enrollEmployee(eid){
	hideBenefitsContainer();
	$("#benefits-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_enroll_employee_form',{eid:eid},function(o) {
		$('#benefits-form-container').html(o);		
	});	
}

function employeesEnrolledToBenefit(eid){
	hideBenefitsContainer();
	$("#benefits-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_employees_enrolled_to_benefit',{eid:eid},function(o) {
		$('#benefits-form-container').html(o);		
	});	
}

function editUser(eid){
	hideUserRoleContainer();
	$("#user-role-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_edit_user_form',{eid:eid},function(o) {
		$('#user-role-form-container').html(o);		
	});	
}


function deleteBreakTimeSchedule(object){
	var dataIndex = $(object).attr("data-index");
	_deleteBreakTimeSchedule(dataIndex, {
		onYes: function(o) {
			if(o.is_success) {								
				load_breaktime_schedules_dt();						
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function load_delete_job_employment_status(job_employment_status_id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_job_employment_status_confirmation',{job_employment_status_id:job_employment_status_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_job_employment_status',{job_employment_status_id:job_employment_status_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_job_employment_status_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();	
}



function load_job_eeo_job_list_dt(){
	var el = 'job_eeo_job_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_eeo_job_category(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_eeo_category(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"category_name", label:"Job Specification", width:350, resizeable:true, sortable:true},
						{key:"description", label:"Description", width:250, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','category_name','description'];
		var height = 	'auto';
		var width = 	'100%';
		
		//alert(fields)
		//if(searched==undefined)
//		{	
			var controller = 'settings/_load_eeo_job_list_dt?';		
		//}else
//		{
//			var controller = 'settings/_load_job_dt?search='+ searched + '&fieldname='+field_name +'&' ;
//		}
		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
	

}


function load_job_salary_rate_list_dt(){
	var el = 'job_salary_rate_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_salary_rate(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_salary_rate(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"job_level", label:"Job Level", width:210, resizeable:true, sortable:true},
						{key:"minimum_salary", label:"Minimum Salary", width:100, resizeable:true, sortable:true},
						{key:"maximum_salary", label:"Maximum Salary", width:100, resizeable:true, sortable:true},
						{key:"step_salary", label:"Step Salary", width:100, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','job_level','minimum_salary','maximum_salary','step_salary'];
		var height = 	'auto';
		var width = 	'100%';
		
		//alert(fields)
		//if(searched==undefined)
//		{	
			var controller = 'settings/_load_job_rate_list_dt?';		
		//}else
//		{
//			var controller = 'settings/_load_job_dt?search='+ searched + '&fieldname='+field_name +'&' ;
//		}
		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
	

}


function load_add_job_title() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/job_title',{},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add Job Title',
		resizable: false,
		position: [400,100],
		width: 500,
		//height: 250,
		modal: false,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
					
		}		
		).show();	
}


function load_edit_job_title(id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	var id = id;
	$.post(base_url + 'settings/edit_job_title',{id:id},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Job Title',
		resizable: false,
		position: [400,100],
		width: 500,
		//height: 250,
		modal: false,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
		}		
		).show();			
	

}

function load_delete_job_title(job_title_id) {
	blockPopUp();
	$("#delete_confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_job_title_confirmation',{job_title_id:job_title_id},function(o) {		
		$("#delete_confirmation").html(o);						 
	})
	 var $dialog = $("#delete_confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_job_title',{job_title_id:job_title_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_job_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function load_add_job_specification() {
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/job_specification',{},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add Job Specification',
		resizable: false,
		position: [420,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				}	
					
		}		
		).show();	
}

function load_edit_job_specification(id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	var id = id;
	$.post(base_url + 'settings/edit_job_specification',{id:id},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add Job Specification',
		resizable: false,
		position: [420,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					 		
				}	
					
		}		
		).show();		
}

function load_delete_job_specification(job_specification_id) {
	blockPopUp();
	$("#delete_confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_job_specification_confirmation',{job_specification_id:job_specification_id},function(o) {
		$("#delete_confirmation").html(o);								 
	})
	 var $dialog = $("#delete_confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_job_specification',{job_specification_id:job_specification_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_job_specification_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function load_add_job_employment_status() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_job_employment_status',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
    $dialog.dialog("destroy");	
	var $dialog = $('#action_form');	
	$dialog.dialog({
		title: 'Add Job Employment Status',
		resizable: false,
		position: [480,120],
		width: 480,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).dialogExtend({
        "maximize" : false,
		"minimize"  : false       
      }).show();			
}

function load_edit_job_employment_status(sid) {
	blockPopUp();

	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/edit_job_employment_status',{sid:sid},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Job Employment Status',
		resizable: false,
		position: [400,20],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   		
				}	
					
		}		
		).show();			
}


function load_add_eeo_job_category_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/eeo_job_category',{},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add EEO Job Category',
		resizable: false,
		position: [400,100],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				}	
		}		
		).show();			
}

function load_edit_eeo_job_category_old(id) {
	blockPopUp();
	$("#action_form").html('Loading...');	
	$.post(base_url + 'settings/edit_eeo_job_category',{id:id},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit EEO Job Category',
		resizable: false,
		position: [400,100],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
			 	   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   		
				}	
					
		}		
		).show();		
}

function load_delete_job_eeo_category(job_eeo_category_id) {
	blockPopUp();
	$("#delete_confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_job_eeo_category',{job_eeo_category_id:job_eeo_category_id},function(o) {		
		$("#delete_confirmation").html(o);						 
	})
	 var $dialog = $("#delete_confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_job_eeo_category',{job_eeo_category_id:job_eeo_category_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_job_eeo_job_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}


function load_add_job_salary_rate_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/job_salary_rate',{},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add Job Salary Rate',
		resizable: false,
		position: [400,20],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					 
				}						
		}		
		).show();			
}

function load_edit_job_salary_rate_old(id) {
	blockPopUp();
	$("#action_form").html('Loading...');	
	$.post(base_url + 'settings/edit_job_salary_rate',{id:id},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add Job Salary Rate',
		resizable: false,
		position: [400,20],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				}						
		}		
		).show();			
}

function load_delete_job_salary_rate(salary_rate_id) {
	blockPopUp();
	$("#delete_confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_job_salary_rate',{salary_rate_id:salary_rate_id},function(o) {		
		$("#delete_confirmation").html(o);						 
	})
	 var $dialog = $("#delete_confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_job_salary_rate',{salary_rate_id:salary_rate_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_job_salary_rate_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

// subdivision type

function load_subdivision_type_dt() {
	var el = 'subdivision_type_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");					
				elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_subdivision(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_subdivision(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
			};
							
		var columns = 	[
						 {key:"action",label:"Action",width:40,resizeable:true,sortable:true, formatter:action },
						 {key:"type",label:"Type",width:400,resizeable:true,sortable:true}
						];
						
		var fields =	['id','company_structure_id','type','action'];
		var height = 	'auto';
		var width = 	'100%';

		var controller = 'settings/_load_subdivision_type_dt?';	
		
		var subdivision_type = new createDataTable(el,controller, columns, fields, height, width);
		subdivision_type.rowPerPage(7);
		subdivision_type.pageLinkLabel('First','Previous','Next','Last');
		subdivision_type.show();			
}


function load_add_new_subdivision_type() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_subdivision_type',{},function(o) {
		$("#action_form").html(o);							 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Subdivision',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_edit_subdivision(subdivision_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_subdivision',{subdivision_id:subdivision_id},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Subdivision',
		resizable: false,
		position: [480,100],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}


function load_delete_subdivision(subdivision_id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_subdivision_confirmation',{subdivision_id:subdivision_id},function(o) {
		$("#confirmation").html(o);							 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_subdivision',{subdivision_id:subdivision_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_subdivision_type_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}


// end subdivision

// dependent relationship

function load_dependent_relationship_dt() {
	var el = 'dependent_relationship_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_dependent(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_dependent(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:true, formatter:action },
					{key:"relationship", label:"Relationship", width:400, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'relationship', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_dependent_relationship_dt?';
	
		var dependent_relationship = new createDataTable(el,controller, columns, fields, height, width);
		dependent_relationship.rowPerPage(7);
		dependent_relationship.pageLinkLabel('First','Previous','Next','Last');
		dependent_relationship.show();	
}

function load_add_new_relationship_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_relationship',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Dependent Relationship',
		resizable: false,
		position: [480,100],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_edit_dependent_old(dependent_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_dependent',{dependent_id:dependent_id},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Dependent Relationship',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				}	
					
		}		
		).show();			
}


function load_delete_dependent(dependent_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_dependent_confirmation',{dependent_id:dependent_id},function(o) {
		$("#confirmation").html(o);							 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_dependent',{dependent_id:dependent_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_dependent_relationship_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

//end dependent

//request approvers
function load_request_approvers_dt() {	
	var el = 'request_approvers_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		/*if(id == 1) {
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_pay_period(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a>";
		}else{
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_pay_period(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_pay_period(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";			
		}	*/	
		elCell.innerHTML = "<a href='javascript:void(0);' onclick=\"javascript:showEditRequestApprovers('" + id + "');\" style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick=\"deleteRequestApprovers('" + id + "');\"' class='btn-delete-approvers' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";			
	};

	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"title", label:"Request Title", width:110, resizeable:true, sortable:true},
					{key:"approvers_name", label:"Approvers", width:110, resizeable:true, sortable:true},
					{key:"requestors_name", label:"Requestors", width:80, resizeable:true, sortable:true}					
				  ];						
	
		var fields = ['id', 'title', 'approvers_name', 'requestors_name'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_request_approvers_dt?';
	
		var request_approvers = new createDataTable(el,controller, columns, fields, height, width);
		request_approvers.rowPerPage(7);
		request_approvers.show();
		request_approvers.pageLinkLabel('First','Previous','Next','Last');
		request_approvers.show();
}

// IP Management

function load_ip_address_list_dt() {
	$("#ip_address_list_dt").html(loading_image);	
	$.get(base_url + 'settings/_load_ip_address_list',{},function(o) {
		$("#ip_address_list_dt").html(o).hide();	
		$("#ip_address_list_dt").fadeIn(1000);
	});	
}

function showAddIpAddress(){
	$(".data-table-container").hide();
	$(".ip-address-form-container").show();
	$('.ip-address-form-container').html(loading_image);

	$.get(base_url + 'settings/_load_add_ip_address_form',{},function(o) {
		$('.ip-address-form-container').html(o);		
	});	
		
}

//pay period

function load_pay_period_dt() {
	var el = 'pay_period_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		if(id == 1) {
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_pay_period(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a>";
		}else{
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_pay_period(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_pay_period(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";			
		}
		
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"pay_period_code", label:"Pay Period Code", width:110, resizeable:true, sortable:true},
					{key:"pay_period_name", label:"Pay Period Name", width:110, resizeable:true, sortable:true},
					{key:"cut_off", label:"Cut Off", width:80, resizeable:true, sortable:true},
					{key:"is_default", label:"Is Default", width:80, resizeable:true, sortable:true}
				  ];						
	
		var fields = ['id', 'company_structure_id', 'pay_period_code', 'pay_period_name', 'cut_off', 'is_default', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_pay_period_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
	
}

function load_add_new_pay_period() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_pay_period',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Pay Period',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $("#addPayPeriod").validationEngine("hide");
				   //$dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
	
}

function load_edit_pay_period(pay_period_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_pay_period',{pay_period_id:pay_period_id},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Pay Period',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   //$dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   $("#editPayPeriod").validationEngine("hide");	
				   disablePopUp();				    
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_delete_pay_period(pay_period_id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_pay_period_confirmation',{pay_period_id:pay_period_id},function(o) {
		$("#confirmation").html(o);							 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_pay_period',{pay_period_id:pay_period_id}, // delete_subdivision
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_pay_period_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

//end pay period

//skill management

function load_skill_management_dt() {
	var el = 'skill_management_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_skill_management(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_skill_management(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"skill", label:"Skill", width:400, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'skill', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_skill_management_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();	
	
}

function load_add_new_skill() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_skill',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Skill',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
	
}

function load_edit_skill_management(skill_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_skill_management',{skill_id:skill_id},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Skill',
		resizable: false,
		position: [480,100],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_delete_skill_management(skill_id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_skill_confirmation',{skill_id:skill_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_skill',{skill_id:skill_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_skill_management_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}									   
						});		
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();		
}

//end skill management

//license

function load_license_dt() {
	var el = 'license_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");		
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_license(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_license(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"license_type", label:"License Type", width:200, resizeable:true, sortable:true},
					{key:"description", label:"Description", width:300, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'license_type', 'description', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_license_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();

}

function load_benefits_dt() {
	var el = 'benefits_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");		
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:editBenefit(\"" + id + "\");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:enrollEmployee(\"" + id + "\");' style='display:inline-block;'><label title='Enroll Employee' id='enroll' class='ui-icon ui-icon-plus' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:employeesEnrolledToBenefit(\"" + id + "\");' style='display:inline-block;'><label title='List of employees enrolled in benefit' id='enrolled_to_benefit' class='ui-icon ui-icon-person' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:deleteBenefit(\"" + id + "\");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:70, resizeable:true, sortable:false, formatter:action},				
					{key:"code", label:"Code", width:50, resizeable:true, sortable:true},
					{key:"name", label:"Name", width:140, resizeable:true, sortable:true},					
					{key:"cutoff", label:"Given every", width:80, resizeable:true, sortable:true},	
					{key:"amount", label:"Amount", width:25, resizeable:true, sortable:true},										
					{key:"is_taxable", label:"Is Taxable", width:25, resizeable:true, sortable:true}					
				  ];						
	
		var fields = ['id', 'code', 'name', 'amount', 'cutoff', 'is_taxable', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_benefits_dt?';
	
		var benefits = new createDataTable(el,controller, columns, fields, height, width);
		benefits.rowPerPage(7);
		benefits.pageLinkLabel('First','Previous','Next','Last');
		benefits.show();
}

function load_add_new_license_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_license',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New License',
		resizable: false,
		position: [480,100],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));					   
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_add_new_license() {
	_addNewLicense({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_license_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}


function load_edit_license_old(license_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_license',{license_id:license_id},function(o) {
		$("#action_form").html(o);							 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit License',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();				    
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_edit_license(license_id) {
	_editLicense(license_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_license_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}


function load_delete_license(license_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_license_confirmation',{license_id:license_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_license',{license_id:license_id},
						    function(o){																				
							if(o==1) {
								$dialog.dialog("close");								
								load_license_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}
// end license

//location

function load_location_dt() {
	var el = 'location_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_location(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_location(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"code", label:"Code", width:100, resizeable:true, sortable:true},
					{key:"location", label:"Location", width:150, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'code', 'location', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_location_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
}


function load_add_new_location_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_location',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Location',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}


function load_edit_location_old(location_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_location',{location_id:location_id},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Location',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_delete_location(location_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_location_confirmation',{location_id:location_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_location',{location_id:location_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_location_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

//membership

function load_membership_type_dt() {
	var el = 'membership_type_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");		
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_membership_type(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_membership_type(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"type", label:"Code", width:400, resizeable:true, sortable:true}
				  ];						
	
		var fields = ['id', 'company_structure_id', 'type', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_membership_type_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
}

function load_add_new_membership_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_membership',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Membership Type',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}


function load_edit_membership_type_old(membership_type_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_membership_type',{membership_type_id:membership_type_id},function(o) {
		$("#action_form").html(o);							 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Membership Type',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_delete_membership_type(membership_type_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_membership_type_confirmation',{membership_type_id:membership_type_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_membership_type',{membership_type_id:membership_type_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_membership_type_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

// end membership

// employment status

function load_employment_status_dt() {
	var el = 'employment_status_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		if(id > 4) {
			elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_employment_status(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_employment_status(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
		}
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"code", label:"Code", width:100, resizeable:true, sortable:true},
					{key:"status", label:"Status", width:200, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'code', 'status', 'action'];
		var height = 'auto';
		var width = '100%';
		
		var controller = 'settings/_load_employment_status_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();	
}

function load_add_new_employment_status_old() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_employment_status',{},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Employment Status',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
	
}

function load_edit_employment_status_old(employment_status_id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_employment_status',{employment_status_id:employment_status_id},function(o) {
		$("#action_form").html(o);							 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Employment Status',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_delete_employment_status(employment_status_id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_employment_status_confirmation',{employment_status_id:employment_status_id},function(o) {
		$("#confirmation").html(o);								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_employment_status',{employment_status_id:employment_status_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_employment_status_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();	
}

// end employment status

// application status

function load_application_status_dt() {
	var el = 'application_status_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_application_status(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_application_status(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"status", label:"Status", width:100, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'status'];
		var height = 'auto';
		var width = 300;
		
		var controller = 'settings/_load_application_status_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();	
}

function load_subdivision_type_dt() {
	var el = 'subdivision_type_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");					
				elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:editSubDivisionType(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_subdivision(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
			};
							
		var columns = 	[
						 {key:"action",label:"Action",width:40,resizeable:true,sortable:true, formatter:action },
						 {key:"type",label:"Type",width:400,resizeable:true,sortable:true}
						];
						
		var fields =	['id','company_structure_id','type','action'];
		var height = 	'auto';
		var width = 	'100%';

		var controller = 'settings/_load_subdivision_type_dt?';	
		
		var subdivision_type = new createDataTable(el,controller, columns, fields, height, width);
		subdivision_type.rowPerPage(7);
		subdivision_type.pageLinkLabel('First','Previous','Next','Last');
		subdivision_type.show();			
}

function chkUnchk(a) {	
    var theForm = document.frmMultiAction;
    for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = a;
    }
}

function load_request_dt() {	
	$.get(base_url+'settings/_load_request_dt',{},
		function(o){			
			$('#request_datatable').html(o);		
		});		
}

function load_request_approvers_dt_depre(request_id) {	
	$.post(base_url+'settings/_load_request_approvers_dt',{request_id:request_id},
		function(o){			
			$('#request_datatable').html(o);		
		});		
}

function sub_load_request_dt() {
	var el = 'request_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_application_status(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_application_status(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"title", label:"Request", width:100, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'title', 'type','is_active','date_created','action'];
		var height = 'auto';
		var width = 300;
		
		var controller = 'settings/_load_request_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();	
}

function load_add_new_application_status() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_application_status',{},function(o) {
		$("#action_form").html(o)								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Add New Employment Status',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
		}	
		}).show();			
	
}

function addNewRequest() {
	_addNewRequest({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();	
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			dialogOkBox(o.message,{});		
		}
	});
}

function addApprovers(request_id) {
	_addApprovers(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			dialogOkBox(o.message,{});		
		}
	});
}

function sortApproversLevel(request_id) {
	_sortApproversLevel(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},		
		onError: function(o) {
			dialogOkBox(o.message,{});		
		}
	});
}

function copyRequestSettings(request_id) {
	_copyRequestSettings(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function showEditRequestApprovers(eid) {
	$(".data-table-container").hide();
	$(".request-approvers-form-container").show();
	$('.request-approvers-form-container').html(loading_image);

	$.get(base_url + 'settings/ajax_edit_request_approvers',{eid:eid},function(o) {
		$('.request-approvers-form-container').html(o);		
	});	

}

function editRequest(request_id) {
	_editRequest(request_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});				
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function closeTheDialog() {
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#add_request');
}



function load_edit_application_status(id) {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_edit_application_status',{id:id},function(o) {
		$("#action_form").html(o);								 
	})	
	var $dialog = $('#action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Employment Status',
		resizable: false,
		position: [480,50],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}).show();			
}

function archiveRequestSettings(request_id) {
	_archiveRequestSettings(request_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveGroup(group_id) {
	_archiveGroup(group_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteRequestApprovers(eid) {	
	_deleteRequestApprovers(eid, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteGroupMember(employee_id) {
	_deleteGroupMember(employee_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteRole(role_id) {
	_deleteRole(role_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function removeEnrollee(eid) {
	_removeEnrollee(eid, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteBenefit(benefit_id) {
	_deleteBenefit(benefit_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteUser(user_id) {
	_deleteUser(user_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


function deleteRequestApproversDeprecated(approver_id) {
	_deleteRequestApprovers(approver_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteBranch(branch_id) {
	_deleteBranch(branch_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function assignOverrideLevel(approver_id) {
	_assignOverrideLevel(approver_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function load_delete_application_status(id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_application_status_confirmation',{id:id},function(o) {
		$("#confirmation").html(o);							 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/delete_application_status',{id:id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_application_status_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();	
}

function editAttendance(date, employee_id, start_date, end_date) {
	_editAttendance(date, employee_id, {
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeTheDialog();
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			_showAttendance('#attendance_container', employee_id, start_date, end_date, {});
		},
		onError: function(o) {
			alert(o.message);	
		}
	});
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function createFormToken() {
	$.post(base_url + 'settings/_load_token',{},function(o){
		$('.token_wrapper').val(o.token);
	},'json');
}

function load_department_list_dt() {
	var h_branch_id = $('#branch_id').val();
	$('#department_list_dt_wrapper').html('Loading...');	
	$.post(base_url + 'settings/_load_department_list_dt',{h_branch_id:h_branch_id},
	function(o){				
		$('#department_list_dt_wrapper').html(o);		
	});		
}

function load_child_group_list(h_company_structure_id) {
	$('.company_structure_wrapper').val(h_company_structure_id);
	load_employee_group_trailing();			
	$(".tipsy").remove();
	$('#group_list_dt_wrapper').html('Loading...');	
	$.post(base_url + 'settings/_load_group_list_dt',{h_company_structure_id:h_company_structure_id},
	function(o){				
		$('#group_list_dt_wrapper').html(o);		
	});		
}

function load_child_department_list(h_company_structure_id) {
    location.href = base_url + 'settings/group_tab?id=' + h_company_structure_id;
    /*$('.company_structure_wrapper').val(h_company_structure_id);
    load_employee_group_trailing();
    $(".tipsy").remove();
    $('#group_list_dt_wrapper').html('Loading...');
    $.post(base_url + 'settings/_load_group_list_dt',{h_company_structure_id:h_company_structure_id},
        function(o){
            $('#group_list_dt_wrapper').html(o);
        });*/
}

function load_group_list_dt() {
	var h_company_structure_id = $('#h_company_structure_id').val();
	
	$('#employee_list_dt_wrapper').html("");
	$('#group_list_dt_wrapper').html('Loading...');	
	$.post(base_url + 'settings/_load_group_list_dt',{h_company_structure_id:h_company_structure_id},
	function(o){				
		$('#group_list_dt_wrapper').html(o);		
	});
}

function load_employee_group_trailing() {
	var h_company_structure_id = $('#h_company_structure_id').val();
	$('#employeeGroupTrailing').html('Loading...');	
	$.post(base_url + 'settings/_load_employee_group_trailing',{h_company_structure_id:h_company_structure_id},
	function(o){				
		$('#employeeGroupTrailing').html(o);		
	});
}


function load_employee_list_dt() {
	var h_company_structure_id = $('#h_company_structure_id').val();
	
	$('#group_list_dt_wrapper').html("");	
	$('#employee_list_dt_wrapper').html('Loading...');	
	$.post(base_url + 'settings/_load_employee_list_dt',{h_company_structure_id:h_company_structure_id},
	function(o){				
		$('#employee_list_dt_wrapper').html(o);		
	});
}

function load_child_employee_list_dt(h_company_structure_id) {
	$('#employee_list_dt_wrapper').html('Loading...');	
	$.post(base_url + 'settings/_load_employee_list_dt',{h_company_structure_id:h_company_structure_id},
	function(o){				
		$('#employee_list_dt_wrapper').html(o);		
	});	
}

function bindBackEvent() {
	$("body").keydown(function (e) {
		var keyId = e.keyCode;
		//alert(keyId);
		if(keyId == 27) {
			var h_static_company_structure_id 	= $('#h_static_company_structure_id').val(); // this serves as the main parent id, it does not change
			var h_company_structure_id			= $('#h_company_structure_id').val(); // $company_structure->getId()
			var h_company_structure_parent_id 	= $('#h_cs_parent_id').val(); // $company_structure->getParetnId()
			
			if($('#group_list_dt_wrapper').html() != "" && h_static_company_structure_id != h_company_structure_id) {
				load_employee_group_trailing();
				load_child_group_list(h_company_structure_parent_id);
			} else { history.go(-1); }
		}
		//return true;
	});
}

function unbindBackEvent() {
	$('body').unbind('keydown');	
}

function addGroup() {
	createFormToken();
	var h_company_structure = $('#h_company_structure_id').val();
	_addGroup(h_company_structure, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_child_group_list(h_company_structure);
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

function addDepartment() {
    createFormToken();
    var h_company_structure = $('#h_company_structure_id').val();
    _addDepartment(h_company_structure, {
        onSaved: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            dialogOkBox(o.message,{});
            load_child_group_list(h_company_structure);
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

function addEmployee() {
	createFormToken();
	var h_company_structure = $('#h_company_structure_id').val();
	_addEmployee(h_company_structure, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
			load_employee_list_dt();
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

function addBranch(company_structure_id) {
	_addBranch(company_structure_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addBranch1(company_structure_id) {
	_addBranch(company_structure_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editBranch(eid) {
	_editBranch(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_company_structure();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addSubBranch(company_structure_id) {
	_addSubBranch(company_structure_id,{
		onLoading: function() {			
		},
		onSaving: function() {
			closeDialog('#sub_action_form');
			//showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeDialog('#sub_action_form');
			//dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#sub_action_form');
			//dialogOkBox(o.message,{});
		}
	});
}

function load_add_branch(parent_id) {
	blockPopUp(); 
	$("#sub_action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_branch',{parent_id:parent_id},function(o) {
		$("#sub_action_form").html(o);								 
	})
	
	var $dialog = $('#sub_action_form');
	$dialog.dialog("destroy");
	
	
	var $dialog = $('#sub_action_form');
	$dialog.dialog({
		title: 'Add Company Branch',
		resizable: false,
		position: [480,70],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();			
				}	
					
		}).dialogExtend({
        "maximize" : false,
		"minimize"  : false       
      }).show();			
	

}

function addNewCompanyStructure(company_structure_id,branch_id) {
	_addNewCompanyStructure(company_structure_id,branch_id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addNewGroupTeam(eid,mode) {
	_addNewGroupTeam(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			if(mode == 1){
				load_branch_departments(o.branch_id);
			}else{
				load_department_teams_groups(eid);
			}
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addNewSection(eid,mode) {
	_addNewSection(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			if(mode == 1){
				load_branch_departments(o.branch_id);
			}else{
				load_department_teams_groups(eid);
			}
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addSubGroupTeam(eid,parent_id) {
	_addNewGroupTeam(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_department_teams_groups(parent_id);
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addNewDepartment(eid,mode) {
	_addNewDepartment(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			if(mode == 1){
				load_company_structure();
			}else{
				load_branch_departments(o.branch_id);
			}
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editDepartment(eid) {
	_editDepartment(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_branch_departments(o.branch_id);
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editTeamGroup(eid,parent_id) {
	_editTeamGroup(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_department_teams_groups(parent_id);
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editSection(eid,parent_id) {
	_editSection(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_department_teams_groups(parent_id);
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editContribution(eid,type) {
	_editContribution(eid,type,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			if(o.c_type == 'sss') {
				load_sss_table();
			}else if(o.c_type == 'philhealth') {
				load_philhealth_table();
			}else if(o.c_type == 'pagibig') {
				load_pagibig_table();
			}
			
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function old_load_add_structure(parent_id) {	
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_structure',{parent_id:parent_id},function(o) {
		$("#action_form").html(o);						 
	})	
	var $dialog = $('#action_form');
    $dialog.dialog("destroy");	
	var $dialog = $('#action_form');	
	$dialog.dialog({
		title: 'Add Company Structure',
		resizable: false,
		position: [480,120],
		width: 400,
		//height: 250,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}).dialogExtend({
        "maximize" : false,
		"minimize"  : false   
      }).show();			
}

function follow_access_rights_main_settings(id,c) {
	var a = $(id).val();
	if(a != 3) {
		$(id).val(a);
		$(c).val(a);		
	}
}

function revert_access_rights_main_settings(id) {
	$(id).val(3);
}

function lockAllPayrollPeriodBySelectedYear(selected_year) {
	_lockAllPayrollPeriodBySelectedYear(selected_year, {
		onYes: function(o) {
			//load_payroll_period_list_dt(selected_year);			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);	
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function lockPayrollPeriod(e_id) {
	_lockPayrollPeriod(e_id, {
		onYes: function() {
			load_payroll_period_list_dt($("#selected_year").val());
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function unlockPayrollPeriod(e_id) {
	_unlockPayrollPeriod(e_id, {
		onYes: function() {
			load_payroll_period_list_dt($("#selected_year").val());
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function payrollPeriodWithSelectedAction(status) {
	if(status){	
		_payrollPeriodWithSelectedAction(status, {
			onYes: function() {
				load_payroll_period_list_dt($("#selected_year").val());
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			}, 
			onNo: function(){
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				$('#chkAction').val('');
			} 
		});
	}
}

function load_deduction_breakdown_list() {
	$("#deduction_breakdown_list_wrapper").html(loading_image);
	$.post(base_url + 'settings/_load_deduction_breakdown_list',{},function(o) {
		$("#deduction_breakdown_list_wrapper").html(o);						 
	})
}

function addNewSubDivisionType() {
	_addNewSubDivisionType({
		onSaved: function(o) {			
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

function editSubDivisionType(id) {
	_editSubDivisionType(id,{
		onSaved: function(o) {			
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

function editDeductionBreakdown(h_id) {
	_editDeductionBreakdown(h_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			load_deduction_breakdown_list();
			
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function clodeEditDeductionBreakdown() {
	$('.formError').remove();
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_deduction_breakdown_form');
}

function editWeeklyDeductionBreakdown(h_id) {
	_editWeeklyDeductionBreakdown(h_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			load_deduction_breakdown_list();
			
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function clodeEditWeeklyDeductionBreakdown() {
	$('.formError').remove();
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_weekly_deduction_breakdown_form');
}

function _deactivateDeductionBreakdown(h_id) {
	$.post(base_url + 'settings/_deactivate_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
		$(".tipsy").remove();
	})
}

function _deactivateWeeklyDeductionBreakdown(h_id) {
	$.post(base_url + 'settings/_deactivate_weekly_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
		$(".tipsy").remove();
	})
}

function _activateDeductionBreakdown(h_id) {
	$.post(base_url + 'settings/_activate_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
		$(".tipsy").remove();
	})
}

function _activateWeeklyDeductionBreakdown(h_id) {
	$.post(base_url + 'settings/_activate_weekly_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
		$(".tipsy").remove();
	})
}

function archiveCompanyBranch(eid) {
	_archiveCompanyBranch(eid, {
		onYes: function(o) {
			load_company_structure();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveCompanyDepartment(eid) {
	_archiveCompanyDepartment(eid, {
		onYes: function(o) {
			load_branch_departments(o.branch_id);
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteExam(eid) {
	_deleteExam(eid, {
		onYes: function(o) {
			window.location.href = o.url;
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restorePerformanceTemplate(eid) {
	_restorePerformanceTemplate(eid, {
		onYes: function(o) {
			load_archive_performance_datatable();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archivePerformanceTemplate(eid) {
	_archivePerformanceTemplate(eid, {
		onYes: function(o) {
			window.location.href = o.url;
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function load_employee_status_list_dt() {	
	switchActiveClass('btn_viewallarchives','btn_viewall');
	$.get(base_url + 'settings/_load_employee_status_list_dt',{},function(o) {
		$('#employee_status_dt_wrapper').html(o);		
	});	
}

function switchActiveClass(obj_remove_class_id,obj_add_class_id){		
	$('#' + obj_remove_class_id).removeClass('active');
	$('#' + obj_add_class_id).addClass('active');
}

function load_requirements_list_dt() {	
	switchActiveClass('btn_viewallarchives','btn_viewall');
	$.get(base_url + 'settings/_load_requirements_list_dt',{},function(o) {
		$('#requirements_dt_wrapper').html(o);		
	});	
}

function load_company_benefits_list_dt() {	
	switchActiveClass('btn_viewallarchives','btn_viewall');
	$.get(base_url + 'settings/_load_company_benefits_list_dt',{},function(o) {
		$('#company_benefits_dt_wrapper').html(o);		
	});	
}

function load_archive_company_benefits_list_dt() {	
	switchActiveClass('btn_viewall','btn_viewallarchives');
	$.get(base_url + 'settings/_load_archive_company_benefits_list_dt',{},function(o) {
		$('#company_benefits_dt_wrapper').html(o);		
	});	
}

function load_archive_employee_status_list_dt() {	
	switchActiveClass('btn_viewall','btn_viewallarchives');
	$.get(base_url + 'settings/_load_archive_employee_status_list_dt',{},function(o) {
		$('#employee_status_dt_wrapper').html(o);		
	});	
}

function load_archive_requirement_list_dt() {	
	switchActiveClass('btn_viewall','btn_viewallarchives');
	$.get(base_url + 'settings/_load_archive_requirements_list_dt',{},function(o) {
		$('#requirements_dt_wrapper').html(o);		
	});	
}

function load_leave_type_list_dt() {	
	$.get(base_url + 'settings/_load_leave_type_list_dt',{},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_grace_period_list_dt() {	
	$('#grace_period_list_dt_wrapper').html(loading_image);		
	$.get(base_url + 'settings/_load_grace_period_list_dt',{},function(o) {
		$('#grace_period_list_dt_wrapper').html(o);		
	});	
}

function load_archive_leave_type_list_dt() {	
	$.get(base_url + 'settings/_load_archive_leave_type_list_dt',{},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function hideAddRequestApprovers(){
	$(".data-table-container").show();
	$(".request-approvers-form-container").hide();
	$('.request-approvers-form-container').html('');
}

function hideEditRequestApprovers(){
	$(".data-table-container").show();
	$(".request-approvers-form-container").hide();
	$('.request-approvers-form-container').html('');
}

function showAddRequestApprovers(){
	$(".data-table-container").hide();
	$(".request-approvers-form-container").show();
	$('.request-approvers-form-container').html(loading_image);

	$.get(base_url + 'settings/_load_add_request_approvers_form',{},function(o) {
		$('.request-approvers-form-container').html(o);		
	});	
		
}

function hideAddBreaktimeSchedule(){
	$(".breaktime-schedule-container").show();
	$('.breaktime-schedule-form-container').html('');
}


function showAddBreaktimeSchedule(){
	$(".breaktime-schedule-container").hide();
	$(".breaktime-schedule-form-container").show();
	$('.breaktime-schedule-form-container').html(loading_image);

	$.get(base_url + 'settings/_load_add_breaktime_schedule_form',{},function(o) {
		$('.breaktime-schedule-form-container').html(o);		
	});	
		
}

function showEditBreaktimeSchedule(){
	$(".breaktime-schedule-container").hide();
	$(".breaktime-schedule-form-container").show();
	$('.breaktime-schedule-form-container').html(loading_image);
}

function showEditIpAddress(eid) {
	_showEditIpAddress(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaved: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
          	load_ip_address_list_dt();
			//showOkDialog(o.message);			
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
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

function showDeleteIpAddress(eid) {
	_showDeleteIpAddress(eid, {
		onYes: function(o) {
			load_ip_address_list_dt();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function addEmployeeStatus() {
	_addEmployeeStatus({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_employee_status_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addRequirement() {
	_addRequirement({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_requirements_list_dt();  	
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addCompanyBenefit() {
	_addCompanyBenefit({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_company_benefits_list_dt();  	
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addLeaveType() {
	_addLeaveType({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_leave_type_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addNewGracePeriod() {
	_addNewGracePeriod({
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_grace_period_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editGracePeriod(eid) {
	_editGracePeriod(eid,{
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_grace_period_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editCompanyBenefit(eid) {
	_editCompanyBenefit(eid,{
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_company_benefits_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function setDefaultGracePeriod(eid) {
	_setDefaultGracePeriod(eid, {
		onYes: function(o) {
			load_grace_period_list_dt();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveGracePeriod(eid) {
	_archiveGracePeriod(eid, {
		onYes: function(o) {
			load_grace_period_list_dt();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function editEmployeeStatus(eid) {
	_editEmployeeStatus(eid, {
		onSaved: function(o) {
			load_employee_status_list_dt();  	
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

function editRequirement(eid) {
	_editRequirement(eid, {
		onSaved: function(o) {
			load_requirements_list_dt(); 
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

function editPayrollSettings(field) {
	_editPayrollSettings(field, {
		onSaved: function(o) {
			load_payroll_settings(); 
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

function editLeaveType(eid) {
	_editLeaveType(eid, {
		onSaved: function(o) {
			load_leave_type_list_dt();			
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

function archiveLeaveType(h_id) {
	_archiveLeaveType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveEmployeeStatus(e_id) {
	_archiveEmployeeStatus(e_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveRequirement(e_id) {
	_archiveRequirement(e_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			//dialogOkBox(o.message,{});
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveBenefit(e_id) {
	_archiveBenefit(e_id, {
		onYes: function(o) {
			load_company_benefits_list_dt();
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreBenefit(e_id) {
	_restoreBenefit(e_id, {
		onYes: function(o) {
			load_archive_company_benefits_list_dt();
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveMemo(e_id) {
	_archiveMemo(e_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreEmployeeStatus(e_id) {
	_restoreEmployeeStatus(e_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreRequirement(e_id) {
	_restoreRequirement(e_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreMemo(e_id) {
	_restoreMemo(e_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreLeaveType(h_id) {
	_restoreLeaveType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function load_loan_list_dt() {	
	$.get(base_url + 'settings/_load_loan_list_dt',{},function(o) {
		$('#loan_list_dt_wrapper').html(o);		
	});	
}

function load_archive_loan_list_dt() {	
	$.get(base_url + 'settings/_load_archive_loan_list_dt',{},function(o) {
		$('#loan_list_dt_wrapper').html(o);		
	});	
}

function addDeductionType() {
	_addDeductionType({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_loan_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editDeductionType(eid) {
	_editDeductionType(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_loan_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function checkUsername()
{
	$("#username_checker").html('checking...');
	var username = $("#username_update").val();
	var user_id = $("#user_id").val();
	$.post(base_url+'settings/_check_username',{username:username,user_id:user_id},
	function(o){
		$("#username_checker").html(o);
	});
}

function updateModuleEdit(mod) {
	module='';
	if($('#module_update_hr').attr('checked')) {
		module+= 'hr,';
	}
	
	if($('#module_update_payroll').attr('checked')) {
		module+= 'payroll,';
	}
	
	if($('#module_update_clerk').attr('checked')) {
		module+= 'clerk,';
	}
	
	if($('#module_update_employee').attr('checked')) {
		module+= 'employee';
	}
	$("#update_module").val(module);
}

function load_add_eeo_job_category() {
	_addEeoJobCategory({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_job_eeo_job_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_add_new_relationship() {
	_addNewRelationship({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_dependent_relationship_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_add_new_location() {
	_addNewLocation({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_location_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_edit_employment_status(id) {
	_editEmploymentStatus(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_employment_status_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_edit_eeo_job_category(id) {
	_editEeoJobCategory(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_job_eeo_job_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_edit_membership_type(id) {
	_editMembershipType(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_membership_type_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_edit_dependent(id) {
	_editDependent(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_dependent_relationship_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_edit_job_salary_rate(id) {
	_editJobSalaryRate(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_job_salary_rate_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_edit_location(id) {
	_editLocation(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_location_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_add_new_employment_status() {
	_addNewEmploymentStatus({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_employment_status_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_add_job_salary_rate() {
	_addJobSalaryRate({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_job_salary_rate_list_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_add_new_membership() {
	_addNewMembership({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_membership_type_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function hideUserRoleContainer() {
	$(".user-role-container").hide();
	$("#user-role-form-container").show();
}

function showUserRoleContainer() {
	$(".user-role-container").show();
	$("#user-role-form-container").hide();
}

function hideBenefitsContainer() {
	$("#benefits-container").hide();
	$("#benefits-form-container").show();
}

function showBenefitsContainer() {
	$("#benefits-container").show();
	$("#benefits-form-container").hide();
}

function addRole() {
	hideUserRoleContainer();
	$("#user-role-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_add_role_form',{},function(o) {
		$('#user-role-form-container').html(o);		
	});	
}

function addBenefit() {
	hideBenefitsContainer();
	$("#benefits-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_add_benefit_form',{},function(o) {
		$('#benefits-form-container').html(o);		
	});	
}

function addUser() {
	hideUserRoleContainer();
	$("#user-role-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_add_user_form',{},function(o) {
		$('#user-role-form-container').html(o);		
	});	
}

function importUser() {
	hideUserRoleContainer();
	$("#user-role-form-container").html(loading_image);	
	$.get(base_url + 'settings/_load_import_user_form',{},function(o) {
		$('#user-role-form-container').html(o);		
	});	
}

function validateUsername(obj_id,ajax_container){
    $("#" + obj_id).blur(function(){
        var username = $(this).val();
        var eid      = $("#eid").val();
        if(username != ""){
            $("#" + ajax_container).html('<div style="margin-top:4px;width:234px;" class=\"label label-success\"><i class="icon-info-sign icon-white"></i> Validating username ' + loading_image + '</div>'); 
            $.get(base_url + 'settings/ajax_verify_username',{username:username,eid:eid},
                function(o){                    
                    $("#" + ajax_container).html(o.message);
                },"json"
            );
        }else{           
            $("#" + ajax_container).html("");
        }
    });
}

function addUserAccount() {
	_addUserAccount({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_user_management_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function editUserAccount(id) {
	_editUserAccount(id,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_user_management_dt();
			closeTheDialog();
			dialogOkBox(o.message,{});
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function archiveLoanType(h_id) {
	_archiveLoanType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreArchiveLoanType(h_id) {
	_restoreArchiveLoanType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function generatePayrollPeriod(selected_year) {
	_generatePayrollPeriod({
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);	
			dialogOkBox(o.message,{});				
			load_payroll_period_list_dt(selected_year);
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function addPayrollPeriod(selected_year) {
	_addPayrollPeriod(selected_year,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_payroll_period_list_dt(o.selected_year);
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function assignBenefit(eid) {
	_assignBenefit(eid,{
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {			
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function requirements_with_selected_confirmation(action,eid) {	
	if(action == 'archive'){
		$("#requirements_with_selected_action").val("archive");
		var message = 'Send to archive all selected entries?';		
	}else{
		$("#requirements_with_selected_action").val("restore");
		var message = 'Restore all selected entries?';	
	}	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	
	$(dialog_id).html(message);
	var $dialog = $(dialog_id);
		$dialog.dialog({
            title: 'Confirmation',
            width: 300,
				resizable: false,
				modal:true,
                close: function() {
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/requirements_with_selected_action',$('#withSelectedAction').serialize(),
						    function(o){													
							if(o.is_success==1) {								
								$dialog.dialog("close");								
								if(action == 'archive'){
								  load_requirements_list_dt();  	
								}else{
								  load_archive_requirement_list_dt();
								}	
							}else {
								alert("Invalid SQL");	
								$dialog.dialog("close");								
							}					
							   										   
						},"json");		
						
                    },
					'No' : function(){
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function company_benefits_with_selected_confirmation(action,eid) {	
	$("#company_benefits_with_selected_action").val(action);
	
	if(action == 'archive'){
		var message = 'Send to archive all selected entries?';		
	}else{
		var message = 'Restore all selected entries?';	
	}	
	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	
	$(dialog_id).html(message);
	var $dialog = $(dialog_id);
		$dialog.dialog({
            title: 'Confirmation',
            width: 300,
				resizable: false,
				modal:true,
                close: function() {
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/company_benefits_with_selected_action',$('#withSelectedAction').serialize(),
						    function(o){													
							if(o.is_success==1) {								
								$dialog.dialog("close");								
								if(action == 'archive'){
								  load_company_benefits_list_dt();  	
								}else{
								  load_archive_company_benefits_list_dt();
								}	
							}else {
								alert("Invalid SQL");	
								$dialog.dialog("close");								
							}					
							   										   
						},"json");		
						
                    },
					'No' : function(){
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function memo_with_selected_confirmation(action,eid) {	
	if(action == 'archive'){
		$("#memo_with_selected_action").val("archive");
		var message = 'Send to archive all selected entries?';		
	}else{
		$("#memo_with_selected_action").val("restore");
		var message = 'Restore all selected entries?';	
	}	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	
	$(dialog_id).html(message);
	var $dialog = $(dialog_id);
		$dialog.dialog({
            title: 'Confirmation',
            width: 300,
				resizable: false,
				modal:true,
                close: function() {
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'settings/memo_with_selected_action',$('#withSelectedAction').serialize(),
						    function(o){													
							if(o.is_success==1) {								
								$dialog.dialog("close");								
								if(action == 'archive'){
								  load_memo_template_list_dt();  	
								}else{
								  load_archive_memo_template_list_dt();
								}	
							}else {
								alert("Invalid SQL");	
								$dialog.dialog("close");								
							}					
							   										   
						},"json");		
						
                    },
					'No' : function(){
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function importEmployeeBenefits() {
	_importEmployeeBenefits({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);	
			//dialogOkBox(o.message,{ok_url: "attendance/attendance_logs"});
			load_benefits_dt();		
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function importSSSTable() {
	_importSSSTable({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);	
			//dialogOkBox(o.message,{ok_url: "attendance/attendance_logs"});
			load_sss_table();		
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function importPhilHealthTable() {
	_importPhilHealthTable({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);	
			//dialogOkBox(o.message,{ok_url: "attendance/attendance_logs"});
			load_philhealth_table();		
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function importPagibigTable() {
	_importPagibigTable({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeTheDialog();
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			showOkDialog(o.message);	
			//dialogOkBox(o.message,{ok_url: "attendance/attendance_logs"});
			load_pagibig_table();		
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

//add employee from exclude employee container
const addExcludeEmployee = (id, element) => {
	let employee_name = element.innerHTML
	let output = "<li onclick='removeExcludeEmployee("+id+",`"+employee_name+"`, this)' >"+
	"<input type='hidden' name='excluded_employees[]' value='"+id+"' />"+
	"<span>"+employee_name+"</span>"+
	"</li>";
	$("#excluded-employee-container").append(output);
	// $("#").append("<h1>Hello</h1>");
	//document.getElementById('excluded-employee-container').innerHTML += element
	//$("#_exclude_employee-container").append(element);

	element.parentNode.removeChild(element);
}

//remove employee from exclude employee container
const removeExcludeEmployee = (id, name, element) =>{
	let output = "<li id='employee_"+id+"' onclick='addExcludeEmployee("+id+", this)'>"+name+"</li>";
	$("#_employee-container").append(output);

	element.parentNode.removeChild(element);
}





function toggleFixedContri(action, eid) {	

	if(action == 'enabled'){
		var message = 'Are you sure you want to ENABLED pagibig fixed contribution?';		
	}else{
		var message = 'Are you sure you want to DISABLED pagibig fixed contribution?';
	}	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	
	$(dialog_id).html(message);
	var $dialog = $(dialog_id);
		$dialog.dialog({
            title: 'Confirmation',
            width: 300,
				resizable: false,
				modal:true,
                close: function() {
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                buttons: {
					'Yes' : function(){						
						$.get(base_url+'settings/change_fixed_contribution_settings',{action:action,eid:eid},
						    function(o){													
							if(o.is_success==1) {								
								$dialog.dialog("close");								
								load_fixed_contribution();

							}else {
								alert("Invalid SQL");	
								$dialog.dialog("close");								
							}					
							   										   
						},"json");		
						
                    },
					'No' : function(){
						  $dialog.dialog("close");
                    }
                }
            }).show();
}


function load_fixed_contribution() {

	$("#fixed_wrapper").html(loading_image);	
	$.get(base_url + 'settings/_load_fixed_contribution_settings',{},function(o) {
		$('#fixed_wrapper').html(o);	
		});	
}

function addGPExmptedEmployees() {
		_addGPExmptedEmployees({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_gp_excempted_employees();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});		
	
}


function load_gp_excempted_employees(){

	$("#exempted_wrapper").html(loading_image);	
	$.get(base_url + 'settings/_load_gp_excempted_employees_list',{},function(o) {
		$('#exempted_wrapper').html(o);		
	});
}


function archiveGracePeriodExempted(eid) {
	_archiveGracePeriodExempted(eid, {
		onYes: function(o) {
			 load_gp_excempted_employees();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}



function load_project_site_dt() {	
	$.get(base_url + 'settings/load_project_site_dt',{},function(o) {
		$('#project_site_list_dt_wrapper').html(o);		
	});	
}
//activity history
function load_activity_dt() {	
	$.get(base_url + 'settings/load_activity_dt',{},function(o) {
		$('#activity_list_dt_wrapper').html(o);		
	});	
}

function show_add_activity_form() {
	$.get(base_url+'settings/_load_add_activity_form',{},
	function(o){
		$("#activity_wrapper").html(o);	
	});	
	
}

function addActivityType() {
	_addActivityType({
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onSaving: function(o) {
			console.log(o);
		},
		onSaved: function(o) {
			load_activity_dt();	
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function _addActivityType(events) {	
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Activity Type';
	var width = 450;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'settings/_load_add_new_activity_type', {}, function(data) {
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

//monthly contribution settings

function _deactivateMonthlyDeductionBreakdown(h_id) {
	$.post(base_url + 'settings/_deactivate_monthly_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
		$(".tipsy").remove();
	})
}


function _activateMonthlyDeductionBreakdown(h_id) {
	$.post(base_url + 'settings/_activate_monthly_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
		$(".tipsy").remove();
	})
}

function editMonthlyDeductionBreakdown(h_id) {

	_editMonthlyDeductionBreakdown(h_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			load_deduction_breakdown_list();
			
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			showLoadingDialog('Loading...');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}