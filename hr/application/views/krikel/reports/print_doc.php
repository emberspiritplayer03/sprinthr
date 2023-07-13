
<table width="464" border="1">
  <tr>
    <td width="144">&nbsp;</td>
    <td width="304">&nbsp;</td>
  </tr>
  <tr>
    <td>Company Name</td>
    <td>Gleent Innovative Technologies</td>
  </tr>
  <tr>
    <td>Company Address</td>
    <td>Cabuyao Laguna</td>
  </tr>
  <tr>
    <td>Phone Number</td>
    <td style="text-align:left">049</td>
  </tr>
  <tr>
    <td>Fax Number</td>
    <td style="text-align:left">049</td>
  </tr>
</table>

<?php

header("Content-type: application/octet-stream");

# replace excelfile.xls with whatever you want the filename <strong class="highlight">to</strong> default <strong class="highlight">to</strong>
header("Content-Disposition: attachment; filename=company_profile.doc");
header("Pragma: no-cache");
header("Expires: 0");

?> 

