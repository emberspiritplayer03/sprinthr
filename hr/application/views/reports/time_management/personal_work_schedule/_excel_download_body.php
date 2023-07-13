<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Schedule Name</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Effectivity Date</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Working Days</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Grace Period</span></strong></td>
        <td align="center" valign="top"><strong>Time In</span></strong></td>        
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Time Out</strong></td>       
    </tr>
	<?php 
        foreach($schedules as $key => $value){ 
            $e = G_Employee_Finder::findById($key);
            if($e){
    ?>
    	<tr style="background-color:#CCC;">
        	<td align="left" valign="top" style="border-bottom:none;" colspan="6"><?php echo $e->getLastname() . ", " . $e->getFirstname(); ?></td>
        </tr>        
	        <?php foreach($value as $v){ ?>
            <tr>
        		<td align="center" valign="top" style="border-bottom:none;"><?php echo $v['schedule_name']; ?></td>
                <td align="center" valign="top" style="border-bottom:none;"><?php echo $v['effectivity_date']; ?></td>
                <td align="center" valign="top" style="border-bottom:none;"><?php echo $v['working_days']; ?></td>
                <td align="center" valign="top" style="border-bottom:none;"><?php echo $v['grace_period']; ?></td>
                <td align="center" valign="top" style="border-bottom:none;"><?php echo $v['time_in']; ?></td>
                <td align="center" valign="top" style="border-bottom:none;"><?php echo $v['time_out']; ?></td>
            </tr>
    	    <?php } ?>
    <?php	
            }else{
            
			}
        }
    ?>
</table>