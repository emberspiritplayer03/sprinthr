<style type="text/css">
<!--table
  {mso-displayed-decimal-separator:"\.";
  mso-displayed-thousand-separator:"\,";}
@page
  {margin:1.0in .75in 1.0in .75in;
  mso-header-margin:.5in;
  mso-footer-margin:.5in;}
-->

tr
  {mso-height-source:auto;}
col
  {mso-width-source:auto;}
br
  {mso-data-placement:same-cell;}
.style0
  {mso-number-format:General;
  text-align:general;
  vertical-align:bottom;
  white-space:nowrap;
  mso-rotate:0;
  mso-background-source:auto;
  mso-pattern:auto;
  color:windowtext;
  font-size:10.0pt;
  font-weight:400;
  font-style:normal;
  text-decoration:none;
  font-family:Arial;
  mso-generic-font-family:auto;
  mso-font-charset:0;
  border:none;
  mso-protection:locked visible;
  mso-style-name:Normal;
  mso-style-id:0;}
td
  {mso-style-parent:style0;
  padding-top:1px;
  padding-right:1px;
  padding-left:1px;
  mso-ignore:padding;
  color:windowtext;
  font-size:10.0pt;
  font-weight:400;
  font-style:normal;
  text-decoration:none;
  font-family:Arial;
  mso-generic-font-family:auto;
  mso-font-charset:0;
  mso-number-format:General;
  text-align:general;
  vertical-align:bottom;
  border:none;
  mso-background-source:auto;
  mso-pattern:auto;
  mso-protection:locked visible;
  white-space:nowrap;
  mso-rotate:0;}
.xl66
  {mso-style-parent:style0;
  font-size:14.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;}
.xl67
  {mso-style-parent:style0;
  font-size:18.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;
  text-align:center;}
.xl68
  {mso-style-parent:style0;
  font-size:14.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;
  text-align:left;
  vertical-align:top;
  white-space:normal;}
.xl69
  {mso-style-parent:style0;
  font-size:14.0pt;
  font-family:Arial, sans-serif;
  mso-font-charset:0;
  text-align:left;}
</style>
<?php if( !empty($e) ) { ?>
  <?php
    $hired_date = date_format(date_create($e->getHiredDate()),"F j, Y");
    if($e->getTerminatedDate() != '0000-00-00') {
      $end_date = date_format(date_create($e->getTerminatedDate()),"F j, Y");
    }elseif($e->getResignationDate() != '0000-00-00') {
      $end_date = date_format(date_create($e->getResignationDate()),"F j, Y");
    }elseif($e->getEndoDate() != '0000-00-00') {
      $end_date = date_format(date_create($e->getEndoDate()),"F j, Y");
    } else {
      $end_date = 'present';  
    }
    $full_name = strtoupper($e->getFirstName() . ' ' . $e->getMiddleName() . ' ' . $e->getLastName());
    $employee_name = strtr(utf8_decode($full_name), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $last_name = strtoupper($e->getLastName());
  ?>
  <table border=0 cellpadding=0 cellspacing=0 width=832 style='border-collapse:
   collapse;table-layout:fixed;width:624pt'>
   <col width=64 span=13 style='width:48pt'>
   <tr height=17 style='height:12.75pt'>
    <td height=17 width=64 style='height:12.75pt;width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
   </tr>
   <tr height=17 style='height:12.75pt'>
    <td colspan=13 rowspan=2 height=34 class=xl67 style='height:25.5pt'>CERTIFICATE
    OF EMPLOYMENT</td>
   </tr>
   <tr height=17 style='height:12.75pt'>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td colspan=11 rowspan=2 class=xl68 width=704 style='width:528pt'>
    This is to certify the <strong><?php echo $person_title; ?> <?php echo $employee_name; ?></strong> 
    has been hired by <?php echo $d['company_name']; ?> as <?php echo $d['position']; ?> from <?php echo $hired_date; ?> up to <?php echo $end_date; ?>. 
    </td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='mso-height-source:userset;height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td colspan=11 rowspan=2 class=xl68 width=704 style='width:528pt'>This certification is being issued upon the request of <strong><?php echo $person_title; ?> <?php echo $last_name; ?></strong> for whatever legal purpose it may serve.</td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>   
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='mso-height-source:userset;height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td colspan=11 rowspan=2 class=xl68 width=704 style='width:528pt'>Given this <?php echo date('d') . date('S'); ?> day of <?php echo date("F"); ?> <?php echo date("Y"); ?> at <?php echo $c->getAddress(); ?>.</td>
    <td class=xl66></td>
   </tr>   
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>

   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>      
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td colspan=11 class=xl69></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td colspan=11 class=xl69>HRAD Manager</td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=24 style='height:18.0pt'>
    <td height=24 class=xl66 style='height:18.0pt'></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
    <td class=xl66></td>
   </tr>
   <tr class=xl66 height=840 style='height:630.0pt;mso-xlrowspan:35'>
    <td height=840 colspan=13 class=xl66 style='height:630.0pt;mso-ignore:colspan'></td>
   </tr>
   <![if supportMisalignedColumns]>
   <tr height=0 style='display:none'>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
    <td width=64 style='width:48pt'></td>
   </tr>
   <![endif]>
  </table>
<?php } ?>