<div id="sidebar" class="sidebar_left">	
<?php if($module == 'leave') { ?>
<?php $url_param = "from=".$_GET['from']."&to=".$_GET['to']."&hpid=".$_GET['hpid']; ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $recent; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/period?'.$url_param);  ?>">Pending Leaves</a></li>
                 <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/approved?'.$url_param);  ?>">Approved Leaves</a></li>
                  <!--<li id="personal_details_nav" class="left_nav"></li>-->
                <li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('leave/history?'.$url_param);  ?>">Employee Leave History</a></li>
                <li <?php echo $credits; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('leave/credits?'.$url_param);  ?>">Employee Leave Credits</a></li>  
                <li <?php echo $type; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('leave/type?'.$url_param);  ?>">Leave Type Management</a></li>  
                <li <?php echo $archives; ?> id="emergency_cont acts_nav" class="left_nav"><a href="<?php echo url('leave/archives?'.$url_param);  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php }elseif($module == 'overtime'){ ?>
	<?php $url_param = "from=".$_GET['from']."&to=".$_GET['to']."&hpid=".$_GET['hpid']; ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li id="personal_details_nav" class="left_nav <?php echo $recent; ?>"><a href="<?php echo url('overtime/period?'.$url_param);  ?>">Pending Overtime</a></li>
                <li id="personal_details_nav" class="left_nav <?php echo $approved; ?>"><a href="<?php echo url('overtime/period?sidebar=2&'.$url_param);  ?>">Approved Overtime</a></li>
                <li id="contact_details_nav" class="left_nav  <?php echo $disapproved; ?>"><a href="<?php echo url('overtime/period?sidebar=5&'.$url_param);  ?>">Disapproved Overtime</a></li>
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
                <li <?php echo $pendings; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('ob/pendings?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']); ?>">Pending Official Business</a></li>
                <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('ob/approved?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']); ?>">Approved Official Business</a></li>                              
                <li <?php echo $archives; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('ob/archives?from=' . $period['from'] . "&to=" . $period['to'] . "&hpid=" . $period['hpid']); ?>">Archives</a></li>                
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
    <?php } ?>
</div>