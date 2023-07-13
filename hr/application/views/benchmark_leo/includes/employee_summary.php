<img src="<?php echo $filename; ?>?<?php echo $filemtime; ?>" width="140" border="1"  />
<h2 class="field_title">Summary Employee Information</h2>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
<table class="table_form" width="400" border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td style="color:#777777; width:170px;">Employee Code:</td>
            <td align="left" valign="top"><?php echo $employee_details['employee_code']; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Name:</td>
            <td align="left" valign="top"><strong><?php echo $employee_details['salutation']; ?> <?php echo $employee_details['employee_name']; ?></strong></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Branch: </td>
            <td align="left" valign="top"><?php echo $employee_details['branch_name']; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Department: </td>
            <td align="left" valign="top"><?php echo $employee_details['department']; ?></td>
          </tr>
          <tr>
            <td style="color:#777777; width:170px;">Position: </td>
            <td align="left" valign="top"><?php echo $employee_details['position']; ?></td>
          </tr>
        </table>
    </td>
  </tr>
</table>