<h2>Employee Name: <?php echo $employee_details->getFirstname() . ' ' . $employee_details->getLastname(); ?></h2>
<h2>Employee Code: <?php echo $employee_details->getEmployeeCode(); ?></h2>
<div style="background-color: #dddddd;"><h1>Timesheet</h1></div>
<?php include_once('timesheet.php'); ?>
<br /><br />
<div style="background-color: #dddddd;"><h1>Payslip</h1><hr /></div>
<?php include_once('payslip.php'); ?>
<br /><br />
<div style="background-color: #dddddd;"><h1>Payroll Register</h1><hr /></div>
<?php include_once('payroll_register.php'); ?>

