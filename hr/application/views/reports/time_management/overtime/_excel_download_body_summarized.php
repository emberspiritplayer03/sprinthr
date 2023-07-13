<br /><br />
<?php
    //utilities::displayArray($overtime);exit;
    foreach($overtime as $a) {
        if( $a['scheduled_time_in'] != '' || $a['scheduled_time_out'] != '' ){
            $total_ot_hrs        = 0;
            $total_scheduled_hrs = 0;
            $total_rdholiday_hours = 0;

            $schedule_hrs = $a['total_schedule_hours']; 

            $total_rd_ot  = 0;
            $total_hol_ot = 0;   
            $total_reg_ot = 0;  

            if($a['is_present'] == 1) {
                if( $a['late_hours'] > 0 ){
                    //$total_ot_hrs = $schedule_hrs - $a['late_hours'];
                }

                if( $a['undertime_hours'] > 0 ){
                    //$total_ot_hrs = $schedule_hrs - $a['undertime_hours'];
                }

                if( $a['total_overtime_hours'] > 0 ){
                    $total_ot_hrs += $a['total_overtime_hours'];
                }

                if($a['total_hours_worked'] >= $a['total_schedule_hours']) {
                    $total_scheduled_hrs = $a['total_schedule_hours'] - ($a['late_hours'] + $a['undertime_hours']);
                }elseif($a['total_hours_worked'] <= $a['total_schedule_hours']) {
                    $total_scheduled_hrs = $a['total_hours_worked'];
                }

                if( $a['is_restday'] == 1 ){
                    $total_rd_ot = $total_ot_hrs + $total_scheduled_hrs;
                }elseif( $a['is_holiday'] == 1 ){
                    $total_hol_ot = $total_ot_hrs + $total_scheduled_hrs;
                }else{
                    $total_reg_ot = $total_ot_hrs;
                }

                //$total_rdholiday_overtime_hours = $a['total_hours_worked'] - $a['total_overtime_hours'];
                //$total_rdholiday_overtime_hours = round($total_rdholiday_overtime_hours,0);

                if( $a['is_restday'] == 1 && $a['is_present'] == 1 && $a['is_holiday'] == 1){
                    $total_rdholiday_hours = $a['total_hours_worked'];
                } 

                //echo $a['employee_name'] . " : " . $o[$a['employee_code']]['total_hol_reg_hrs'] . " - " . $total_reg_ot;
                //echo '<br />';                

                $o[$a['employee_code']] = array(
                    'employee_code' => $a['employee_code'],
                    'employee_name' => $a['employee_name'],
                    'department_name' => $a['department_name'],
                    'section_name' => $a['section_name'],
                    'position' => $a['position'],                    
                    'project_site_id' => $a['project_site_id'], 
                    'total_rd_ot_hrs' => $o[$a['employee_code']]['total_rd_ot_hrs'] + $total_rd_ot,
                    'total_hol_ot_hrs' => $o[$a['employee_code']]['total_hol_ot_hrs'] + $total_hol_ot,
                    'total_hol_reg_hrs' => $o[$a['employee_code']]['total_hol_reg_hrs'] + $total_reg_ot
                );
                
            }
        }
        
    }

?>
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
         <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Regular OT</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Restday OT</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Holiday OT</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total</span></strong></td>            
    </tr>
	<?php 
		$g_total_ot = 0;
        $row_total  = 0;
        $g_total    = array();
		foreach($o as $a){
			//$g_total_ot += $a['total_ot_hrs'];
            $row_total  = $a['total_rd_ot_hrs'] + $a['total_hol_ot_hrs'] + $a['total_hol_reg_hrs'];
            $g_total['regular'] += $a['total_hol_reg_hrs'];
            $g_total['rd']      += $a['total_rd_ot_hrs'];
            $g_total['hol']     += $a['total_hol_ot_hrs'];
            $g_total['total']   += $a['total_rd_ot_hrs'] + $a['total_hol_ot_hrs'] + $a['total_hol_reg_hrs'];

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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'],MB_CASE_TITLE, "UTF-8"); ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'],MB_CASE_TITLE, "UTF-8"); ?></td>
            

            <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($a['total_hol_reg_hrs'],2,".",","); ?></td>           
            <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($a['total_rd_ot_hrs'],2,".",","); ?></td>   
            <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($a['total_hol_ot_hrs'],2,".",","); ?></td>   
            <td align="right" valign="top" style="border-bottom:none;mso-number-format:'\@';"><?php echo number_format($row_total,2,".",","); ?></td>   
        </tr>
    <?php } ?>
    <tr>
    	<td colspan="6" align="left" valign="top" style="border-bottom:none;"><b>Grand Total</b></td>
        <?php foreach( $g_total as $value ){ ?>
            <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
                <?php echo number_format($value,2,".",",");  ?></b>
            </td>
        <?php } ?>        
    </tr>
</table>	