<table class="table_form" width="587" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td width="198" align="right" valign="top">Title:</td>
    <td width="368" valign="top"><?php echo $details->title; ?></td>
  </tr>
  <tr>
    <td align="right" valign="top">Description</td>
    <td valign="top"><?php echo $details->description; ?></td>
  </tr>
  <tr>
    <td align="right" valign="top">Passing Percentage</td>
    <td valign="top"><?php echo $details->passing_percentage; ?></td>
  </tr>
  <tr>
    <td align="right" valign="top">Created by:</td>
    <td valign="top"><?php echo $details->created_by; ?></td>
  </tr>
  <tr>
    <td align="right" valign="top">Date Created:</td>
    <td valign="top"><?php echo Date::convertDateIntIntoDateString($details->date_created); ?></td>
  </tr>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td valign="top"><input type="submit" name="button" id="button" value="Edit" onclick="javascript:loadExaminationDetailsForm();"  /></td>
  </tr>
  </table>
