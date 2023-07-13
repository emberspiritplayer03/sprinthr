<?php
//$payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
?>
<table border="1" cellpadding="0" cellspacing="0">
  <?php
$i = 1;
foreach ($employees as $e) {
	//if ($i > 5) {
		//break;	
	//}
?>
  <tr>
    <td width="64"><?php echo $e->getEmployeeCode();?></td>
    <td align="right" width="64">21-Jun</td>
    <td width="64">9am</td>
    <td width="64">6pm</td>
    <td width="64"></td>
    <td width="64"></td>
  </tr>
<!--  <tr>
    <td></td>
    <td align="right">22-Jun</td>
    <td>9am</td>
    <td>7:30pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">23-Jun</td>
    <td>8am</td>
    <td>8pm</td>
    <td>6pm</td>
    <td>8pm</td>
  </tr>
  <tr>
    <td></td>
    <td align="right">24-Jun</td>
    <td>9am</td>
    <td>6pm</td>
    <td>6pm</td>
    <td>8pm</td>
  </tr>
  <tr>
    <td></td>
    <td align="right">25-Jun</td>
    <td>9am</td>
    <td>6pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">26-Jun</td>
    <td>9am</td>
    <td>7:30pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">27-Jun</td>
    <td>8am</td>
    <td>8pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">28-Jun</td>
    <td>10am</td>
    <td>7pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">29-Jun</td>
    <td>10am</td>
    <td>7pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">30-Jun</td>
    <td>9am</td>
    <td>6pm</td>
    <td>6pm</td>
    <td>8pm</td>
  </tr>
  <tr>
    <td></td>
    <td align="right">01-Jul</td>
    <td>9am</td>
    <td>6pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">02-Jul</td>
    <td>9am</td>
    <td>6pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">03-Jul</td>
    <td>9am</td>
    <td>6pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">04-Jul</td>
    <td>9am</td>
    <td>6pm</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">05-Jul</td>
    <td>9am</td>
    <td>6pm</td>
    <td></td>
    <td></td>
  </tr>-->
  <?php
  $i++;
}
?>
</table>
<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=timesheet.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

