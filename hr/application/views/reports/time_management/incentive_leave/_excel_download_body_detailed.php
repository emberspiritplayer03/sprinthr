<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
    <tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Hired Date</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Status</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>    
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>    
        <?php foreach( $months as $m ){ ?>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $m; ?></span></strong></td>
        <?php } ?>        
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Balance</span></strong></td>
    </tr>
    <?php 
        foreach($data as $key => $l){
            $firstname   = strtr(utf8_decode($l['employee_details']['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $lastname    = strtr(utf8_decode($l['employee_details']['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $middlename  = strtr(utf8_decode($l['employee_details']['middlename']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $department_name = strtr(utf8_decode($l['employee_details']['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $section_name = strtr(utf8_decode($l['employee_details']['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $position     = strtr(utf8_decode($l['employee_details']['position']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $name = $lastname . ", " . $firstname;
    ?>
        <tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($name,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['hired_date']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $l['employee_details']['employee_status']; ?></td>   
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($department_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($section_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($position,  MB_CASE_TITLE, "UTF-8"); ?></td> 
            <?php 
                $total_incentive_leave = 0; 
                $employee_incentive_leave = 0;
            ?>

            <?php foreach($months as $m){ ?>
                <td align="left" valign="top" style="border-bottom:none;">
                   <?php 
                        if( isset($l['leave_credit'][$m]) && $l['leave_credit'][$m] > 0 ){ 

                            $nmonth = date("m", strtotime($m));
                            $is_processed = G_Incentive_Leave_History_Helper::isMonthNumberAndYearExists($nmonth, $incentive_leave_year);
                            if( $is_processed > 0 ){
                                echo $l['leave_credit'][$m];
                                $total_incentive_leave += $l['leave_credit'][$m];
                            } else {
                                echo 0;
                            }

                        }else{
                            echo 0;
                        }
                   ?> 
                </td>              
            <?php } ?>
            <td align="left" valign="top" style="border-bottom:none;">
                <?php echo $total_incentive_leave; ?>
            </td>
            <?php
                $balance_leave = 0;
                $incentive_leave = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdAndYear($key, 11, $_POST['incentive_leave_year']);
                if(!empty($incentive_leave)) {
                    $balance_leave = $incentive_leave->getNoOfDaysAvailable();
                }   
            ?>
            <td align="left" valign="top" style="border-bottom:none;">
                <?php if($balance_leave > $total_incentive_leave) {?>
                        <?php echo $total_incentive_leave; ?>
                <?php } else { ?>
                        <?php echo $balance_leave; ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>

</table>