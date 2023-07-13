<table width="976" border="1">
  <tr>
    <td width="121"><strong>Department</strong></td>
    <td width="171"><strong>Employee Name</strong></td>
    <td width="143"><strong>Position</strong></td>
    <td width="115"><strong>Work Telephone</strong></td>
    <td width="125"><strong>Work Email</strong></td>
    <td width="148"><strong>Home Telephone</strong></td>
    <td width="107"><strong>Mobile</strong></td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
  	<?php
		$work_email	    = '="' . $val['work_email'] . '"';
		$work_telephone = '="' . $val['work_telephone'] . '"';
		$home_telephone = '="' . $val['home_telephone'] . '"';
		$mobile		    = '="' . $val['mobile'] . '"';
	?>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['employee_name']; ?></td>
    <td><?php echo $val['position']; ?></td>
    <td><?php echo $work_telephone; ?></td>
    <td><?php echo $work_email; ?></td>
    <td><?php echo $home_telephone; ?></td>
    <td><?php echo $mobile; ?></td>
  </tr>
  <?php } ?>
</table>
<?php

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="TELEPHONE_DIRECTORY.xls"');
header('Cache-Control: max-age=0');


?>