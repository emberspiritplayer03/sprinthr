<table width="2012" border="1">
  <tr>
    <td><strong>Job Applied</strong></td>
    <td><strong>Applied Date</strong></td>
    <td><strong>Lastname</strong></td>
    <td><strong>Firstname</strong></td>
    <td><strong>Middlename</strong></td>
    <td><strong>Extension Name</strong></td>
    <td><strong>Gender</strong></td>
    <td><strong>Birthdate</strong></td>
    <td><strong>Marital Status</strong></td>
    <td><strong>Address</strong></td>
    <td><strong>City</strong></td>
    <td><strong>Province</strong></td>
    <td><strong>Home Telephone</strong></td>
    <td><strong>Mobile Number</strong></td>
    <td><strong>Email Address</strong></td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
  	<?php
		$mobile = '="' . $val['mobile'] . '"';
	?>
    <td><?php echo $val['job_applied']; ?></td>
    <td><?php echo $val['applied_date_time']; ?></td>
    <td><?php echo $val['lastname']; ?></td>
    <td><?php echo $val['firstname']; ?></td>
    <td><?php echo $val['middlename']; ?></td>
    <td><?php echo $val['extension_name']; ?></td>
    <td><?php echo $val['gender']; ?></td>
    <td><?php echo $val['birthdate']; ?></td>
    <td><?php echo $val['marital_status']; ?></td>
    <td><?php echo $val['address']; ?></td>
    <td><?php echo $val['city']; ?></td>
    <td><?php echo $val['province']; ?></td>
    <td><?php echo $val['home_telephone']; ?></td>
    <td><?php echo $mobile; ?></td>
    <td><?php echo $val['email_address']; ?></td>
  </tr>
  <?php } ?>
</table>


<?php
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="PENDING_APPLICANTS.xls"');
header('Cache-Control: max-age=0');

?>