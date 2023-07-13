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

function loadPage(hash) 
{
	hide_all_canvass();
	clearInlineValidationForm();
	loadPhoto();
	$("#application_event_wrapper").html('');
	$("#applicant_summary_wrapper").show();
	var applicant_id = $("#applicant_id").val();
	if(hash=='#personal_details' || hash=='') {	
		$("#applicant_summary_wrapper").hide();
		displayPage({canvass:'#personal_details_wrapper',parameter:'recruitment/_load_personal_details?applicant_id='+applicant_id});
	}else if(hash=='#application_history') {
		displayPage({canvass:'#application_history_wrapper',parameter:'recruitment/_load_application_history?applicant_id='+applicant_id});
	}else if(hash=='#contact_details') {
		displayPage({canvass:'#contact_details_wrapper',parameter:'recruitment/_load_contact_details?applicant_id='+applicant_id});
	}else if(hash=='#requirements') {
		displayPage({canvass:'#requirements_wrapper',parameter:'recruitment/_load_requirements?applicant_id='+applicant_id});
	}else if(hash=='#examination') {
		displayPage({canvass:'#examination_wrapper',parameter:'recruitment/_load_examination?applicant_id='+applicant_id});
	}else if(hash=='#attachment') {
		displayPage({canvass:'#attachment_wrapper',parameter:'recruitment/_load_attachment?applicant_id='+applicant_id});
	}else if(hash=='#interview') {
		displayPage({canvass:'#application_event_wrapper',parameter:'recruitment/_load_interview?applicant_id='+applicant_id});
	}else if(hash=='#offer_job') {
		displayPage({canvass:'#application_event_wrapper',parameter:'recruitment/_load_offer_job?applicant_id='+applicant_id});
	}else if(hash=='#rejected') {
		displayPage({canvass:'#application_event_wrapper',parameter:'recruitment/_load_rejected?applicant_id='+applicant_id});
	}else if(hash=='#hired') {
		displayPage({canvass:'#application_event_wrapper',parameter:'recruitment/_load_hired?applicant_id='+applicant_id});
	}else if(hash=='#declined_offer') {

		displayPage({canvass:'#application_event_wrapper',parameter:'recruitment/_load_declined_offer?applicant_id='+applicant_id});
	}else if(hash=='#work_experience') {
		displayPage({canvass:'#work_experience_wrapper',parameter:'recruitment/_load_work_experience?applicant_id='+applicant_id});
	}else if(hash=='#education') {
		displayPage({canvass:'#education_wrapper',parameter:'recruitment/_load_education?applicant_id='+applicant_id});
	}else if(hash=='#skills') {
		displayPage({canvass:'#skills_wrapper',parameter:'recruitment/_load_skills?applicant_id='+applicant_id});
	}else if(hash=='#language') {
		displayPage({canvass:'#language_wrapper',parameter:'recruitment/_load_language?applicant_id='+applicant_id});
	}else if(hash=='#license') {
		displayPage({canvass:'#license_wrapper',parameter:'recruitment/_load_license?applicant_id='+applicant_id});
	}else if(hash=='#training') {
		displayPage({canvass:'#training_wrapper',parameter:'recruitment/_load_training?applicant_id='+applicant_id});
	}
}


function loadApplicantSummary()
{	
	var applicant_id = $("#applicant_id").val();
	$.post(base_url+'recruitment/_load_applicant_summary',{applicant_id:applicant_id},
	function(o){
		$("#applicant_summary_wrapper").html(o);
	});
}

function hideApplicantSummary()
{
	$("#applicant_summary_wrapper").hide();
}


function loadPhoto()
{
	var applicant_id = $("#applicant_id").val();
	
	$("#photo_frame_wrapper").html('');
	$("#photo_frame_wrapper").html(loading_image);
	$("#photo_frame_personal_wrapper").html(loading_image);
	$.post(base_url+'recruitment/_load_photo_frame',{applicant_id:applicant_id},
	function(o){
		
		$("#photo_frame_wrapper").html(o);
		$("#photo_frame_personal_wrapper").html(o);
		$("#photo_frame_personal_edit_wrapper").html(o);		
	});

	$.post(base_url+'recruitment/_get_photo_filename',{applicant_id:applicant_id},function(o){
		$("#photo_filename_wrapper").html(o);
		$("#photo").val($("#photo_filename_text").val());
	});
}

function loadPhotoDialog()
{
	var applicant_id = $("#applicant_id").val();
	$("#photo_wrapper").html(loading_message);
	dialogGeneric('#photo_wrapper',{title:'Photo'});
	$.post(base_url+'recruitment/_load_photo',{applicant_id:applicant_id},
	function(o){
		$("#photo_wrapper").html(o);
		dialogGeneric('#photo_wrapper',{title:'Photo',height:'auto'});		
	});	
}

function closePhotoDialog()
{
	closeDialog("#photo_wrapper",'');
}

// personal details

function loadPersonalDetailsForm() {
	
	$("#personal_details_table_wrapper").hide();
	$("#personal_details_form").show();
	
}

function loadPersonalDetailsTable() {
	clearPersonalDetailsInlineErrorForm();
	$("#personal_details_form").hide();
	$("#personal_details_table_wrapper").show();
}

function clearPersonalDetailsInlineErrorForm()
{
	$("#personal_details_form").validationEngine('hide');
} 
	

// end of personal details

//contact details

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

//end of contact details

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
		height: 240,
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
					
					$.post(base_url+'recruitment/_delete_attachment',{attachment_id:attachment_id},
					function(o){
						dialogOkBox('Successfully Deleted',{height:240,width:390});
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#attachment_edit_form_wrapper").show();
	$("#attachment_table_wrapper").hide();
	$("#attachment_add_form_wrapper").hide();
	$("#attachment_add_button_wrapper").hide();
	$("#attachment_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_attachment_edit_form',{attachment_id:attachment_id},
	function(o){
		$("#attachment_edit_form_wrapper").html(o);
		
	});
}

//end of attachment

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
	var applicant_id = $("#applicant_id").val();
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 240,
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
					
					$.post(base_url+'recruitment/_add_default_requirements',{applicant_id:applicant_id},
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
	var applicant_id = $("#applicant_id").val();
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 240,
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
					
					$.post(base_url+'recruitment/_delete_requirements',{requirement_id:requirement_id,applicant_id:applicant_id},
					function(o){
						dialogOkBox('Successfully Deleted',{height:240,width:390});
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

function loadRequirementsEditForm(id)
{
	var requirement_id = id;
	var applicant_id = $("#applicant_id").val();	
	
	$("#requirements_edit_form_wrapper").show();
	$("#requirements_table_wrapper").hide();
	$("#requirements_add_form_wrapper").hide();
	$("#requirements_add_button_wrapper").hide();
	$("#requirements_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_requirements_edit_form',{requirement_id:requirement_id},
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
//end requirements

//examination

/// Dependent

function loadExaminationAddForm() {
	
	$("#examination_add_form_wrapper").show();
	$("#examination_table_wrapper").hide();
	$("#examination_edit_form_wrapper").hide();
	$("#examination_add_button_wrapper").hide();
}

function clearExaminationInlineErrorForm()
{
	$("#examination_edit_form").validationEngine('hide');
	$("#examination_add_form").validationEngine('hide');	
}

function loadExaminationTable() {
	clearExaminationInlineErrorForm();
	$("#examination_add_form_wrapper").hide();
	$("#examination_edit_form_wrapper").html('');
	$("#examination_table_wrapper").show();
	$("#examination_add_button_wrapper").show();
}

function loadExaminationDeleteDialog(id)
{
	clearExaminationInlineErrorForm();
	var examination_id = id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#examination_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 240,
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
					
					$.post(base_url+'recruitment/_delete_examination',{examination_id:examination_id},
					function(o){
						dialogOkBox('Successfully Deleted',{height:240,width:390});
						$("#examination_wrapper").html('');
						loadPage("#examination");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadExaminationEditForm(id)
{
	var examination_id = id;
	var applicant_id = $("#applicant_id").val();	
	
	$("#examination_edit_form_wrapper").show();
	$("#examination_table_wrapper").hide();
	$("#examination_add_form_wrapper").hide();
	$("#examination_add_button_wrapper").hide();
	$("#examination_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_examination_edit_form',{examination_id:examination_id},
	function(o){
		$("#examination_edit_form_wrapper").html(o);
		
	});
}


//end of examination

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
		height: 200,
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
					
					$.post(base_url+'recruitment/_delete_work_experience',{work_experience_id:work_experience_id},
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#work_experience_edit_form_wrapper").show();
	$("#work_experience_table_wrapper").hide();
	$("#work_experience_add_form_wrapper").hide();
	$("#work_experience_add_button_wrapper").hide();
	$("#work_experience_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_work_experience_edit_form',{work_experience_id:work_experience_id},
	function(o){
		$("#work_experience_edit_form_wrapper").html(o);
		
	});
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
	$("#training_edit_form_wrapper").html('');
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
		height: 200,
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
					
					$.post(base_url+'recruitment/_delete_training',{training_id:training_id},
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#training_edit_form_wrapper").show();
	$("#training_table_wrapper").hide();
	$("#training_add_form_wrapper").hide();
	$("#training_add_button_wrapper").hide();
	$("#training_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_training_edit_form',{training_id:training_id},
	function(o){
		$("#training_edit_form_wrapper").html(o);
		
	});
}


// training



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
		height: 200,
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
					
					$.post(base_url+'recruitment/_delete_education',{education_id:education_id},
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#education_edit_form_wrapper").show();
	$("#education_table_wrapper").hide();
	$("#education_add_form_wrapper").hide();
	$("#education_add_button_wrapper").hide();
	$("#education_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_education_edit_form',{education_id:education_id},
	function(o){
		$("#education_edit_form_wrapper").html(o);
		
	});
}
//education

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
		height: 200,
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
					
					$.post(base_url+'recruitment/_delete_skill',{skill_id:skill_id},
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#skill_edit_form_wrapper").show();
	$("#skill_table_wrapper").hide();
	$("#skill_add_form_wrapper").hide();
	$("#skill_add_button_wrapper").hide();
	$("#skill_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_skill_edit_form',{skill_id:skill_id},
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
		height: 200,
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
					
					$.post(base_url+'recruitment/_delete_language',{language_id:language_id},
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#language_edit_form_wrapper").show();
	$("#language_table_wrapper").hide();
	$("#language_add_form_wrapper").hide();
	$("#language_add_button_wrapper").hide();
	$("#language_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_language_edit_form',{language_id:language_id},
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
		height: 200,
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
					
					$.post(base_url+'recruitment/_delete_license',{license_id:license_id},
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
	var applicant_id = $("#applicant_id").val();	
	
	$("#license_edit_form_wrapper").show();
	$("#license_table_wrapper").hide();
	$("#license_add_form_wrapper").hide();
	$("#license_add_button_wrapper").hide();
	$("#license_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_license_edit_form',{license_id:license_id},
	function(o){
		$("#license_edit_form_wrapper").html(o);
		
	});
}



//end of license

//load applicatoin history

function loadApplicationHistoryEditForm(id)
{
	var application_history_id = id;
	var applicant_id = $("#applicant_id").val();	
	
	$("#application_history_edit_form_wrapper").show();
	$("#application_history_table_wrapper").hide();
	$("#application_history_add_form_wrapper").hide();
	$("#application_history_add_button_wrapper").hide();
	$("#application_history_edit_form_wrapper").html(loading_message);
	$.post(base_url+'recruitment/_load_application_history_edit_form',{application_history_id:application_history_id},
	function(o){
		$("#application_history_edit_form_wrapper").html(o);
		
	});
}

function loadApplicationHistoryTable() {
	clearApplicationHistoryInlineErrorForm();
	$("#application_history_edit_form_wrapper").html('');
	$("#application_history_table_wrapper").show();
}

function clearApplicationHistoryInlineErrorForm()
{
	$("#license_edit_form").validationEngine('hide');
	$("#license_add_form").validationEngine('hide');	
}


function clear_all_canvass() {

	$("#personal_details_wrapper").html('');
	$("#contact_details_wrapper").html('');
	$("#emergency_contacts_wrapper").html('');
	$("#training_wrapper").html('');
	$("#work_experience_wrapper").html('');
	$("#education_wrapper").html('');
	$("#skills_wrapper").html('');
	$("#language_wrapper").html('');
	$("#license_wrapper").html('');
}


function hide_all_canvass() {

	$("#personal_details_wrapper").hide();
	$("#contact_details_wrapper").hide();
	$("#application_history_wrapper").hide();
	$("#requirements_wrapper").hide();
	$("#examination_wrapper").hide();
	$("#attachment_wrapper").hide();
	$("#work_experience_wrapper").hide();	
	$("#education_wrapper").hide();	
	$("#training_wrapper").hide();	
	$("#skills_wrapper").hide();	
	$("#license_wrapper").hide();	
	$("#language_wrapper").hide();	
}




function hideEmployeeSummary()
{
	
}

function clearInlineValidationForm()
{

	clearEducationInlineErrorForm();
	clearSkillInlineErrorForm();
	clearLanguageInlineErrorForm();
	clearLicenseInlineErrorForm();
}

