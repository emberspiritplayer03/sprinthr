<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Name</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Is Approve</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Comment</span></strong></td>
        <td align="center" valign="top"><strong>Date From</span></strong></td>        
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Date To</strong></td>        
    </tr>    
    <?php
		$counter = 1; 			
		foreach($requests as $r){ 				
	?>
    <tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;text-align:left;"><?php echo $r['emp_name']; ?></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['job_name']; ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['is_approved']; ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['comments']; ?></span></strong></td>                
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['date_start']; ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $r['date_end']; ?></span></strong></td>        
    </tr>  
    
    <?php 
		$counter++;
		} 
	?>    
</table>