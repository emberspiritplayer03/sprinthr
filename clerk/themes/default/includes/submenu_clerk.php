<div id="sidebar" class="sidebar_left">	
<?php if($module == 'leave') { ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li <?php echo $recent; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave');  ?>">Pending Leaves</a></li>
                <li <?php echo $approved; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('leave/approved');  ?>">Approved Leaves</a></li>
                <!--<li id="personal_details_nav" class="left_nav"></li>-->
                <li <?php echo $history; ?> id="contact_details_nav" class="left_nav"><a href="<?php echo url('leave/history');  ?>">Employee Leave History</a></li>
                <li <?php echo $archives; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('leave/archives');  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php } else if($module == 'overtime') { ?>
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li id="personal_details_nav" class="left_nav <?php echo $recent; ?>"><a href="<?php echo url('overtime');  ?>">Pending Overtime</a></li>
                <li id="personal_details_nav" class="left_nav <?php echo $approved; ?>"><a href="<?php echo url('overtime?sidebar=2');  ?>">Approved Overtime</a></li>
                <li id="contact_details_nav" class="left_nav  <?php echo $history; ?>"><a href="<?php echo url('overtime?sidebar=3');  ?>">Employee Overtime History</a></li>
                <li id="emergency_contacts_nav" class="left_nav <?php echo $archives; ?>"><a href="<?php echo url('overtime?sidebar=4');  ?>">Archives</a></li>                
            </ul>
        </li>
    </ul>
    <?php } ?>
</div>