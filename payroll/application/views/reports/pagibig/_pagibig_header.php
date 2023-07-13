<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
	<tr>
    	<td colspan="14" style="border:none; height:4pt;">&nbsp;</td>
    </tr>
	<tr>
    	<td rowspan="3" align="center" style="text-align:center; border:none; vertical-align:central; "><img src="http://gleent.internal/products/sprinthr_generic/payroll/files/images/logo_pagibig.jpg" width="90" height="64" border="0" /></td>
    	<td colspan="12" style="border:none;">&nbsp;</td>
    	<td align="center" style="border:none; font-size:10pt;"><strong>FPF060</strong></td>
    </tr>
	<tr>
    	<td colspan="12" style="border:none; font-size:16pt;" align="center"><strong>MEMBERSHIP CONTRIBUTIONS REMITTANCE FORM (MCRF)</strong></td>
    	<td style="border:none;">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="13" style="height:12pt; border:none;">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="14" style="border:none; height:4pt;">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="4" style="font-size:10pt; border-bottom:none;"><strong>PERIOD COVERED</strong></td>
        <td colspan="7" rowspan="3" style="border:none;">&nbsp;</td>
        <td colspan="3" style="background:#CCC; font-size:8pt; border-bottom:none;"><strong>Employer's Pag-IBIG ID No.</strong></td>
    </tr>
	<tr>
    	<td colspan="2" align="center" style="border:none; height:10pt;"><i>(month)</i></td>
    	<td style="border:none; height:10pt;">&nbsp;</td>
    	<td align="center" style="height:10pt; border-left:none; border-top:none; border-bottom:none;"><i>(year)</i></td>
        <td colspan="3" rowspan="2" style="mso-number-format:'\@';font-size:11pt; color:#002060; vertical-align:middle;" align="center"><strong><?php echo $info->getPagibigNumber(); ?></strong></td>
    </tr>
	<tr>
    	<td colspan="2" align="center" style="border:none; font-size:11pt; color:#002060;"><strong><?php echo date("F",strtotime($from)); ?></strong></td>
    	<td align="center" style="border:none;">&nbsp;</td>
    	<td align="center" style="border-left:none; border-top:none; font-size:11pt; color:#002060;"><strong><?php echo date("Y",strtotime($from)); ?></strong></td>
    </tr>
</table>

<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
  <tr>
  	<td colspan="6" rowspan="2" style="border-bottom:none;" valign="top"><strong>EMPLOYER/BUSINESS NAME</strong><i>(Per SEC Registration, if private)</i></td>
    <td colspan="4" style="border-bottom:none;"><strong>EMPLOYER SSS NO.</strong><br /></td>
    <td colspan="4" style="border-bottom:none;"><strong>AGENCY/BRANCH/DIVISION CODE</strong><br /></td>
  </tr>
  <tr>
    <td colspan="4" style="border-top:none; border-bottom:none; height:10pt;"><i>(for private Employers only)</i></td>
    <td colspan="4" style="border-top:none; border-bottom:none; height:10pt;"><i>(for government Employers only)</i></td>
  </tr>
  <tr>
  	<td colspan="6" style="font-size:11pt; color:#002060; border-top:none;"><strong><?php echo ($structure ? $structure->getTitle() : ''); ?></strong></td>
    <td colspan="4" align="center" style="font-size:11pt; color:#002060; border-top:none;"><strong><?php echo $info->getSssNumber(); ?></strong></td>
    <td colspan="4" align="center" style="font-size:11pt; color:#002060; border-top:none;"><strong><?php echo ($branch ? $branch->getName() . ' - ' . $branch->getProvince() : ''); ?></strong></td>
  </tr>
</table>	

<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
  <tr>
  	<td colspan="6" style="border-bottom:none;"><strong>BUSINESS ADDRESS (Unit/Room/Floor/Building/Street)</strong></td>
    <td align="center" style="border-bottom:none;"><strong>ZIP CODE</strong></td>
    <td colspan="5" style="border-bottom:none;"><strong>TIN</strong></td>
    <td colspan="2" style="border-bottom:none;"><strong>CONTACT NO/S.</strong></td>
  </tr>
  <tr>
  	<td colspan="6" style="color:#002060; font-size:8pt; border-top:none; border-bottom:none;"><strong><?php echo $info->getAddress(); ?></strong></td>
    <td align="center" style="color:#002060; font-size:11pt; border-top:none; border-bottom:none;"><strong><?php echo $info->getZipCode(); ?></strong></td>
    <td colspan="5" align="center" style="color:#002060; font-size:11pt; border-top:none; border-bottom:none;"><strong><?php echo $info->getTinNumber(); ?></strong></td>
    <td colspan="2" align="center" style="color:#002060; font-size:11pt; border-top:none; border-bottom:none;"><strong><?php echo $info->getPhone(); ?></strong></td>
  </tr>
</table>