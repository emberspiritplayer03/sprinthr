<div id="sidebar" class="sidebar_left">
    <ul class="ulmenu">
        <li class="selected">
            <div class="tabtitle">
                <a id="personal_information_tab" style="cursor:pointer">Menu<!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>

            <ul class="ulsubmenu">
                <!--<h2> Settings</h2>-->
                <li class="selected">
                <li <?php echo $site_attendance_logs; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('project_site');  ?>">Site Attendance Logs</a></li>
                <li <?php echo $employee_list; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('project_site/employee_list');  ?>">Employee List</a></li>
                <li <?php echo $holiday; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('project_site/holiday');  ?>">Holiday</a></li>
                <li <?php echo $payroll; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url('project_site/payroll_register');  ?>">Payroll</a></li>
            </ul>

        </li>
    </ul>
</div>