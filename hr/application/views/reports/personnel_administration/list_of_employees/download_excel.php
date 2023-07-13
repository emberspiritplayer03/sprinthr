<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

<table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
  	<td><strong>Branch</strong></td>
    <td><strong>Department</strong></td>
    <td><strong>Employee Code</strong></td>
    <td><strong>Lastname</strong></td>
    <td><strong>Firstname </strong></td>
    <td><strong>Middlename</strong></td>
    <td><strong>Extension Name</strong></td>
    <td><strong>Nickname</strong></td>
    <td><strong>Birthdate</strong></td>
    <td><strong>Gender</strong></td>
    <td><strong>Marital Status</strong></td>
    <td><strong>No. of Dependent</strong></td>
    <td><strong>Position</strong></td>
    <td><strong>Employment Status</strong></td>
    <td><strong>Hired Date</strong></td>
    <td><strong>Terminated Date</strong></td>
    <td><strong>Salary</strong></td>
    <td><strong>Type (Monthly, Daily, Hourly)</strong></td>
    <td><strong>Pay Frequency</strong></td>
    <td><strong>SSS Number</strong></td>
    <td><strong>Pagibig Number</strong></td>
    <td><strong>Philhealth Number</strong></td>
    <td><strong>Tin Number</strong></td>
    <td><strong>Contract Start</strong></td>
    <td><strong>Contract End</strong></td>
    <td><strong>Address</strong></td>
    <td><strong>City</strong></td>
    <td><strong>Province</strong></td>
    <td><strong>Zip Code</strong></td>
    <td><strong>Home Telephone</strong></td>
    <td><strong>Mobile</strong></td>
    <td><strong>Personal Email</strong></td>
    <td><strong>Work Telephone</strong></td>
    <td><strong>Work Email</strong></td>
    <td><strong>Bank Name</strong></td>
    <td><strong>Account Number</strong></td>
    <td><strong>Emergency Contact Name</strong></td>
    <td><strong>Emergency Contact Number</strong></td>
    <td><strong>Username</strong></td>
    <td><strong>Password</strong></td>   
    <td><strong>Module</strong></td>
  </tr>
<?php 
$x=0;
foreach ($data as $key => $val) { ?>
<?php 
	$branch 		= G_Company_Structure_Helper::findCompanyBranch($val['company_structure_id']);
	$job_history 	= G_Employee_Job_History_Helper::getCurrentJobAndStatusByEmployeeId($val['id']);
	$salary_history	= G_Employee_Basic_Salary_History_Helper::getEmployeeCurrentSalaryAndPayPeriod($val['id']);
	$contact		= G_Employee_Contact_Details_Finder::findByEmployeeId($val['id']);
	$bank			= G_Employee_Direct_Deposit_Finder::findSingleEmployeeBankRecordByEmployeeId($val['id']);
	$emergency		= G_Employee_Emergency_Contact_Finder::findSingleEmployeeEmergencyContact($val['id']);
	$user			= G_User_Finder::findByEmployeeId($val['id']);
?>
  <tr>
  	<td><?php echo $branch['name']; ?></td>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['employee_code']; ?></td>
    <td><?php echo $val['lastname']; ?></td>
    <td><?php echo $val['firstname']; ?></td>
    <td><?php echo $val['middlename']; ?></td>
    <td><?php echo $val['extension_name']; ?></td>
    <td><?php echo $val['nickname']; ?></td>
    <td><?php echo $val['birthdate']; ?></td>
    <td><?php echo $val['gender']; ?></td>
    <td><?php echo $val['marital_status']; ?></td>
    <td><?php echo $val['number_dependent']; ?></td>
    <td><?php echo $job_history[0]['name']; ?></td>
    <td><?php echo $job_history[0]['employment_status']; ?></td>
    <td><?php echo $val['hired_date']; ?></td>
    <td><?php echo $val['terminated_date']; ?></td> 
    <td><?php echo $salary_history['basic_salary']; ?></td>
    <td><?php echo $salary_history['type']; ?></td>
    <td><?php echo $salary_history['pay_period_name']; ?></td>
    <td><?php echo $val['sss_number']; ?></td>
    <td><?php echo $val['pagibig_number']; ?></td>
    <td><?php echo $val['philhealth_number']; ?></td>
    <td><?php echo $val['tin_number']; ?></td>
    <td><?php echo $val['contract_start']; ?></td>
    <td><?php echo $val['contract_end']; ?></td>
    <td><?php echo ($contact ? $contact->getAddress() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getCity() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getProvince() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getZipCode() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getHomeTelephone() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getMobile() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getOtherEmail() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getWorkTelephone() : ''); ?></td>
    <td><?php echo ($contact ? $contact->getWorkEmail() : ''); ?></td>
    <td><?php echo ($bank ? $bank->getBankName() : ''); ?></td>
    <td><?php echo ($bank ? $bank->getAccount() : ''); ?></td>
    <td><?php echo ($emergency ? $emergency->getPerson() : ''); ?></td>
    <td><?php echo ($emergency ? $emergency->getMobile() : ''); ?></td>
    <td><?php echo ($user ? $user->getUserName() : ''); ?></td>
    <td></td>
    <td><?php echo ($user ? $user->getModule() : ''); ?></td>
  </tr>
<?php 
	$x++;
} ?>
  <tr>
    <td colspan="3">Total Record(s): <?php echo $x; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=employee_list.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
