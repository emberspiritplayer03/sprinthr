//company info
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
	$.get(base_url + 'startup/_load_company_info',{},
		function(o){			
			$('#c-info').html(o);		
		});		
}

function load_edit_company_info() {
	$("#company_profile_form").html(loading_image);
	$.get(base_url + 'startup/_load_edit_company_info',{},function(o) {
		$("#company_profile_form").html(o);							 
	})
	
	var $dialog = $('#company_profile_form');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#company_profile_form');
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
//end of company info

//Department

function load_company_structure() {
	$('#c-department').html(loading_image);	
	$.get(base_url + 'startup/_load_department_startup',{},
		function(o){			
			$('#c-department').html(o);		
		});		
}

//end of Department

//Employee 
function load_employee() {
	$('#c-employee').html(loading_image);	
	$.get(base_url + 'startup/_load_employee',{},
		function(o){			
			$('#c-employee').html(o);		
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


function load_edit_employee()
{
	$("#editemployeesummary_details_startup").show();
	//$("#employee_form_wrapper").slideDown();
	$("#employee_view_startup").hide();
}

function cancel_edit_employee_form()
{
	clear_form_error();
	//$("#employee_form_wrapper").slideUp();
	$("#editemployeesummary_details_startup").hide();
	$("#employee_view_startup").show();
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

function closePhotoDialog()
{
	closeDialog("#photo_wrapper",'');
}

function closeImportDialog()
{
	$("#import_employee_wrapper").dialog('destroy');
}

function checkForAddBranch() 
{
	clear_form_error();

	branch_id = $("#branch_id").val();	
	if(branch_id=='add') {
			load_branch_dropdown();
			//if($("#branch_wrapper_form").html()!='') {
			//	dialogGeneric("#branch_wrapper_form",{height:'auto',width:330,form_id:'#addBranch'});	
//			}else {
				$.get(base_url+"startup/_load_add_branch_form",{},
				function(o){
					$("#branch_wrapper_form").html(o);
					dialogGeneric("#branch_wrapper_form",{height:'auto',width:330,form_id:'#addBranch'});	
				});
			//}
	}else {
		if(branch_id!='') {
			load_department_dropdown(branch_id);	
		}	
	}
}

function checkForAddDepartment()
{
	clear_form_error();
	
	department_id = $("#department_id").val();	
	branch_id = $("#branch_id").val();

	if(department_id=='add' && branch_id>0 ) {
			load_department_dropdown();
			branch_id = $("#branch_id").val();
			$.post(base_url+"startup/_load_add_department_form",{branch_id:branch_id},
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

function load_branch_dropdown() 
{
	$("#branch_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_branch_dropdown',{branch_id:branch_id},
	function(o){
		$("#branch_dropdown_wrapper").html(o);
	});
	
}

function load_department_dropdown()
{
	branch_id = $("#branch_id").val();
	$("#department_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_department_dropdown',{branch_id:branch_id},
	function(o){
		$("#department_dropdown_wrapper").html(o);
	});
}


function checkForAddBranchDepartmentStartup(add) 
{
	clear_form_error();

	branch_id = $("#branch_id_startup").val();	
	if(add=='add') {
			$.get(base_url+"startup/_load_add_branch_form_startup",{},
			function(o){
				$("#branch_wrapper_form_department_startup").html(o);
   dialogGeneric("#branch_wrapper_form_department_startup",{height:'auto',width:330,form_id:'#addBranch', title:'Add Branch'});	
 			});
			load_branch_dropdown_startup();
	}else {
		if(branch_id!='') {
			//load_department_dropdown_startup(branch_id);	
			<!-- need to create one of this for separate -->
		}	
	}
}

function checkForAddDepartmentStartup(add)
{
	clear_form_error();
	
	department_id = $("#department_id_startup").val();	
	branch_id = $("#branch_id_startup").val();

	if(add=='add' && branch_id>0 ) {
			//load_department_dropdown_startup();
			//load_department_startup_dt();
			branch_id = $("#branch_id_startup").val();
			$.post(base_url+"startup/_load_add_department_form_startup",{branch_id:branch_id},
				function(o){
					$("#department_wrapper_form_startup").html(o);
					dialogGeneric("#department_wrapper_form_startup",{height:'auto', width:330, title:'Add Department'});		
				});
	}else {
	
		if(branch_id=='') {
			dialogOkBox('Please Choose a Branch First',{icon:'alert'});
		}	
	}
}


function load_branch_dropdown_startup() 
{
	$("#branch_dropdown_wrapper_startup").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_branch_dropdown_startup',{branch_id:branch_id},
	function(o){
		$("#branch_dropdown_wrapper_startup").html(o);
	});
}

function load_department_dropdown_startup()
{
	branch_id = $("#branch_id_startup").val();
	$("#department_dropdown_wrapper_startup").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_department_dropdown_startup',{branch_id:branch_id},
	function(o){
		$("#department_dropdown_wrapper_startup").html(o);
	});
}

function load_department_startup_dt()
{
	branch_id = $("#branch_id_startup").val();
	$("#c-depatment_dt").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_department_startup_dt',{branch_id:branch_id},
	function(o){
		$("#c-depatment_dt").html(o);
	});
}

function closeBranchPopUp(dialog_id) {
	closeDialog(dialog_id,'#addBranchStartup'); 
}

function closeDepartmentPopUp(dialog_id) {
	closeDialog(dialog_id,'#addDepartmentFormStartup'); 
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
				$.post(base_url+"startup/_load_add_position_form",{},
				function(o){
					$("#position_wrapper_form").html(o);
					dialogGeneric("#position_wrapper_form",{height:'auto',width:'auto',title:'Add Position'});	
				});
		}
	}else {
		$("#status_dropdown_wrapper").html(loading_image+' loading...');
		$.post(base_url+'startup/_load_employee_status_dropdown',{position_id:position_id},
		function(o){
			$("#status_dropdown_wrapper").html(o);	
		});	
	}
}

function load_position_dropdown()
{
	$("#position_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_position_dropdown',{},
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
				
				$.post(base_url+"startup/_load_add_status_form",{position_id:position_id},
				function(o){
					$("#status_wrapper_form").html(o);
					dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',title:'Add Employment Status'});	
				});
		}
		
	
	}
}

function load_status_dropdown()
{
	var position_id = $("#position_id").val();
	$("#position_id_form").val(position_id);
	$("#status_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'startup/_load_employee_status_dropdown',{position_id:position_id},
	function(o){
		$("#status_dropdown_wrapper").html(o);
	});
}

function closeStatusPopUp(dialog_id)
{
	closeDialog(dialog_id,'#addStatus');
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
	}
	
	if($("#category").val()=='Department:'  ) {
		$("#department_option").show();
	}else {
		$("#department_option").hide();
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

function searchEmployee() {
	var searched = $("#search").val();
	load_employee_datatable(searched);
	load_total_search(searched);
}

function load_total_search(searched) {
	$.post(base_url+'startup/_get_total_result',{searched:searched},
	function(o){
		$("#total_result_wrapper").html(o);	
	});	
}

function loadPhotoDialog()
{
	var employee_id = $("#employee_id").val();
	$("#photo_wrapper").html(loading_message);
	dialogGeneric('#photo_wrapper',{title:'Photo'});
	$.post(base_url+'startup/_load_photo',{employee_id:employee_id},
	function(o){
		$("#photo_wrapper").html(o);
		dialogGeneric('#photo_wrapper',{title:'Photo',height:'auto'});
		
	});	
}

function loadPhoto()
{
	var employee_id = $("#employee_id").val();
	
	$("#photo_frame_wrapper").html('');
	$("#photo_frame_wrapper").html(loading_image);
	$("#photo_frame_personal_wrapper").html('loading');
	$.post(base_url+'startup/_load_photo_frame',{employee_id:employee_id},
	function(o){
		
		$("#photo_frame_wrapper").html(o);
		$("#photo_frame_personal_wrapper").html(o);
		$("#photo_frame_personal_edit_wrapper").html(o);		
	});

	$.post(base_url+'startup/_get_photo_filename',{employee_id:employee_id},function(o){
		$("#photo_filename_wrapper").html(o);
		$("#photo").val($("#photo_filename_text").val());
	});
}

function hashClick(hash) {	
	var hash = hash;
	loadPage(hash); 
	
    $(".left_nav").removeClass("selected");
    $(hash+"_nav").addClass("selected");  
	
}

function loadPage(hash) 
{
	var employee_id = $("#employee_id").val();
	
	hide_all_canvass();
	clearInlineValidationForm();
	loadPhoto();
	
	$("#employee_summary_wrapper").show();
	
	if(hash=='#personal_details' || hash=='') {	
		$("#employee_summary_wrapper").hide();
		displayPage({canvass:'#personal_details_wrapper',parameter:'startup/_load_personal_details?employee_id='+employee_id});
	}else if(hash=='#contact_details') {
		displayPage({canvass:'#contact_details_wrapper',parameter:'employee/_load_contact_details?employee_id='+employee_id});
	}else if(hash=='#emergency_contacts') {
		displayPage({canvass:'#emergency_contacts_wrapper',parameter:'employee/_load_emergency_contact?employee_id='+employee_id});
	}else if(hash=='#dependents') {
		displayPage({canvass:'#dependents_wrapper',parameter:'employee/_load_dependents?employee_id='+employee_id});
	}else if(hash=='#bank') {
		displayPage({canvass:'#banks_wrapper',parameter:'employee/_load_bank?employee_id='+employee_id});
	}else if(hash=='#medical_history') {
		displayPage({canvass:'#medical_history_wrapper',parameter:'employee/_load_medical_history?employee_id='+employee_id});
	}else if(hash=='#duration'){
		displayPage({canvass:'#duration_wrapper',parameter:'employee/_load_duration?employee_id='+employee_id});
	}else if(hash=='#employment_status') {
		displayPage({canvass:'#employment_status_wrapper',parameter:'employee/_load_employment_status?employee_id='+employee_id});
		displayPage({canvass:'#job_history_wrapper',parameter:'employee/_load_job_history?employee_id='+employee_id});
		displayPage({canvass:'#subdivision_history_wrapper',parameter:'employee/_load_subdivision_history?employee_id='+employee_id});
	}else if(hash=='#compensation') {
		displayPage({canvass:'#compensation_wrapper',parameter:'employee/_load_compensation?employee_id='+employee_id});
		displayPage({canvass:'#compensation_history_wrapper',parameter:'employee/_load_compensation_history?employee_id='+employee_id});
	}else if(hash=='#performance') {
		displayPage({canvass:'#performance_wrapper',parameter:'employee/_load_performance?employee_id='+employee_id});
	}else if(hash=='#memo_notes') {
		displayPage({canvass:'#memo_notes_wrapper',parameter:'employee/_load_memo_notes?employee_id='+employee_id});
	}else if(hash=='#supervisor') {
		displayPage({canvass:'#supervisor_wrapper',parameter:'employee/_load_supervisor?employee_id='+employee_id});
	}else if(hash=='#leave') {
		displayPage({canvass:'#leave_wrapper',parameter:'employee/_load_leave?employee_id='+employee_id});
	}else if(hash=='#deductions') {
		displayPage({canvass:'#deductions_wrapper',parameter:'employee/_load_deductions?employee_id='+employee_id});
	}else if(hash=='#membership') {
		displayPage({canvass:'#membership_wrapper',parameter:'employee/_load_membership?employee_id='+employee_id});
	}else if(hash=='#work_experience') {
		displayPage({canvass:'#work_experience_wrapper',parameter:'employee/_load_work_experience?employee_id='+employee_id});
	}else if(hash=='#education') {
		displayPage({canvass:'#education_wrapper',parameter:'employee/_load_education?employee_id='+employee_id});
	}else if(hash=='#skills') {
		displayPage({canvass:'#skills_wrapper',parameter:'employee/_load_skills?employee_id='+employee_id});
	}else if(hash=='#language') {
		displayPage({canvass:'#language_wrapper',parameter:'employee/_load_language?employee_id='+employee_id});
	}else if(hash=='#license') {
		displayPage({canvass:'#license_wrapper',parameter:'employee/_load_license?employee_id='+employee_id});
	}else if(hash=='#work_schedule') {
		displayPage({canvass:'#work_schedule_wrapper',parameter:'employee/_load_work_schedule?employee_id='+employee_id});
	}else if(hash=='#attachment') {
		displayPage({canvass:'#attachment_wrapper',parameter:'employee/_load_attachment?employee_id='+employee_id});
	}else if(hash=='#requirements') {
		displayPage({canvass:'#requirements_wrapper',parameter:'employee/_load_requirements?employee_id='+employee_id});
	}else if(hash=='#contribution') {
		displayPage({canvass:'#contribution_wrapper',parameter:'employee/_load_contribution?employee_id='+employee_id});
	}else if(hash=='#training') {
		displayPage({canvass:'#training_wrapper',parameter:'employee/_load_training?employee_id='+employee_id});
	}	
}

function clearDependentInlineErrorForm()
{
	$("#dependent_edit_form").validationEngine('hide');
	$("#dependent_add_form").validationEngine('hide');	
}

function clearEmergencyContactInlineErrorForm()
{
	$("#emergency_contacts_edit_form").validationEngine('hide');
	$("#emergency_contacts_add_form").validationEngine('hide');	
}

function clearPersonalDetailsInlineErrorForm()
{
	$("#personal_details_form").validationEngine('hide');
} 

function clearContactDetailsInlineErrorForm()
{
	$("#contact_details_form").validationEngine('hide');
}

function hide_all_canvass() {

	$("#personal_details_wrapper").hide();
	$("#contact_details_wrapper").hide();
	$("#emergency_contacts_wrapper").hide();
	$("#dependents_wrapper").hide();
	$("#banks_wrapper").hide();
	$("#medical_history_wrapper").hide();
	$("#employment_status_wrapper").hide();
	$("#compensation_wrapper").hide();
	$("#performance_wrapper").hide();
	$("#training_wrapper").hide();
	$("#memo_notes_wrapper").hide();
	$("#supervisor_wrapper").hide();
	$("#leave_wrapper").hide();
	$("#membership_wrapper").hide();
	$("#work_experience_wrapper").hide();
	$("#education_wrapper").hide();
	$("#skills_wrapper").hide();
	$("#language_wrapper").hide();
	$("#license_wrapper").hide();
	$("#work_schedule_wrapper").hide();
	$("#requirements_wrapper").hide();
	$("#attachment_wrapper").hide();
	$("#contribution_wrapper").hide();
	$("#duration_wrapper").hide();
	$("#job_history_wrapper").hide();
	$("#compensation_history_wrapper").hide();
	$("#subdivision_history_wrapper").hide();
}

function clear_all_canvass() {

	$("#personal_details_wrapper").html('');
	$("#contact_details_wrapper").html('');
	$("#emergency_contacts_wrapper").html('');
	$("#dependents_wrapper").html('');
	$("#banks_wrapper").html('');
	$("#medical_history_wrapper").html('');
	$("#employment_status_wrapper").html('');
	$("#compensation_wrapper").html('');
	$("#performance_wrapper").html('');
	$("#training_wrapper").html('');
	$("#memo_notes_wrapper").html('');
	$("#supervisor_wrapper").html('');
	$("#leave_wrapper").html('');
	$("#membership_wrapper").html('');
	$("#work_experience_wrapper").html('');
	$("#education_wrapper").html('');
	$("#skills_wrapper").html('');
	$("#language_wrapper").html('');
	$("#license_wrapper").html('');
	$("#work_schedule_wrapper").html('');
	$("#requirements_wrapper").html('');
	$("#attachment_wrapper").html('');
	$("#contribution_wrapper").html('');
	$("#duration_wrapper").html('');
	$("#job_history_wrapper").html('');
	$("#compensation_history_wrapper").html('');
	
}

function clearInlineValidationForm()
{
	clearDirectDepositInlineErrorForm();
	clearDependentInlineErrorForm();
	clearEmergencyContactInlineErrorForm();
	clearContactDetailsInlineErrorForm();
	clearPersonalDetailsInlineErrorForm();
}

function clearDirectDepositInlineErrorForm()
{
	$("#direct_deposit_edit_form").validationEngine('hide');
	$("#direct_deposit_add_form").validationEngine('hide');	
}



function load_add_employee_confirmation(employee_id)
{
	var employee_id = employee_id;
	$("#confirmation").html(loading_message);
	$.post(base_url + 'startup/_load_add_employee_confirmation',{},function(o) {
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
							window.location = "startup/profile?eid="+employee_id+"&hash="+hash;						
                    }//,
//					'Add Another Employee' : function(){						
//							load_employee_datatable();
//							window.location = "employee?add_employee=true";
//                    }
                }
            }).show();
}



function load_employee_datatable(searched)
{
	element_id = 'employee_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var hash = oRecord.getData("hash");
	
				elCell.innerHTML = "<div id='dropholder'><a class='dropbutton' href="+base_url+"startup/profile?eid="+id+"&hash="+hash+"#personal_details'>Display</a></div>"; 
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
					elCell.innerHTML = "<font color='red'><img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</span></font>"; 	
				}else {
					elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='80' style='display:none' align='left'  /><span class='employee_image_name'>"+employee_name+"</span>";
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
						 {key:"employment_status",label:"Status",width:80,resizeable:true,sortable:true,formatter:employment_status_f}
						 
						 //,
//						  {key:"action",label:"Action",width:45,resizeable:true,sortable:true, formatter:action}
						 ];
		var fields =	['id','e_id','branch_name','photo','department','employee_code','employee_name','position','employment_status','hash'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {
			var controller = 'startup/_json_encode_employee_list?search='+searched+'&';			
		}else {
			var controller = 'startup/_json_encode_employee_list?';			
		}
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(12);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();	
		
		load_total_records();
}

function loadDepartment()
{
	//branch_id = $("#branch").val();
//	
//	$.post(base_url+'startup/_load_department',{branch_id:branch_id},
//	function(o){
//		$("#department").html(o);
//	});	
	
		$("#search").val($("#search").val()+$("#department").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#department_option").hide();
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
	$(".images_wrapper").show();	
}

function loadListView()
{
	$(".images_wrapper").hide();
}

function clear_form_error() {
	$("#addBranch").validationEngine('hide');  	
	$("#employee_form").validationEngine('hide'); 
	$("#addPosition").validationEngine('hide');  
	$("#addStatus").validationEngine('hide');  	
}

//access Module
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
//End of employee

//Schedule start 

function load_schedule_startup() {
	$('#c-default-schedule').html(loading_image);	
	$.post(base_url + 'startup/_load_schedule_startup',{},
		function(o){			
			$('#c-default-schedule').html(o);		
		});		
}

function checkForAddGraceStartup(grace_period)
{
	clear_form_error();
	
	grace_period_id = $("#grace_period_id").val();	
	if(grace_period=='add') {
			load_grace_period_dropdown_startup();
				$.post(base_url+"startup/_load_add_grace_period_form_startup",{},
				function(o){
					$("#grace_period_wrapper_form_startup").html(o);
					dialogGeneric("#grace_period_wrapper_form_startup",{height:'auto', width:400});		
				});
	}else {
			$.post(base_url+"startup/save_grace_period_default",{grace_period_id:grace_period_id},
				function(o){});
	}
}

function load_grace_period_dropdown_startup() 
{
	$("#grace_period_dropdown_wrapper_startup").html(loading_image);
	$.post(base_url+'startup/_load_grace_period_dropdown_startup',{},
	function(o){
		$("#grace_period_dropdown_wrapper_startup").html(o);
	});
}


function load_leave_default_startup() 
{
	$("#c-default-leave").html(loading_image);
	$.post(base_url+'startup/_load_default_leave_startup',{},
	function(o){
		$("#c-default-leave").html(o);
	});
}

function load_grace_period() 
{
	$("#c-grace-period").html(loading_image);
	$.get(base_url+'startup/_load_grace_period_dt',{},
	function(o){
		$("#c-grace-period").html(o);
	});
}

function load_add_default_leave(lid)
{
	//load_leave_default_startup();
	$.post(base_url+"startup/_load_add_default_leave_form_startup",{lid:lid},
	function(o){
		$("#default_leave_wrapper_form_startup").html(o);
		dialogGeneric("#default_leave_wrapper_form_startup",{height:'auto', width:400});		
	});	
}

function load_edit_default_leave(lid)
{
	//load_leave_default_startup();
	$.post(base_url+"startup/_load_edit_default_leave_form_startup",{lid:lid},
	function(o){
		$("#edit_default_leave_wrapper_form_startup").html(o);
		dialogGeneric("#edit_default_leave_wrapper_form_startup",{height:'auto', width:400});		
	});	
}

//schedule from let
function showWeeklyScheduleList(element) {
	$(element).html(loading_image);
	$.get(base_url + 'startup/ajax_show_weekly_schedule_list', function(data) {
		$(element).html(data);
	});		
}

function closeTheDialog() {
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_schedule_form');
	$('.formError').remove();
}

function importSchedule() {
	_importSchedule({
		onLoading: function() {
			showLoadingDialog('');
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		 },		
		onImported: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();				
			showOkDialog(o.message, {
				onOk: function() {
					showWeeklyScheduleList('#schedule_list');					
				}
			});
		},
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function _importSchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Schedule';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'startup/ajax_import_schedule', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#import_schedule_form'
		});
		$('#import_schedule_form').validationEngine({scroll:false});		
		$('#import_schedule_form').ajaxForm({
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
				if ($('#import_schedule_file').val() == '') {
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

function importScheduleSpecific() {
	_importScheduleSpecific({
		onLoading: function() {
			showLoadingDialog('');
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onImported: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();				
			showOkDialog(o.message, {
				onOk: function() {
					showWeeklyScheduleList('#schedule_list');					
				}
			});
		},
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function _importScheduleSpecific(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Changed Schedule';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'startup/ajax_import_schedule_specific', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#import_schedule_specific_form'
		});
		$('#import_schedule_specific_form').validationEngine({scroll:false});		
		$('#import_schedule_specific_form').ajaxForm({
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
				if ($('#import_schedule_specific_file').val() == '') {
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

function createAndAssignWeeklySchedule(employee_id) {
	_createWeeklySchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);		
			$('#schedule_'+ employee_id).html(loading_image + ' Updating...');	
			_directAssignGroupScheduleToEmployee(o.schedule_group_id, employee_id,{
				onAssigned: function(data) {					
					var query = window.location.search;
					$.get(base_url + 'startup/show_employee'+ query, {ajax:1}, function(html_data){
						$('#main').html(html_data)	
					});	
				},
				onError: function(data) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(data.message);
				}
			});
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			//$('#message_container').hide();
			//cancelCreateSchedule();
			showLoadingDialog('');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function _createWeeklySchedule(events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'startup/ajax_add_weekly_schedule_form', {}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_schedule_form'
		});
		
		$('#name').select();
		$('#add_schedule_form').validationEngine({scroll:false});		
		$('#add_schedule_form').ajaxForm({
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
			beforeSubmit: function() {
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#add_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#add_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function showScheduleMembersList(element, schedule_id) {
	_showScheduleMembersList(element, schedule_id, {
		onSuccess: function() {
			
		},
		onLoading: function() {
			$(element).html(loading_image + ' Loading...');	
		}
	});
}

function _showScheduleMembersList(element, schedule_id, events) {
	if (events) {
		events.onLoading();	
	}
	$.get(base_url + 'startup/ajax_show_schedule_members_list', {schedule_id:schedule_id}, function(data) {
		$(element).html(data);
		if (events) {
			events.onSuccess();	
		}
	});
}

function editWeeklySchedule(public_id) { 
	_editWeeklySchedule(public_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$('.payslip_period_container h2 b').html(o.title_string);
			$('.payslip_period_container h2 span').html(o.schedule_string);			
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function importEmployeesInSchedule(public_id) {
	_importEmployeesInSchedule(public_id, {
		onLoading: function() {
			showLoadingDialog('');
		},
		onImporting: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			$("body").append("<div id='_new_dialog_'></div>");	
			dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
		},		
		onImported: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();				
			showOkDialog(o.message, {
				onOk: function() {
					showScheduleMembersList('#schedule_members_list', public_id);					
				}
			});
		},
		onError: function(o) {
			closeDialog('#_new_dialog_');
			$('#_new_dialog_').remove();			
			showOkDialog(o.message);
		}
	});
}

function _importEmployeesInSchedule(public_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Import Employees';
	var width = 330;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.get(base_url + 'startup/ajax_import_employees_in_schedule', {public_id:public_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#import_employees_in_schedule'
		});
		$('#import_employees_in_schedule').validationEngine({scroll:false});		
		$('#import_employees_in_schedule').ajaxForm({
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
				if ($('#import_employees').val() == '') {
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

function deleteSchedule(schedule_id) {
	//$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('');
	//var count = _countMembers(schedule_id);
	//if (count > 0) {
	//	$('#status_message').html('');
	//	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	//	showOkDialog('You have to remove first all groups and employees before you can delete this schedule.');
	//} else {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showYesNoDialog('Are you sure you want to delete this schedule?', {
			onYes: function(){
				_deleteSchedule(schedule_id, {
					onDeleted: function(message) {
						$dialog.dialog('destroy');	
						disablePopUp();
						location.href = base_url + 'startup';	
						
					},
					onLoading: function() {
						disablePopUp();
						showLoadingDialog('Deleting...');
					},
					onBeforeDelete: function() {
						return true;
					},
					onError: function(message) {
						alert(message);	
					}
				});	
			}
		});		
	//}
}

function deleteScheduleList(schedule_id) {
	//$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('');
	//var count = _countMembers(schedule_id);
	//if (count > 0) {
	//	$('#status_message').html('');
	//	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	//	showOkDialog('You have to remove first all groups and employees before you can delete this schedule.');
	//} else {
		$('#status_message').html('');
		closeDialog('#' + DIALOG_CONTENT_HANDLER);
		showYesNoDialog('Are you sure you want to delete this schedule?', {
			onYes: function(){
				_deleteSchedule(schedule_id, {
					onDeleted: function(message) {
						$dialog.dialog('destroy');	
						disablePopUp();
						showWeeklyScheduleList('#schedule_list');
					},
					onLoading: function() {
						disablePopUp();
						showLoadingDialog('Deleting...');
					},
					onBeforeDelete: function() {
						return true;
					},
					onError: function(message) {
						alert(message);	
					}
				});	
			}
		});		
	//}
}


function _deleteSchedule(schedule_id, events) {
	var ans = true;
	if (events) {
		ans = events.onBeforeDelete();
	}	
	if (ans) {
		if (events) {
			events.onLoading();	
		}
		$.post(base_url + 'startup/_delete_schedule', {schedule_id:schedule_id}, function(o) {
			if (o.is_deleted) {
				if (events) { events.onDeleted(o.message);}
			} else {
				if (events) { events.onError(o.message);}				
			}
		},'json');
	}
}

function assignScheduleGroups(schedule_id) {
	_assignScheduleGroups(schedule_id, {
		onSave: function(message) {
			_showScheduleMembersList('#schedule_members_list', schedule_id, {
				onSuccess: function() {
					$('#status_message').html('');
				},
				onLoading: function() {
					$('#status_message').html(' ' + loading_image + ' Updating...');
				}
			});			
		},
		onError: function(message) {
			alert(message);
		},		
		onBeforeSave: function() {		
			var n = $(".textboxlist-bit-box").length;
			if (n == 0) {
				showOkDialog('You have to select at least 1 group or department', {
					onOk: function() {
						assignScheduleEmployees(schedule_id);
					}
				});
				return false;
			} else {				
				return true;	
			}
		}
	});
}

function _assignScheduleGroups(schedule_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add Groups or Department';
	var width = 350;
	var height = 'auto';
	
	blockPopUp();
	$(dialog_id).html(loading_image + ' Loading...');
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: title,
		resizable: false,
		width: width,
		height: height,
		modal: true
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
	
	$.get(base_url + 'startup/ajax_assign_schedule_groups', {schedule_id:schedule_id}, function(data) {
		$(dialog_id).html(data);							
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: title,
			resizable: true,
			width: width,
			height: height,
			modal: true,
			close: function() {
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			},
			buttons: {
				'Save' : function(){					
					var ans = true;
					if (events) {
						ans = events.onBeforeSave();
					}			
					if (ans) {
						$(dialog_id + ' form').ajaxSubmit({
							success:function(o) {
								if (o.saved) {
									if (events) {
										events.onSave(o.message);
									}
								} else {
									if (events) {
										events.onError(o.message);	
									}
								}
							},
							dataType:'json'
						});
						$dialog.dialog('destroy');						
						$dialog.hide();	
						disablePopUp();							
					}							
				},
				'Cancel' : function(){
					$dialog.dialog("destroy");
					$dialog.hide();
					disablePopUp();
				}			
			}
		}).show().parent().find('.ui-dialog-titlebar-close').show();
		
		$('#groups_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
			minLength: 3,
			onlyFromValues: true,
			queryRemote: true,
			remote: {url: base_url + 'group/ajax_get_groups_autocomplete'}
		}}});
		
		$('#groups_autocomplete').focus();	
	});	
}


function deleteSpecificSchedule(schedule_id) {
	//$('#status_message').html(' ' + loading_image + ' Loading...');
	showLoadingDialog('');
	closeDialog('#' + DIALOG_CONTENT_HANDLER);
	showYesNoDialog('Are you sure you want to delete this schedule?', {
		onYes: function(){
			_deleteSpecificSchedule(schedule_id, {
				onDeleted: function(o) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					//var query = window.location.search;
//					$.get(base_url + 'schedule/show_employee'+ query, {ajax:1}, function(html_data){
//						$('#main').html(html_data)	
//					});		
					showSearchScheduleList();	
				},
				onDeleting: function() {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showLoadingDialog('Deleting...');
				},
				onBeforeDelete: function() {
					return true;
				},
				onError: function(message) {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showOkDialog(o.message);
				}
			});	
		}
	});
}

function _deleteSpecificSchedule(schedule_id, events) {
	var ans = true;
	if (typeof events.onBeforeDelete == "function") {
		ans = events.onBeforeDelete();
	}	
	if (ans) {
		if (typeof events.onDeleting == "function") {
			events.onDeleting();
		}
		$.post(base_url + 'startup/_delete_specific_schedule', {schedule_id:schedule_id}, function(o) {
			if (o.is_deleted) {
				if (typeof events.onDeleted == "function") {
					events.onDeleted(o);
				}				
			} else {
				if (typeof events.onError == "function") {
					events.onError(o);
				}		
			}
		},'json');
	}
}

function _directAssignGroupScheduleToEmployee(schedule_group_id, employee_id, events) {
	if (typeof events.onSaving == "function") {
		events.onSaving();
	}
	$.post(base_url + 'startup/_assign_group_schedule_to_employee', {schedule_group_id:schedule_group_id, employee_id:employee_id}, function(o) {
		if (o.is_assigned) {
			if (typeof events.onAssigned == "function") {		
				events.onAssigned(o);
			}
		} else {
			if (typeof events.onError == "function") {			
				events.onError(o);
			}
		}
	}, 'json');	
}

function createSpecificSchedule(employee_id) {
	_createSpecificSchedule(employee_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			var query = window.location.search;
			$.get(base_url + 'startup/show_employee'+ query, {ajax:1}, function(html_data){
				$('#main').html(html_data)	
			});				
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function _createSpecificSchedule(employee_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Add Schedule';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'startup/ajax_add_specific_schedule', {employee_id:employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_specific_schedule_form'
		});
			
		$('#add_specific_schedule_form').ajaxForm({
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
			beforeSubmit: function() {
				if (typeof events.onSaving == "function") {
					events.onSaving();
				}
				return true;
			}
		});
	});	
}

function editWeeklyScheduleFromList(public_id) {
	_editWeeklySchedule(public_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showWeeklyScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}

function _editWeeklySchedule(public_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Schedule';
	var width = 500;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.get(base_url + 'startup/ajax_edit_weekly_schedule_form', {public_id:public_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_schedule_form'
		});
		
		$('#name').select();
		$('#edit_schedule_form').validationEngine({scroll:false});		
		$('#edit_schedule_form').ajaxForm({
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
			beforeSubmit: function() {
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#edit_schedule_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_schedule_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function cancelCreateSchedule() {
	$('.formError').remove();
	$('#create_schedule_link').show();
	$('#create_schedule_handler').html('');	
}

function createWeeklySchedule() {
	_createWeeklySchedule({
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			showWeeklyScheduleList('#schedule_list');
		},
		onSaving: function() {
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			$('#message_container').hide();
			cancelCreateSchedule();
			showLoadingDialog('');
		},
		onBeforeSave: function(o) {			
			var count_value = 0;
			$(".time_in").each(function() {
				value = $(this).val();
				if (value != '') {
					count_value++;
				}
			});
			if (count_value == 0) {
				$('#schedule_message').html('You have to add at least 1 schedule');
				return false;
			} else {
				return true;	
			}
		},		
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});	
}

function showSearchScheduleList() {
	var employee_basic_search = $('#search_employee').val();
	if(employee_basic_search){
		$('#search_employee_schedule_result').html(loading_image + ' Loading...');
		$.get(base_url + 'startup/show_employee_result?query='+employee_basic_search, function(data) {
			$('#search_employee_schedule_result').html(data);
		});		
	}
}

//end schedule

//start payroll settings

function load_payroll_settings_startup() {
	$('#c-payroll-settings-startup').html(loading_image);	
	$.post(base_url + 'startup/_load_payroll_settings',{},
		function(o){			
			$('#c-payroll-settings-startup').html(o);		
		});		
}

function load_deduction_breakdown() {
	$("#c-deduction").html(loading_image);
	$.post(base_url + 'startup/_load_deduction_breakdown',{},function(o) {
		$("#c-deduction").html(o);						 
	})
}


function load_deduction_breakdown_list() {
	$("#deduction_breakdown_list_wrapper").html(loading_image);
	$.post(base_url + 'startup/_load_deduction_breakdown_list',{},function(o) {
		$("#deduction_breakdown_list_wrapper").html(o);						 
	})
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
			showLoadingDialog('');
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showOkDialog(o.message);
		}
	});
}


function _editDeductionBreakdown(h_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;	
	var title = 'Edit Deduction Breakdown';
	var width = 400;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}

	$.post(base_url + 'startup/ajax_edit_deduction_breakdown', {h_id:h_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_deduction_breakdown_form'
		});
		
		$('#edit_deduction_breakdown_form').validationEngine({scroll:false});		
		$('#edit_deduction_breakdown_form').ajaxForm({
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
			beforeSubmit: function() {
				var ans = true;
					if (typeof events.onBeforeSave == "function") {
						ans = events.onBeforeSave();
					}
				if (ans) {				
					var is_form_valid = $('#edit_deduction_breakdown_form').validationEngine('validate');
					if (is_form_valid) {
						closeDialog(dialog_id, '#edit_deduction_breakdown_form');
						if (typeof events.onSaving == "function") {
							events.onSaving();
						}
						return true;
					}
				} else {
					return false;	
				}
			}
		});
	});	
}

function _deactivateDeductionBreakdown(h_id) {
	$.post(base_url + 'startup/_deactivate_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
	})
}

function _activateDeductionBreakdown(h_id) {
	$.post(base_url + 'startup/_activate_deduction_breakdown',{h_id:h_id},function(o) {
		load_deduction_breakdown_list();				 
	})
}

function load_pay_period() {
	$("#c-pay-period").html(loading_image);
	$.post(base_url + 'startup/_load_pay_period',{},function(o) {
		$("#c-pay-period").html(o);						 
	})
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
		var width = '100%';
		
		var controller = 'startup/_load_pay_period_dt?';
	
		var license = new createDataTable(el,controller, columns, fields, height, width);
		license.rowPerPage(7);
		license.pageLinkLabel('First','Previous','Next','Last');
		license.show();
	
}

function load_delete_pay_period(pay_period_id) {
	$("#payperiod_form").html(loading_image);
	$.post(base_url + 'startup/_load_delete_pay_period_confirmation',{pay_period_id:pay_period_id},function(o) {
		$("#payperiod_form").html(o);							 
	})
	 var $dialog = $("#payperiod_form");
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
						$.post(base_url+'startup/delete_pay_period',{pay_period_id:pay_period_id}, // delete_subdivision
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
// end payrol settings


function closeDialog(div_id,form_id)
{
	
	$(form_id).validationEngine("hide");
	 var $dialog = $(div_id);
	 disablePopUp();
	 $dialog.dialog("destroy");
     
}

function clodeEditDeductionBreakdown() {
	$('.formError').remove();
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_deduction_breakdown_form');
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function editDefaultLeaveCredits(eid) {	
	_editDefaultLeaveCredits(eid,{
		onLoading: function() {
			showLoadingDialog('');
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

function addBranch(company_structure_id) {	
	_addBranch(company_structure_id,{
		onLoading: function() {
			showLoadingDialog('');
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
			showLoadingDialog('');
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

function addNewDepartment(eid,mode) {
	_addNewDepartment(eid,{
		onLoading: function() {
			showLoadingDialog('');
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
			load_grace_period();
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
			load_grace_period();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}


function load_branch_departments(eid) {	
	$(".tipsy").remove();
	$('#c-department').html(loading_image);	
	$.post(base_url + 'startup/_load_branch_departments',{eid:eid},
		function(o){				
			$('#c-department').html(o);		
		});				
}

function editDepartment(eid) {
	_editDepartment(eid,{
		onLoading: function() {
			showLoadingDialog('');
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

function load_edit_pay_period(eid) {
	_load_edit_pay_period(eid,{
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_pay_period();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function load_add_new_pay_period() {
	_load_add_new_pay_period({
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_pay_period();
			closeTheDialog();
			dialogOkBox(o.message,{});		
		},
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});
}

function addNewLeaveType() {
	_addNewLeaveType({
		onLoading: function() {
			showLoadingDialog('');
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onSaved: function(o) {
			load_leave_default_startup();
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
			load_grace_period();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function setDefaultPayPeriod(eid) {
	_setDefaultPayPeriod(eid, {
		onYes: function(o) {
			load_pay_period();
			dialogOkBox(o.message,{});		
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function updateStartupXml() {
	_updateStartupXml( {
		onYes: function(o) {
			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function load_total_records() {
	$.get(base_url+'startup/_get_total_records',{},
	function(o){
		$("#total_result_wrapper").html('0');
		$("#total_result_wrapper").html(o);	
	});	
}