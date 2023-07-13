// JavaScript Document
function load_company_info() {
	$('#c-info').html('Loading...');	
	$.post(base_url + 'settings/_load_company_info',{},
		function(o){			
			$('#c-info').html(o);		
		});		
}

function load_branch_list() {
	$('#branch-list').html('Loading...');	
	$.post(base_url + 'settings/_load_branch_list',{},
		function(o){			
			$('#branch-list').html(o);		
		});		
}

function load_location_list() {
	$('#location-list').html('Loading...');	
	$.post(base_url + 'settings/_load_location_list',{},
		function(o){			
			$('#location-list').html(o);		
		});		
}

function load_membership_type_list() {
	$('#membership-list').html('Loading...');	
	$.post(base_url + 'settings/_load_membership_type_list',{},
		function(o){			
			$('#membership-list').html(o);		
		});		
}

function load_subdivision_list() {
	$('#subdivsion-list').html('Loading...');	
	$.post(base_url + 'settings/_load_subdivision_list',{},
		function(o){			
			$('#subdivsion-list').html(o);		
		});		
}

function load_dependent_relationship() {
	$('#relationship-list').html('Loading...');	
	$.post(base_url + 'settings/_load_dependent_relationship',{},
		function(o){			
			$('#relationship-list').html(o);		
		});		
}

function load_license_list() {
	$('#license-list').html('Loading...');	
	$.post(base_url + 'settings/_load_license_list',{},
		function(o){			
			$('#license-list').html(o);		
		});		
}

function load_company_structure() {
	$('#c-structure').html('Loading...');	
	$.post(base_url + 'settings/_load_company_structure',{},
		function(o){				
			$('#c-structure').html(o);		
		});		
}

function load_edit_company_info() {
	$("#action_form").html('Loading...');
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

function load_add_new_employment_status() {
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

function load_add_new_relationship() {
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

function load_add_new_license() {
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

function load_edit_dependent(dependent_id) {
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
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   disablePopUp();				    
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
}

function load_edit_license(license_id) {
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
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				    disablePopUp();
				   //load_my_messages_list();				
				}	
					
		}		
		).show();			
	
}

function load_add_new_location() {
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

function load_add_new_membership() {
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

function load_edit_location(location_id) {
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

function load_edit_membership_type(membership_type_id) {
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


function load_add_employment_status() {
	blockPopUp();
	$("#action_form").html('Loading...');
	$.post(base_url + 'settings/_load_add_new_employment_status',{},function(o) {
		$("#action_form").html(o);							 
	})	
	var $dialog = $('#action_form');
    $dialog.dialog("destroy");	
	var $dialog = $('#action_form');	
	$dialog.dialog({
		title: 'Add Employment Status',
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
					
		}		
		).dialogExtend({
        "maximize" : false,
		"minimize"  : false 
      }).show();			
}


function load_edit_employment_status(employment_status_id) {
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
		"minimize"  : false,        
      }).show();			
	

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

function load_add_subdivision_type(parent_id) {
	blockPopUp();
	$("#sub_action_form").html('Loading...');
	$.post(base_url + 'settings/load_add_subdivision_type',{parent_id:parent_id},function(o) {
		$("#sub_action_form").html(o);								 
	})
	
	
	var $dialog = $('#sub_action_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#sub_action_form');
	$dialog.dialog({
		title: 'Add Type',
		resizable: false,
		position: [480,50],
		width: 400,		
		modal: true,
		close: function() {
 	 			   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
					
		}		
		).dialogExtend({
        "maximize" : false,
		"minimize"  : false,        
      }).show();
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


function load_add_eeo_job_category() {
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

function load_edit_eeo_job_category(id) {
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


function load_add_job_salary_rate() {
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

function load_edit_job_salary_rate(id) {
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

function load_job_list_dt(){
	var el = 'job_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_title(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_title(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"title", label:"Job Title", width:200, resizeable:true, sortable:true},
						{key:"name", label:"Job Specification", width:200, resizeable:true, sortable:true},
						{key:"description", label:"Specification Description", width:280, resizeable:true, sortable:true},
						];		
		var fields =	['id','company_structure_id','job_specification_id','title','is_active','name','description'];
		var height = 	'300px';
		var width = 	'800px';
		
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

function load_job_employment_status_list_dt(){
	var el = 'job_employment_status_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id     = oRecord.getData("id");
		var job_id = oRecord.getData("job_id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_employment_status(" + job_id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"title", label:"Job Title", width:220, resizeable:true, sortable:true},
						{key:"employment_status", label:"Status", width:200, resizeable:true, sortable:true}						
						];		
		var fields =	['id','job_id','company_structure_id','title','status','employment_status'];
		var height = 	'300px';
		var width = 	'500px';
		
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

function load_job_specification_list_dt(){
	var el = 'job_specification_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_job_specification(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_specification(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"name", label:"Job Specification", width:220, resizeable:true, sortable:true},
						{key:"description", label:"Job Description", width:220, resizeable:true, sortable:true},
						{key:"duties", label:"Duties", width:240, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','name','description','duties'];
		var height = 	'300px';
		var width = 	'800px';
		
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

function load_job_eeo_job_list_dt(){
	var el = 'job_eeo_job_list';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_eeo_job_category(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_job_eeo_category(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
			
		var columns = 	[//{key:"id",label:"id",width:,resizeable:true,sortable:true},
						{key:"action", label:"Action", width:35, resizeable:true, sortable:false, formatter:action },
						{key:"category_name", label:"Job Specification", width:550, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','category_name'];
		var height = 	'250px';
		var width = 	'530px';
		
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
						{key:"minimum_salary", label:"Minimum Salary", width:150, resizeable:true, sortable:true},
						{key:"maximum_salary", label:"Maximum Salary", width:150, resizeable:true, sortable:true},
						{key:"step_salary", label:"Step Salary", width:150, resizeable:true, sortable:true}
						];		
		var fields =	['id','company_structure_id','job_level','minimum_salary','maximum_salary','step_salary'];
		var height = 	'300px';
		var width = 	'800px';
		
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
						{key:"province", label:"Province", width:110, resizeable:true, sortable:true},
						{key:"city", label:"City", width:80, resizeable:true, sortable:true},
						{key:"address", label:"Address", width:80, resizeable:true, sortable:true},
						{key:"zip_code", label:"Zip Code", width:80, resizeable:true, sortable:true},
						{key:"phone", label:"Phone", width:80, resizeable:true, sortable:true},
						{key:"fax", label:"Fax", width:80, resizeable:true, sortable:true}
						
						];		
		var fields =	['id','name','province','city','address','zip_code','phone','fax'];
		var height = 	'300px';
		var width = 	'972px';
		
		var controller = 'settings/_load_branch_dt?';		
		var datatableDT = new createDataTable(el,controller, columns, fields, height, width);
		datatableDT.rowPerPage(15);
		datatableDT.pageLinkLabel('First','Previous','Next','Last');
		datatableDT.show();
}

// yui datatable
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
		var width = 	400;

		var controller = 'settings/_load_subdivision_type_dt?';	
		
		var subdivision_type = new createDataTable(el,controller, columns, fields, height, width);
		subdivision_type.rowPerPage(7);
		subdivision_type.pageLinkLabel('First','Previous','Next','Last');
		subdivision_type.show();			
}

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
		var width = 400;
		
		var controller = 'settings/_load_dependent_relationship_dt?';
	
		var dependent_relationship = new createDataTable(el,controller, columns, fields, height, width);
		dependent_relationship.rowPerPage(7);
		dependent_relationship.pageLinkLabel('First','Previous','Next','Last');
		dependent_relationship.show();	
}

function load_license_dt() {
	var el = 'license_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");		
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_license(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_license(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"license_type", label:"License Type", width:200, resizeable:true, sortable:true},
					{key:"description", label:"Description", width:400, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'license_type', 'description', 'action'];
		var height = 'auto';
		var width = 700;
		
		var controller = 'settings/_load_license_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();

}

function load_pay_period_dt() {
	var el = 'pay_period_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_pay_period(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_pay_period(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
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
		var width = 600;
		
		var controller = 'settings/_load_pay_period_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
	
}

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
		var width = 300;
		
		var controller = 'settings/_load_location_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
}

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
		var width = 400;
		
		var controller = 'settings/_load_membership_type_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
}

function load_employment_status_dt() {
	var el = 'employment_status_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:load_edit_employment_status(" + id + ");' style='display:inline-block;'><label title='Edit' id='tipsy_edit' class='ui-icon ui-icon-pencil' style='cursor:pointer;'></label></a><a href='javascript:void(0);' onclick='javascript:load_delete_employment_status(" + id + ");' style='display:inline-block;'><label title='Delete' id='delete' class='ui-icon ui-icon-trash' style='cursor:pointer;'></label></a>";
	};
	
	var columns = [
					{key:"action", label:"Action", width:40, resizeable:true, sortable:false, formatter:action },				
					{key:"code", label:"Code", width:100, resizeable:true, sortable:true},
					{key:"status", label:"Status", width:200, resizeable:true, sortable:true}
					
				  ];						
	
		var fields = ['id', 'company_structure_id', 'code', 'status', 'action'];
		var height = 'auto';
		var width = 450;
		
		var controller = 'settings/_load_employment_status_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();	
}

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
		var width = 400;
		
		var controller = 'settings/_load_skill_management_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();	
	
}
