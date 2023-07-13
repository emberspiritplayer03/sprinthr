<div id="sidebar" class="sidebar_left">
    <ul class="ulmenu">
        <li class="selected">
            <div class="tabtitle">
                <a id="personal_information_tab" style="cursor:pointer">Menu<!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>
            <ul class="ulsubmenu">
                <!--<h2> Settings</h2>-->
                <li class="selected">
                    <?php
                        $now = date('Y-m-d');
                        ?>
                            <li <?php echo $dashboard; ?> id="personal_details_nav" class="left_nav"><a href="<?php echo url("new_schedule/dashboard?date={$now}");  ?>">Dashboard</a></li>
                        <?php
                        $schedule_settings_check = G_Schedule_Settings_Finder::findAll();
                        foreach($schedule_settings_check as $settings){
                            $checked_shift = $settings->getShift();
                            $checked_flexible = $settings->getFlexible();
                            $checked_compressed = $settings->getCompressed();
                            $checked_staggered = $settings->getStaggered();
                            $checked_security = $settings->getSecurity();
                            $checked_actual = $settings->getActual();
                            $checked_per_trip = $settings->getPerTrip();
                        }
                        if($checked_shift == 1){ ?>
                            <li <?php echo $shift_schedule; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url("new_schedule/index_shift_schedule?date={$now}"); ?>">Shift Schedule</a></li>
                        <?php }
                        if($checked_compressed){ ?>
                            <li <?php echo $compress_schedule; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url("new_schedule/index_compress_schedule?date={$now}"); ?>">Compress Schedule</a></li>
                        <?php }
                        if($checked_flexible){ ?>
                            <li <?php echo $flextime_schedule; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url("new_schedule/index_flextime_schedule?date={$now}"); ?>">Flextime Schedule</a></li>
                        <?php }
                        if($checked_staggered){ ?>
                            <li <?php echo $staggered_schedule; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url("new_schedule/index_staggered_schedule?date={$now}"); ?>">Staggered Schedule</a></li>
                        <?php }
                        if($checked_security){ ?>
                            <li <?php echo $security_schedule; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/security_schedule'); ?>">Security Schedule</a></li>
                        <?php }
                        if($checked_actual){ ?>
                            <li <?php echo $actual_hours; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/actual_hours'); ?>">Actual Hours</a></li>
                        <?php }
                        if($checked_per_trip){ ?>
                            
                        <?php }
                    ?>
            </ul>

            <br>
            <ul class="ulsubmenu">
                <li <?php echo $calendar; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/calendar'); ?>">Calendar</a></li>
                <li <?php echo $schedule_list; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/schedule_list'); ?>">Schedule List</a></li>
                <li <?php echo $set_employee_schedule; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/set_employee_schedule'); ?>">Set Employee Schedule</a></li>
                <li <?php echo $mass_set_schedule_monthly; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/mass_set_schedule_monthly'); ?>">Mass Set Schedule Monthly</a></li>
                <li <?php echo $schedule_settings; ?> id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('new_schedule/schedule_settings'); ?>">Schedule Settings</a></li>
            </ul>
        </li>
    </ul>
</div>