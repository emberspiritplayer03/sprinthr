<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
	<tr>
    	<td style="border:none; font-size:16pt;" align="left">
        <strong>Required Shift Data</strong>
        </td>
    	<td style="border:none;">&nbsp;</td>
    </tr>   
    <tr>
    	<td style="border:none; font-size:11pt;" align="left"><b>Schedule</b></td>
        <?php if( $is_all ){ ?>
            <td style="border:none;"><b><?php echo $start_date . " to " . $end_date; ?></b></td>
        <?php }else{ ?>
            <td style="border:none;"><b><?php echo $schedule_name;?></b></td>
        <?php } ?>
    </tr>
    <tr>
    	<td style="border:none; font-size:14pt;" align="left"><strong>Date Generated</strong></td>
    	<td style="border:none;"><b><?php echo date('Y-m-d'); ?></b></td>
    </tr>
</table>	