<form id="form1" name="form1" method="post" action="<?php echo url('source/_load_excel'); ?>">
  <input type="submit" name="button" id="button" value="Download Excel" />
</form>
<textarea name="textarea" id="textarea" cols="65" rows="15">
<table width="464" border="1">
  <tr>
    <td width="144">&nbsp;</td>
    <td width="304">&nbsp;</td>
  </tr>
  <tr>
    <td>Company Name</td>
    <td><?php echo $details['name']; ?></td>
  </tr>
  <tr>
    <td>Company Address</td>
    <td><?php echo $details['address']; ?></td>
  </tr>
  <tr>
    <td>Phone Number</td>
    <td style="text-align:left"><?php echo $details['phone_number']; ?></td>
  </tr>
  <tr>
    <td>Fax Number</td>
    <td style="text-align:left"><?php echo $details['fax_number']; ?></td>
  </tr>
</table>

< ? php

header("Content-type: application/octet-stream");

# replace excelfile.xls with whatever you want the filename <strong class="highlight">to</strong> default <strong class="highlight">to</strong>
header("Content-Disposition: attachment; filename=company_profile.xls");
header("Pragma: no-cache");
header("Expires: 0");

? >  


</textarea>
