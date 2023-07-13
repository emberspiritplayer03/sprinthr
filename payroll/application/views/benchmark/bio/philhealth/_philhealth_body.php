<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4" align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><br /><strong><span style="font-family:Arial Narrow; font-size:8pt">PHILHEALTH/SSS/GSIS<br />NUMBER</span></strong></td>
    <td colspan="3" align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><strong><span style="font-family:Arial Narrow; font-size:8pt">NAME OF EMPLOYEE</span></strong></td>
    <td colspan="2" align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><strong><span style="font-family:Arial Narrow; font-size:8pt">POSITION</span></strong></td>
    <td align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><strong><span style="font-family:Arial Narrow; font-size:8pt">SALARY</span></strong></td>
    <td align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><strong><span style="font-family:Arial Narrow; font-size:8pt">DATE OF<br />EMLPOYMENT</span></strong></td>
    <td align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><strong><span style="font-family:Arial Narrow; font-size:8pt">(DO NOT FILL)<br />EFF. DATE OF<br />COVERAGE</span></strong></td>
    <td colspan="4" align="center" valign="middle" style="vertical-align:middle; line-height:15px;"><br /><strong><span style="font-family:Arial Narrow; font-size:8pt">PREVIOUS EMPLOYER<br />(IF ANY)</span></strong></td>
  </tr>
  <?php		
		$counter = 1; 		
		foreach($employees as $e){ 		
		$p           = G_Payslip_Finder::findByEmployeeAndDateRange($e, $from, $to);
		$pos         = G_Employee_Job_History_Finder::findCurrentJob($e);
		$salary      = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);
		$employer    = G_Employee_Work_Experience_Finder::findPreviousEmployerByEmployeeId($e->getId());
		//$p_employers = G_Employee_Work_Experience_Finder::findByEmployeeId($e->getId());
		
		//Get Contri Id
 		if($e->getPhilhealthNumber() != ''){
			$ref_number = $e->getPhilhealthNumber();
		}else{
			if($e->getSssNumber() != ''){
				$ref_number = $e->getSssNumber();
			}else{
				if($e->getPagibigNumber() != ''){
					$ref_number = $e->getPagibigNumber();
				}else{
					$ref_number = '';
				}
			}
		}
				
		//Get Prev Employers
		
		if($employer){
			$ee = $employer->getCompany();
		}else{$ee = '';}
		
		/*$eEmployers = NULL;
		foreach($p_employers as $employer){
			$eEmployers[] = $employer->getCompany();
		}
		if(!empty($eEmployers)){
			$ee = implode(",",$eEmployers);
		}else{$ee = '';}*/
		$ref_number = '="' . $ref_number . '"';
	?>
  <tr>
    <td colspan="4" align="center" valign="top"><span style="font-family:Arial Narrow; font-size:10pt"><?php echo $ref_number;?></span></td>
    <td colspan="3" align="center" valign="top"><span style="font-family:Arial Narrow; font-size:10pt"><?php echo $e->getLastname() . ',' . $e->getFirstname(); ?></span></td>
    <td colspan="2" align="center" valign="top"><span style="font-family:Arial Narrow; font-size:10pt"><?php echo ($pos ? $pos->getName() : ''); ?></span></td>
    <td align="center" valign="top" style="mso-number-format:'\@';"><span style="font-family:Arial Narrow; font-size:10pt"><?php echo ($salary ? number_format($salary->getBasicSalary(),2,".",",") : '0.00'); ?></span></td>
    <td align="center" valign="top"><span style="font-family:Arial Narrow; font-size:10pt"><?php echo $e->getHiredDate(); ?></span></td>
    <td align="center" valign="top">&nbsp;</td>
    <td colspan="4" align="center" valign="top"><span style="font-family:Arial Narrow; font-size:10pt"><?php echo $ee; ?></span></td>
    <?php 
		$counter++;
		} 
	?>
  </tr>
  <!--<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>-->
