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

function checkReportType()
{
	var report_type = $("#loan_report_type").val();	
	if(report_type=='default'){

		$("#month").show();
		$("#year").show();
		$("#month_label").show();
		$("#year_label").show();

		$("#payroll_period").hide();	
		$("#payroll_period_label").hide();		

		$("#all_value").show();
		$("#all_value2").show();		

	} else if(report_type=='semi_month_loan_reg') {
		$("#month").hide();
		$("#year").hide();
		$("#month_label").hide();
		$("#year_label").hide();

		$("#payroll_period").show();	
		$("#payroll_period_label").show();

		$("#all_value").hide();
		$("#all_value2").hide();
	} else {
		$("#month").show();	
		$("#year").show();
		$("#month_label").show();	
		$("#year_label").show();	

		$("#payroll_period").hide();	
		$("#payroll_period_label").hide();		

		$("#all_value").hide();
		$("#all_value2").hide();
	}	
}

function loadPage(hash) 
{
	hide_all_canvass();
	
	if(hash=='#sss_r1a') {	
		displayPage({canvass:'#sss_r1a_wrapper',parameter:'reports/_load_sss_r1a?applicant_id='});
	}else if(hash=='#philhealth') {
		displayPage({canvass:'#philhealth_wrapper',parameter:'reports/_load_philhealth'});
	}else if(hash=='#alphalist') {
		displayPage({canvass:'#alphalist_wrapper',parameter:'reports/_load_alpha_list'});
	}else if(hash=='#bir_2316') {
		displayPage({canvass:'#bir_2316_wrapper',parameter:'reports/_load_bir_2316'});
	}else if(hash=='#yearly_bonus') {
		displayPage({canvass:'#yearly_bonus_wrapper',parameter:'reports/_load_yearly_bonus'});
	}else if(hash=='#pagibig') {
		displayPage({canvass:'#pagibig_wrapper',parameter:'reports/_load_pagibig'});
	}else if(hash=='#tax') {
		displayPage({canvass:'#tax_wrapper',parameter:'reports/_load_tax'});
	}else if(hash=='#annual_tax') {
		displayPage({canvass:'#annual_tax_wrapper',parameter:'reports/_load_annual_tax'});
	}else if(hash=='#other_earnings') {
		displayPage({canvass:'#other_earnings_wrapper',parameter:'reports/_load_other_earnings'});
	}else if(hash=='#contribution') {
		displayPage({canvass:'#contribution_wrapper',parameter:'reports/_load_contribution'});
	}else if(hash=='#payslip') {
		displayPage({canvass:'#payslip_wrapper',parameter:'reports/_load_payslip'});
	}else if(hash=='#cash_file') {
		displayPage({canvass:'#cash_file_wrapper',parameter:'reports/_load_cash_file'});
	}else if(hash=='#payroll_register') {
		displayPage({canvass:'#payroll_register_wrapper',parameter:'reports/_load_payroll_register'});
	}else if(hash=='#cost_center') {
		displayPage({canvass:'#cost_center_wrapper',parameter:'reports/_load_cost_center'});		
	}else if(hash=='#leave_converted') {
		displayPage({canvass:'#leave_converted_wrapper',parameter:'reports/_load_leave_converted'});
	}else if(hash=='#payable') {
		displayPage({canvass:'#payable_wrapper',parameter:'reports/_load_payable'});
	}else if(hash=='#bank') {
		displayPage({canvass:'#bank_wrapper',parameter:'reports/_load_bank'});
	}else if(hash=='#applicant_list') {
		displayPage({canvass:'#applicant_list_wrapper',parameter:'reports/_load_applicant_list'});
	}else if(hash=='#applicant_by_schedule') {
		displayPage({canvass:'#applicant_by_schedule_wrapper',parameter:'reports/_load_applicant_by_schedule'});
	}else if(hash=='#applicants_education_training') {
		displayPage({canvass:'#applicants_education_training_wrapper',parameter:'reports/_load_applicants_education_training'});
	}else if(hash=='#applications_received') {
		displayPage({canvass:'#applications_received_wrapper',parameter:'reports/_load_applications_received'});
	}else if(hash=='#applicants_statistics') {
		displayPage({canvass:'#applicants_statistics_wrapper',parameter:'reports/_load_applicants_statistics'});
	}else if(hash=='#planned_activities') {
		displayPage({canvass:'#planned_activities_wrapper',parameter:'reports/_load_planned_activities'});
	}else if(hash=='#pending_applicants') {
		displayPage({canvass:'#pending_applicants_wrapper',parameter:'reports/_load_pending_applicants'});
	}else if(hash=='#job_advertisements') {
		displayPage({canvass:'#job_advertisements_wrapper',parameter:'reports/_load_job_advertisements'});
	}else if(hash=='#task_overview') {
		displayPage({canvass:'#task_overview_wrapper',parameter:'reports/_load_task_overview'});
	}else if(hash=='#anniversaries') {
		displayPage({canvass:'#anniversaries_wrapper',parameter:'reports/_load_anniversaries'});
	}else if(hash=='#power_of_attorney') {
		displayPage({canvass:'#power_of_attorney_wrapper',parameter:'reports/_load_power_of_attorney'});
	}else if(hash=='#education') {
		displayPage({canvass:'#education_wrapper',parameter:'reports/_load_education'});
	}else if(hash=='#employee_entered_left') {
		displayPage({canvass:'#employee_entered_left_wrapper',parameter:'reports/_load_employee_entered_left'});
	}else if(hash=='#family_members') {
		displayPage({canvass:'#family_members_wrapper',parameter:'reports/_load_family_members'});
	}else if(hash=='#birthday_list') {
		displayPage({canvass:'#birthday_list_wrapper',parameter:'reports/_load_birthday_list'});
	}else if(hash=='#vehicle_list') {
		displayPage({canvass:'#vehicle_list_wrapper',parameter:'reports/_load_vehicle_list'});
	}else if(hash=='#telephone_directory') {
		displayPage({canvass:'#telephone_directory_wrapper',parameter:'reports/_load_telephone_directory'});
	}else if(hash=='#time_spend_pay_scale') {
		displayPage({canvass:'#time_spend_pay_scale_wrapper',parameter:'reports/_load_time_spend_pay_scale'});
	}else if(hash=='#hr_master_data_sheet') {
		displayPage({canvass:'#hr_master_data_sheet_wrapper',parameter:'reports/_load_hr_master_data_sheet'});
	}else if(hash=='#flexible_employee_data') {
		displayPage({canvass:'#flexible_employee_data_wrapper',parameter:'reports/_load_flexible_employee_data'});
	}else if(hash=='#list_of_employees') {
		displayPage({canvass:'#list_of_employees_wrapper',parameter:'reports/_load_list_of_employees'});
	}else if(hash=='#leave_overview') {
		displayPage({canvass:'#leave_overview_wrapper',parameter:'reports/_load_leave_overview'});
	}else if(hash=='#headcount_development') {
		displayPage({canvass:'#headcount_development_wrapper',parameter:'reports/_load_headcount_development'});
	}else if(hash=='#nationalities') {
		displayPage({canvass:'#nationalities_wrapper',parameter:'reports/_load_nationalities'});
	}else if(hash=='#salary_list') {
		displayPage({canvass:'#salary_list_wrapper',parameter:'reports/_load_salary_list'});
	}else if(hash=='#certificate_of_employment') {
		displayPage({canvass:'#certificate_of_employment_wrapper',parameter:'reports/_load_certificate_of_employment'});
	}else if(hash=='#profile_matchup') {
		displayPage({canvass:'#profile_matchup_wrapper',parameter:'reports/_load_profile_matchup'});
	}else if(hash=='#profile_evaluation') {
		displayPage({canvass:'#profile_evaluation_wrapper',parameter:'reports/_load_profile_evaluation'});
	}else if(hash=='#qualification') {
		displayPage({canvass:'#qualification_wrapper',parameter:'reports/_load_qualification'});
	}else if(hash=='#development_plan') {
		displayPage({canvass:'#development_plan_wrapper',parameter:'reports/_load_development_plan'});
	}else if(hash=='#development_item') {
		displayPage({canvass:'#development_item_wrapper',parameter:'reports/_load_development_item'});
	}else if(hash=='#qualification_template') {
		displayPage({canvass:'#qualification_template_wrapper',parameter:'reports/_load_qualification_template'});
	}else if(hash=='#appraisal_evaluation') {
		displayPage({canvass:'#appraisal_evaluation_wrapper',parameter:'reports/_load_appraisal_evaluation'});
	}else if(hash=='#development_plan_template') {
		displayPage({canvass:'#development_plan_template_wrapper',parameter:'reports/_load_development_plan_template'});
	}else if(hash=='#appraisal_template') {
		displayPage({canvass:'#appraisal_template_wrapper',parameter:'reports/_load_appraisal_template'});
	}else if(hash=='#careers') {
		displayPage({canvass:'#careers_wrapper',parameter:'reports/_load_careers'});
	}else if(hash=='#vacant_obselete_position') {
		displayPage({canvass:'#vacant_obselete_position_wrapper',parameter:'reports/_load_vacant_obselete_position'});
	}else if(hash=='#qualification_overview') {
		displayPage({canvass:'#qualification_overview_wrapper',parameter:'reports/_load_qualification_overview'});
	}else if(hash=='#eligible_employee') {
		displayPage({canvass:'#eligible_employee_wrapper',parameter:'reports/_load_eligible_employee'});
	}else if(hash=='#participation') {
		displayPage({canvass:'#participation_wrapper',parameter:'reports/_load_participation'});
	}else if(hash=='#total_compensation_statement') {
		displayPage({canvass:'#total_compensation_statement_wrapper',parameter:'reports/_load_total_compensation_statement'});
	}else if(hash=='#job_salary_rate') {
		displayPage({canvass:'#job_salary_rate_wrapper',parameter:'reports/_load_job_salary_rate'});
	}else if(hash=='#plan_labor_cost') {
		displayPage({canvass:'#plan_labor_cost_wrapper',parameter:'reports/_load_plan_labor_cost'});
	}else if(hash=='#personal_work_schedule') {
		displayPage({canvass:'#personal_work_schedule_wrapper',parameter:'reports/_load_personal_work_schedule'});
	}else if(hash=='#daily_work_schedule') {
		displayPage({canvass:'#daily_work_schedule_wrapper',parameter:'reports/_load_daily_work_schedule'});
	}else if(hash=='#attendance_absence_data') {
		displayPage({canvass:'#attendance_absence_data_wrapper',parameter:'reports/_load_attendance_absence_data'});
	}else if(hash=='#display_absence_quota_information') {
		displayPage({canvass:'#display_absence_quota_information_wrapper',parameter:'reports/_load_display_tardiness'});	//displayPage({canvass:'#display_absence_quota_information_wrapper',parameter:'reports/_load_display_absence_quota_information'});
	}else if(hash=='#display_tardiness') {
		displayPage({canvass:'#display_absence_quota_information_wrapper',parameter:'reports/_load_display_tardiness'});
	}else if(hash=='#display_manpower_count') {
		displayPage({canvass:'#display_manpower_count_wrapper',parameter:'reports/_load_manpower_count'});
	}else if(hash=='#display_end_of_contract') {
		displayPage({canvass:'#display_end_of_contract_wrapper',parameter:'reports/_load_end_of_contract'});
	}else if(hash=='#display_daily_time_record') {
		displayPage({canvass:'#display_daily_time_record_wrapper',parameter:'reports/_load_daily_time_record'});
	}else if(hash=='#display_incomplete_time_in_out') {
		displayPage({canvass:'#display_incomplete_time_in_out_wrapper',parameter:'reports/_load_incomplete_time_in_out'});
	}else if(hash=='#display_incorrect_shift') {
		displayPage({canvass:'#display_incorrect_shift_wrapper',parameter:'reports/_load_incorrect_shift'});
	}else if(hash=='#display_loans') {
		displayPage({canvass:'#display_loans_wrapper',parameter:'reports/_load_loans_report'});
	}else if(hash=='#display_timesheet') {
		displayPage({canvass:'#display_timesheet_wrapper',parameter:'reports/_load_timesheet'});
	}else if(hash=='#display_overtime') {
		displayPage({canvass:'#display_overtime_wrapper',parameter:'reports/_load_overtime'});
	}else if(hash=='#display_undertime') {
		displayPage({canvass:'#display_undertime_wrapper',parameter:'reports/_load_undertime'});
	}else if(hash=='#display_leave') {
		displayPage({canvass:'#display_leave_wrapper',parameter:'reports/_load_leave'});
	}else if(hash=='#display_incentive_leave') {
		displayPage({canvass:'#display_incentive_leave_wrapper',parameter:'reports/_load_incentive_leave'});
	}else if(hash=='#display_leave_balance') {
		displayPage({canvass:'#display_leave_balance_wrapper',parameter:'reports/_load_leave_balance'});
	}else if(hash=='#display_employment_status') {
		displayPage({canvass:'#display_employment_status_wrapper',parameter:'reports/_load_employment_status'});
	}else if(hash=='#display_employee_details') {
		displayPage({canvass:'#display_employee_details_wrapper',parameter:'reports/_load_employee_details'});
	}else if(hash=='#display_ee_er_contribution') {
		displayPage({canvass:'#display_ee_er_contribution_wrapper',parameter:'reports/_load_ee_er_contribution'});
	} else if(hash=='#display_disciplinary_action') {		
		displayPage({canvass:'#display_disciplinary_action_wrappers',parameter:'reports/_load_disciplinary_action'});
	}else if(hash=='#display_resigned_employees') {
		displayPage({canvass:'#display_resigned_employees_wrapper',parameter:'reports/_load_resigned_employees'});
	}else if(hash=='#display_terminated_employees') {
		displayPage({canvass:'#display_terminated_employees_wrapper',parameter:'reports/_load_terminated_employees'});
	}else if(hash=='#display_birthday') {
		displayPage({canvass:'#display_birthday_wrapper',parameter:'reports/_load_birthday_list'});
	}else if(hash=='#display_shift_schedule') {
		displayPage({canvass:'#display_shift_schedule_wrapper',parameter:'reports/_load_shift_schedule'});
	}else if(hash=='#display_final_pay') {
		displayPage({canvass:'#display_final_pay_wrapper',parameter:'reports/_load_final_pay'});
	}else if(hash=='#display_perfect_attendance') {
		displayPage({canvass:'#display_perfect_attendance_wrapper',parameter:'reports/_load_perfect_attendance'});
	}else if(hash=='#display_coe') {		
		displayPage({canvass:'#display_coe_wrapper',parameter:'reports/_load_coe'});
	}else if(hash=='#display_actual_hours') {		
		displayPage({canvass:'#display_actual_hours_wrapper',parameter:'reports/_load_actual_hours'});
	}else if(hash=='#display_required_shift') {		
		displayPage({canvass:'#display_required_shift_wrapper',parameter:'reports/_load_required_shift'});
	}else if(hash=='#display_government_remittances') {		
		displayPage({canvass:'#display_government_remittances_wrapper',parameter:'reports/_load_government_remittances'});
	}else if(hash=='#display_last_pay') {		
		displayPage({canvass:'#display_last_pay_wrapper',parameter:'reports/_load_last_pay'});
	}
	else if(hash=='#audit_trail_data') {
		displayPage({canvass:'#audit_trail_data_wrapper',parameter:'reports/_load_audit_trail_data'});
	}
}

function clear_all_canvass() {

	$("#personal_details_wrapper").html('');

}

function load_report_total_pages(date_start,date_to){
	$("#pagibig_total_pages").html("<span style='font-size:11px;'>Calculating total number of pages...</span>");
	$("#print_page_text").html("");
	
	$.post(base_url + 'benchmark_bio/_load_total_pages',{date_start:date_start,date_to:date_to},
		function(o){
			$("#print_page_text").html("Print Page:");
			$("#pagibig_total_pages").html(o);		
		}
	);
}

function load_department_sections(eid){
	$('#li-sections').html("<small>" + loading_image + "</small>");
	$.get(base_url + 'reports/_load_department_sections',{eid:eid},function(o) {		
		$('#li-sections').html(o);
	});
}

function load_philhealth_report_total_pages(date_start,date_to){
	$("#philhealth_total_pages").html("<span style='font-size:11px;'>Calculating total number of pages...</span>");
	$("#philhealth_print_page_text").html("");
	
	$.post(base_url + 'benchmark_bio/_load_philhealth_total_pages',{date_start:date_start,date_to:date_to},
		function(o){
			$("#philhealth_print_page_text").html("Print Page:");
			$("#philhealth_total_pages").html(o);		
		}
	);
}

function hide_all_canvass() {
	$("#bir_2316_wrapper").hide();
	$("#sss_r1a_wrapper").hide();
	$("#philhealth_wrapper").hide();
	$("#pagibig_wrapper").hide();
	$("#tax_wrapper").hide();
	$("#annual_tax_wrapper").hide();
	$("#alphalist_wrapper").hide();
	$("#yearly_bonus_wrapper").hide();
	$("#contribution_wrapper").hide();
	$("#payslip_wrapper").hide();
	$("#payroll_register_wrapper").hide();
	$("#cost_center_wrapper").hide();
	$("#cash_file_wrapper").hide();
	$("#payable_wrapper").hide();
	$("#bank_wrapper").hide();
	$("#leave_converted_wrapper").hide();
	$("#applicant_list_wrapper").hide();
	$("#applicant_by_schedule_wrapper").hide();
	$("#applicants_education_training_wrapper").hide();
	$("#applications_received_wrapper").hide();
	$("#applicants_statistics_wrapper").hide();
	$("#other_earnings_wrapper").hide();
	$("#planned_activities_wrapper").hide();
	$("#pending_applicants_wrapper").hide();
	$("#job_advertisements_wrapper").hide();
	$("#task_overview_wrapper").hide();
	$("#anniversaries_wrapper").hide();
	$("#power_of_attorney_wrapper").hide();
	$("#education_wrapper").hide();
	$("#employee_entered_left_wrapper").hide();
	$("#family_members_wrapper").hide();
	$("#birthday_list_wrapper").hide();
	$("#vehicle_list_wrapper").hide();
	$("#telephone_directory_wrapper").hide();
	$("#time_spend_pay_scale_wrapper").hide();
	$("#hr_master_data_sheet_wrapper").hide();
	$("#flexible_employee_data_wrapper").hide();
	$("#list_of_employees_wrapper").hide();
	$("#leave_overview_wrapper").hide();
	$("#headcount_development_wrapper").hide();
	$("#nationalities_wrapper").hide();
	$("#salary_list_wrapper").hide();
	$("#certificate_of_employment_wrapper").hide();
	$("#display_loans_wrapper").hide();
	$("#profile_matchup_wrapper").hide();
	$("#profile_evaluation_wrapper").hide();
	$("#qualification_wrapper").hide();
	$("#development_plan_wrapper").hide();
	$("#development_item_wrapper").hide();
	$("#appraisal_evaluation_wrapper").hide();
	$("#qualification_template_wrapper").hide();
	$("#development_plan_template_wrapper").hide();
	$("#appraisal_template_wrapper").hide();
	$("#careers_wrapper").hide();
	$("#vacant_obselete_position_wrapper").hide();
	$("#qualification_overview_wrapper").hide();
	$("#eligible_employee_wrapper").hide();
	$("#participation_wrapper").hide();
	$("#total_compensation_statement_wrapper").hide();
	$("#job_salary_rate_wrapper").hide();
	$("#plan_labor_cost_wrapper").hide();
	$("#personal_work_schedule_wrapper").hide();
	$("#daily_work_schedule_wrapper").hide();
	$("#attendance_absence_data_wrapper").hide();
	$("#display_absence_quota_information_wrapper").hide();
    $('#display_manpower_count_wrapper').hide();
    $('#display_end_of_contract_wrapper').hide();
    $('#display_daily_time_record_wrapper').hide();
    $('#display_incomplete_time_in_out_wrapper').hide();
    $('#display_incorrect_shift_wrapper').hide();
    $('#display_timesheet_wrapper').hide();
    $('#display_disciplinary_action_wrappers').hide();
    $('#display_overtime_wrapper').hide();
    $('#display_undertime_wrapper').hide();
    $('#display_leave_wrapper').hide();
    $('#display_incentive_leave_wrapper').hide();
    $('#display_leave_balance_wrapper').hide();
    $('#display_employment_status_wrapper').hide();
    $('#display_employee_details_wrapper').hide();
    $('#display_ee_er_contribution_wrapper').hide();
    $('#display_terminated_employees_wrapper').hide();
    $('#display_resigned_employees_wrapper').hide();
    $('#display_birthday_wrapper').hide();
    $('#display_shift_schedule_wrapper').hide();
    $('#display_perfect_attendance_wrapper').hide();
    $('#display_coe_wrapper').hide();
    $('#display_actual_hours_wrapper').hide();
    $('#display_required_shift_wrapper').hide();
    $('#display_government_remittances_wrapper').hide();
    $('#display_final_pay_wrapper').hide();
    $('#display_last_pay_wrapper').hide();

    $("#audit_trail_data_wrapper").hide();
}

//applicant list
function checkIfAll()
{
	var search_field = $("#search_field").val();		
	if(search_field=='all'){
		$("#search").hide();
		$("#birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#search").hide();
		$("#birthdate").show();	
	}else {
		$("#birthdate").hide();
		$("#search").show();	
	}
}

function checkIfAllActualHoursReport()
{
	var search_field = $("#search_field").val();		
	if(search_field=='all'){
		$("#search").hide();
		$("#birthdate_actual_hours").hide();	
	}else if(search_field=='birthdate'){
		$("#search").hide();
		$("#birthdate_actual_hours").show();	
	}else {
		$("#birthdate_actual_hours").hide();
		$("#search").show();	
	}
}

function checkIfAllTardiness()
{
	var search_field = $("#tardi_search_field").val();	
	if(search_field=='all'){
		$("#tardi_search").hide();
		$("#tardi_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#tardi_search").hide();
		$("#tardi_birthdate").show();	
	}else {
		$("#tardi_birthdate").hide();
		$("#tardi_search").show();	
	}
}

function checkIfAllIncentiveLeave()
{
	var search_field = $("#incentive_leave_search_field").val();	
	if(search_field=='all'){
		$("#incentive_leave_search").hide();
		$("#incentive_leave_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#incentive_leave_search").hide();
		$("#incentive_leave_birthdate").show();	
	}else {
		$("#incentive_leave_birthdate").hide();
		$("#incentive_leave_search").show();	
	}
}

function checkIfAllLeave()
{
	var search_field = $("#leave_search_field").val();	
	if(search_field=='all'){
		$("#leave_search").hide();
		$("#leave_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#leave_search").hide();
		$("#leave_birthdate").show();	
	}else {
		$("#leave_birthdate").hide();
		$("#leave_search").show();	
	}
}

function checkIfAllOvertime()
{
	var search_field = $("#ot_search_field").val();	
	if(search_field=='all'){
		$("#ot_search").hide();
		$("#ot_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#ot_search").hide();
		$("#ot_birthdate").show();	
	}else {
		$("#ot_birthdate").hide();
		$("#ot_search").show();	
	}
}

function checkIfAllUndertime()
{
	var search_field = $("#ut_search_field").val();	
	if(search_field=='all'){
		$("#ut_search").hide();
		$("#ut_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#ut_search").hide();
		$("#ut_birthdate").show();	
	}else {
		$("#ut_birthdate").hide();
		$("#ut_search").show();	
	}
}

function checkIfAllEndOfContract()
{
	var search_field = $("#eoc_search_field").val();	
	if(search_field=='all'){
		$("#eoc_search").hide();
		$("#eoc_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#eoc_search").hide();
		$("#eoc_birthdate").show();	
	}else {
		$("#eoc_birthdate").hide();
		$("#eoc_search").show();	
	}
}

function checkIfAllDailyTimeRecord()
{
	var search_field = $("#dtr_search_field").val();	
	if(search_field=='all'){
		$("#dtr_search").hide();
		$("#dtr_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#dtr_search").hide();
		$("#dtr_birthdate").show();	
	}else {
		$("#dtr_birthdate").hide();
		$("#dtr_search").show();	
	}
}

function checkIfAllIncompleteTimeInOut()
{
	var search_field = $("#itio_search_field").val();	
	if(search_field=='all'){
		$("#itio_search").hide();
		$("#itio_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#itio_search").hide();
		$("#itio_birthdate").show();	
	}else {
		$("#itio_birthdate").hide();
		$("#itio_search").show();	
	}
}

function checkIfAllIncorrectShift()
{
	var search_field = $("#inc_shift_search_field").val();	
	if(search_field=='all'){
		$("#inc_shift_search").hide();
		$("#inc_shift_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#inc_shift_search").hide();
		$("#inc_shift_birthdate").show();	
	}else {
		$("#inc_shift_birthdate").hide();
		$("#inc_shift_search").show();	
	}
}

function checkIfAllTimesheet()
{
	var search_field = $("#timesheet_search_field").val();	
	if(search_field=='all'){
		$("#timesheet_search").hide();
		$("#timesheet_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#timesheet_search").hide();
		$("#timesheet_birthdate").show();	
	}else {
		$("#timesheet_birthdate").hide();
		$("#timesheet_search").show();	
	}
}

function checkIfAllEmploymentStatus()
{
	var search_field = $("#employment_status_search_field").val();	
	if(search_field=='all'){
		$("#employment_status_search").hide();
		$("#employment_status_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#employment_status_search").hide();
		$("#employment_status_birthdate").show();	
	}else {
		$("#employment_status_birthdate").hide();
		$("#employment_status_search").show();	
	}
}

function checkIfAllEeErContribution()
{
	var search_field = $("#ee_er_contribtion_search_field").val();	
	if(search_field=='all'){
		$("#ee_er_contribtion_search").hide();
		$("#ee_er_contribtion_birthdate").hide();	
	}else if(search_field=='birthdate'){
		$("#ee_er_contribtion_search").hide();
		$("#ee_er_contribtion_birthdate").show();	
	}else {
		$("#ee_er_contribtion_birthdate").hide();
		$("#ee_er_contribtion_search").show();	
	}
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


//general report / audit trail
function load_hr_audit_log_list() {
	$('#hr-audit-log-wrapper').html(loading_image);
	$.get(base_url + 'reports/load_hr_audit_log_list',{},function(o) {
		$('#hr-audit-log-wrapper').html(o);
	});
}

function load_payroll_audit_log_list() {
	$('#payroll-audit-log-wrapper').html(loading_image);
	$.get(base_url + 'reports/load_payroll_audit_log_list',{},function(o) {
		$('#payroll-audit-log-wrapper').html(o);
	});
}

function load_timekeeping_audit_log_list() {
	$('#timekeeping-audit-log-wrapper').html(loading_image);
	$.get(base_url + 'reports/load_timekeeping_audit_log_list',{},function(o) {
		$('#timekeeping-audit-log-wrapper').html(o);
	});
}


function filter_load_timekeeping_audit_log_list(search_col, search_field) {
	
	$('#filter-timekeeping-audit-log-wrapper').html(loading_image);
	$.post(base_url + 'reports/filter_load_timekeeping_audit_log_list',{search_col:search_col, search_field:search_field},function(o) {
		$('#filter-timekeeping-audit-log-wrapper').html(o);
	});
}