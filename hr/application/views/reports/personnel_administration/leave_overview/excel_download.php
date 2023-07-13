<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<table width="2012" border="1">
  <tr>
    <td><strong>Department</strong></td>
    <td><strong>Employee Code</strong></td>
    <td><strong>Employee Name</strong></td>    
    <td><strong>Type</strong></td>    
    <td><strong>Date Applied</strong></td>
    <td><strong>Date Start</strong></td>
    <td><strong>Date End</strong></td>
    <td><strong>Day(s)</strong></td>
    <td><strong>Status</strong></td>
    <td><strong>With Pay</strong></td>
    <td><strong>Comment</strong></td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['employee_code']; ?></td>
    <td><?php echo $val['employee_name']; ?></td>
    <td><?php echo $val['type']; ?></td>
    <td><?php echo $val['date_applied']; ?></td>
    <td><?php echo $val['date_start']; ?></td>
    <td><?php echo $val['date_end']; ?></td>
    <td>
		<?php 
			$days = Date::get_day_diff($val['date_start'],$val['date_end']);
			echo $days['days'] + 1; 
		?>
    </td>
	<td>
		<?php 
			if($val['is_approved']==0) {
				$status='pending';
			}elseif($val['is_approved']==1) {
				$status='approved';
			}elseif($val['is_approved']=-1) {
				$status='disapproved';
			}
			echo $status;
		?>
    </td>
    <td><?php echo $val['is_paid']; ?></td>    
    <td>
    	<?php 
			$comments = '="' . $val['leave_comments'] . '"';
			echo $comments;
		?>
    </td>
  </tr>
  <?php } ?> 
</table>

<?php
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="leave_overview.xls"');
header('Cache-Control: max-age=0');
?>


