<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Status</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Lastname</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Firstname</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Start date</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>End Date</strong></td>        
        <td align="center" valign="top" style="border-bottom:none;"><strong>Loan Type</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Payable Date</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Loan Amount</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Gives</strong></td>
        <?php foreach( $loans_header_date as $hdr_date ){ ?>
            <?php $formatted_date = date("d-M-Y",strtotime($hdr_date)); ?>
            <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $formatted_date; ?></strong></td>
        <?php } ?>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Balance</strong></td>
    </tr>
	<?php 
		foreach($loans_data as $key => $l){
            $firstname   = strtr(utf8_decode($l['employee_details']['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $lastname    = strtr(utf8_decode($l['employee_details']['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');                        
            foreach( $l['loan_header'] as $hdr ){
                $start_date = date("d-M-Y",strtotime($hdr['header']['start_date']));
                $end_date   = date("d-M-Y",strtotime($hdr['header']['end_date']));
    ?>
        <tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['employee_status']; ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($firstname,  MB_CASE_TITLE, "UTF-8"); ?></td>            

            <td align="left" valign="top" style="border-bottom:none;"><?php echo $start_date; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $end_date; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $hdr['header']['loan_title']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $hdr['header']['deduction_type']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($hdr['header']['total_amount_to_pay'],2); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $hdr['header']['months_to_pay']; ?></td>
            <?php
                $total_loan_amount   += $hdr['header']['total_amount_to_pay'];
                $total_months_to_pay += $hdr['header']['months_to_pay'];
            ?>
            <?php foreach( $loans_header_date as $hdr_date ){ ?>
                <?php if( isset($hdr['payment'][$hdr_date]) && $hdr['payment'][$hdr_date] != '' ){ ?>
                        <?php
                            $total_payment[$hdr_date] += $hdr['payment'][$hdr_date]['amount_paid'];
                        ?>
                        <td align="center" valign="top" style="border-bottom:none;"><?php echo $hdr['payment'][$hdr_date]['amount_paid']; ?></td>
                <?php }else{ ?>                
                        <td align="center" valign="top" style="border-bottom:none;">0.00</td>
                <?php } ?>
            <?php } ?>

            <?php $balance = $hdr['header']['total_amount_to_pay'] - $hdr['header']['amount_paid']; ?>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($balance,2); ?></td>
        </tr>
            <?php $total_balance += $balance; ?>
            <?php } ?>
        <?php } ?>
        <tr>
            <td align="right" colspan="8" valign="top" style="border-bottom:none;">Total: </td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($total_loan_amount,2); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($total_months_to_pay,2); ?></td>
            <?php foreach( $loans_header_date as $hdr_date ){ ?>

                    <td align="center" valign="top" style="border-bottom:none;"><?php echo number_format($total_payment[$hdr_date],2); ?></td>
            <?php } ?>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo number_format($total_balance, 2); ?></td>
        </tr>        
</table>