<div id="sidebar" class="sidebar_left">	
<?php if($module == 'leave') { ?>
<?php
    if($_GET['from'] && $_GET['to'] && $_GET['hpid']) {
        $url_param = "from=".$_GET['from']."&to=".$_GET['to']."&hpid=".$_GET['hpid']."&selected_frequency=" . $_GET['selected_frequency']; 
    } elseif($get_from && $get_to && $get_hpid) {
        $url_param = "from=".$get_from."&to=".$get_to."&hpid=".$get_hpid."&selected_frequency=" . $_GET['selected_frequency']; 
    }
    //$url_param = "from=".$_GET['from']."&to=".$_GET['to']."&hpid=".$_GET['hpid']; 
?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $recent; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/period?'.$url_param);  ?>">Pending Leaves</a></li>
                <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/approved?'.$url_param);  ?>">Approved Leaves</a></li>
                <li <?php echo $disapproved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/disapproved?'.$url_param);  ?>">Disapproved Leaves</a></li>
                <!-- <li <?php echo $incentive; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/incentive_leave');  ?>">Incentive Leaves</a></li> -->
                 <!--<li id="personal_details_nav" class="left_nav"></li>-->
                <li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('leave/history?'.$url_param);  ?>">Employee Leave History</a></li>
                <li <?php echo $credits; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('leave/credits?'.$url_param);  ?>">Employee Leave Credits</a></li>  
                <li <?php echo $type; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('leave/type?'.$url_param);  ?>">Leave Type Management</a></li>  
                <li <?php echo $archives; ?> id="emergency_cont acts_nav" class="left_nav"><a href="<?php echo url('leave/archives?'.$url_param);  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'overtime'){ ?>
	<?php 
        if($_GET['from'] && $_GET['to'] && $_GET['hpid']) {
            $url_param = "from=".$_GET['from']."&to=".$_GET['to']."&hpid=".$_GET['hpid']."&selected_frequency=" . $_GET['selected_frequency']; 
        } elseif($get_from && $get_to && $get_hpid) {
            $url_param = "from=".$get_from."&to=".$get_to."&hpid=".$get_hpid."&selected_frequency=" . $_GET['selected_frequency']; 
        }    
        //$url_param = "from=".$_GET['from']."&to=".$_GET['to']."&hpid=".$_GET['hpid']; 
    ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li id="personal_details_nav" class="left_nav <?php echo $recent; ?>"><a href="<?php echo url('overtime/period?'.$url_param);  ?>">Pending Overtime</a></li>
                <li id="personal_details_nav" class="left_nav <?php echo $approved; ?>"><a href="<?php echo url('overtime/period?sidebar=2&'.$url_param);  ?>">Approved Overtime</a></li>
                <li id="contact_details_nav" class="left_nav  <?php echo $disapproved; ?>"><a href="<?php echo url('overtime/period?sidebar=5&'.$url_param);  ?>">Disapproved Overtime</a></li>
                <li id="custom_overtime_nav" class="left_nav <?php echo $custom_overtime; ?>"><a href="<?php echo url('overtime/period?sidebar=7&'.$url_param);  ?>">Custom Overtime</a></li>
                <li id="emergency_contacts_nav" class="left_nav <?php echo $archives; ?>"><a href="<?php echo url('overtime/period?sidebar=4&'.$url_param);  ?>">Error Report <?php echo $total_errors;?></a></li>
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'loan'){ ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $recent; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('loan');  ?>">Deduction</a></li>                
                <li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('loan/history');  ?>">Employee Deduction History</a></li>               
                <li <?php echo $loan_type; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('loan/loan_type');  ?>">Deduction Type Management</a></li>
                <!--<li <?php //echo $loan_deduction_type; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php //echo url('loan/loan_deduction_type');  ?>">Loan Deduction Type Management</a></li>  -->
                <li <?php echo $archives; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('loan/archives');  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'ob'){ ?>
    <ul class="ulmenu">
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $pendings; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('ob/pendings?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid'] . "&selected_frequency=" . $selected_frequency); ?>">Pending Official Business</a></li>
                <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('ob/approved?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid'] . "&selected_frequency=" . $selected_frequency); ?>">Approved Official Business</a></li>
                <li <?php echo $disapproved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('ob/disapproved?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid'] . "&selected_frequency=" . $selected_frequency); ?>">Disapproved Official Business</a></li>                              
                <li <?php echo $archives; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('ob/archives?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid'] . "&selected_frequency=" . $selected_frequency); ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'earnings'){ ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $recent; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('earnings?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Pending Earnings</a></li>                
                <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('earnings/approved?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Approved Earnings</a></li>                
                <!--<li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('earnings/history?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Employee Earnings History</a></li>               -->
                <li <?php echo $archives; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('earnings/archives?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'deductions'){ ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $recent; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('deductions?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Pending Deductions</a></li>                
                <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('deductions/approved?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Approved Deductions</a></li>                
                <!--<li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('deductions/history?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Employee Deductions History</a></li>               -->
                <li <?php echo $archives; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('deductions/archives?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'undertime'){ ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li id="personal_details_nav" class="left_nav <?php echo $recent; ?>"><a href="<?php echo url('undertime/pending?from=' . $from_period . "&to=" . $to_period . "&hpid=" . $hpid);  ?>">Pending Undertime</a></li>                
                <li id="personal_details_nav" class="left_nav <?php echo $approved; ?>"><a href="<?php echo url('undertime/approved?from=' . $from_period . "&to=" . $to_period . "&hpid=" . $hpid);  ?>">Approved Undertime</a></li>                
                <!--<li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('undertime/history?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']);  ?>">Employee Earnings History</a></li>               -->
                <li id="emergency_contacts_nav" class="left_nav <?php echo $archives; ?>"><a href="<?php echo url('undertime/archives?from=' . $from_period . "&to=" . $to_period . "&hpid=" . $hpid);  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'activity'){ ?>
    <ul class="ulmenu">
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $employee_activities; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('activity/employee_activities'); ?>">Activities</a></li>
                <li <?php echo $activities; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('activity/activities'); ?>">List of Activities</a></li>
                <li <?php echo $designations; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('activity/designations'); ?>">List of Designations</a></li> 
                 <!--
                  <li <?php echo $project_sites; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('activity/project_sites'); ?>">List of Project Sites</a></li>  -->

                <li <?php echo $download_activity; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('activity/reports'); ?>">Activity Reports</a></li>                              
            </ul>
        </li>
    </ul>
    <?php } ?>
</div>