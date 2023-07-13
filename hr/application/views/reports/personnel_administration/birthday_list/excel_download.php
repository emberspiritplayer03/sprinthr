<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

<table width="949" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
      <td style="border:none; font-size:16pt;" align="left">
        <strong>
          <?php echo "Birthday List Data"; ?>
        </strong>
        </td>
      <td style="border:none;">&nbsp;</td>
    </tr>   
    <tr>
      <td style="border:none; font-size:14pt;" align="left"><strong>From</strong></td>
      <?php
        if($month != '') {
          $month = "- " . date('F', mktime(0, 0, 0, $month, 10));
        } else {
          $month = "- All";
        }
      ?>
      <td style="border:none; text-align="right""><b><?php echo date("Y"); ?> <?php echo $month; ?></b></td>
    </tr>
    <tr>
      <td style="border:none; font-size:14pt;" align="left"><strong>Date Generated</strong></td>
      <td style="border:none;"><b><?php echo date('Y-m-d'); ?></b></td>
    </tr>
</table>  

<table width="949" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td width="120"><strong>Employee Code</strong></td>
    <td width="141"><strong>Employee Name</strong></td>
    <td width="141"><strong>Nickname</strong></td>
    <td width="162"><strong>Date of Birth</strong></td>
    <td width="92"><strong>Birth Day</strong></td>
    <td width="92"><strong>Age</strong></td>
    <td width="158"><strong>Department</strong></td>
    <td width="158"><strong>Section</strong></td>
    <td width="157"><strong>Position</strong></td>
    <td width="190"><strong>Employment Status</strong></td>
  </tr>
  <?php 
  $x=0;
  foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['employee_code']; ?></td>
    <td><?php echo $val['employee_name'];?></td>
    <td><?php echo $val['nickname'];?></td>    
    <td><?php echo date("m-d",strtotime($val['birthdate'])); ?></td>
    <?php 
      $day   = date("d",strtotime($val['birthdate']));
      $month = date("m",strtotime($val['birthdate']));
      $birth_day = date("D",strtotime(date("Y") . "-" . $month . "-" . $day));
    ?>
    <td><?php echo $birth_day; ?></td>
    <td><?php echo $age = ($val['age']>0) ? $val['age'] : 0 ; ' Yrs Old'; ?></td>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['section_name']; ?></td>
    <td><?php echo $val['position']; ?></td>
    <td><?php echo $val['employment_status']; ?></td>
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
  </tr>
</table>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=birthday_list.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

