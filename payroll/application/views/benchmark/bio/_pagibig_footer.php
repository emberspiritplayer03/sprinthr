<table border="1" cellpadding="0" cellspacing="0" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td colspan="14" style="height:10pt; border:none;"></td>
    </tr>
	<tr>
        <td colspan="5" rowspan="9">
            <table border="1" cellpadding="0" cellspacing="0" style="font-size:8pt; line-height:12pt; width:361pt;">
                <tr>
                    <td colspan="2" style="font-size:6pt; height:11pt; vertical-align:middle; border-right:none; border-bottom:none;">No. of Employees</td>
					<td rowspan="4" style="border-right:none; border-left:none;"></td>
                    <td colspan="2" style="font-size:6pt; height:11pt; vertical-align:middle; border-left:none; border-bottom:none;">Total No. of Employees</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size:6pt; height:11pt; vertical-align:middle; border-right:none; border-bottom:none; border-top:none;">on this page</td>
                    <td colspan="2" style="font-size:6pt; height:11pt; vertical-align:middle; border-left:none; border-top:none; border-bottom:none;">if last page</td>
                </tr>
				<tr>
                    <td colspan="2" rowspan="2" align="center" style="vertical-align:middle; height:11pt; font-size:11pt; border-bottom:none; border-right:none; border-top:none;"><strong><?php echo $counter - 1; ?></strong></td>
                    <td colspan="2" rowspan="2" align="center" style="vertical-align:middle; height:11pt; font-size:11pt; border-bottom:none; border-left:none; border-top:none;"><strong><?php echo $total_employees; ?></strong></td>
                </tr>                
            </table>    
             <table border="1" cellpadding="0" cellspacing="0" style="font-size:8pt; background:#ccc;">
                <tr>
                    <td align="center" colspan="5" style="font-size:9pt; border-bottom:none; height:22pt; vertical-align:middle;"><strong>FOR Pag-IBIG USE ONLY</strong></td>
                </tr>
                <tr>
                    <td valign="bottom" style="vertical-align:bottom; border-bottom:none; border-top:none; border-right:none;">POSTED BY:</td>
                    <td valign="bottom" colspan="2" style=" border-left:none; border-top:none; border-right:none;">&nbsp;</td>
                    <td valign="bottom" style="vertical-align:bottom; border-bottom:none; border-top:none; border-right:none; border-left:none;">DATE:</td>
                    <td valign="bottom" style="border-top:none; border-left:none;">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="bottom" style="border-bottom:none; border-top:none; border-right:none; border-left:none;">&nbsp;</td>
                    <td valign="bottom" colspan="2" style="border-top:none; border-right:none; border-left:none;">&nbsp;</td>
                    <td valign="bottom" style="border-bottom:none; border-top:none; border-right:none; border-left:none;">&nbsp;</td>
                    <td valign="bottom" style="border-top:none; border-left:none;">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="bottom" style="vertical-align:bottom; border-bottom:none; border-top:none; border-right:none;">APPROVED BY:</td>
                    <td valign="bottom" colspan="2" style=" border-left:none; border-top:none; border-right:none;">&nbsp;</td>
                    <td valign="bottom" style="vertical-align:bottom; border-bottom:none; border-top:none; border-right:none; border-left:none;">DATE:</td>
                    <td valign="bottom" style="border-top:none; border-left:none;">&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="5" style="border-top:none;"></td>
                </tr>
            </table>
        </td>
		<td rowspan="9" style="border:none; width:20pt; color:#fff;">_</td>
        <td colspan="8" rowspan="9">
            <table border="1" cellpadding="0" cellspacing="0" style="font-size:8pt; line-height:12pt;">
                <tr>
                    <td align="left" style="font-size:6pt; vertical-align:middle; height:11pt; border-bottom:none; border-right:none;">TOTAL FOR</td>
                    <td align="right" rowspan="2" style="width:20pt; vertical-align:bottom; font-size:11pt; border-bottom:none; border-left:none;"><strong>&#8369;</strong></td>
                    <td align="right" rowspan="2" style="vertical-align:bottom; font-size:10pt; border-bottom:none; border-right:none;"><strong><?php echo number_format($er_gtotal,2,".",","); ?></strong></td>
                    <td align="right" rowspan="2" style="width:20pt; vertical-align:bottom; font-size:11pt; border-bottom:none; border-left:none;"><strong>&#8369;</td>
                    <td align="right" rowspan="2" style="font-size:10pt; border-bottom:none; border-right:none;"><strong><?php echo number_format($ee_gtotal,2,".",","); ?></strong></td>
                    <td align="right" rowspan="2" style="width:20pt; font-size:11pt; border-left:none;"><strong>&#8369;</strong></td>
                    <td align="right" colspan="2" rowspan="2" style="font-size:10pt; border-bottom:none;"><strong><?php echo number_format((float)$er_gtotal + $ee_gtotal,2,".",","); ?></strong></td>
                </tr>
                <tr>
                    <td align="left" style="font-size:6pt; vertical-align:middle; height:11pt; border-bottom:none; border-top:none; border-right:none;">THIS PAGE</td>
				</tr>                

                <tr>
                    <td align="left" style="font-size:6pt; vertical-align:middle; height:11pt; border-bottom:none; border-right:none;">GRAND TOTAL</td>
                    <td align="right" rowspan="2" style="width:20pt; vertical-align:bottom; border-bottom:none; border-left:none; font-size:11pt;"><strong>&#8369;</strong></td>
                    <td align="right" rowspan="2" style="vertical-align:bottom; border-bottom:none; border-left:none; border-right:none; font-size:10pt;"><strong><?php echo number_format($pagibig_total['ee'],2,".",","); ?></strong></td>
                    <td align="right" rowspan="2" style="width:20pt; vertical-align:bottom; border-right:none; border-left:none; font-size:11pt;"><strong>&#8369;</td>
                    <td align="right" rowspan="2" style="border-right:none; font-size:10pt;"><strong><?php echo number_format($pagibig_total['er'],2,".",","); ?></strong></td>
                    <td align="right" rowspan="2" style="width:20pt; border-top:none; border-left:none; font-size:11pt;"><strong>&#8369;</strong></td>
                    <td align="right" colspan="2" rowspan="2" style="font-size:10pt;"><strong><?php echo number_format($pagibig_total['gtotal'],2,".",","); ?></strong></td>
                </tr>
                <tr>
                    <td align="left" style="font-size:6pt; vertical-align:middle; border-top:none; border-right:none; height:11pt;">(if last page)</td>
				</tr>
                <tr>
                    <td align="center" colspan="8" style="text-align:center; vertical-align:bottom; font-size:9pt; height:20pt;"><strong>CERTIFIED CORRECT BY:</strong></td>
                </tr>
                <tr>
                    <td colspan="6" align="left" style="border-bottom:none; height:10pt; vertical-align:middle;"><strong>SIGNATURE OVER PRINTED NAME</strong></td>
                    <td colspan="2" align="left" style="border-bottom:none;"><strong>DATE</strong></td>            
                </tr>
                <tr>            
                    <td colspan="6" align="center" style="color:#002060; border-top:none;"><strong>LALAINE B. BAYOTAS</strong></td>
                    <td colspan="2" align="center" style=" color:#002060; border-top:none;"><strong></strong></td>            
                </tr>
                <tr>
                    <td colspan="6" align="left" style="border-bottom:none; height:10pt; vertical-align:middle;"><strong>OFFICIAL DESIGNATION</strong></td>
                    <td align="left" style="font-size:6pt; border-bottom:none; vertical-align:middle;">PAGE NO.</td>            
                    <td align="left" style="font-size:6pt; border-bottom:none; vertical-align:middle;">NO. OF PAGES</td>            
                </tr>
                <tr>            
                    <td colspan="6" align="center" style="color:#002060; border-top:none;"><strong>FINANCE HEAD</strong></td>
                    <td align="left" style="border-top:none;"></td>            
                    <td align="left" style="border-top:none;"></td>            
                </tr>
            </table>   
        </td>
	</tr>
</table> 
<table cellpadding="0" cellspacing="0" style="font-size:10pt; width:836pt; line-height:12pt;">
	<tr>
    	<td colspan="14" align="left"><strong><i>NOTE: PLEASE READ INSTRUCTIONS AT THE BACK.</i></strong></td>
    </tr>    
	<tr>
    	<td colspan="14" align="center"><strong>THIS FORM CAN BE REPRODUCED. NOT FOR SALE.</strong></td>
    </tr>
    <tr>
    	<td colspan="14">&nbsp;</td>
    </tr>
</table>