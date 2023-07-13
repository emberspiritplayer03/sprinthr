<br /><br />

<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" rowspan="2" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Department Name</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Section Name</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Date </span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Time IN</span></strong></td>
        <td align="center"  colspan="<?php echo $max_breaks*2; ?>" valign="top" style="border-bottom:none;"><strong>Breaks</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Time OUT</span></strong></td>
        <td align="center" rowspan="2" valign="top" style="border-bottom:none;"><strong>Remarks</span></strong></td>
    </tr>
    <?php
            if(count($max_breaks) > 0):
                for( $counter = 0; $counter < $max_breaks; $counter++):
        ?>
        <tr>
            <td align="center"  valign="top" style="border-bottom:none;"><strong>Break Out</span></strong></td>
            <td align="center"  valign="top" style="border-bottom:none;"><strong>Break In</span></strong></td>
        </tr>
        <?php
                endfor;
            endif;
        ?>
	<?php 
		foreach($daily_time_record as $emp_id => $value){
            foreach($value as $a) {

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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'], MB_CASE_TITLE, "UTF-8"); ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section'], MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'], MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_in']; ?></td>
            <?php
                if(count($max_breaks) > 0):
                    // foreach($a['matched_breaks'] as $break):

                    for( $counter2 = 0; $counter2 < $max_breaks; $counter2++):

                        if($a['matched_breaks'][$counter2]):
                        $break = $a['matched_breaks'][$counter2];
            ?>
                            <td align="left" valign="top" style="border-bottom:none;"><?php echo isset($break->from) ? date('H:i:s', strtotime($break->from->datetime)) : null; ?></td>
                            <td align="left" valign="top" style="border-bottom:none;"><?php echo isset($break->to) ? date('H:i:s', strtotime($break->to->datetime)) : null; ?></td>
            <?php
                        else:
            ?>
                            <td align="left" valign="top" style="border-bottom:none;"></td>
                            <td align="left" valign="top" style="border-bottom:none;"></td>
            <?php

                        endif;
                    endfor;
                endif;
            ?>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_out']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['remarks']; ?></td>       
        </tr>
    <?php }} ?>
</table>