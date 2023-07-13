<h2 class="field_title">Summary Employee Information</h2>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
<table class="table_form" width="400" border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td width="170" align="right" valign="top">Employee Code:</td>
            <td align="left" valign="top"><?php echo $employee_details['employee_code']; ?></td>
          </tr>
          <tr>
            <td width="170" align="right" valign="top">Name:</td>
            <td align="left" valign="top"><?php echo $employee_details['salutation']; ?> <?php echo $employee_details['employee_name']; ?></td>
          </tr>
          <tr>
            <td width="170" align="right" valign="top">Branch: </td>
            <td align="left" valign="top"><?php echo $employee_details['employee_name']; ?></td>
          </tr>
          <tr>
            <td width="170" align="right" valign="top">Department: </td>
            <td align="left" valign="top"><?php echo $employee_details['department']; ?></td>
          </tr>
          <tr>
            <td width="170" align="right" valign="top">Position: </td>
            <td align="left" valign="top"><?php echo $employee_details['position']; ?></td>
          </tr>
        </table>
    </td>
    <td width="150" align="right" valign="top"><a href="javascript:loadPhoto();"><img src="<?php echo BASE_FOLDER; ?>images/profile_noimage.gif" width="140" border="0"  /></a></td>
  </tr>
</table>