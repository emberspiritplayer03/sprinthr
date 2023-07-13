<table width="1163" border="1">
  <tr>
    <td><strong>Applied Position</strong></td>
    <td><strong>Date Applied</strong></td>
    <td><strong>Lastname</strong></td>
    <td><strong>Firstname</strong></td>
    <td><strong>Middlename</strong></td>
    <td><strong>Extension Name</strong></td>
    <td><strong>Birthdate</strong></td>
    <td><strong>Gender</strong></td>
    <td><strong>Marital Status</strong></td>
    <td><strong>Address</strong></td>
    <td><strong>City</strong></td>
    <td><strong>Province</strong></td>
    <td><strong>Email Address</strong></td>
    <td><strong>Home Telephone</strong></td>
    <td><strong>Mobile Number</strong></td>
    <td><strong>Application Status</strong></td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['applied_position']; ?></td>
    <td><?php echo $val['date_applied']; ?></td>
    <td><?php echo $val['lastname']; ?></td>
    <td><?php echo $val['firstname']; ?></td>
    <td><?php echo $val['middlename']; ?></td>
    <td><?php echo $val['extension_name']; ?></td>
    <td><?php echo $val['birthdate']; ?></td>
    <td><?php echo $val['gender']; ?></td>
    <td><?php echo $val['marital_status']; ?></td>
    <td><?php echo $val['adddress']; ?></td>
    <td><?php echo $val['city']; ?></td>
    <td><?php echo $val['province']; ?></td>
    <td><?php echo $val['email_address']; ?></td>
    <td><?php echo $val['home_telephone']; ?></td>
    <td><?php echo $val['mobile']; ?></td>
    <td><?php echo $GLOBALS['hr']['application_status'][$val['application_status_id']]; ?></td>
  </tr>
  <?php } ?>
</table>
<?php


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=APPLICANT_LIST.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
