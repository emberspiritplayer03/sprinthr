// JavaScript Document
// JavaScript Document


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
