<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">	
    <tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employment Status</strong></td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Gender</strong></td>        
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Number of employees as of <?php echo $date_prev; ?></strong></td>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>New hires/services</strong></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Resignation/EOC</strong></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Subtotal</strong></td>           
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Remarks</strong></td>                   
    </tr>    
    <?php foreach( $data['current'] as $key => $d ){ $row_count = 0; ?>                        
        <?php foreach( $d as $subkey => $subData ){ ?>
        <?php 
            //Previous summary
            $total_prev_month = $data['previous'][$key][$subkey][0]['total_employees'] - $data['previous'][$key][$subkey][0]['total_resigned'];
            $row_total        = $total_prev_month + $subData[0]['total_newly_hired'] - $subData[0]['total_resigned'];

            $g_total['total_employees']   += $total_prev_month;
            $g_total['total_newly_hired'] += $subData[0]['total_newly_hired'];
            $g_total['total_resigned']    += $subData[0]['total_resigned'];
            $g_total['sub_total']         += $row_total;
        ?>
            <tr>    
                <?php if( $row_count == 0 ){ ?>
                    <td rowspan="2"><?php echo $key; ?></td>
                <?php } ?>
                <td><?php echo $subkey; ?></td>
                <td><?php echo $total_prev_month; ?></td>
                <td><?php echo $subData[0]['total_newly_hired']; ?></td>
                <td><?php echo $subData[0]['total_resigned']; ?></td>
                <td><?php echo $row_total; ?></td>
                <td></td>
            </tr>
        <?php $row_count++;} ?>        
    <?php } ?>  
    <tr>
        <td colspan="2"><strong>Total</strong></td>
        <?php foreach( $g_total as $value ){ ?>  
            <td><?php echo $value; ?></td>
        <?php } ?>
        <td></td>
    </tr>    
</table>
