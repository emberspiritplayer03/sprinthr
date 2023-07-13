<?php	
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();
ob_start();
?>
<table border="1" cellpadding="3">
<tr>
	<td width="50%">
		<table>
			<tr>
				<td align="left"><img style ="width:359px;height:36px" src ="<?php echo BASE_FOLDER;?>images/logo.png" /></td>
			</tr>
			<tr>
				<td align="center" style ="font-size:20px;font-style:italic">3rd Flr. Business Solution Ctr., Meralco Compound, Ortigas Pasig City</td>						
			</tr>
		</table>
		<br>
		<table>
			<tr>
				<td style ="font-size:20px" align="center">Tax Status :</td>
				<td style ="font-size:20px" align="center">AcctNo<br /><?php echo $employee->getAccountNumber();?></td>
				<td style ="font-size:20px" align="center">Paydate<br /><?php echo Tools::dateFormat($payout_date);?></td>
			</tr>
			<tr>
				<td style ="font-size:20px;font-weight:bold" align="center"></td>
				<td style ="font-size:20px;font-weight:bold" align="center"></td>
				<td style ="font-size:20px;font-weight:bold" align="center"></td>
			</tr>					
			<tr>
				<td colspan="6"><br></td>
			</tr>
			<tr>
				<td style ="font-size:20px">Name :</td>
				<td style ="font-size:20px;font-weight:bold" colspan="5" align="left"><?php echo $employee->getName();?></td>
			</tr>
			<tr>
				<td colspan="6"><hr></td>
			</tr>
			</table>
			<table>
			<tr>
				<td>
					<table cellpadding="2">
					<tr>
						<td style ="font-size:20px" colspan="4">SEMI-MONTHLY</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('basic_pay'));?></td>
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">NSD HRS</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('nightshift_hours'));?></td>
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">NSD AMT</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('total_nightshift_amount'));?></td>
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Reg OT HRs</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('regular_ot_hours'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Reg OT Amt</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('regular_ot_amount'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">RDH OT (HRS)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('restday_holiday_ot_hours'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">RDH OT Amount</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('restday_holiday_ot_amount'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Late Hrs</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('late_hours'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Late Amt</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('late_amount'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Absent(Days)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('absent_days'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Absent(Amt)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('absent_amount'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Suspension(day)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('suspension_days'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Suspension(Amt)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('suspension_amount'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Holiday(Hrs)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('holiday_hours'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Holiday(Amt)</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('holiday_amount'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">OtherAdj</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($payslip->computeTotalEarnings(Earning::EARNING_TYPE_ADJUSTMENT));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">EXCESSLINES</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('excess lines'));?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">GROSS PAY</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($payslip->getGrossPay());?></td>																	
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Allowance</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($payslip->computeTotalEarnings(Earning::EARNING_TYPE_ALLOWANCE));?></td>																	
					</tr>
					</table>
				</td>
				<td>
					<table cellpadding="2">
					<tr>
						<td style ="font-size:20px;font-weight:bold" colspan="4">DEDUCTIONS</td>
						<td style ="font-size:20px" colspan="2"></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Advances</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($payslip->computeTotalDeductions(Deduction::DEDUCTION_TYPE_ADVANCE));?></td>																
					</tr>
<!--					<tr>
						<td style ="font-size:20px" colspan="4">MEDICARD</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('healthcard'));?></td>																
					</tr>-->
					<tr>
						<td style ="font-size:20px" colspan="4">SSS</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('sss'));?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">PHIC</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('philhealth'));?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">HDMF</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('pagibig'));?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">CA SSS</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('ss loan'));?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">HDMF LOAN</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($ps->getValue('hdmf loan'));?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">TAXABLE</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($payslip->getTaxable());?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">W_TAX</td>
						<td style ="font-size:20px" colspan="2"><?php echo Tools::currencyFormat($payslip->getWithheldTax());?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px;font-weight:bold" colspan="4">NET DUE</td>
						<td style ="font-size:20px;font-weight:bold" colspan="2"><?php echo Tools::currencyFormat($payslip->getNetPay());?></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="6"><br><br></td>																					
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Remarks</td>
						<td style ="font-size:20px" colspan="2"></td>																
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="6"><br><br></td>																					
					</tr>
					<tr>
						<td style ="font-size:20px" colspan="4">Prepared By:</td>
						<td style ="font-size:20px;font-weight:bold" colspan="2">KAB</td>																
					</tr>
					</table>
				</td>
			</tr>
			
		</table>
	</td>
	<td width="30%">
		<table cellpadding="2">					
			<tr><td align="center"><img style ="width:359px;height:36px" src ="<?php echo BASE_FOLDER;?>images/logo.png" /></td></tr>
			<tr>
				<td align="center" style ="font-size:20px"><br>
					I acknowledge to have received the sum as shown below as full payment of
					my salaries and benefits as of the above paydate.<br><br>
				</td>
			</tr>
			<tr><td align="center" style ="font-size:20px;font-weight:bold">Paydate: <?php echo Tools::dateFormat($payout_date);?></td></tr>
			<tr><td align="center" style ="font-size:20px;font-weight:bold"></td></tr>
			<tr><td align="center" style ="font-size:20px"><hr></td></tr>
			<tr><td align="center" style ="font-size:20px;font-weight:bold"><?php echo $employee->getName();?></td></tr>
			<tr><td align="center" style ="font-size:20px"><hr></td></tr>
			<tr><td align="center" style ="font-size:20px;">Sign over printed Name</td></tr>
			<tr><td align="center" style ="font-size:20px;"><br><br><br>Kindly Return this Copy</td></tr>
			<tr><td align="center" style ="font-size:20px;"><br><br><br>Prepared By:</td></tr>
			<tr><td align="center" style ="font-size:20px;font-weight:bold"><br>KAB</td></tr>
		</table>
	</td>
</tr>
</table>
<?php
$content = ob_get_contents();
ob_end_clean();
$pdf->writeHTML($content, true, false, true, false, '');		
$pdf->Output($payout_date .'-'. $employee->getLastname(). '_'. $employee->getFirstname() .'.pdf', 'D');
?>