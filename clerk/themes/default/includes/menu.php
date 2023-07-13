<div id="menu"> 
    <ul class="mainmenu">      
        <li class="first <?php echo $dashboard; ?>"><span class="lshad"></span><span class="selectedarrow"></span><a href="<?php echo url('dashboard');  ?>"><span class="menu_icon dashboard"></span>Dashboard</a></li>
        <li class="<?php echo $schedule; ?>" ><span class="selectedarrow"></span><a href="<?php echo url('schedule'); ?>"><span class="menu_icon schedule"></span>Schedule</a></li>      
        <li class="<?php echo $leave; ?>" ><span class="selectedarrow"></span><a href="<?php echo url('leave'); ?>"><span class="menu_icon recruitment"></span>Leave</a></li>      
        <li class="<?php echo $overtime; ?>"><span class="selectedarrow"></span><span class="rshad"></span><a href="<?php echo url('overtime');  ?>"><span class="menu_icon recruitment"></span>Overtime</a></li>
        <li class="last <?php echo $dtr; ?>"><span class="selectedarrow"></span><span class="rshad"></span><a href="<?php echo url('dtr');  ?>"><span class="menu_icon recruitment"></span>DTR</a></li>
           
      </li>
    </ul>
</div><!-- #menu -->

