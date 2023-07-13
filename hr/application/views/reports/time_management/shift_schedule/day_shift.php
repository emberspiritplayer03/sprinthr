<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">   
  <tr>
      <td style="border:none; font-size:16pt;" align="left" colspan="3">      
        <h2>Day Shift Employees</h2>
      </td>
  </tr>  
  <tr>
      <td style="border:none;" colspan="2">From</td>
      <td style="border:none;"><?php echo date("F j, Y",strtotime($date_from)); ?></td>
  </tr>
  <tr>
      <td style="border:none;" colspan="2">To</td>
      <td style="border:none;"><?php echo date("F j, Y",strtotime($date_to)); ?></td>
  </tr>
  <tr>
      <td style="border:none;" colspan="2">Date Generated</td>
      <td style="border:none;"><?php echo date("F j, Y"); ?></td>
  </tr>
</table>
<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
  <tr>
    <td width="158"><strong>Employee Code</strong></td>
    <td width="120"><strong>Employee Name</strong></td>
    <td width="141"><strong>Department</strong></td>
    <td width="141"><strong>Section</strong></td>
    <td width="162"><strong>Position</strong></td>    
    <td><strong>Employment Status</span></strong></td>     
  </tr>
  <?php $count = 0; foreach( $data_day_shift as $shift ){ $count++; ?>
  <?php $employee_name = $shift['employee']['lastname'] . ', ' . $shift['employee']['firstname']; ?>
    <tr>
      <td><?php echo $shift['employee']['employee_code']; ?></td>
      <td><?php echo mb_convert_case($employee_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
      <td><?php echo $shift['employee']['department_name']; ?></td>
      <td><?php echo $shift['employee']['section_name']; ?></td>
      <td><?php echo $shift['employee']['position']; ?></td>
      <td><?php echo mb_convert_case($shift['employee']['status'],  MB_CASE_TITLE, "UTF-8"); ?></td>
    </tr>
  <?php } ?>
    <tr>
      <td colspan="4">Total</td>
      <td><?php echo $count; ?></td>
    </tr>
</table>