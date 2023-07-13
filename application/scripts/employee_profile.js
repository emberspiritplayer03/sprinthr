var can_manage = "";
$(function(){
	function hashCheck(){
        var hash = window.location.hash;
		loadPage(hash);
        $(".left_nav").removeClass("selected");
	   $(hash+"_nav").addClass("selected");  
    }
    hashCheck();	
});

function hashClick(hash) {	
	var hash = hash;
	loadPage(hash); 
	
    $(".left_nav").removeClass("selected");
    $(hash+"_nav").addClass("selected");  
	
}

/*
* TODO TO BE CONTINUED
 */
function resignEmployee(employee_id, date) {
    _resignEmployee(employee_id, date, {
        onSaved: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            /*var query = window.location.search;
            $.post(base_url + 'overtime/check_overtime_error', {employee_id:employee_id, date:date}, function(oo){
                if (oo.has_error) {
                    showOkDialog(oo.message);
                } else {
                    showOkDialog(oo.message);
                    $.get(base_url + 'overtime/period'+ query, {ajax:1}, function(html_data){
                        $('#main').html(html_data)
                    });
                }
            }, 'json');*/
        },
        onSaving: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Saving...');
        },
        onLoading: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showLoadingDialog('Loading...');
        },
        onBeforeSave: function(o) {

        },
        onError: function(o) {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            showOkDialog(o.message);
        }
    });
}

function terminateEmployee(employee_id) {
    alert(employee_id);
}

function endoEmployee(employee_id) {
    alert(employee_id);
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
		displayPage({canvass:'#personal_details_wrapper',parameter:'employee/_load_personal_details?employee_id='+employee_id});
	}else if(hash=='#contact_details') {
		displayPage({canvass:'#contact_details_wrapper',parameter:'employee/_load_contact_details?employee_id='+employee_id});
	}else if(hash=='#emergency_contacts') {
		displayPage({canvass:'#emergency_contacts_wrapper',parameter:'employee/_load_emergency_contact?employee_id='+employee_id});
	}else if(hash=='#dependents') {
		displayPage({canvass:'#dependents_wrapper',parameter:'employee/_load_dependents?employee_id='+employee_id});
	}else if(hash=='#benefits') {
		displayPage({canvass:'#employee_benefits',parameter:'employee/_load_employee_benefits?employee_id='+employee_id});
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
		displayPage({canvass:'#project_site_history_wrapper',parameter:'employee/_load_project_site_history?employee_id='+employee_id});

	}else if(hash=='#compensation') {
		//displayPage({canvass:'#compensation_wrapper',parameter:'employee/_load_compensation?employee_id='+employee_id});
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


function loadEmployeeSummary()
{
	var employee_id = $("#employee_id").val();
	$.post(base_url+'employee/_load_employee_summary',{employee_id:employee_id},
	function(o){
		$("#employee_summary_wrapper").html(o);
	});
}

function deleteEmployeeBenefit(eid) {
	_deleteEmployeeBenefit(eid, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function loadEmployeeBenefits(eid)
{	
	$.get(base_url+'employee/_dt_employee_benefits_list',{eid:eid},
	function(o){
		$("#benefit_table_wrapper").html(o);
	});
}

function loadPhoto()
{
	var employee_id = $("#employee_id").val();
	
	$("#photo_frame_wrapper").html('');
	$("#photo_frame_wrapper").html(loading_image);
	$("#photo_frame_personal_wrapper").html('loading');
	$.post(base_url+'employee/_load_photo_frame',{employee_id:employee_id},
	function(o){
		
		$("#photo_frame_wrapper").html(o);
		$("#photo_frame_personal_wrapper").html(o);
		$("#photo_frame_personal_edit_wrapper").html(o);		
	});

	$.post(base_url+'employee/_get_photo_filename',{employee_id:employee_id},function(o){
		$("#photo_filename_wrapper").html(o);
		$("#photo").val($("#photo_filename_text").val());
	});
}

function loadPhotoDialog()
{
	var employee_id = $("#employee_id").val();
	$("#photo_wrapper").html(loading_message);
	dialogGeneric('#photo_wrapper',{title:'Photo'});
	$.post(base_url+'employee/_load_photo',{employee_id:employee_id},
	function(o){
		$("#photo_wrapper").html(o);
		dialogGeneric('#photo_wrapper',{title:'Photo',height:'auto'});
		
	});	
}

function closePhotoDialog()
{
	closeDialog("#photo_wrapper",'');
}

function loadPersonalDetailsFormDepre() {
	$("#personal_details_form").show();
	$("#personal_details_table_wrapper").hide();
}

function loadPersonalDetailsForm() {
	$("#personal-details-form").html(loading_image);
	var eid = $("#h_employee_id").val();
	$.get(base_url + 'employee/_load_edit_personal_details',{eid:eid},function(o) {
		$('#personal-details-form').html(o);		
	});	
	$("#personal_details_table_wrapper").hide();
}

function loadPersonalDetailsTable() {
	clearPersonalDetailsInlineErrorForm();
	$("#personal_details_form").hide();
	$("#personal_details_table_wrapper").show();
}

function hideEditPersonalDetailsForm() {
	$("#personal-details-form").html("");
	$("#personal_details_table_wrapper").show();
}

function clearPersonalDetailsInlineErrorForm()
{
	$("#personal_details_form").validationEngine('hide');
} 

function loadContactDetailsForm() {
	$("#contact_details_form").show();
	$("#contact_details_table_wrapper").hide();
}

function clearContactDetailsInlineErrorForm()
{
	$("#contact_details_form").validationEngine('hide');
}

function loadContactDetailsTable() {
	clearContactDetailsInlineErrorForm();
	$("#contact_details_form").hide();
	$("#contact_details_table_wrapper").show();
}

//emergency contact

function loadEmergencyContactAddForm() {
	$("#emergency_contacts_add_form_wrapper").show();
	$("#emergency_contacts_table_wrapper").hide();
	$("#emergency_contacts_edit_form_wrapper").hide();
	$("#emergency_contact_add_button_wrapper").hide();
}


function clearEmergencyContactInlineErrorForm()
{
	$("#emergency_contacts_edit_form").validationEngine('hide');
	$("#emergency_contacts_add_form").validationEngine('hide');	
}

function loadEmergencyContactsTable() {
	clearEmergencyContactInlineErrorForm();
	$("#emergency_contacts_add_form_wrapper").hide();
	$("#emergency_contacts_edit_form_wrapper").html('');
	$("#emergency_contacts_table_wrapper").show();
	$("#emergency_contact_add_button_wrapper").show();
}

function loadEmergencyContactDeleteDialog(id)
{
	clearEmergencyContactInlineErrorForm();
	var emergency_contact_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#emergency_contacts_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_emergency_contact',{emergency_contact_id:emergency_contact_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#emergency_contacts_wrapper").html('');
						loadPage("#emergency_contacts");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadEmergencyContactEditForm(id)
{
	var emergency_contact_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#emergency_contacts_edit_form_wrapper").show();
	$("#emergency_contacts_table_wrapper").hide();
	$("#emergency_contacts_add_form_wrapper").hide();
	$("#emergency_contact_add_button_wrapper").hide();
	$("#emergency_contacts_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_emergency_contacts_edit_form',{emergency_contact_id:emergency_contact_id},
	function(o){
		$("#emergency_contacts_edit_form_wrapper").html(o);
		
	});
}


/// Dependent

function loadDependentAddForm() {
	
	$("#dependent_add_form_wrapper").show();
	$("#dependent_table_wrapper").hide();
	$("#dependent_edit_form_wrapper").hide();
	$("#dependent_add_button_wrapper").hide();
}

function loadAddBenefitForm(eid) {
	$("#add_benefit_form_wrapper").show();	
	$("#benefit_table_wrapper").hide();
	$("#benefits_add_button_wrapper").hide();
	$.get(base_url+'employee/_load_employee_add_benefit_form',{eid:eid},
	function(o){
		$("#add_benefit_form_wrapper").html(o);
		
	});
}

function hideAddBenefitForm(){
	$("#add_benefit_form_wrapper").hide();	
	$("#benefit_table_wrapper").show();
	$("#benefits_add_button_wrapper").show();
}

function clearDependentInlineErrorForm()
{
	$("#dependent_edit_form").validationEngine('hide');
	$("#dependent_add_form").validationEngine('hide');	
}

function loadDependentTable() {
	clearDependentInlineErrorForm();
	$("#dependent_add_form_wrapper").hide();
	$("#dependent_edit_form_wrapper").html('');
	$("#dependent_table_wrapper").show();
	$("#dependent_add_button_wrapper").show();
}

function loadDependentDeleteDialog(id)
{
	clearDependentInlineErrorForm();
	var dependent_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#dependent_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_dependent',{dependent_id:dependent_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#dependents_wrapper").html('');
						loadPage("#dependents");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadDependentEditForm(id)
{
	var dependent_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#dependent_edit_form_wrapper").show();
	$("#dependent_table_wrapper").hide();
	$("#dependent_add_form_wrapper").hide();
	$("#dependent_add_button_wrapper").hide();
	$("#dependent_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_dependent_edit_form',{dependent_id:dependent_id},
	function(o){
		$("#dependent_edit_form_wrapper").html(o);
		
	});
}

//direct deposit

function loadDirectDepositAddForm() {
	
	$("#direct_deposit_add_form_wrapper").show();
	$("#direct_deposit_table_wrapper").hide();
	$("#direct_deposit_edit_form_wrapper").hide();
	$("#direct_deposit_add_button_wrapper").hide();
}

function clearDirectDepositInlineErrorForm()
{
	$("#direct_deposit_edit_form").validationEngine('hide');
	$("#direct_deposit_add_form").validationEngine('hide');	
}

function loadDirectDepositTable() {
	clearDirectDepositInlineErrorForm();
	$("#direct_deposit_add_form_wrapper").hide();
	$("#direct_deposit_edit_form_wrapper").html('');
	$("#direct_deposit_table_wrapper").show();
	$("#direct_deposit_add_button_wrapper").show();
}

function loadDirectDepositDeleteDialog(id)
{
	clearDirectDepositInlineErrorForm();
	var direct_deposit_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#direct_deposit_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_direct_deposit',{direct_deposit_id:direct_deposit_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#banks_wrapper").html('');
						loadPage("#bank");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadDirectDepositEditForm(id)
{
	var direct_deposit_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#direct_deposit_edit_form_wrapper").show();
	$("#direct_deposit_table_wrapper").hide();
	$("#direct_deposit_add_form_wrapper").hide();
	$("#direct_deposit_add_button_wrapper").hide();
	$("#direct_deposit_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_direct_deposit_edit_form',{direct_deposit_id:direct_deposit_id},
	function(o){
		$("#direct_deposit_edit_form_wrapper").html(o);
		
	});
}

//employment status

function clearEmploymentStatusInlineErrorForm()
{
	$("#employment_status_form").validationEngine('hide');

}

function loadEmploymentStatusTable() {
	clearEmploymentStatusInlineErrorForm();

	$("#employment_status_form").hide();
	$("#employment_status_table_wrapper").show();
}

function loadEmploymentStatusDeleteDialog(id)
{
	clearEmploymentStatusInlineErrorForm();
	var direct_deposit_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#direct_deposit_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_direct_deposit',{direct_deposit_id:direct_deposit_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#banks_wrapper").html('');
						loadPage("#bank");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadEmploymentStatusEditForm(id)
{
	var direct_deposit_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#employment_status_form").show();
	$("#employment_status_table_wrapper").hide();
/*	
	$("#employment_status_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_employment_status_edit_form',{direct_deposit_id:direct_deposit_id},
	function(o){
		alert(o);
		$("#employment_status_edit_form_wrapper").html(o);
		
	});*/
}

// Extend Contract

function loadDurationAddForm() {
	
	$("#duration_add_form_wrapper").show();
	$("#duration_table_wrapper").hide();
	$("#duration_edit_form_wrapper").hide();
	$("#duration_add_button_wrapper").hide();
}

function clearDurationInlineErrorForm()
{
	$("#duration_edit_form").validationEngine('hide');
	$("#duration_add_form").validationEngine('hide');	
}

function loadDurationTable() {
	clearDurationInlineErrorForm();
	$("#duration_add_form_wrapper").hide();
	$("#duration_edit_form_wrapper").html('');
	$("#duration_table_wrapper").show();
	$("#duration_add_button_wrapper").show();
}

function loadDurationDeleteDialog(id)
{
	clearDurationInlineErrorForm();
	var duration_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#duration_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_duration',{duration_id:duration_id},
					function(o){
						dialogOkBox('Successfully Deleted',{height:240,width:390});
						$("#duration_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadDurationEditForm(id)
{
	var duration_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#duration_edit_form_wrapper").show();
	$("#duration_table_wrapper").hide();
	$("#duration_add_form_wrapper").hide();
	$("#duration_add_button_wrapper").hide();
	$("#duration_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_duration_edit_form',{duration_id:duration_id},
	function(o){
		$("#duration_edit_form_wrapper").html(o);
		
	});
}

//end of duration	


function loadJobDutiesDescriptionStatus()
{
	var job_id = $("#job_id").val();	
	
	$.post(base_url+'employee/_load_job_description',{job_id:job_id},
	function(o){
		$("#job_description_label").html(o);	
	});
	
	$.post(base_url+'employee/_load_job_duties',{job_id:job_id},
	function(o){
		$("#job_duties_label").html(o);
	});
	
	load_profile_status_dropdown();
}

function load_profile_status_dropdown()
{

	var position_id = $("#job_id").val();
	var employee_id = $("#employee_id").val();
	
	$("#employment_status_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'employee/_load_profile_status_dropdown?employee_id='+employee_id+"&pid="+position_id,{},
	function(o){
		$("#employment_status_dropdown_wrapper").html(o);
	});
}

function checkForTermination()
{
	var employment_status_id = $("#employment_status_id").val();	
	if(employment_status_id==0) {
		//termination	
		$("#termination_date_wrapper").show();
		$("#termination_memo_wrapper").show();
	}else {
		$("#termination_date_wrapper").hide();
		$("#termination_memo_wrapper").hide();
	}
}

function checkForTerminationEmployeeStatus(terminated_id)
{
	var employee_status_id = $("#employee_status_id").val();	
	if(employee_status_id == terminated_id) {
		//termination	
		$("#termination_date_wrapper").show();
		$("#termination_memo_wrapper").show();
	}else {
		$("#termination_date_wrapper").hide();
		$("#termination_memo_wrapper").hide();
	}
}

function validateEmployeeStatus(e_status_id)
{	
	var terminated_id = $("#validate_estatus_terminated_id").val();
	var endo_id 	 = $("#validate_estatus_endo_id").val();
	var resigned_id  = $("#validate_estatus_resigned_id").val();
	var inactive_id  = $("#validate_estatus_inactive_id").val();
	var active_id    = $("#validate_estatus_active_id").val();

	var awol_id    = $("#validate_estatus_awol_id").val();
	
	if(e_status_id == terminated_id) {
		//termination	
		$("#termination_date_wrapper").show();
		$("#estatus_attachment_wrapper").hide();
		
		$("#endo_date_wrapper").hide();
		$("#resigned_date_wrapper").hide();
		$("#inactive_date_wrapper").hide();
		$("#active_date_wrapper").hide();
		$("#awol_date_wrapper").hide();
	}else if(e_status_id == endo_id){
		//endo	
		$("#endo_date_wrapper").show();
		$("#estatus_attachment_wrapper").hide();		
		
		$("#termination_date_wrapper").hide();
		$("#resigned_date_wrapper").hide();
		$("#inactive_date_wrapper").hide();
		$("#active_date_wrapper").hide();
		$("#awol_date_wrapper").hide();

	}else if(e_status_id == resigned_id){
		//resigned	
		$("#resigned_date_wrapper").show();
		$("#estatus_attachment_wrapper").hide();
		
		$("#termination_date_wrapper").hide();
		$("#endo_date_wrapper").hide();
		$("#inactive_date_wrapper").hide();
		$("#active_date_wrapper").hide();
		$("#awol_date_wrapper").hide();
	}else if(e_status_id == inactive_id) {
		//inactive
		$("#inactive_date_wrapper").show();
		$("#estatus_attachment_wrapper").hide();

		$("#termination_date_wrapper").hide();
		$("#endo_date_wrapper").hide();
		$("#resigned_date_wrapper").hide();
		$("#active_date_wrapper").hide();
		$("#awol_date_wrapper").hide();
	}

	else if(e_status_id == awol_id) {
		//inactive
		$("#awol_date_wrapper").show();
		$("#termination_date_wrapper").hide();

		$("#endo_date_wrapper").hide();
		$("#resigned_date_wrapper").hide();
		$("#active_date_wrapper").hide();
		$("#inactive_date_wrapper").hide();
	}

	else if(e_status_id == active_id) {
		//active
		$("#active_date_wrapper").show();
		$("#estatus_attachment_wrapper").hide();

		$("#termination_date_wrapper").hide();
		$("#endo_date_wrapper").hide();
		$("#resigned_date_wrapper").hide();		
		$("#inactive_date_wrapper").hide();
		$("#awol_date_wrapper").hide();
	} else {
		$("#termination_date_wrapper").hide();
		$("#endo_date_wrapper").hide();
		$("#resigned_date_wrapper").hide();
		$("#estatus_attachment_wrapper").hide();
		$("#inactive_date_wrapper").hide();
		$("#active_date_wrapper").hide();
		$("#awol_date_wrapper").hide();
	}
}


//end of employment status

// compensation
function loadCompensationForm() {
	$("#compensation_form").show();
	$("#compensation_table_wrapper").hide();
}

function clearCompensationInlineErrorForm()
{
	$("#compensation_form").validationEngine('hide');
}

function loadCompensationTable() {
	clearCompensationInlineErrorForm();
	$("#compensation_form").hide();
	$("#compensation_table_wrapper").show();
}

function loadMinimumMaximumRate()
{
	var job_salary_rate_id = $("#job_salary_rate_id").val();

	if(job_salary_rate_id!='') {
		$("#minimum_rate_label").html(loading_image+' loading...');
		$("#maximum_rate_label").html(loading_image+' loading...');
		$.post(base_url+'employee/_load_minimum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#minimum_rate_label").html(o);});	
		$.post(base_url+'employee/_load_maximum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#maximum_rate_label").html(o);});	
	}else {
		$("#minimum_salary_rate").html('');
		$("#maximum_salary_rate").html('');
	}
	
}

//end compensation


//compensation history

function loadCompensationHistoryAddForm() {
	
	$("#compensation_history_add_form_wrapper").show();
	$("#compensation_history_table_wrapper").hide();
	$("#compensation_history_edit_form_wrapper").hide();
	$("#compensation_history_add_button_wrapper").hide();
}

function clearCompensationHistoryInlineErrorForm()
{
	$("#compensation_history_edit_form").validationEngine('hide');
	$("#compensation_history_add_form").validationEngine('hide');	
}

function loadCompensationHistoryTable() {
	clearCompensationHistoryInlineErrorForm();
	$("#compensation_history_add_form_wrapper").hide();
	$("#compensation_history_edit_form_wrapper").html('');
	$("#compensation_history_table_wrapper").show();
	$("#compensation_history_add_button_wrapper").show();
}

function loadCompensationHistoryDeleteDialog(id)
{
	clearCompensationHistoryInlineErrorForm();
	var compensation_history_id = id;

	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#compensation_history_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 'auto',
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_compensation_history',{compensation_history_id:compensation_history_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#compensation_history_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadCompensationHistoryEditForm(id)
{
	var compensation_history_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#compensation_history_edit_form_wrapper").show();
	$("#compensation_history_table_wrapper").hide();
	$("#compensation_history_add_form_wrapper").hide();
	$("#compensation_history_add_button_wrapper").hide();
	$("#compensation_history_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_compensation_history_edit_form',{compensation_history_id:compensation_history_id},
	function(o){
		$("#compensation_history_edit_form_wrapper").html(o);
		
	});
}

function loadMinimumMaximumRateHistoryAdd()
{
	var job_salary_rate_id = $("#job_salary_rate_id_add").val();

	if(job_salary_rate_id!='') {
		$("#minimum_rate_label_add").html(loading_image+' loading...');
		$("#maximum_rate_label_add").html(loading_image+' loading...');
		$.post(base_url+'employee/_load_minimum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#minimum_rate_label_add").html(o);});	
		$.post(base_url+'employee/_load_maximum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#maximum_rate_label_add").html(o);});	
	}else {
		$("#minimum_salary_rate_add").html('');
		$("#maximum_salary_rate_add").html('');
	}
	
}

function loadMinimumMaximumRateHistoryEdit()
{
	var job_salary_rate_id = $("#job_salary_rate_id_edit").val();

	if(job_salary_rate_id!='') {
		$("#minimum_rate_label_edit").html(loading_image+' loading...');
		$("#maximum_rate_label_edit").html(loading_image+' loading...');
		$.post(base_url+'employee/_load_minimum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#minimum_rate_label_edit").html(o);});	
		$.post(base_url+'employee/_load_maximum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#maximum_rate_label_edit").html(o);});	
	}else {
		$("#minimum_salary_rate_edit").html('');
		$("#maximum_salary_rate_edit").html('');
	}
	
}


// end of compensation history

//performance
function loadPerformanceAddForm() {
	
	$("#performance_add_form_wrapper").show();
	$("#performance_table_wrapper").hide();
	$("#performance_edit_form_wrapper").hide();
	$("#performance_add_button_wrapper").hide();
}

function clearPerformanceInlineErrorForm()
{
	$("#performance_edit_form").validationEngine('hide');
	$("#performance_add_form").validationEngine('hide');	
}

function loadPerformanceTable() {
	clearPerformanceInlineErrorForm();
	$("#performance_add_form_wrapper").hide();
	$("#performance_edit_form_wrapper").html('');
	$("#performance_table_wrapper").show();
	$("#performance_add_button_wrapper").show();
}

function loadPerformanceDeleteDialog(id)
{
	clearPerformanceInlineErrorForm();
	var performance_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#performance_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_performance',{performance_id:performance_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#performance_wrapper").html('');
						loadPage("#performance");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadPerformanceEditForm(id)
{
	var performance_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#performance_edit_form_wrapper").show();
	$("#performance_table_wrapper").hide();
	$("#performance_add_form_wrapper").hide();
	$("#performance_add_button_wrapper").hide();
	$("#performance_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_performance_edit_form',{performance_id:performance_id},
	function(o){
		$("#performance_edit_form_wrapper").html(o);
		
	});
}


function load_employee_performance() 
{
	alert('test');
	var xml = document.createElement("xml");
	xml.src = base_url+"_load_employee_performance";
	document.body.appendChild(xml);
	var xmlDocument = xml.XMLDocument;
	
	$("#div_xml").html(document.body.removeChild(xml));	
}

//requirements

function loadRequirementsAddForm() {
	
	$("#requirements_add_form_wrapper").show();
	$("#requirements_table_wrapper").hide();
	$("#requirements_edit_form_wrapper").hide();
	$("#requirements_add_button_wrapper").hide();
}

function clearRequirementsInlineErrorForm()
{
	$("#requirements_edit_form").validationEngine('hide');
	$("#requirements_add_form").validationEngine('hide');	
}

function loadRequirementsTable() {
	clearRequirementsInlineErrorForm();
	$("#requirements_add_form_wrapper").hide();
	$("#requirements_edit_form_wrapper").html('');
	$("#requirements_table_wrapper").show();
	$("#requirements_add_button_wrapper").show();
}

function addDefaultRequirements() {
	clearRequirementsInlineErrorForm();
	var icon = '<br><div class="confirmation-alert"><div> ';
	var message = "The current requirements will be overwritten. Do you want to continue?";
	
	var dialog_id = $("#requirements_delete_wrapper");
	var employee_id = $("#employee_id").val();
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('updating...');
					
					$.post(base_url+'employee/_add_default_requirements',{employee_id:employee_id},
					function(o){
						dialogOkBox('Successfully Updated',{height:240,width:390});
						$("#requirements_wrapper").html('');
						loadPage("#requirements");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadRequirementsDeleteDialog(id)
{
	clearRequirementsInlineErrorForm();
	var requirement_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#requirements_delete_wrapper");
	var employee_id = $("#employee_id").val();
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_requirements',{requirement_id:requirement_id,employee_id:employee_id},
					function(o){
						dialogOkBox('Successfully Deleted',{height:240,width:390});
						$("#requirements_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadRequirementsEditForm(id)
{
	var requirement_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#requirements_edit_form_wrapper").show();
	$("#requirements_table_wrapper").hide();
	$("#requirements_add_form_wrapper").hide();
	$("#requirements_add_button_wrapper").hide();
	$("#requirements_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_requirements_edit_form',{requirement_id:requirement_id},
	function(o){
		$("#requirements_edit_form_wrapper").html(o);
		
	});
}

function displayDelete(id) {
	$("#"+id).show();	 
}

function hideDelete(id) {
	$(".delete_requirement_nav").hide();
}


//training

function loadTrainingAddForm() {
	
	$("#training_add_form_wrapper").show();
	$("#training_table_wrapper").hide();
	$("#training_edit_form_wrapper").hide();
	$("#training_add_button_wrapper").hide();
}

function clearTrainingInlineErrorForm()
{
	$("#training_edit_form").validationEngine('hide');
	$("#training_add_form").validationEngine('hide');	
}

function loadTrainingTable() {
	clearTrainingInlineErrorForm();
	$("#training_add_form_wrapper").hide();
	$("#training_edit_form_wrapper").hide();
	$("#training_table_wrapper").show();
	$("#training_add_button_wrapper").show();
}

function loadTrainingDeleteDialog(id)
{
	clearTrainingInlineErrorForm();
	var training_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#training_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_training',{training_id:training_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#training_wrapper").html('');
						loadPage("#training");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadTrainingEditForm(id)
{
	
	var training_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#training_edit_form_wrapper").show();
	$("#training_table_wrapper").hide();
	$("#training_add_form_wrapper").hide();
	$("#training_add_button_wrapper").hide();
	$("#training_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_training_edit_form',{training_id:training_id},
	function(o){
		$("#training_edit_form_wrapper").html(o);
		
	});
}


// training

//job_history

function loadJobHistoryAddForm() {
	
	$("#job_history_add_form_wrapper").show();
	$("#job_history_table_wrapper").hide();
	$("#job_history_edit_form_wrapper").hide();
	$("#job_history_add_button_wrapper").hide();
}

function clearJobHistoryInlineErrorForm()
{
	$("#job_history_edit_form").validationEngine('hide');
	$("#job_history_add_form").validationEngine('hide');	
}

function loadJobHistoryTable() {
	clearJobHistoryInlineErrorForm();
	$("#job_history_add_form_wrapper").hide();
	$("#job_history_edit_form_wrapper").html('');
	$("#job_history_table_wrapper").show();
	$("#job_history_add_button_wrapper").show();
}

function loadJobHistoryDeleteDialog(id)
{
	clearJobHistoryInlineErrorForm();
	var job_history_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#job_history_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_job_history',{job_history_id:job_history_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#job_history_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadJobHistoryEditForm(id)
{
	var job_history_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#job_history_edit_form_wrapper").show();
	$("#job_history_table_wrapper").hide();
	$("#job_history_add_form_wrapper").hide();
	$("#job_history_add_button_wrapper").hide();
	$("#job_history_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_job_history_edit_form',{job_history_id:job_history_id},
	function(o){
		$("#job_history_edit_form_wrapper").html(o);
		
	});
}


// end of job history


//subdivision history

function loadSubdivisionHistoryAddForm() {
	
	$("#subdivision_history_add_form_wrapper").show();
	$("#subdivision_history_table_wrapper").hide();
	$("#subdivision_history_edit_form_wrapper").hide();
	$("#subdivision_history_add_button_wrapper").hide();
}

function clearSubdivisionHistoryInlineErrorForm()
{
	$("#subdivision_history_edit_form").validationEngine('hide');
	$("#subdivision_history_add_form").validationEngine('hide');	
}

function loadSubdivisionHistoryTable() {
	clearSubdivisionHistoryInlineErrorForm();
	$("#subdivision_history_add_form_wrapper").hide();
	$("#subdivision_history_edit_form_wrapper").html('');
	$("#subdivision_history_table_wrapper").show();
	$("#subdivision_history_add_button_wrapper").show();
}

function loadSubdivisionHistoryDeleteDialog(id)
{
	clearSubdivisionHistoryInlineErrorForm();
	var subdivision_history_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#subdivision_history_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_subdivision_history',{subdivision_history_id:subdivision_history_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#subdivision_history_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadSubdivisionHistoryEditForm(id)
{
	var subdivision_history_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#subdivision_history_edit_form_wrapper").show();
	$("#subdivision_history_table_wrapper").hide();
	$("#subdivision_history_add_form_wrapper").hide();
	$("#subdivision_history_add_button_wrapper").hide();
	$("#subdivision_history_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_subdivision_history_edit_form',{subdivision_history_id:subdivision_history_id},
	function(o){
		$("#subdivision_history_edit_form_wrapper").html(o);
		
	});
}


// end of subdivision history


//contribution

function loadContributionForm() {
	$("#contribution_form").show();
	$("#contribution_table_wrapper").hide();
	$("#contribution_button_wrapper").hide();
}

function loadContributionTable() {
	clearContributionInlineErrorForm();
	$("#contribution_form").hide();
	$("#contribution_table_wrapper").show();
	$("#contribution_button_wrapper").show();
}

function clearContributionInlineErrorForm()
{
	$("#contribution_form").validationEngine('hide');
} 


//work experience

function loadWorkExperienceAddForm() {
	
	$("#work_experience_add_form_wrapper").show();
	$("#work_experience_table_wrapper").hide();
	$("#work_experience_edit_form_wrapper").hide();
	$("#work_experience_add_button_wrapper").hide();
}

function clearWorkExperienceInlineErrorForm()
{
	$("#work_experience_edit_form").validationEngine('hide');
	$("#work_experience_add_form").validationEngine('hide');	
}

function loadWorkExperienceTable() {
	clearWorkExperienceInlineErrorForm();
	$("#work_experience_add_form_wrapper").hide();
	$("#work_experience_edit_form_wrapper").html('');
	$("#work_experience_table_wrapper").show();
	$("#work_experience_add_button_wrapper").show();
}

function loadWorkExperienceDeleteDialog(id)
{
	clearWorkExperienceInlineErrorForm();
	var work_experience_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#work_experience_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_work_experience',{work_experience_id:work_experience_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#work_experience_wrapper").html('');
						loadPage("#work_experience");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadWorkExperienceEditForm(id)
{
	var work_experience_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#work_experience_edit_form_wrapper").show();
	$("#work_experience_table_wrapper").hide();
	$("#work_experience_add_form_wrapper").hide();
	$("#work_experience_add_button_wrapper").hide();
	$("#work_experience_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_work_experience_edit_form',{work_experience_id:work_experience_id},
	function(o){
		$("#work_experience_edit_form_wrapper").html(o);
		
	});
}


// work experience

//education

function loadEducationAddForm() {
	
	$("#education_add_form_wrapper").show();
	$("#education_table_wrapper").hide();
	$("#education_edit_form_wrapper").hide();
	$("#education_add_button_wrapper").hide();
}

function clearEducationInlineErrorForm()
{
	$("#education_edit_form").validationEngine('hide');
	$("#education_add_form").validationEngine('hide');	
}

function loadEducationTable() {
	clearEducationInlineErrorForm();
	$("#education_add_form_wrapper").hide();
	$("#education_edit_form_wrapper").html('');
	$("#education_table_wrapper").show();
	$("#education_add_button_wrapper").show();
}

function loadEducationDeleteDialog(id)
{
	clearEducationInlineErrorForm();
	var education_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#education_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_education',{education_id:education_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#education_wrapper").html('');
						loadPage("#education");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadEducationEditForm(id)
{
	var education_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#education_edit_form_wrapper").show();
	$("#education_table_wrapper").hide();
	$("#education_add_form_wrapper").hide();
	$("#education_add_button_wrapper").hide();
	$("#education_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_education_edit_form',{education_id:education_id},
	function(o){
		$("#education_edit_form_wrapper").html(o);
		
	});
}


//education


// supervisor
function loadSupervisorAddForm() {
	
	$("#supervisor_add_form_wrapper").show();
	$("#supervisor_table_wrapper").hide();
	$("#supervisor_edit_form_wrapper").hide();
	$("#supervisor_add_button_wrapper").hide();
}

function clearSupervisorInlineErrorForm()
{
	$("#supervisor_edit_form").validationEngine('hide');
	$("#supervisor_add_form").validationEngine('hide');	
}

function loadSupervisorTable() {
	clearSupervisorInlineErrorForm();
	$("#supervisor_add_form_wrapper").hide();
	$("#supervisor_edit_form_wrapper").html('');
	$("#supervisor_table_wrapper").show();
	$("#supervisor_add_button_wrapper").show();
}

function loadSupervisorDeleteDialog(id)
{
	clearSupervisorInlineErrorForm();
	var id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#supervisor_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_supervisor',{id:id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#supervisor_wrapper").html('');
						loadPage("#supervisor");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadSubordinatesDeleteDialog(id)
{
	clearSupervisorInlineErrorForm();
	var id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";

	var dialog_id = $("#supervisor_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_subordinates',{id:id},
					function(o){
						dialogOkBox('Successfully Deleted',{height:240,width:390});
						$("#supervisor_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}


function loadSupervisorEditForm(id)
{
	var id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#supervisor_edit_form_wrapper").show();
	$("#supervisor_table_wrapper").hide();
	$("#supervisor_add_form_wrapper").hide();
	$("#supervisor_add_button_wrapper").hide();
	$("#supervisor_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_supervisor_edit_form',{id:id},
	function(o){
	
		$("#supervisor_edit_form_wrapper").html(o);
		
	});
}

function loadSubordinatesEditForm(id)
{
	var id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#supervisor_edit_form_wrapper").show();
	$("#supervisor_table_wrapper").hide();
	$("#supervisor_add_form_wrapper").hide();
	$("#supervisor_add_button_wrapper").hide();
	$("#supervisor_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_subordinates_edit_form',{id:id},
	function(o){
	
		$("#supervisor_edit_form_wrapper").html(o);
		
	});
}


//end supervisor

/// skills

function loadSkillAddForm() {
	
	$("#skill_add_form_wrapper").show();
	$("#skill_table_wrapper").hide();
	$("#skill_edit_form_wrapper").hide();
	$("#skill_add_button_wrapper").hide();
}

function clearSkillInlineErrorForm()
{
	$("#skill_edit_form").validationEngine('hide');
	$("#skill_add_form").validationEngine('hide');	
}

function loadSkillTable() {
	clearSkillInlineErrorForm();
	$("#skill_add_form_wrapper").hide();
	$("#skill_edit_form_wrapper").html('');
	$("#skill_table_wrapper").show();
	$("#skill_add_button_wrapper").show();
}

function loadSkillDeleteDialog(id)
{
	clearSkillInlineErrorForm();
	var skill_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#skill_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_skill',{skill_id:skill_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#skills_wrapper").html('');
						loadPage("#skills");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadSkillEditForm(id)
{
	var skill_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#skill_edit_form_wrapper").show();
	$("#skill_table_wrapper").hide();
	$("#skill_add_form_wrapper").hide();
	$("#skill_add_button_wrapper").hide();
	$("#skill_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_skill_edit_form',{skill_id:skill_id},
	function(o){
		$("#skill_edit_form_wrapper").html(o);
		
	});
}

//end of skills

//language

function loadLanguageAddForm() {
	
	$("#language_add_form_wrapper").show();
	$("#language_table_wrapper").hide();
	$("#language_edit_form_wrapper").hide();
	$("#language_add_button_wrapper").hide();
}

function clearLanguageInlineErrorForm()
{
	$("#language_edit_form").validationEngine('hide');
	$("#language_add_form").validationEngine('hide');	
}

function loadLanguageTable() {
	clearLanguageInlineErrorForm();
	$("#language_add_form_wrapper").hide();
	$("#language_edit_form_wrapper").html('');
	$("#language_table_wrapper").show();
	$("#language_add_button_wrapper").show();
}

function loadLanguageDeleteDialog(id)
{
	clearLanguageInlineErrorForm();
	var language_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#language_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_language',{language_id:language_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#language_wrapper").html('');
						loadPage("#language");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadLanguageEditForm(id)
{
	var language_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#language_edit_form_wrapper").show();
	$("#language_table_wrapper").hide();
	$("#language_add_form_wrapper").hide();
	$("#language_add_button_wrapper").hide();
	$("#language_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_language_edit_form',{language_id:language_id},
	function(o){
		$("#language_edit_form_wrapper").html(o);
		
	});
}

//end of language

//license

function loadLicenseAddForm() {
	
	$("#license_add_form_wrapper").show();
	$("#license_table_wrapper").hide();
	$("#license_edit_form_wrapper").hide();
	$("#license_add_button_wrapper").hide();
}

function clearLicenseInlineErrorForm()
{
	$("#license_edit_form").validationEngine('hide');
	$("#license_add_form").validationEngine('hide');	
}

function loadLicenseTable() {
	clearLicenseInlineErrorForm();
	$("#license_add_form_wrapper").hide();
	$("#license_edit_form_wrapper").html('');
	$("#license_table_wrapper").show();
	$("#license_add_button_wrapper").show();
}

function loadLicenseDeleteDialog(id)
{
	clearLicenseInlineErrorForm();
	var license_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#license_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_license',{license_id:license_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#license_wrapper").html('');
						loadPage("#license");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadLicenseEditForm(id)
{
	var license_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#license_edit_form_wrapper").show();
	$("#license_table_wrapper").hide();
	$("#license_add_form_wrapper").hide();
	$("#license_add_button_wrapper").hide();
	$("#license_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_license_edit_form',{license_id:license_id},
	function(o){
		$("#license_edit_form_wrapper").html(o);
		
	});
}

//end of license

//membership

function loadMembershipAddForm() {
	
	$("#membership_add_form_wrapper").show();
	$("#membership_table_wrapper").hide();
	$("#membership_edit_form_wrapper").hide();
	$("#membership_add_button_wrapper").hide();
}

function clearMembershipInlineErrorForm()
{
	$("#membership_edit_form").validationEngine('hide');
	$("#membership_add_form").validationEngine('hide');	
}

function loadMembershipTable() {
	clearMembershipInlineErrorForm();
	$("#membership_add_form_wrapper").hide();
	$("#membership_edit_form_wrapper").html('');
	$("#membership_table_wrapper").show();
	$("#membership_add_button_wrapper").show();
}

function loadMembershipDeleteDialog(id)
{
	clearMembershipInlineErrorForm();
	var membership_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#membership_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_membership',{membership_id:membership_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#membership_wrapper").html('');
						loadPage("#membership");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadMembershipEditForm(id)
{
	var membership_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#membership_edit_form_wrapper").show();
	$("#membership_table_wrapper").hide();
	$("#membership_add_form_wrapper").hide();
	$("#membership_add_button_wrapper").hide();
	$("#membership_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_membership_edit_form',{membership_id:membership_id},
	function(o){
		$("#membership_edit_form_wrapper").html(o);
		
	});
}

//end of membership

//memo

function loadMemoAddForm() {
	
	$("#memo_add_form_wrapper").show();
	$("#memo_table_wrapper").hide();
	$("#memo_edit_form_wrapper").hide();
	$("#memo_add_button_wrapper").hide();
}

function clearMemoInlineErrorForm()
{
	$("#memo_edit_form").validationEngine('hide');
	$("#memo_add_form").validationEngine('hide');	
}

function loadMemoTable() {
	//clearMemoInlineErrorForm();
	$("#memo_add_form_wrapper").hide();
	$("#memo_edit_form_wrapper").html('');
	$("#memo_table_wrapper").show();
	$("#memo_add_button_wrapper").show();
}

function loadMemoDeleteDialog(id)
{
	clearMemoInlineErrorForm();
	var memo_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#memo_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_memo',{memo_id:memo_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#memo_notes_wrapper").html('');
						loadPage("#memo_notes");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadMemoEditForm(id)
{
	var memo_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#memo_edit_form_wrapper").show();
	$("#memo_table_wrapper").hide();
	$("#memo_add_form_wrapper").hide();
	$("#memo_add_button_wrapper").hide();
	$("#memo_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_memo_edit_form',{memo_id:memo_id},
	function(o){
		$("#memo_edit_form_wrapper").html(o);
		
	});
}

// end memo

//leave available

function loadLeaveAvailableAddForm(eid) {
	$("#leave_available_add_form_wrapper").show();
	$("#leave_available_add_form_wrapper").html(loading_image);	
	$.get(base_url+'employee/_load_add_leave_available_form',{eid:eid},
	function(o){
		$("#leave_available_add_form_wrapper").html(o);
		
	});
}

function loadLeaveAvailableAddFormDepre() {
	
	$("#leave_available_add_form_wrapper").show();
	$("#leave_available_table_wrapper").hide();
	$("#leave_available_edit_form_wrapper").hide();
	$("#leave_available_add_button_wrapper").hide();
}

function clearLeaveAvailableInlineErrorForm()
{
	$("#leave_available_edit_form").validationEngine('hide');
	$("#leave_available_add_form").validationEngine('hide');	
}

function loadLeaveAvailableTableDepre() {
	clearLeaveAvailableInlineErrorForm();
	$("#leave_available_add_form_wrapper").hide();
	$("#leave_available_edit_form_wrapper").html('');
	$("#leave_available_table_wrapper").show();
	$("#leave_available_add_button_wrapper").show();
}

function loadLeaveAvailableTable() {
	clearLeaveAvailableInlineErrorForm();
	$("#leave_available_add_form_wrapper").html("");
	$("#leave_available_edit_form_wrapper").html('');
	$("#leave_available_table_wrapper").show();
	$("#leave_available_add_button_wrapper").show();
}

function loadLeaveAvailableDeleteDialog(id)
{
	clearLeaveAvailableInlineErrorForm();
	var leave_available_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#leave_available_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_leave_available',{leave_available_id:leave_available_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#leave_wrapper").html('');
						loadPage("#leave");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadLeaveAvailableEditForm(id)
{
	var leave_available_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#leave_available_edit_form_wrapper").show();
	$("#leave_available_table_wrapper").hide();
	$("#leave_available_add_form_wrapper").hide();
	$("#leave_available_add_button_wrapper").hide();
	$("#leave_available_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_leave_available_edit_form',{leave_available_id:leave_available_id},
	function(o){
		$("#leave_available_edit_form_wrapper").html(o);
		
	});
}

// end leave available

//leave request

function loadLeaveRequestAddForm() {
	
	$("#leave_request_add_form_wrapper").show();
	$("#leave_request_table_wrapper").hide();
	$("#leave_request_edit_form_wrapper").hide();
	$("#leave_request_add_button_wrapper").hide();
}

function clearLeaveRequestInlineErrorForm()
{
	$("#leave_request_edit_form").validationEngine('hide');
	$("#leave_request_add_form").validationEngine('hide');	
}

function loadLeaveRequestTable() {
	clearLeaveRequestInlineErrorForm();
	$("#leave_request_add_form_wrapper").hide();
	$("#leave_request_edit_form_wrapper").html('');
	$("#leave_request_table_wrapper").show();
	$("#leave_request_add_button_wrapper").show();
}

function loadLeaveRequestDeleteDialog(id)
{
	clearLeaveRequestInlineErrorForm();
	var leave_request_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#leave_request_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_leave_request',{leave_request_id:leave_request_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#leave_wrapper").html('');
						loadPage("#leave");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadLeaveRequestEditForm(id)
{
	var leave_request_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#leave_request_edit_form_wrapper").show();
	$("#leave_request_table_wrapper").hide();
	$("#leave_request_add_form_wrapper").hide();
	$("#leave_request_add_button_wrapper").hide();
	$("#leave_request_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_leave_request_edit_form',{leave_request_id:leave_request_id},
	function(o){
		$("#leave_request_edit_form_wrapper").html(o);
		
	});
}

// end leave request

//attachment

function loadAttachmentAddForm() {
	
	$("#attachment_add_form_wrapper").show();
	$("#attachment_table_wrapper").hide();
	$("#attachment_edit_form_wrapper").hide();
	$("#attachment_add_button_wrapper").hide();
}

function clearAttachmentInlineErrorForm()
{
	$("#attachment_edit_form").validationEngine('hide');
	$("#attachment_add_form").validationEngine('hide');	
}

function loadAttachmentTable() {
	clearAttachmentInlineErrorForm();
	$("#attachment_add_form_wrapper").hide();
	$("#attachment_edit_form_wrapper").html('');
	$("#attachment_table_wrapper").show();
	$("#attachment_add_button_wrapper").show();
}

function loadAttachmentDeleteDialog(id)
{
	clearAttachmentInlineErrorForm();
	var attachment_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#attachment_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_attachment',{attachment_id:attachment_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#attachment_wrapper").html('');
						loadPage("#attachment");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadAttachmentEditForm(id)
{
	var attachment_id = id;
	var employee_id = $("#employee_id").val();	
	
	$("#attachment_edit_form_wrapper").show();
	$("#attachment_table_wrapper").hide();
	$("#attachment_add_form_wrapper").hide();
	$("#attachment_add_button_wrapper").hide();
	$("#attachment_edit_form_wrapper").html(loading_message);
	$.post(base_url+'employee/_load_attachment_edit_form',{attachment_id:attachment_id},
	function(o){
		$("#attachment_edit_form_wrapper").html(o);
		
	});
}

//end of attachment


function hide_all_canvass() {

	$("#personal_details_wrapper").hide();
	$("#contact_details_wrapper").hide();
	$("#emergency_contacts_wrapper").hide();
	$("#dependents_wrapper").hide();
	$("#employee_benefits").hide();
	$("#banks_wrapper").hide();
	$("#medical_history_wrapper").hide();
	$("#employment_status_wrapper").hide();
	$("#compensation_wrapper").hide();
	$("#performance_wrapper").hide();
	$("#training_wrapper").hide();
	$("#memo_notes_wrapper").hide();
	$("#supervisor_wrapper").hide();
	$("#deductions_wrapper").hide();
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
	$("#project_site_history_wrapper").hide();
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
	$("#deductions_wrapper").html('');
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
	$("#project_site_history_wrapper").hide();
	
}

function hideEmployeeSummary()
{
	
}

function clearInlineValidationForm()
{
	clearDirectDepositInlineErrorForm();
	clearDependentInlineErrorForm();
	clearEmergencyContactInlineErrorForm();
	clearContactDetailsInlineErrorForm();
	clearPersonalDetailsInlineErrorForm();
}

function loadAddNewFieldForm()
{
	$("#personal_details_table").last().append("<tr><td align='right' valign='top'><input name='title' type='text' id='title' size='10' /></td><td valign='top'><input name='value' type='text' id='value' size='10' />+</td><td valign='top'>&nbsp;</td></tr>");
	
}

function onCheckCompensationHistory() {
	$("#compensation_history_to").validationEngine('hide'); 
	if ($('#present').attr('checked')) {
		$("#compensation_history_to_tr").hide();
	}else {
		
		$("#compensation_history_to_tr").show();
	}
	
}

function onCheckCompensationHistoryAdd() {
	$("#compensation_history_to_add").validationEngine('hide');  
	if ($('#present').attr('checked')) {	
		$("#compensation_history_to_tr").hide();
	}else {
		
		$("#compensation_history_to_tr").show();
	}
	
}

function _printEmployeeDetails(h_id) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Print Options';
	var width = 850;
	var height = 'auto';
	$.post(base_url + 'employee/_load_print_employee_details_options', {h_id:h_id}, function(data) {
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

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function _addEmployeeHistoryDialog(h_employee_id,employee_name) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Add History ('+employee_name+')';
	var width = 600;
	var height = 'auto';
	$.post(base_url + 'employee/_load_employee_history_form', {h_employee_id:h_employee_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#add_history_form'
		});
	});
}


function load_employee_history_list_dt() {
	$('#employee_history_list_dt_wrapper').html(loading_message);
	$.post(base_url + 'employee/_load_employee_history_list_dt',{},function(o) {
		$('#employee_history_list_dt_wrapper').html(o);
	});
}

function editEmployeeHistoryDialog(h_id) {
	_editEmployeeHistoryDialog(h_id,{
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

function deleteEmployeeHistory(eid) {	
	_deleteEmployeeHistory(eid, {
		onYes: function(o) {	
			load_employee_history_list_dt();			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveEmployee(eid) {	
	_archiveEmployee(eid, {
		onYes: function(o) {	
			location.reload(); 
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreEmployee(eid) {	
	_restoreEmployee(eid, {
		onYes: function(o) {	
			location.reload(); 
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function deleteSpecificSchedule(schedule_id) {	
	_deleteSpecificSchedule(schedule_id, {
		onYes: function(o) {	
			$("#work_schedule_wrapper").html("");					
			displayPage({canvass:'#work_schedule_wrapper',parameter:'employee/_load_work_schedule?employee_id='+o.employee_id});				
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}


function clearWorkExperienceInlineErrorForm()
{
	$("#work_experience_edit_form").validationEngine('hide');
	$("#work_experience_add_form").validationEngine('hide');	
}

function loadWorkExperienceDialog(h_id)
{
	clearWorkExperienceInlineErrorForm();

	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#work_experience_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'employee/_delete_work_experience',{h_id:h_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#work_experience_wrapper").html('');
						loadPage("#work_experience");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function hideEmployeeSummaryWrapper()
{
	$("#employee_summary_wrapper").hide();
}




//project_site_history
//load add form
function loadProjectSiteHistoryAddForm() {

	$("#project_site_history_add_form_wrapper").show();
	$("#project_site_history_table_wrapper").hide();
	$("#project_site_history_edit_form_wrapper").hide();
	$("#project_site_history_add_button_wrapper").hide();
}

function clearProjectSiteHistoryInlineErrorForm()
{
	$("#project_site_history_edit_form").validationEngine('hide');
	$("#project_site_history_add_form").validationEngine('hide');


	$('.text-input').val('');
	$('.select_option').val('');
}

//load table
function loadProjectSiteHistoryTable() {
	clearProjectSiteHistoryInlineErrorForm();
	$("#project_site_history_add_form_wrapper").hide();
	$("#project_site_history_edit_form_wrapper").html('');
	$("#project_site_history_table_wrapper").show();
	$("#project_site_history_add_button_wrapper").show();
}

function loadProjectSiteHistoryDeleteDialog(id)
{
	clearProjectSiteHistoryInlineErrorForm();
	var project_site_history_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";

	var dialog_id = $("#project_site_history_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');

					$.post(base_url+'employee/remove_project',{project_site_history_id:project_site_history_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#project_site_history_wrapper").html('');
						var hash = window.location.hash;
						loadPage(hash);

					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();

				}
			}
		}).show();
}

//load edit form
function loadProjectSiteHistoryEditForm(id)
{
	var project_history_id = id;
	var employee_id = $("#employee_id").val();

	$("#project_site_history_edit_form_wrapper").show();
	$("#project_site_history_table_wrapper").hide();
	$("#project_site_history_add_form_wrapper").hide();
	$("#project_site_history_add_button_wrapper").hide();
	$("#project_site_history_edit_form_wrapper").html(loading_message);

	$.post(base_url+'employee/_load_project_history_edit_form',
		{
			project_history_id:project_history_id 
		},
	
	function(o){
		$("#project_site_history_edit_form_wrapper").html(o);

	});
}


// end of project_site_history

