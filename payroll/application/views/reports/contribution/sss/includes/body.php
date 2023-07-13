<table width="100%" border="1" cellpadding="1" cellspacing="1" style="font-size:9pt; width:1142pt; font-family:Calibri, Verdana, Geneva, sans-serif;">
  <tr>
    <td align="center" valign="top" rowspan="2" colspan="6" style="font-size:11pt; width:155pt;"><b>SS Number</b></td>
    <td align="center" valign="top" colspan="9" style="font-size:11pt; border-bottom:none;"><b>NAME OF EMPLOYEE</b></td>
    <td align="center" valign="top" colspan="2" style="font-size:10pt; border-bottom:none;"><b>DATE OF BIRTH</b></td>
    <td align="center" valign="top" colspan="2" style="font-size:10pt; border-bottom:none;"><b>DATE OF EMPLOYMENT</b></td>
    <td align="center" valign="top" colspan="2" style="font-size:10pt; border-bottom:none;"><b>MONTHLY</b></td>
    <td align="center" valign="top" colspan="2" rowspan="2" style="font-size:10pt; vertical-align:bottom;"><b>POSITION</b></td>
    <td align="center" valign="top" colspan="2" style="font-size:10pt; border-bottom:none;"><b>RELATIONSHIP</b></td>
    <td align="center" valign="top" colspan="2" rowspan="2"  style="font-size:10pt; vertical-align:bottom;">FOR SSS Use</td>
  </tr>
  
  <tr>
    <td valign="top" align="left" colspan="4" style="border-top:none;border-right:none;">(Surname)</td>
    <td valign="top" align="left" colspan="4" style="border-top:none; border-left:none; border-right:none;">(Given Name)</td>
    <td valign="top" align="left" style="border-top:none; border-left:none; border-right:none;">(M.I.)</td>
    <td valign="top" align="center" colspan="2" style="border-top:none;">(mm/dd/yy)</td>
    <td valign="top" align="center" colspan="2" style="border-top:none;">(mm/dd/yy)</td>
    <td valign="top" align="center" colspan="2" style="border-top:none;mso-number-format:'\@';"><b>EARNINGS</b></td>
    <td valign="top" align="center" colspan="2" style="border-top:none;"><b>WITH OWNER/HR</b></td>
  </tr>
  
  <?php foreach($payslip as $p): ?>
  <?php
    $employee_counter++;
	$employee 	= G_Employee_Finder::findByEmployeeCode($p->getEmployeeCode());
	$job 		= G_Employee_Job_History_Finder::findCurrentJob($employee);
	
  	$name 		= $employee->getLastName() . ' ' . $employee->getExtensionName(). ', ' . $employee->getFirstName() . ' ' . $employee->getMiddlename();
	$position 	= $job->getName();
	$birthdate	= date('m/d/Y',strtotime($employee->getBirthdate()));
	$hired_date = date('m/d/Y',strtotime($employee->getHiredDate()));
	
	$ps = G_Payslip_Finder::findByEmployeeAndDateRange($employee, $from, $to);
	if($ps) {
		$ph = new G_Payslip_Helper($ps);

		$sss_number 		= $employee->getSssNumber();
		$monthly_earnings 	= number_format($ph->getValue('sss'),2,'.',',');
	}
		
	$i++;
  ?>
  <tr>
    <td align="left" valign="top" style="font-size:10pt; width:30pt; border-right:none;"><?php echo $employee_counter;  ?>.</td>
    <td align="left" valign="top" colspan="5" style="width:125pt; border-left:none;"><b><?php echo $sss_number; ?></b></td>
    <td align="left" valign="top" colspan="4" style="border-right:none;"><?php echo $employee->getLastName() . ' ' . $employee->getExtensionName(); ?></td>
    <td align="left" valign="top" colspan="4" style="border-left:none; border-right:none;"><?php echo $employee->getFirstName();?></td>
    <td align="left" valign="top" style="border-left:none;"><?php echo $employee->getMiddlename();?></td>
    <td align="center" valign="top" colspan="2"><?php echo $birthdate; ?></td>
    <td align="center" valign="top" colspan="2"><?php echo $hired_date; ?></td>
    <td align="center" valign="top" colspan="2"><?php echo $monthly_earnings; ?></td>
    <td align="center" valign="top" colspan="2"><?php echo $position; ?></td>
    <td align="center" valign="top" colspan="2"><?php echo $relationship; ?></td>
    <td align="center" valign="top" colspan="2"><?php echo $for_sss; ?></td>
  </tr>
  <?php endforeach; ?>
</table>