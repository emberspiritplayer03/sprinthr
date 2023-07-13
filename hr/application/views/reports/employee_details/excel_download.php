<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: 8pt!important;
}
</style>

<?php 

//utilities::displayArray($emp);
foreach ($emp as $employee) {
	$employee_id = $employee->getId();


	$personal_details 		= G_Employee_Finder::findById($employee_id);
	$contact_details 		= G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
	$contacts 				= G_Employee_Emergency_Contact_Finder::findByEmployeeId($employee_id);
	$dependents 			=	G_Employee_Dependent_Finder::findByEmployeeId($employee_id);
	$banks 					= G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);

	$employee_salary 		= G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);
	$employee_rate 			= G_Job_Salary_Rate_Finder::findById($employee_salary->job_salary_rate_id);
	$employee_pay_period 	= G_Settings_Pay_Period_Finder::findById($employee_salary->pay_period_id);

	$pay_period 		= G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
	$rate 				= G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
	$compensation_history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId($employee_id);

	$durations			 = G_Employee_Extend_Contract_Finder::findByEmployeeId($employee_id);
	$c 					 = G_Employee_Contribution_Finder::findByEmployeeId($employee_id);

	$performance 		 = G_Employee_Performance_Finder::findByEmployeeId($employee_id);
	$training  			 = G_Employee_Training_Finder::findByEmployeeId($employee_id);
	$memo 				 = G_Employee_Memo_Finder::findByEmployeeId($employee_id);

	$e = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
	$data[] = unserialize($e->requirements);
	$requirements = $data;

	$subordinate 		= G_Employee_Supervisor_Finder::findByEmployeeId($employee_id);
	$supervisor 		= G_Employee_Supervisor_Finder::findBySupervisorId($employee_id);

	$availables 		= G_Employee_Leave_Available_Finder::findByEmployeeId($employee_id);
	$request 			= G_Employee_Leave_Request_Finder::findByEmployeeId($employee_id);
	$gcs 				= G_Company_Structure_Finder::findById($this->company_structure_id);
	$leaves 			= G_Leave_Finder::findByCompanyStructureId($gcs);

	$work_experience	= G_Employee_Work_Experience_Finder::findByEmployeeId($employee_id);
	$education 			= G_Employee_Education_Finder::findByEmployeeId($employee_id);
	$skills 			= G_Employee_Skills_Finder::findByEmployeeId($employee_id);
	$languages 			= G_Employee_Language_Finder::findByEmployeeId($employee_id);
	$license 			= G_Employee_License_Finder::findByEmployeeId($employee_id);

	$subdivision_history = G_Employee_Subdivision_History_Finder::findByEmployeeId($employee_id);
	$department 		 = $department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
	$job_history 		 = G_Employee_Job_History_Finder::findByEmployeeId($employee_id);
	$job 				 = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
	$status 			 = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
	$branch 			 = G_Company_Branch_Finder::findByCompanyStructureId($this->company_structure_id);

	$project_site = G_Employee_Project_Site_History_Finder::getAllprojectSiteByEmployeeId($employee_id);
	
	$d = G_Employee_Helper::findByEmployeeId($employee_id);


	//utilities::displayArray($ec_emp);
?>
<table border="1" >
<tr>
	<td>
	<?php 
	include('personal_information_section.php');
	echo '<br/><br/>';
	include('employment_information_section.php');
	echo '<br/><br/>';
	include('qualification_section.php');
	?>
	</td>
</tr>

</table>
<br><br><br><br>
<?php 
}
?>

<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>