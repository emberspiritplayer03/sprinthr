<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: 8pt!important;
}
</style>

<?php 

foreach ($emp as $employee) {
	$employee_id = $employee->getId();
	$personal_details = G_Employee_Finder::findById($employee_id);
	$c_emp = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
	$ec_emp = G_Employee_Emergency_Contact_Finder::findByEmployeeId($employee_id);
	$dependents =	G_Employee_Dependent_Finder::findByEmployeeId($employee_id);
	$banks = G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);

	$depedents_title = "Dependents";
	$title_banks = "Bank Account";

	//utilities::displayArray($ec_emp);
?>
<table border="1" >
<tr>
	<td>
		<?php include('personal_details.php');?>
		<?php include('other_details.php');?>
	</td>
	<td>
		<?php include('contact_details.php');?>
		<?php include('bank.php');?>
	</td>
	<td>
		<?php include('emergency_contacts.php');?>
		<?php include('dependents.php');?>
	</td>
</tr>
<tr>
	<td></td>
</tr>
</table>
<br><br><br><br>
<?php 
}
?>

<?php
/*header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");*/
?>