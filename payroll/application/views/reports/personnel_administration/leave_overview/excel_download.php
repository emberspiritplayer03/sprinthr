<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<table width="200" border="1">
  <tr>
    <td>Department</td>
    <td>Employee Code</td>
    <td>Employee Name</td>
    <td>Type</td>
    <td>Date Applied</td>
    <td>Date Start</td>
    <td>Day(s)</td>
    <td>Status</td>
    <td>With Pay</td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['employee_code']; ?></td>
    <td><?php echo $val['employee_name']; ?></td>
    <td><?php echo $val['type']; ?></td>
    <td><?php echo $val['date_applied']; ?></td>
    <td><?php echo $val['date_start']; ?></td>
    <td><?php 
	$days = Date::get_day_diff($val['date_start'],$val['date_end']);
	echo $days['days']; ?></td>
    <td><?php 
	if($val['is_approved']==0) {
		$status='pending';
	}elseif($val['is_approved']==1) {
		$status='approved';
	}elseif($val['is_approved']=-1) {
		$status='disapproved';
	}
	echo $status;
	
	?></td>
    <td><?php echo $val['is_pay']; ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>


