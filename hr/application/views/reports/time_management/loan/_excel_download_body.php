<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:980pt; line-height:12pt;">
	<tr>
        <td align="center" valign="top" style="border-bottom:none; width: 200pt;"><strong>&nbsp;</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Loan Type</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Granted</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Start of Payment</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Months to pay</span></strong></td>      
        <td align="center" valign="top" style="border-bottom:none;"><strong>Principal Amount</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Balance</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Amount Deducted</span></strong></td> 
        <td align="center" valign="top" style="border-bottom:none;"><strong>Penalty</span></strong></td> 
        <td align="center" valign="top" style="border-bottom:none;"><strong>Deduction per Period</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Deduction Type</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td>            
    </tr>

    <!--
	<?php 
		//foreach($loans as $l){
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['employee_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['loan_type']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['loan_amount']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['balance']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['amount_paid']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['total_deduction_per_period']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['deduction_type']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php //echo $l['status']; ?></td>
        </tr>
    <?php //} ?>
    -->    
    <?php
        $grand_total = array();
    ?>
    <?php foreach($loans as $group_key => $key_data) { ?> 
            <?php
                $sub_total      = array();
                $show_name = false;;
            ?>       
            <?php foreach($key_data as $l) { ?>
                    <?php if(!$show_name) { ?>
                        <?php $show_name = true; ?>
                        <tr>
                            <td colspan="12" align="left" valign="top" style="border-bottom:none;">&nbsp;<?php echo $l['employee_name'] . " ({$l['employee_code']})"; ?></td>
                        </tr>
                    <?php } ?>
                    <?php
                        $sub_total['total_loan_amount']     += str_replace(',', '', $l['loan_amount']);
                        $sub_total['total_balance']         += str_replace(',', '', $l['balance']);
                        $sub_total['total_amount_paid']     += str_replace(',', '', $l['amount_paid']);
                        $sub_total['total_penalty']         += str_replace(',', '', $l['total_penalty']);
                        $sub_total['total_deduction_per_period'] += str_replace(',', '', $l['total_deduction_per_period']);
                    ?>            
                    <tr>
                        <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>       
                        <td align="left" valign="top" style="border-bottom:none;">&nbsp;<?php echo $l['loan_type']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                        <td align="left" valign="top" style="border-bottom:none;">&nbsp;<?php echo date("F d, Y", strtotime($l['start_date'])); ?></td>
                        <td align="left" valign="top" style="border-bottom:none;">&nbsp;<?php echo $l['months_to_pay']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['loan_amount']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['balance']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['amount_paid']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                        <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['total_deduction_per_period']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['deduction_type']; ?></td>
                        <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['status']; ?></td>
                    </tr>
            <?php } ?>
            <tr>
                <td align="left" colspan="5" valign="top" style="border-bottom:none;">&nbsp;<?php echo "Subtotal"; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($sub_total['total_loan_amount'],2, '.' , ',' ); ?></strong></td>
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($sub_total['total_balance'],2, '.' , ',' ); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($sub_total['total_amount_paid'],2, '.' , ',' ); ?></td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;<strong><?php //echo number_format($sub_total['total_penalty'],2, '.' , ',' ); ?></td>               
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($sub_total['total_deduction_per_period'],2, '.' , ',' ); ?></td>
                <td align="left" colspan="2" valign="top" style="border-bottom:none;">&nbsp;</td>
            </tr>
            <?php 
                $grand_total['total_loan_amount']           += str_replace(',', '', $sub_total['total_loan_amount']);
                $grand_total['total_balance']               += str_replace(',', '', $sub_total['total_balance']);
                $grand_total['total_amount_paid']           += str_replace(',', '', $sub_total['total_amount_paid']);
                $grand_total['total_penalty']               += 0;
                $grand_total['total_deduction_per_period']  += str_replace(',', '', $sub_total['total_deduction_per_period']);
            ?>            
    <?php } ?>   
            <tr>
                <td align="left" colspan="5" valign="top" style="border-bottom:none;">&nbsp;</td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;</td>
                <td align="left" colspan="2" valign="top" style="border-bottom:none;">&nbsp;</td>
            </tr>  
            <tr>
                <td align="left" colspan="5" valign="top" style="border-bottom:none;"><?php echo "Total"; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($grand_total['total_loan_amount'],2, '.' , ',' ); ?></strong></td>
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($grand_total['total_balance'],2, '.' , ',' ); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($grand_total['total_amount_paid'],2, '.' , ',' ); ?></td>
                <td align="left" valign="top" style="border-bottom:none;">&nbsp;<strong><?php //echo number_format($grand_total['total_penalty'],2, '.' , ',' ); ?></td>               
                <td align="left" valign="top" style="border-bottom:none;"><strong><?php echo number_format($grand_total['total_deduction_per_period'],2, '.' , ',' ); ?></td>
                <td align="left" colspan="2" valign="top" style="border-bottom:none;">&nbsp;</td>
            </tr>     
</table>