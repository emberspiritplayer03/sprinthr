<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
	<tr>
    	<td style="border:none; font-size:16pt;" align="left">
        <strong>
        	<?php //echo($report_body_type == DETAILED ? "Detailed : Leave Data" : "Summarized : Leave Data"); ?>
            <?php echo 'Loan Data'; ?>
        </strong>
        </td>
    	<td style="border:none;">&nbsp;</td>
    </tr>   
    <tr>
    	<td style="border:none; font-size:14pt;" align="left"><strong><?php echo $loans_start_date; ?></strong></td>
    	<td style="border:none;"><b><?php //echo $date_from . " to " . $date_to; ?></b></td>
    </tr>
    <tr>
    	<td style="border:none; font-size:14pt;" align="left"><strong>Date Generated</strong></td>
    	<td style="border:none;"><b><?php echo date('Y-m-d'); ?></b></td>
    </tr>
</table>	