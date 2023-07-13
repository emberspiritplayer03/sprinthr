<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Name</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Is Approve</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Comment</span></strong></td>
        <td align="center" valign="top"><strong>Date of Undertime</span></strong></td>        
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Time Out</strong></td>        
    </tr>    
    <?php
		$counter = 1; 			
		foreach($requests as $r){ 				
	?>
    <tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;text-align:left;"><?php echo $r['emp_name']; ?></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['job_name']; ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['is_approved']; ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['reason']; ?></span></strong></td>                
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['date_of_undertime']; ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['time_out']; ?></span></strong></td>        
    </tr>  
    
    <?php 
		$counter++;
		} 
	?>    
</table>