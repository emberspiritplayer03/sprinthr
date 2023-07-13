<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
	<tr>
    	<td style="border:none; font-size:16pt;" align="left" colspan="6">
        <strong>
        	<?php echo($report_body_type == DETAILED ? "Detailed : Tardiness Data" : "Summarized : Tardiness Data"); ?>
        </strong>
        </td>
    	<td style="border:none;">&nbsp;</td>
    </tr>   
    <tr>
        <td style="border:none; font-size:13pt;" align="left"><strong>Project Site:</strong></td>
        <td style="border:none; font-size:13pt;"><?php echo $filter_by; ?></td>
    </tr>
    <tr>
    	<td style="border:none; font-size:12pt;" align="left"><strong>From:</strong></td>
    	<td style="border:none;"><b><?php echo $date_from . " to " . $date_to; ?></b></td>
    </tr>
    <tr>
    	<td style="border:none; font-size:12pt;" align="left"><strong>Date Generated:</strong></td>
    	<td style="border:none;" align="left"><b><?php echo date('Y-m-d'); ?></b></td>
    </tr>
</table>	