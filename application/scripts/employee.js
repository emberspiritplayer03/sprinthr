var can_manage = "";
$(document).ready(function(){
	  $("#personal_information_max_button").click(function(){
		  $("#personal_information_max_button").hide();
		$("#personal_information_min_button").show();
		//$("#personal_information_submenu").show();
		$("#personal_information_submenu").slideDown();
	  });
	  
	  $("#personal_information_min_button").click(function(){
		$("#personal_information_max_button").show();
		$("#personal_information_min_button").hide();
		$("#personal_information_submenu").slideUp();
		
	  });
	  
	   $("#employment_information_max_button").click(function(){
		$("#employment_information_max_button").hide();
		$("#employment_information_min_button").show();
		$("#employment_information_submenu").slideDown();
	  });
	   
	  
	  
	  $("#employment_information_min_button").click(function(){
		$("#employment_information_max_button").show();
		$("#employment_information_min_button").hide();
		$("#employment_information_submenu").slideUp();
	  });
	  
	  $("#qualification_max_button").click(function(){
		  $("#qualification_max_button").hide();
		$("#qualification_min_button").show();
		$("#qualification_submenu").slideDown();
	  });
	  
	   $("#qualification_min_button").click(function(){
		$("#qualification_max_button").show();
		$("#qualification_min_button").hide();
		$("#qualification_submenu").slideUp();
	  });
	  
	  
	   $("#schedule_max_button").click(function(){
		  $("#schedule_max_button").hide();
		$("#schedule_min_button").show();
		$("#schedule_submenu").slideDown();
	  });
	  
	   $("#schedule_min_button").click(function(){
		$("#schedule_max_button").show();
		$("#schedule_min_button").hide();
		$("#schedule_submenu").slideUp();
	  });
	  

	$( "#search" ).change(function() {
	  //alert( $("#search").val() );
	  if($("#search").val() == "") {
	  	$("#datepicker").hide();
	  }
	});

});

function load_branch_dropdown() 
{
	$("#branch_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'employee/_load_branch_dropdown',{branch_id:branch_id},
	function(o){
		$("#branch_dropdown_wrapper").html(o);
	});
}

function load_department_dropdown()
{
	branch_id = $("#branch_id").val();
	$("#department_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'employee/_load_department_dropdown',{branch_id:branch_id},
	function(o){
		$("#department_dropdown_wrapper").html(o);
	});
}

function clear_form_error() {
	$("#addBranch").validationEngine('hide');  	
	$("#employee_form").validationEngine('hide'); 
	$("#addPosition").validationEngine('hide');  
	$("#addStatus").validationEngine('hide');  	
}


function checkForAddBranch() 
{
	clear_form_error();

	branch_id = $("#branch_id").val();	
	if(branch_id=='add') {
			load_branch_dropdown();
			if($("#branch_wrapper_form").html()!='') {
				dialogGeneric("#branch_wrapper_form",{height:'auto',width:330,form_id:'#addBranch'});	
			}else {
				$.post(base_url+"employee/_load_add_branch_form",{},
				function(o){
					$("#branch_wrapper_form").html(o);
					dialogGeneric("#branch_wrapper_form",{height:'auto',width:330,form_id:'#addBranch'});	
				});
			}
	}else {
		if(branch_id!='') {
			load_department_dropdown(branch_id);	
		}	
	}
}


function closeBranchPopUp(dialog_id) {
	closeDialog(dialog_id,'#addBranch');
}


function checkForAddDepartment()
{
	clear_form_error();
	
	department_id = $("#department_id").val();	
	branch_id = $("#branch_id").val();

	if(department_id=='add' && branch_id>0 ) {
			load_department_dropdown();
			branch_id = $("#branch_id").val();
			$.post(base_url+"employee/_load_add_department_form",{branch_id:branch_id},
				function(o){
					$("#department_wrapper_form").html(o);
					dialogGeneric("#department_wrapper_form",{height:'auto', width:330});		
				});
	}else {
	
		if(branch_id=='') {
			dialogOkBox('Please Choose a Branch First',{icon:'alert'});
		}	
	}
}

function closePositionPopUp(dialog_id) {
	closeDialog(dialog_id,'#addPosition');
}

function checkForAddPosition()
{
	clear_form_error();
	
	var position_id = $("#position_id").val();	

	if(position_id=='add') {
		load_position_dropdown();			
		if($("#position_wrapper_form").html()!='') {
				dialogGeneric("#position_wrapper_form",{height:'auto',width:'auto',form_id:'#addPosition'});	
		}else {
				$.post(base_url+"employee/_load_add_position_form",{},
				function(o){
					$("#position_wrapper_form").html(o);
					dialogGeneric("#position_wrapper_form",{height:'auto',width:'auto',title:'Add Position'});	
				});
		}
	}else {
		/*$("#status_dropdown_wrapper").html(loading_image+' loading...');
		$.post(base_url+'employee/_load_employee_status_dropdown',{position_id:position_id},
		function(o){
			$("#status_dropdown_wrapper").html(o);	
		});	*/
	}
}

function load_status_dropdown()
{
	var position_id = $("#position_id").val();
	$("#position_id_form").val(position_id);
	$("#status_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'employee/_load_employee_status_dropdown',{position_id:position_id},
	function(o){
		$("#status_dropdown_wrapper").html(o);
	});
}


function load_position_dropdown()
{
	$("#position_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'employee/_load_position_dropdown',{},
	function(o){
		$("#position_dropdown_wrapper").html(o);
	});
}

function checkForAddStatus() 
{
	clear_form_error();
	
	var employment_status_id = $("#employment_status_id").val();
	var position_id = $("#position_id").val();

	if(employment_status_id=='add') {
		load_status_dropdown();			
		if($("#status_wrapper_form").html()!='') {
				dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',form_id:'#addStatus'});	

		}else {
				
				$.post(base_url+"employee/_load_add_status_form",{position_id:position_id},
				function(o){
					$("#status_wrapper_form").html(o);
					dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',title:'Add Employment Status'});	
				});
		}
		
	
	}
}

function checkForAddJobStatus()
{
	clear_form_error();
	var employment_status_id = $("#employment_status_id").val();
	var position_id = $("#position_id").val();
	if(employment_status_id=='add') {
		load_status_dropdown();			
		if($("#status_wrapper_form").html()!='') {
				dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',form_id:'#addStatus'});	
		}else {
				$.post(base_url+"employee/_load_add_job_status_form",{position_id:position_id},
				function(o){
					$("#status_wrapper_form").html(o);
					dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',title:'Add Employment Status'});	
				});
		}
	}
}

function addEmployeeActionScripts() {
	$("#department_id").change(function(){
	    var did = $(this).val();
	    if(did == "add") {
	      	checkForAddDepartment();
	    }else{
	    	load_section_dropdown_by_department_id(did);
	    }
	});

	$("#section_id").change(function(){
	    var did = $("#department_id").val();
	    if($(this).val() == "add") {
	      	checkForAddSection(did);
	    }
	});	

}

function checkForAddSection()
{
	clear_form_error();
	
	section_id = $("#section_id").val();	
	department_id = $("#department_id").val();	
	branch_id = $("#branch_id").val();

	if(department_id !='add') {
			//load_department_dropdown();
			
			$.post(base_url+"employee/_load_add_section_form",{branch_id:branch_id,department_id:department_id},
				function(o){
					$("#department_wrapper_form").html(o);
					dialogGeneric("#department_wrapper_form",{height:'auto', width:330});		
				});
	}else {
	
		if(branch_id=='') {
			dialogOkBox('Please Choose a Branch First',{icon:'alert'});
		}	
	}
}

function load_section_dropdown_by_department_id(did) {
	$("#section_dropdown_wrapper").html(loading_image);
	$.post(base_url+"employee/_load_section_dropdown",{did:did},
	function(o){
		$("#section_dropdown_wrapper").html(o);
	});
}

function closeStatusPopUp(dialog_id)
{
	closeDialog(dialog_id,'#addStatus');
}


function load_employee_datatable(searched)
{
	element_id = 'employee_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var hash = oRecord.getData("hash");
				var has_permission = oRecord.getData("has_permission");
				var default_module = oRecord.getData("default_module");
				if(has_permission == 'true') {
					elCell.innerHTML = "<div id='dropholder'><a class='btn btn-mini' href='employee/profile?eid="+id+"&hash="+hash+"#"+default_module+"'><i class='icon-user'></i> Display Profile</a> <a class='btn btn-mini' href='javascript:void(0);' onclick='javascript:archiveEmployee(\"" + id + "\")'><i class='icon-trash'></i> Archive</a></div>"; 
				}else{
					elCell.innerHTML = "<div id='dropholder'></div>"; 
				}
		};
		
	var employee_code_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var employee_code = oRecord.getData("employee_code");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+employee_code+"</font>"; 	
				}else {
					elCell.innerHTML = employee_code; 	
				}
				
		};

	var section_name_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var section_name = oRecord.getData("section_name");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+section_name+"</font>"; 	
				}else {
					elCell.innerHTML = section_name; 	
				}
				
		};
		
	var employee_name_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var photo = oRecord.getData("photo");
				var hash = oRecord.getData("hash");
				var status = oRecord.getData("employment_status");
				var employee_name = oRecord.getData("employee_name");
				var has_permission = oRecord.getData("has_permission");
				var default_module = oRecord.getData("default_module");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					if(has_permission == 'true') {
						elCell.innerHTML = "<a class='dropbutton' href='employee/profile?eid="+id+"&hash="+hash+"#"+default_module+"'><font color='red'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</font></span></a>"; 	
					}else{
						elCell.innerHTML = "<font color='red'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</font></span>"; 	
					}
				}else {
					if(has_permission == 'true') {
						elCell.innerHTML = "<a class='dropbutton' href='employee/profile?eid="+id+"&hash="+hash+"#"+default_module+"'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</span></a>"; 	
					}else{
						elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</span>"; 	
					}
				}
				
		};
		
	var position_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var position = oRecord.getData("position");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+position+"</font>"; 	
				}else {
					elCell.innerHTML = position; 	
				}
				
		};

	
	var department_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var department = oRecord.getData("department");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+department+"</font>"; 	
				}else {
					elCell.innerHTML = department; 	
				}
				
		};

	var section_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status  = oRecord.getData("employment_status");
				var section = oRecord.getData("section_name");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+section+"</font>"; 	
				}else {
					elCell.innerHTML = section; 	
				}
				
		};
		
	var employment_status_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+status+"</font>"; 	
				}else {
					elCell.innerHTML = status; 	
				}
				
		};
		
	var branch_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var branch_name = oRecord.getData("branch_name");
				
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+branch_name+"</font>"; 	
				}else {
					elCell.innerHTML = branch_name; 	
				}
				
		};
		var columns = 	[
						 {key:"branch_name",label:"Branch",width:80,resizeable:true,sortable:true,formatter:branch_f},
						 {key:"department",label:"Department",width:100,resizeable:true,sortable:true, formatter:department_f},
						 {key:"section_name",label:"Section",width:100,resizeable:true,sortable:true, formatter:section_f},
						 {key:"employee_code",label:"Employee ID ",width:70,resizeable:true,sortable:true, formatter:employee_code_f},
						 {key:"employee_name",label:"Employee Name",width:180,resizeable:true,sortable:true,formatter:employee_name_f},
						 {key:"position",label:"Position",width:100,resizeable:true,sortable:true,formatter:position_f},
						 {key:"employment_status",label:"Status",width:80,resizeable:true,sortable:true,formatter:employment_status_f},
						  {key:"action",label:"Action",width:180,resizeable:true,sortable:true, formatter:action}
						 ];
		var fields =	['id','e_id','branch_name','photo','department','section_name','employee_code','employee_name','position','employment_status','hash','has_permission','default_module'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {
			var controller = 'employee/_json_encode_employee_list?search='+searched+'&';			
		}else {
			var controller = 'employee/_json_encode_employee_list?';			
		}
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(12);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();	
}

function load_view_all_employee_datatable(searched)
{
	addActiveState('btn_viewall','btn-small');
	
	element_id = 'employee_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var hash = oRecord.getData("hash");
	
				//elCell.innerHTML = "<div id='dropholder'><a class='btn btn-mini' href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'><i class='icon-user'></i> Display Profile</a> <a class='btn btn-mini' href='javascript:void(0);' onclick='javascript:archiveEmployee(\"" + id + "\")'><i class='icon-trash'></i> Archive</a></div>"; 
				//elCell.innerHTML = "<div id='dropholder'><a class='' id='btn_listview' title='View Profile' href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'><i class='icon-user'></i></a> <a class='' title='View Archive' href='javascript:void(0);' onclick='javascript:archiveEmployee(\"" + id + "\")'><i class='icon-trash'></i></a></div>";
		};
		
	var employee_code_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employee_status");
				var employee_code = oRecord.getData("employee_code");
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+employee_code+"</font>"; 	
				}else {
					elCell.innerHTML = employee_code; 	
				}
				
		};

/*	var section_name_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employee_status");
				var section_name = oRecord.getData("section_name");
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+section_name+"</font>"; 	
				}else {
					elCell.innerHTML = section_name; 	
				}
				
		};*/
		
	var employee_name_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var photo = oRecord.getData("photo");
				var hash = oRecord.getData("hash");
				var status = oRecord.getData("employee_status");
				var employee_name = oRecord.getData("employee_name");
				var has_permission = oRecord.getData("has_permission");
				var default_module = oRecord.getData("default_module");
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					if(has_permission == 'true') {
						elCell.innerHTML = "<a class='dropbutton' href='employee/profile?eid="+id+"&hash="+hash+"#"+default_module+"'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'><font color='red'>"+employee_name+"</font></span></a>"; 	
					}else{
						elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'><font color='red'>"+employee_name+"</font></span>"; 	
					}
					
				}else {
					if(has_permission == 'true') {
						elCell.innerHTML = "<a class='dropbutton' href='employee/profile?eid="+id+"&hash="+hash+"#"+default_module+"'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</span></a>"; 							
					}else{
						elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</span>"; 							
					}				
					
				}
				
		};
		
	var position_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employee_status");
				var position = oRecord.getData("position");
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+position+"</font>"; 	
				}else {
					elCell.innerHTML = position; 	
				}
				
		};

	
	var department_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employee_status");
				var department = oRecord.getData("department");
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+department+"</font>"; 	
				}else {
					elCell.innerHTML = department; 	
				}
				
		};

	var section_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status  = oRecord.getData("employee_status");
				var section = oRecord.getData("section_name");
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+section+"</font>"; 	
				}else {
					elCell.innerHTML = section; 	
				}
				
		};
		
	var employee_status_f = function(elCell, oRecord, oColumn, oData) {
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employee_status");
				
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+status+"</font>"; 	
				}else {
					elCell.innerHTML = status; 	
				}
				
		};
		
	var employment_status_f = function(elCell, oRecord, oColumn, oData) {
				var id = oRecord.getData("e_id");
				var stat = oRecord.getData("employee_status");
				var status = oRecord.getData("employment_status");
				
				if(stat=='TERMINATED' || stat=='End of Contract' || stat=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+status+"</font>"; 	
				}else {
					elCell.innerHTML = status; 	
				}
				
		};
		
	var branch_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employee_status");
				var branch_name = oRecord.getData("branch_name");
				
				if(status=='TERMINATED' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+branch_name+"</font>"; 	
				}else {
					elCell.innerHTML = branch_name; 	
				}
				
		};
		var columns = 	[
						 {key:"branch_name",label:"Branch",width:55,resizeable:true,sortable:true,formatter:branch_f},
						 {key:"department",label:"Department",width:80,resizeable:true,sortable:true, formatter:department_f},
						 {key:"section_name",label:"Section",width:80,resizeable:true,sortable:true, formatter:section_f},
						 {key:"employee_code",label:"Employee ID ",width:80,resizeable:true,sortable:true, formatter:employee_code_f},
						 {key:"employee_name",label:"Employee Name",width:160,resizeable:true,sortable:true,formatter:employee_name_f},
						 {key:"position",label:"Position",width:105,resizeable:true,sortable:true,formatter:position_f},
						 {key:"employee_status",label:"Employee Status",width:100,resizeable:true,sortable:true,formatter:employee_status_f},
						 {key:"employment_status",label:"Employment Status",width:120,resizeable:true,sortable:true,formatter:employment_status_f}
						  //{key:"action",label:"Action",width:50,resizeable:true,sortable:true, formatter:action}
						 ];
		var fields =	['id','e_id','branch_name','photo','department','section_name','employee_code','employee_name','position','employee_status','employment_status','hash','has_permission','default_module'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {
			var controller = 'employee/_json_encode_view_all_employee_list?search='+searched+'&';			
		}else {
			var controller = 'employee/_json_encode_view_all_employee_list?';			
		}
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(12);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();	
		load_total_records();
}

function addActiveState(obj_id,class_name)
{
	$('.' + class_name).removeClass('active');
	$("#" + obj_id).addClass("active");
}

function load_view_all_archive_employee_datatable(searched)
{
	//Add Active state
	addActiveState('btn_viewallarchives','btn-small');
	//
	element_id = 'employee_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var hash = oRecord.getData("hash");
	
				//elCell.innerHTML = "<div id='dropholder'><a class='btn btn-mini' href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'><i class='icon-user'></i> Display Profile</a> <a class='btn btn-mini' href='javascript:void(0);' onclick='javascript:restoreEmployee(\"" + id + "\")'><i class='icon-refresh'></i> Restore</a></div>"; 
				elCell.innerHTML = "<div id='dropholder'><a class='' title='View Profile' href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'><i class='icon-user'></i></a> <a class='' title='Restore' href='javascript:void(0);' onclick='javascript:restoreEmployee(\"" + id + "\")'><i class='icon-refresh'></i></a> <a class='' title='Permanent Delete' href='javascript:void(0);' onclick='javascript:deleteEmployee(\"" + id + "\")'><i class='icon-trash'></i></a></div>"; 
		};
		
	var employee_code_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var employee_code = oRecord.getData("employee_code");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+employee_code+"</font>"; 	
				}else {
					elCell.innerHTML = employee_code; 	
				}
				
		};
		
	var employee_name_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var photo = oRecord.getData("photo");
				var hash = oRecord.getData("hash");
				var status = oRecord.getData("employment_status");
				var employee_name = oRecord.getData("employee_name");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<a class='dropbutton' href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_view'>"+employee_name+"</span></font></a>"; 	
				}else {
					elCell.innerHTML = "<a class='dropbutton' href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_view'>"+employee_name+"</span></a>"; 	
				}
				
		};
		
	var position_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var position = oRecord.getData("position");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+position+"</font>"; 	
				}else {
					elCell.innerHTML = position; 	
				}
				
		};

	
	var department_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var department = oRecord.getData("department");
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+department+"</font>"; 	
				}else {
					elCell.innerHTML = department; 	
				}
				
		};
		
	var employment_status_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+status+"</font>"; 	
				}else {
					elCell.innerHTML = status; 	
				}
				
		};
		
	var branch_f = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var status = oRecord.getData("employment_status");
				var branch_name = oRecord.getData("branch_name");
				
				if(status=='Terminated' || status=='End of Contract' || status=='AWOL') {
					elCell.innerHTML = "<font color='red'>"+branch_name+"</font>"; 	
				}else {
					elCell.innerHTML = branch_name; 	
				}
				
		};
		var columns = 	[
						 {key:"branch_name",label:"Branch",width:80,resizeable:true,sortable:true,formatter:branch_f},
						 {key:"department",label:"Department",width:120,resizeable:true,sortable:true, formatter:department_f},
						 {key:"employee_code",label:"Employee ID ",width:70,resizeable:true,sortable:true, formatter:employee_code_f},
						 {key:"employee_name",label:"Employee Name",width:220,resizeable:true,sortable:true,formatter:employee_name_f},
						 {key:"position",label:"Position",width:120,resizeable:true,sortable:true,formatter:position_f},
						 {key:"employment_status",label:"Status",width:80,resizeable:true,sortable:true,formatter:employment_status_f},
						  {key:"action",label:"Action",width:50,resizeable:true,sortable:true, formatter:action}
						 ];
		var fields =	['id','e_id','branch_name','photo','department','employee_code','employee_name','position','employment_status','hash'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {
			var controller = 'employee/_json_encode_view_all_archive_employee_list?search='+searched+'&';			
		}else {
			var controller = 'employee/_json_encode_view_all_archive_employee_list?';			
		}
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(12);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
		
		load_total_records_is_archive();	
}

function searchEmployee() {
	var searched = $("#search").val();
	load_employee_datatable(searched);
	load_total_search(searched);
}

function load_total_search(searched) {
	$.post(base_url+'employee/_get_total_result',{searched:searched},
	function(o){
		$("#total_result_wrapper").html('0');
		$("#total_result_wrapper").html(o);	
	});	
}

function load_total_records() {
	$.get(base_url+'employee/_get_total_records',{},
	function(o){
		$("#total_result_wrapper").html('0');
		$("#total_result_wrapper").html(o);	
	});	
}

function load_total_records_is_archive() {
	$.get(base_url+'employee/_get_total_records_is_archive',{},
	function(o){
		$("#total_result_wrapper").html('0');
		$("#total_result_wrapper").html(o);	
	});	
}

function load_add_employee()
{
	$("#employee_form_wrapper").show();
	//$("#employee_form_wrapper").slideDown();
	$("#add_employee_button_wrapper").hide();
}





function cancel_add_employee_form()
{
	clear_form_error();
	
	//$("#employee_form_wrapper").slideUp();
	$("#employee_form_wrapper").hide();
	$("#add_employee_button_wrapper").show();
}

function load_add_employee_confirmation(employee_id)
{
	var employee_id = employee_id;
	$("#confirmation").html(loading_message);
	$.post(base_url + 'employee/_load_add_employee_confirmation',{},function(o) {
		$("#confirmation").html(o)								 
	})
	
	hash = $("#employee_hash").val();
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 'auto',
				height: 'auto',				
				resizable: false,
				modal:true,
                buttons: {
					'Go to Employee Profile' : function(){						
							window.location = "employee/profile?eid="+employee_id+"&hash="+hash;						
                    },
					'Add Another Employee' : function(){						
							load_employee_datatable();
							window.location = "employee?add_employee=true";
                    },
					'View Employee List' : function(){
							load_employee_datatable();					
						  	disablePopUp();
						  	$dialog.dialog("close");
							window.location = "employee";
                    },
					'Go To Schedule' : function(){
							load_employee_datatable();					
						  	disablePopUp();
						  	$dialog.dialog("close");
							window.location = "schedule/show_employee_schedule?eid="+employee_id+"&hash="+hash;		
                    }
                }
            }).show();
}

function load_advance_search()
{
	$("#search_wrapper").hide();
	$("#advance_search_wrapper").slideDown();
	$("#advance_search_wrapper").show();
	
}

function load_search()
{
	$("#advance_search_wrapper").hide();
	$("#search_wrapper").slideDown();
	$("#search_wrapper").show();
	
}

function loadDepartment()
{
	branch_id = $("#branch").val();
	
	$.post(base_url+'employee/_load_department',{branch_id:branch_id},
	function(o){
		$("#department").html(o);
	});	
}

function loadSection()
{
	branch_id = $("#branch").val();
	
	$.post(base_url+'employee/_load_department',{branch_id:branch_id},
	function(o){
		$("#section").html(o);
	});	
}


function importEmployee()
{

	 var $dialog = $("#import_employee_wrapper");
		$dialog.dialog({
                title: 'Import Employee',
                width: 350,
				height: 'auto',				
				resizable: false,
				modal:true
            }).show();
}

function importEmployeeTraining()
{
	 var $dialog = $("#import_employee_training_wrapper");
		$dialog.dialog({
                title: 'Import Training',
                width: 350,
				height: 'auto',				
				resizable: false,
				modal:true
            }).show();	
}

function importEmployeeSalary()
{

	 var $dialog = $("#import_employee_salary_wrapper");
		$dialog.dialog({
                title: 'Import Employee Salary',
                width: 350,
				height: 'auto',				
				resizable: false,
				modal:true
            }).show();
}

function closeImportDialog()
{
	$("#import_employee_wrapper").dialog('destroy');
	$("#import_employee_salary_wrapper").dialog('destroy');
	$("#import_employee_training_wrapper").dialog('destroy');	
}


//for quick dynamic search
function loadCategory()
{
	$.fn.setCursorToTextEnd = function() {
        $initialVal = this.val();
        this.val($initialVal + ' ');
        this.val($initialVal);
    };

	if($("#category").val()=='Hired Date:' || $("#category").val()=='Terminated Date:' || $("#category").val()=='End of Contract:' || $("#category").val()=='Birthdate:') {
		$("#datepicker").show();
	} else {
		$("#datepicker").hide();
	}
	
	if($("#category").val()=='Department:'  ) {
		$("#department_option").show();
	}else {
		$("#department_option").hide();
	}

	if($("#category").val()=='Section:'  ) {
		$("#section_option").show();
	}else {
		$("#section_option").hide();
	}
	
	
	if($("#category").val()=='Position:'  ) {
		$("#position_option").show();
	}else {
		$("#position_option").hide();
	}
	
	if($("#category").val()=='Employment Status:'  ) {
		$("#employment_status_option").show();
	}else {
		$("#employment_status_option").hide();
	}
	
	if($("#search").val()=='') {
		$("#search").val($("#search").val()+$("#category").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	}else {
		$("#search").val($("#search").val()+","+$("#category").val());		
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	}
	$("#category")[0].selectedIndex = 0;
	
}

function loadDepartment() {
	
		$("#search").val($("#search").val()+$("#department").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#department_option").hide();
}

function loadSection() {
	
		$("#search").val($("#search").val()+$("#section").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#section_option").hide();
}

function loadPosition() {
	
		$("#search").val($("#search").val()+$("#position").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#position_option").hide();
}

function loadEmploymentStatus() {
	
		$("#search").val($("#search").val()+$("#employment_status").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#employment_status_option").hide();
}


function loadImageView()
{
	addActiveState('btn_imageview','btn-small');
	$(".images_wrapper").show();	
}

function loadListView()
{
	addActiveState('btn_listview','btn-small');
	$(".images_wrapper").hide();
}

function load_add_account()
{
	$("#account_form_wrapper").show();

	$("#add_account_button_wrapper").hide();
}

function cancel_add_account_form()
{
	$("#employee_account_form").validationEngine('hide');  	
	$("#account_form_wrapper").hide();
	$("#add_account_button_wrapper").show();
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

function updateModule(mod)
{
	module='';
	if($('#module_hr').attr('checked')) {
		module+= 'hr,';
	}
	
	if($('#module_payroll').attr('checked')) {
		module+= 'payroll,';
	}
	
	if($('#module_clerk').attr('checked')) {
		module+= 'clerk,';
	}
	
	if($('#module_employee').attr('checked')) {
		module+= 'employee';
	}
	$("#module").val(module);
}

function load_employee_account_datatable(searched)
{
	element_id = 'account_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
				var hash = oRecord.getData("hash");
	
				elCell.innerHTML = "<div id='dropholder'><a class='btn btn-mini' href='javascript:void(0);' onClick='javascript:loadAccount("+id+")'><i class='icon-edit'></i> Update</a>&nbsp;<a class='btn btn-mini' href='javascript:void(0);' onClick='javascript:deleteEmployeeAccount("+id+")'><i class='icon-trash'></i> Delete</a></div>"; 
		};

	var columns = 	[
					 {key:"employee_name",label:"User",width:150,resizeable:true,sortable:true},
					 {key:"module",label:"User Type",width:180,resizeable:true,sortable:true},
					 {key:"date_entered",label:"Registration Date",width:120,resizeable:true,sortable:true},
					 {key:"employment_status",label:"Status",width:120,resizeable:true,sortable:true},
					 {key:"username",label:"Username",width:120,resizeable:true,sortable:true},
					  {key:"action",label:"Action",width:160,resizeable:true,sortable:true,formatter:action}
					 ];
		var fields =	['id','employee_name','module','date_entered','employment_status','username'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {
			var controller = 'employee/_json_encode_employee_account_list?search='+searched+'&';			
		}else {
			var controller = 'employee/_json_encode_employee_account_list?';			
		}
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(12);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();	
}

function loadAccount(user_id)
{
	
	$("#account_edit_form_wrapper").show();
	$.post(base_url+'employee/_load_edit_account',{user_id:user_id},
	function(o){
		$("#account_edit_form_wrapper").html(o);
		$("#account_list_wrapper").hide();
	});
	
	
}

function cancel_edit_account_form()
{
	$("#update_account_form").validationEngine('hide');  
	$("#account_edit_form_wrapper").hide();
	$("#account_list_wrapper").show();
}

function checkUsername()
{
	$("#username_checker").html('checking...');
	var username = $("#username_update").val();
	var user_id = $("#user_id").val();
	$.post(base_url+'employee/_check_username',{username:username,user_id:user_id},
	function(o){
		$("#username_checker").html(o);
	});
}

function importAccount() {
	_importAccount({
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},
		onImported: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			load_employee_account_datatable();
			dialogOkBox(o.message,{});
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:'auto', message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});			
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();
			dialogOkBox(o.message,{});
		}
	});
}

function _importAccount(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Account';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'employee/ajax_import_account', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});	
		
		$('#import_account_form').validationEngine({scroll:false});		
		$('#import_account_form').ajaxForm({
			success:function(o) {
				if (o.is_imported) {
					if (typeof events.onImported == "function") {
						events.onImported(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				if ($('#earning_file').val() == '') {					
					return false;	
				}
				if (typeof events.onImporting == "function") {
					events.onImporting();
				}				
				return true;
			}
		});					
	});
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function deleteEmployeeAccount(id) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>delete</b> the selected account?';
	
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
				$.post(base_url+'employee/_delete_employee_account',{id:id},
					function(o){													
					if(o.is_success==1) {	
						load_employee_account_datatable();	
						dialogOkBox(o.message,{});				
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

function restoreEmployee(eid) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>restore</b> the selected archived employee?';
	
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
				$.post(base_url+'employee/_restore_employee',{eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_view_all_archive_employee_datatable('nothing');
						dialogOkBox(o.message,{});				
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

function archiveEmployee(eid) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>archive</b> the selected employee?';
	
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
				$.post(base_url+'employee/_archive_employee',{eid:eid},
					function(o){													
					if(o.is_success==1) {	
						//load_employee_datatable('nothing');
						load_view_all_employee_datatable('nothing');
						dialogOkBox(o.message,{});				
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

function deleteEmployee(eid)
{
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to <b>permanently delete</b> the selected employee?';
	
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
				$.post(base_url+'employee/_permanent_delete_employee',{eid:eid},
					function(o){													
					if(o.is_success==1) {	
						load_view_all_archive_employee_datatable('nothing');
						dialogOkBox(o.message,{});				
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

function showMemoPTemplate(memo_id) {
	var memo_id = memo_id;
	$.post(base_url + 'employee/_get_p_memo_content',{memo_id:memo_id},function(o) {
		$("#view_dialog_wrapper_p").html(o);								 
	})
	
	var $dialog = $('#view_dialog_wrapper_p');
	$dialog.dialog("destroy");
	 
	var $dialog = $('#view_dialog_wrapper_p');
	$dialog.dialog({
		title: 'View Memo Template',
		resizable: false,
		position: [330,100],
		width: 650,
		modal: false,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");						   			
				}	
		}		
		).show();		
}

function showMemoTemplate(memo_id) {
	var memo_id = memo_id;
	$.post(base_url + 'employee/_get_memo_content',{memo_id:memo_id},function(o) {
		$("#view_dialog_wrapper").html(o);								 
	})
	
	var $dialog = $('#view_dialog_wrapper');
	$dialog.dialog("destroy");
	 
	var $dialog = $('#view_dialog_wrapper');
	$dialog.dialog({
		title: 'View Memo Template',
		resizable: false,
		position: [330,100],
		width: 650,
		modal: false,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");						   			
				}	
		}		
		).show();	
}


// alex -evaluation page js

function load_view_all_employee_eval_datatable()
{ 

	$.get(base_url + 'evaluation/_load_employee_evaluation_list_dt',{},function(o) {
		$('#employee_datatable_wrapper').html(o);		
	});



}



function load_add_employee_eval() {
	_createEvaluation({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			
			load_view_all_employee_eval_datatable();

			showOkDialog(o.message);

			
			//$('#employee_eval_datatable').dataTable().ajax.reload();
			//alert(o);			
			
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			showLoadingDialog('Loading...');
		},

		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}



function _createEvaluation(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Employee Evaluation';
	var width = 530;
	var height = 'auto';

	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'evaluation/ajax_add_evaluation', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_evaluation_form'
		});

		$('#add_evaluation_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function(events) {

				var date = document.getElementById("evaldate").value;
				var date2 = document.getElementById("nextevaldate").value;
				var eDate = new Date(date); //dd-mm-YYYY
				var nDate = new Date(date2); 

				if(eDate >= nDate){
					alert('next evaluation date must be greater than evaluation date');
					return false;
				}
				else{

					if (typeof events.onSaving == "function") {
					events.onSaving();
					}
					return true;
				}
				
			}
		});
	});
}

function viewEvaluation(evid,events){

	var evid = evid;
	//window.location = base_url+"evaluation/history?evid="+id;


	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Employee Evaluation History';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	

	$.get(base_url + 'evaluation/history', {evid:evid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});		
	});



}

function editEvaluation(evid, events){

	_editEvaluation(evid, {
			onSaved: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				
				load_view_all_employee_eval_datatable();

				showOkDialog(o.message);

				
				//$('#employee_eval_datatable').dataTable().ajax.reload();
				//alert(o);			
				
			},
			onSaving: function() {
				showLoadingDialog('Saving...');
			},
			onLoading: function() {
				$('#message_container').hide();
				showLoadingDialog('Loading...');
			},

			onError: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				showOkDialog(o.message);
			}
		});	

}



function _editEvaluation(evid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Employee Evaluation';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'evaluation/ajax_edit_employee_evaluation', {evid:evid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_evaluation_form'
		});

		$('#edit_evaluation_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function(events) {
				
				var date = document.getElementById("evaldate").value;
				var date2 = document.getElementById("nextevaldate").value;
				var eDate = new Date(date); //dd-mm-YYYY
				var nDate = new Date(date2); 

				if(eDate >= nDate){
					alert('next evaluation date must be greater than evaluation date');
					return false;
				}
				else{

					if (typeof events.onSaving == "function") {
					events.onSaving();
					}
					return true;
				}
			}
			});			
		
		});	
}



function archiveEvaluation(e_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to archive the selected entry?';
	
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
				$.post(base_url+'evaluation/archive_evaluation',{e_id:e_id},
					function(o){													
					if(o.is_success == 1) {														
						load_view_all_employee_eval_datatable();

						if (typeof events.onYes == "function") {
							events.onYes();
						}
						
					}else {
						alert("Invalid SQL");	
						$dialog.dialog("close");								
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



function searchEmployee2() {
	var searched = $("#search").val();
	load_view_all_employee_eval_datatable2(searched);
	//load_total_search(searched);
}


function load_view_all_employee_eval_datatable2(searched)
{ 

	$.get(base_url + 'evaluation/_load_employee_evaluation_search_list_dt',{searched:searched},function(o) {
		$('#employee_datatable_wrapper').html(o);		
	});



}




function importEmployeeEvaluation()
{

	 var $dialog = $("#import_employee_wrapper");
		$dialog.dialog({
                title: 'Import Employee Evaluation',
                width: 350,
				height: 'auto',				
				resizable: false,
				modal:true
            }).show();
}


function getEvalHistory(eid, date)
{ 


	$.get(base_url + 'evaluation/_load_employee_evaluation_history_list_dt',{eid:eid,date:date},function(o) {
		$('#employee_evaluation_history_list_wrapper').html(o);		
	});



}



