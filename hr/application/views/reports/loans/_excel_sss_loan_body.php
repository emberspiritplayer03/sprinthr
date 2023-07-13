<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee ID</strong></td>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>SSS No.</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>SurName</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>GivenName</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Midlnit</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</strong></td>        
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employment Status</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Loan Type</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Granted</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Loan Amount</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Amount Paid</strong></td>       
    </tr>
	<?php 
		foreach($loans_data as $key => $l){
            $firstname    = strtr(utf8_decode($l['employee_details']['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $lastname     = strtr(utf8_decode($l['employee_details']['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');                        
            $middlename   = strtr(utf8_decode($l['employee_details']['middlename']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');                        
            $section_name = strtr(utf8_decode($l['employee_details']['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');                        
            $department_name = strtr(utf8_decode($l['employee_details']['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');                        
            $employee_status = strtr(utf8_decode($l['employee_details']['employee_status']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');                        
    ?>
        <?php foreach( $l['loan_header'] as $loan ){ ?>
        <?php
            $total_current_payment = 0;

            $current_payment = $loan['payment'];
            if($current_payment) {

                foreach($current_payment as $key_date => $key_date_data) {
                    $total_current_payment += $key_date_data['amount_paid'];
                }

            } else {
                $total_current_payment = 0;
            }
        ?>
        <?php if($total_current_payment > 0) { ?>
        <tr>
            <td align="left" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo str_pad($l['employee_details']['employee_code'], 4, "0", STR_PAD_LEFT); ?></td>
            <td align="left" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo $l['employee_details']['sss_number']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $lastname; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $firstname; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $middlename; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $department_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $section_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_status; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $loan['header']['loan_title']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo $loan['header']['start_date']; ?></td>
            <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($loan['header']['total_amount_to_pay'],2); ?></td>
            <!-- <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($loan['header']['amount_paid'],2); ?></td> -->
            <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($total_current_payment,2); ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
    <?php } ?>   
</table>