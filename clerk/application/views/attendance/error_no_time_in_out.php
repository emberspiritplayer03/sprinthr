<table class="formtable" width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="14%"><strong>Employee Code</strong></td>
    <td width="20%"><strong>Date</strong></td>
    <td width="20%"><strong>Error Type</strong></td>
    <td width="18%"><strong>Details</strong></td>
    <td width="18%"><strong>Action</strong></td>
  </tr>
  <?php foreach ($errors as $error):?>
  <tr>
    <td><?php echo $error->getEmployeeCode();?></td>
    <td><?php echo $error->getDate();?></td>
    <td><?php echo $error->getErrorTypeString();?></td>
    <td><?php echo $error->getMessage();?></td>
    <td>Select</td>
  </tr>
  <?php endforeach;?>
</table>