<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Filed</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Start</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date End</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>No. of Days</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Type</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Paid</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Reason</span></strong></td>
    </tr>
	<?php 
		foreach($leave as $a){
        //$employee_name = strtr(utf8_decode($a['employee_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');    

        $employee_name = mb_convert_encoding($a['employee_name'] , "HTML-ENTITIES", "UTF-8");
        if(!empty($a['project_site_id']) || $a['project_site_id'] != 0){
            $project = G_Project_Site_Finder::findById($a['project_site_id']);
            if($project){
                $project_site_name = $project->getprojectname();
            }
            else{
                $project_site_name = "";
            }
        }
        else{
            $project_site_name = "";
        }

	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['department_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['section_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['position']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_applied'] . ' ' . $a['time_applied']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_start']; ?></td>   
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_end']; ?></td> 
            <?php if($a['apply_half_day_date_start'] == 'Yes') { ?>
                <td align="left" valign="top" style="border-bottom:none;">0.5</td> 
            <?php }else{ ?>
                <?php
                    $datetime1 = new DateTime($a['date_start']);
                    $datetime2 = new DateTime($a['date_end']);
                    $interval_days = $datetime1->diff($datetime2);
                    $days = $interval_days->format('%a');
                ?>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $days + 1; ?></td> 
            <?php } ?>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['leave_type']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['is_approved']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['is_paid']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['leave_comments']; ?></td>            
        </tr>
    <?php } ?>

</table>