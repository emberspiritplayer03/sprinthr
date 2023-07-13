<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
	<tr>
    	<td style="border:none; font-size:16pt;" align="left" colspan="6">
        <strong>
        	<!-- <?php echo($report_body_type == DETAILED ? "Detailed : Daily Time Record Data" : "Summarized : Daily Time Record Data"); ?> -->
            <?php
            
                switch($report_body_type)
                {
                    case DETAILED:
                        echo 'Detailed : Daily Time Record Data';
                        break;
                    case SUMMARIZED:
                        echo 'Summarized : Daily Time Record Data';
                        break;
                    case INCOMPLETE_BREAK_LOGS:
                        echo 'Incomplete Break Logs : Daily Time Record Data';
                        break;
                    case NO_BREAK_LOGS:
                        echo 'No Break Logs : Daily Time Record Data';
                        break;
                    case EARLY_BREAK_OUT:
                        echo 'Early Break Out : Daily Time Record Data';
                        break;
                    case LATE_BREAK_IN:
                        echo 'Late Break In : Daily Time Record Data';
                        break;
                    default;
                        echo 'Detailed : Daily Time Record Data';
                        break;
                }
            ?>
        </strong>
        </td>
    	<td style="border:none;">&nbsp;</td>
    </tr>   
    <tr>
    	<td style="border:none; font-size:14pt;" align="left"><strong>Project Site:</strong></td>
    	<td style="border:none;font-size:14pt;"><b><?php echo $filter_by; ?></b></td>
    </tr>
     <tr>
        <td style="border:none; font-size:12pt;" align="left"><strong>From:</strong></td>
        <td style="border:none;"><b><?php echo $date_from . " to " . $date_to; ?></b></td>
    </tr>
    <tr>
    	<td style="border:none; font-size:12pt;" align="left"><strong>Date Generated:</strong></td>
    	<td style="border:none;"  align="left"><b><?php echo date('Y-m-d'); ?></b></td>
    </tr>
</table>	