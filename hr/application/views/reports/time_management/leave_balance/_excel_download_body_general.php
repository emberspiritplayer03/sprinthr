<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Hired Date</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Status</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
          <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>        
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <!-- <td align="center" valign="top" style="border-bottom:none;"><strong>Leave Credits</span></strong></td> -->
        <?php foreach( $leave_type as $ltkey => $lt ){ ?>
                <?php if($ltkey == 10) { ?>
                        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $lt->getName(); ?></span></strong></td>
                <?php } ?>
        <?php } ?>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Used Leave</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total Leave</span></strong></td>
    </tr>
	<?php 
		foreach($leave as $key => $l){
            //$firstname   = strtr(utf8_decode($l['employee_details']['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           // $lastname    = strtr(utf8_decode($l['employee_details']['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $middlename  = strtr(utf8_decode($l['employee_details']['middlename']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $department_name = strtr(utf8_decode($l['employee_details']['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $section_name = strtr(utf8_decode($l['employee_details']['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $position     = strtr(utf8_decode($l['employee_details']['position']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

            $name = $l['employee_details']['lastname'] . ", " . $l['employee_details']['firstname']; 
            $employee_name = mb_convert_encoding($name , "HTML-ENTITIES", "UTF-8");

            if(!empty($l['employee_details']['project_site_id']) || $l['employee_details']['project_site_id'] != 0){
              
                $project = G_Project_Site_Finder::findById($l['employee_details']['project_site_id']);
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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['employee_code']; ?></td>
          
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['hired_date']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['employee_status']; ?></td>   
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($department_name,  MB_CASE_TITLE, "UTF-8"); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($section_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($position,  MB_CASE_TITLE, "UTF-8"); ?></td>             
            <!-- <td align="left" valign="top" style="border-bottom:none;">
                <?php //$leave_credits_total = 0; ?>
            </td>-->
            <?php 
                $total_leave_used      = 0;                 
                $total_leave_credits   = 0;
            ?>
            <?php foreach( $leave_type as $ltkey => $lt ){ ?>
                <?php if($ltkey == 10) { ?>
                        <td align="left" valign="top" style="border-bottom:none;">              
                            <?php 
                                if( isset($l['leave_details'][10]) && $l['leave_details'][10]['no_of_days_alloted'] > 0){
                                    echo $l['leave_details'][10]['no_of_days_alloted'];
                                    $total_leave_used += ($l['leave_details'][10]['no_of_days_alloted'] - $l['leave_details'][10]['no_of_days_available']);
                                }else{
                                    echo 0;
                                }
                                $total_leave_credits += $l['leave_details'][10]['no_of_days_alloted'];
                            ?>
                        </td> 
                <?php } ?>
            <?php } ?>
            <td align="left" valign="top" style="border-bottom:none;">
                <?php echo $total_leave_used = $total_leave_used <= 0 ? 0 : $total_leave_used; ?>
            </td>
            <td align="left" valign="top" style="border-bottom:none;">
                <?php echo ($total_leave_credits - $total_leave_used); ?>
            </td>
        </tr>
    <?php } ?>

</table>