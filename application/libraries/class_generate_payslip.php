<?php
class Generate_Payslip {
				
	public static function payslip($employee,$pay_period,$earnings,$deductions)
	{
		$logo = 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . "themes/default/themes-images/gleent-logo.jpg";			
		$msg  = '
		<br><br><table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="40">&nbsp;</td>
			<td width="660">
				<img width="220" src="' . $logo . '" alt="Gleent Logo" /><br><br>
				<div style="padding:5px 30px; font-size:13px; text-align:right; font-style:italic; color:#222222;">Employee&rsquo;s Copy</div>
				<div style="padding:8px 5px; font-size:18px; text-align:center; color:#222222; background:#eeeeee;"><strong>PAYSLIP</strong></div><br /><br>
			</td>
			<td width="30">&nbsp;</td>
		  </tr>
		</table>		
		<div style="width:700px; margin:0 auto;">
			<table style="width:100%; display:block;" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="50">&nbsp;</td>
				<td width="330">NAME:&nbsp; <strong>' . $employee['name'] . '</strong></td>
				<td width="300">DESIGNATION:&nbsp; <strong>HR Manager</strong></td>
			</tr>
			<tr>
				<td width="50">&nbsp;</td>
				<td width="330">ID NUMBER:&nbsp; <strong>G-006</strong></td>
				<td width="300">Pay Period:&nbsp; <strong>HR Dept.</strong></td>
			</tr>
			<tr>
				<td width="50">&nbsp;</td>
				<td width="330">PAY PERIOD:&nbsp; <strong>' . $pay_period['from'] . ' - ' . $pay_period['to'] . '</strong></td>				
				<td width="300">PAY CYCLE:&nbsp; <strong>Bi-Monthly</strong></td>
			</tr>
			</table><br /><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="100">&nbsp;</td>
				<td width="520" style="border-left:1px solid #000000; border-right:1px solid #000000; border-top:1px solid #000000; border-bottom:1px solid #000000;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="10">&nbsp;</td>
						<td width="240">
							<h3 style="font-size:16px; margin-top:10px; margin-bottom:15px;">Earnings</h3>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="115">Basic Pay</td>
								<td width="100" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($earnings['basic_pay'],2) . '</span></td>
							</tr>
							<tr>
								<td width="115">Overtime</td>
								<td width="100" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($earnings['overtime'],2) . '</span></td>
							</tr>
							<tr>
								<td colspan="2" height="5">&nbsp;</td>
							</tr>
							<tr>
								<td width="115">Total Earnings</td>
								<td width="100" style="text-align:right;"><strong style="text-decoration:underline;">' . number_format(self::totalEarnings($earnings),2) . '</strong></td>
							</tr>
							</table><br><br>
						</td>
						<td width="10" style="border-left:1px solid #000000;">&nbsp;</td>
						<td width="260">
							<h3 style="font-size:16px; margin-top:10px; margin-bottom:15px;">Deductions</h3>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="120">SSS</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['sss'],2) . '</span></td>
							</tr>
							<tr>
								<td width="120">PhilHealth</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['philhealth'],2) . '</span></td>
							</tr>
							<tr>
								<td width="120">Pagibig</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['pagibig'],2) . '</span></td>
							</tr>
							<tr>
								<td width="120">Late</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['late'],2) . '</span></td>

							</tr>
							<tr>
								<td width="120">Undertime</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['undertime'],2) . '</span></td>
							</tr>
							<tr>
								<td width="120">Absent Amount</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['absent_amount'],2) . '</span></td>
							</tr>
							<tr>
								<td width="120">Suspended Amount</td>
								<td width="115" style="text-align:right;"><span style="text-decoration:underline; font-size:12px;">' . number_format($deductions['suspended_amount'],2) . '</span></td>
							</tr>
							<tr>
								<td colspan="2" height="5">&nbsp;</td>
							</tr>
							<tr>
								<td width="120">Total Deductions</td>
								<td width="115" style="text-align:right;"><strong style="text-decoration:underline;">' . number_format(self::totalDeductions($deductions),2) . '</strong></td>
							</tr>
							</table><br><br>
						</td>
					  </tr>
					</table>
				</td>
				<td width="90">&nbsp;</td>
			  </tr>   
			  <tr>
              	<td width="20">&nbsp;</td>
                <td width="520" style="border-left:1px solid #000000; border-right:1px solid #000000; border-bottom:1px solid #000000;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  	<td width="20">&nbsp;</td>
						<td width="420" height="35"><h3 style="font-size:16px; margin-top:10px; margin-bottom:25px;">Summary</h3></td>
						<td width="20">&nbsp;</td>
					  </tr>
					  <tr>
						<td width="20">&nbsp;</td>
						<td width="420">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="30">&nbsp;</td>
								<td width="100">Earnings</td>
								<td width="50">-</td>				
								<td width="120"> Deductions </td>				
								<td width="30">=</td>
								<td width="110" style="text-align:right;"><b>NET PAY</b></td>
							</tr>
							<tr>
								<td width="30">&nbsp;</td>
								<td width="100"><b style="margin-top:5px;">' . number_format(self::totalEarnings($earnings),2) . '</b></td>
								<td width="50"><span style="margin-top:5px;">-</span></td>
								<td width="120"><b style="margin-top:5px;">' .  number_format(self::totalDeductions($deductions),2) . '</b></td>				
								<td width="30"><span style="margin-top:5px;">=</span></td>
								<td width="110" style="text-align:right;"><b style="text-decoration:underline; font-size:15px; margin-top:5px;">' . number_format(self::totalGrossPay($earnings,$deductions),2) . '</b></td>
							</tr>
							</table>
							<br>
						</td>
						<td width="20">&nbsp;</td>
					  </tr>
					</table>
				</td>
                <td width="10">&nbsp;</td>
              </tr>           
			</table>
		</div>
			
		';			
		$html2pdf = new HTML2PDF('P','A4','en');
		$html2pdf->WriteHTML($msg, false);		
		$today = date("Ymd");			
		$pName = "payslip.pdf";	
		//echo BASE_PATH."files/payslip/$pName";
		//exit;
		$html2pdf->Output(BASE_PATH."files/payslip/$pName", 'F');	
		return BASE_PATH."files/payslip/$pName";	
	}
	
	public static function payslipCarbonizedWithDefault($e,$l,$b,$p,$ea,$de,$oed,$to,$la)
	{
		$logo = 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . "themes/default/themes-images/gleent-logo.jpg";			
		$msg  = '
		<br><br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>' . $e['company_name'] . '</td>
				<td>' . $e['employee_number'] . ' ' . $e['name'] . '</td>
			</tr>
			<tr>
				<td>Date Range: ' . date("Y/m/d", strtotime($p['from'])) . ' to ' . date("Y/m/d", strtotime($p['to'])) . '</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="40">&nbsp;</td>
			<td width="660">
				<img width="220" src="' . $logo . '" alt="Gleent Logo" /><br><br>
				<div style="padding:5px 30px; font-size:13px; text-align:right; font-style:italic; color:#222222;">Employee&rsquo;s Copy</div>
				<div style="padding:8px 5px; font-size:18px; text-align:center; color:#222222; background:#eeeeee;"><strong>PAYSLIP</strong></div><br /><br>
			</td>
			<td width="30">&nbsp;</td>
		  </tr>
		</table>		
		<div style="width:700px; margin:0 auto;">
			<table style="width:100%; display:block;" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="300">Emp Info</td>
				<td width="330">Earnings</td>
				<td width="300">Other Earnings/Deductions</td>
			</tr>
			<tr>
				<td width="50">
					<table>
						<tr>
							<td>Position</td>
							<td>' . $e['position'] . '</td>
						</tr>
						<tr>
							<td>Dept.</td>
							<td>' . $e['dept'] . '</td>
						</tr>
						<tr>
							<td>Basic Pay</td>
							<td>' . number_format($e['basic_pay'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Wage</td>
							<td>' .  number_format($e['wage'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Daily Rate</td>
							<td>' . number_format($e['daily_rate'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>DL Work</td>
							<td>' . $e['dl_work'] . '</td>
						</tr>	
						<tr>
							<td>Tax Scm</td>
							<td>' . $e['tax_scm'] . '</td>
						</tr>
						<tr>
							<td>C. Status</td>
							<td>' . $e['civil_status'] . '</td>
						</tr>
						<tr>
							<td>Emp Stat</td>
							<td>' . $e['emp_stat'] . '</td>
						</tr>
					</table>
					<p><b>Leave Balance</b></p>
					<table>
						<tr>
							<td>Vacation</td>
							<td>' . number_format($l['vl'],1,".",",") . '</td>
						</tr>
						<tr>
							<td>Sickness</td>
							<td>' . number_format($l['sl'],1,".",",") . '</td>
						</tr>
						<tr>
							<td>Emergency</td>
							<td>' . number_format($l['el'],1,".",",") . '</td>
						</tr>						
					</table>					
					<p style="text-align:center;">Net Pay ' . number_format($to['total_earnings'],2,".",",") . '</p>
				</td>
				<td width="330">
					<table>
						<tr>
							<td>Regular OT</td>
							<td>' . number_format($ea['regular_ot'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Restday OT</td>
							<td>' . number_format($ea['restday_ot'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Work on Spcl Hol</td>
							<td>' . number_format($ea['work_on_spcl_hol'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Work on Lgl Hol</td>
							<td>' . number_format($ea['work_on_lgl_hol'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Work+RD+Spcl</td>
							<td>' . number_format($ea['work_rd_spcl'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Work+RD+Lgl</td>
							<td>' . number_format($ea['work_rd_lgl'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>OT Allowance</td>
							<td>' . number_format($ea['ot_allowance'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Leave Amount</td>
							<td>' . number_format($ea['leave_amount'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Night Diff</td>
							<td>' . number_format($ea['nd_pay'],2,".",",") . '</td>
						</tr>						
					</table>
					<p><b>Deductions</b></p>
					<table>
						<tr>
							<td>Late</td>
							<td>' . number_format($de['late'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Undertime</td>
							<td>' . number_format($de['undertime'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>IDL Absent</td>
							<td>' . number_format($de['idl_absent'],2,".",",") . '</td>
						</tr>
					</table>
					<p><b>Contributions</b></p>
					<table>
						<tr>
							<td>Philhealth</td>
							<td>' . number_format($de['philhealth'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Pag-ibig</td>
							<td>' . number_format($de['pagibig'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Withholding Tax</td>
							<td>' . number_format($de['tax'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>Social Security System</td>
							<td>' . number_format($de['sss'],2,".",",") . '</td>
						</tr>
					</table>
					<p style="text-align:center;">Net Pay ' . number_format($to['total_deductions'],2,".",",") . '</p>
				</td>
				<td width="300">
					<table>
						<tr>
							<td>bereavement aid</td>
							<td>' . number_format($oed['bereavement_aid'],2,".",",") . '</td>
						</tr>
						<tr>
							<td></td>
							<td>.00</td>
						</tr>
						<tr>
							<td></td>
							<td>.00</td>
						</tr>
						<tr>
							<td></td>
							<td>.00</td>
						</tr>
					</table>
					<p><b>-Loans and Advances-</b></p>
					<table>
						<tr>
							<td>1.ssl</td>
							<td>' . number_format($la['ssl_loan'],2,".",",") . '</td>
							<td>' . number_format($la['ssl_advances'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>2.ccl</td>
							<td>' . number_format($la['ccl_loan'],2,".",",") . '</td>
							<td>' . number_format($la['ccl_advances'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>3.cmg</td>
							<td>' . number_format($la['cmg_loan'],2,".",",")	 . '</td>
							<td>' . number_format($la['cmg_advances'],2,".",",") . '</td>
						</tr>
						<tr>
							<td>4</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>5</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td rowspan="3"><hr /></td>
						</tr>
					</table>
					<p style="text-align:center;">Net Pay ' . number_format($to['net_pay'],2,".",",") . '</p>
				</td>
			</tr>			      
			</table>
		</div>
			
		';			
		$html2pdf = new HTML2PDF('P','A4','en');
		$html2pdf->WriteHTML($msg, false);		
		$today = date("Ymd");			
		$pName = "payslip.pdf";		
		$html2pdf->Output(BASE_PATH."files/payslip/$pName", 'F');	
		return BASE_PATH."files/payslip/$pName";	
	}
	
	public static function payslipCarbonized($e,$leaves,$ph,$period,$p)
	{		
		$total_earnings   = $ph->computeTotalEarnings();
		$total_deductions = $ph->computeTotalDeductions();
		$net_pay		  = $total_earnings - $total_deductions;
		$logo = 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . "themes/default/themes-images/gleent-logo.jpg";			
		$msg  = '
		<table width="700" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;">
  <tr>
    <td width="700" align="right" valign="top"><em>Employee&acute;s Copy</em></td>
  </tr>
  <tr>
    <td width="100%" align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="700" align="left" valign="top">
        <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="350" align="left" valign="top"><img width="220" src="' . $logo . '" alt="Gleent Logo" /></td>
            <td width="350" align="left" valign="top">
                <h3 style="margin:10px 0 5px; padding:0; font-size:18px;">' . $e['company_name'] . '</h3>
                <span style="font-size:11px;"><strong style="font-size:13px; display:block; margin:0 0 1px;">Payslip for the period of January 2013</strong><br>Pay Period: ' . date("Y/m/d", strtotime($period['from'])) . ' to ' . date("Y/m/d", strtotime($period['to'])) . '</span>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td width="100%" align="left" valign="top">&nbsp;</td>
  </tr>  
  <tr>
    <td width="100%" align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="100%" align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="700" align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="700" colspan="3" align="left" valign="top">Name&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . $e['lastname'] . ', ' . $e['firstname'] . ' ' . $e['middlename'] . '</strong></td>
        </tr>
      <tr>
        <td width="240" align="left" valign="top">Employee #&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . $e['employee_number'] . '</td>
        <td width="240" align="left" valign="top">Tax Scm&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . $e['tax_scm'] . '</td>
        <td width="240" align="left" valign="top">Wage&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' .  number_format($e['basic_salary'],2,".",",") . '</td>
      </tr>
      <tr>
        <td align="left" valign="top">Position&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . $e['position'] . '</td>
        <td align="left" valign="top">Civil Status&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . $e['marital_status'] . '</td>
        <td align="left" valign="top">Basic Pay&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . number_format($e['basic_salary']/2,2,".",",") . '</td>
      </tr>
      <tr>
        <td align="left" valign="top">Department.&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . $e['department'] . '</td>
        <td align="left" valign="top">Employment Status&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . $e['employment_status'] . '</td>
        <td align="left" valign="top">Daily Rate&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;' . number_format($ph->getValue('daily_rate'),2,".",",") . '</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="700" align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="700" align="left" valign="top">
    <table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="233" align="left" valign="top" style="border-left:1px solid #000000; border-top:1px solid #000000; border-bottom:1px solid #000000;">
        	<div style="border-bottom:1px solid #000000; margin-bottom:5px;"><table width="233" border="0" cellspacing="0" cellpadding="0" style="background:#f4f4f4;">
              <tr>
                <td style="background:#ebebeb;" width="180" height="17" align="left" valign="middle"><h4 style="font-size:12px; padding:5px 0; margin:0; vertical-align:middle;">EARNINGS</h4></td>
                <td style="background:#ebebeb;" width="53" height="17" align="center" valign="middle"><span style="font-size:10px;">Amount</span></td>
              </tr>
            </table></div>
            <table width="223" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="170" align="left" valign="top">Regular OT</td>
                <td width="53" align="right" valign="top">' . number_format($ph->getValue('regular_ot_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Restday OT</td>
                <td align="right" valign="top">' . number_format($ph->getValue('restday_ot_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Work on Spcl Hol</td>
                <td align="right" valign="top">' . number_format($ph->getValue('holiday_special_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Work on Lgl Hol</td>
                <td align="right" valign="top">' . number_format($ph->getValue('holiday_legal_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Work+RD+Spcl</td>
                <td align="right" valign="top">' . number_format($ph->getValue('holiday_special_restday_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Night Diff</td>
                <td align="right" valign="top">' . number_format($ph->getValue('total_ns_amount'),2,".",",") . '</td>
              </tr>
              <tr>
              	<td align="left" valign="top">Other Earnings</td>
                <td>&nbsp;</td>
              </tr>
              ';			
                foreach($p->getOtherEarnings() as $ea){			
                $msg .= '                     
                    <tr>
                        <td style="padding-left:20px;" align="left" valign="top">' . $ea->getLabel() . '</td>
                        <td align="right" valign="top">' . number_format($ea->getAmount(),2,".",",") . '</td>
                    </tr>';
                }
                
                $msg .= '
            </table>
        </td>
        <td width="233" align="left" valign="top" style="border-left:1px solid #000000; border-right:1px solid #000000; border-top:1px solid #000000; border-bottom:1px solid #000000;">
        	<div style="border-bottom:1px solid #000000; margin-bottom:5px;"><table width="233" border="0" cellspacing="0" cellpadding="0" style="background:#f4f4f4;">
              <tr>
                <td style="background:#ebebeb;" width="180" height="17" align="left" valign="middle"><h4 style="font-size:12px; padding:5px 0; margin:0; vertical-align:middle;">LEAVE BALANCE</h4></td>
                <td style="background:#ebebeb;" width="53" height="17" align="center" valign="middle"><span style="font-size:10px;">Amount</span></td>
              </tr>
            </table></div>
            <table width="223" border="0" cellspacing="0" cellpadding="0">
            ';
                if($leaves){
                    $msg .= '';
                    foreach($leaves as $l){
                        $msg .= '
                        <tr>
                            <td border="0"  width="170">' . $l['name'] . '</td>
                            <td border="0"  width="53" align="right" valign="top">' . number_format($l['no_of_days_available'],1,".",",") . '</td>
                        </tr>';						
                    }
                    $msg .='';
                }else{
                    $msg .= '<tr>
                            <td border="0"  width="220" align="center"><small>No Leave available</small></td>
                            <td border="0"  width="3" align="right" valign="top"></td>
                        </tr>
                    ';
                }
                $msg .= '
                <tr><td colspan="2">&nbsp;</td></tr>
            </table>
            <div style="border-bottom:1px solid #000000; border-top:1px solid #000000; margin-bottom:5px;"><table width="233" border="0" cellspacing="0" cellpadding="0" style="background:#f4f4f4;">
              <tr>
                <td style="background:#ebebeb;" width="180" height="17" align="left" valign="middle"><h4 style="font-size:12px; padding:5px 0; margin:0; vertical-align:middle;">CONTRIBUTIONS</h4></td>
                <td style="background:#ebebeb;" width="53" height="17" align="center" valign="middle"><span style="font-size:10px;">Amount</span></td>
              </tr>
            </table></div>
            <table width="223" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="170" align="left" valign="top">Philhealth</td>
                <td width="53" align="right" valign="top">' . number_format($ph->getValue('philhealth'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Pag-ibig</td>
                <td align="right" valign="top">' . number_format($ph->getValue('pagibig'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Withholding Tax</td>
                <td align="right" valign="top">' . number_format($ph->getValue('taxable'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">SSS</td>
                <td align="right" valign="top">' . number_format($de['sss'],2,".",",") . '</td>
              </tr>
            </table>
        </td>
        <td width="234" align="left" valign="top" style="border-right:1px solid #000000; border-top:1px solid #000000; border-bottom:1px solid #000000;">
        	<div style="border-bottom:1px solid #000000; margin-bottom:5px;"><table width="233" border="0" cellspacing="0" cellpadding="0" style="background:#f4f4f4;">
              <tr>
                <td style="background:#ebebeb;" width="180" height="17" align="left" valign="middle"><h4 style="font-size:12px; padding:5px 0; margin:0; vertical-align:middle;">DEDUCTIONS</h4></td>
                <td style="background:#ebebeb;" width="53" height="17" align="center" valign="middle"><span style="font-size:11px;">Amount</span></td>
              </tr>
            </table></div>
            <table width="223" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="170" align="left" valign="top">Late</td>
                <td width="53" align="right" valign="top">' . number_format($ph->getValue('late_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Undertime</td>
                <td align="right" valign="top">' . number_format($ph->getValue('undertime_amount'),2,".",",") . '</td>
              </tr>
              <tr>
                <td align="left" valign="top">Absent</td>
                <td align="right" valign="top">' . number_format($ph->getValue('absent_amount'),2,".",",") . '</td>
              </tr>
            </table>
            <table width="223" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                    <td colspan="3" width="223" align="left" valign="top">Other Deductions</td>
                </tr>
                <tr>
                    <td width="40" style="padding-left:20px;" align="left" valign="top">1.ssl</td>
                    <td width="70" align="right" valign="top">' . number_format($la['ssl_loan'],2,".",",") . '</td>
                    <td width="70" align="right" valign="top">' . number_format($la['ssl_advances'],2,".",",") . '</td>
                </tr>
                <tr>
                    <td align="left" style="padding-left:20px;" valign="top">2.ccl</td>
                    <td align="right" valign="top">' . number_format($la['ccl_loan'],2,".",",") . '</td>
                    <td align="right" valign="top">' . number_format($la['ccl_advances'],2,".",",") . '</td>
                </tr>
                <tr>
                    <td align="left" style="padding-left:20px;" valign="top">3.cmg</td>
                    <td align="right" valign="top">' . number_format($la['cmg_loan'],2,".",",")	 . '</td>
                    <td align="right" valign="top">' . number_format($la['cmg_advances'],2,".",",") . '</td>
                </tr>
            </table>
        </td>
      </tr>
      <tr>
      	<td colspan="3" style="font-weight:bold; font-size:12px; padding-top:7px; padding-bottom:2px; border-bottom:1px solid #aaaaaa;">
        	<div style="padding-left:380px;">
        	<table width="320" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                    <td width="100" align="left" valign="top">Total Deduction :</td>
                    <td width="220" align="right" valign="top">' . number_format($total_deductions,2,".",",") . '</td>
                  </tr>
            </table>
            </div>
      	</td>
      </tr>
      <tr>
      	<td colspan="3" style="font-weight:bold; font-size:12px; padding-top:2px; padding-bottom:2px; border-bottom:1px solid #aaaaaa;">
            <div style="padding-left:380px;">
            <table width="320" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="100" align="left" valign="top">Gross Pay :</td>
                    <td width="220" align="right" valign="top">' . number_format($total_earnings,2,".",",") . '</td>
                  </tr>
            </table>
            </div>
      	</td>
      </tr>
      <tr>
      	<td colspan="3" style="font-weight:bold; font-size:14px; padding-top:2px; padding-bottom:2px; border-bottom:1px solid #aaaaaa;">
            <div style="padding-left:380px; font-size:14px;">
            <table width="320" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="100" align="left" valign="top">Net Pay :</td>
                    <td width="220" align="right" valign="top">' . number_format($net_pay,2,".",",") . '</td>
                  </tr>
            </table>
            </div>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
</table>
		';					
		$html2pdf = new HTML2PDF('P','A4','en');
		$html2pdf->WriteHTML($msg, false);		
		$today = date("Ymd");			
		$pName = "payslip_carbonized.pdf";				
		$html2pdf->Output(BASE_PATH."files/payslip/$pName", 'F');	
		return $pName;	
	}
	
	public static function totalDeductions($deductions)
	{
		$total = $deductions['sss'] + $deductions['philhealth'] + $deductions['pagibig'] + $deductions['late'] + $deductions['undertime'] + $deductions['absent_amount'] + $deductions['suspended_amount'];
		return $total;
	}
	
	public static function totalEarnings($earnings)
	{
		$total = $earnings['basic_pay'] + $earnings['overtime'];
		return $total;
	}
	
	public static function totalGrossPay($earnings,$deductions)
	{
		$total_earnings   = self::totalEarnings($earnings);
		$total_deductions = self::totalDeductions($deductions);
		$total = $total_earnings - $total_deductions;
		return $total;
	}
	
	
}
?>