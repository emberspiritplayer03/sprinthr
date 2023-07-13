<div id="menu"> 
    <ul class="mainmenu">
    	<li class="first <?php echo $attendance; ?>"><span class="lshad"></span><span class="selectedarrow"></span><a href="<?php echo url('attendance');  ?>"><span class="menu_icon dashboard"></span>Attendance<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/transparent.png" class="menudropicon" border="0" /></a>
            <div class="sbmnhldr">
                <span class="sbmntp_arrw"></span>
                <ul class="submenu">
                    <li class="first"><a href="<?php echo url('attendance/timesheet'); ?>">Timesheet</a></li>
                    <li class="last"><a href="<?php echo url('attendance/attendance_logs'); ?>">Attendance Logs</a></li>
                </ul>
            </div>
			<span class="selectedarrow"></span>
		</li>
        <li class="<?php echo $payroll_register; ?>"><span class="selectedarrow"></span><a href="<?php echo url('payroll_register'); ?>"><span class="menu_icon schedule"></span>Payroll Register<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/transparent.png" class="menudropicon" border="0" /></a>
        	<div class="sbmnhldr">
                <span class="sbmntp_arrw"></span>
                <ul class="submenu">
                    <li class="first"><a href="<?php echo url('payroll_register/generation'); ?>">Payroll Generation</a></li>
                    <li class="last"><a href="<?php echo url('payroll_register/history'); ?>">Payroll History</a></li>
                </ul>
            </div>
			<span class="selectedarrow"></span>        
        </li>
        <li class="<?php echo $earnings_deductions; ?>"><span class="selectedarrow"></span><a href="<?php echo url('earnings_deductions'); ?>"><span class="menu_icon attendance"></span>Earnings / Deductions<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/transparent.png" class="menudropicon" border="0" /></a>
        	<div class="sbmnhldr">
                <span class="sbmntp_arrw"></span>
                <ul class="submenu">
                    <li class="first"><a href="<?php echo url('earnings_deductions/earnings'); ?>">Earnings</a></li>
                    <li><a href="<?php echo url('earnings_deductions/loans'); ?>">Loans</a></li>
                    <li><a href="<?php echo url('earnings_deductions/other_deductions'); ?>">Other Deductions</a></li>
                    <li class="last"><a href="<?php echo url('earnings_deductions/government_deductions'); ?>">Government Deductions</a></li>
                </ul>
            </div>
			<span class="selectedarrow"></span>
        </li>
        <!--<li class="<?php echo $payroll; ?>"><span class="selectedarrow"></span><a href="<?php echo url('payslip'); ?>"><span class="menu_icon payroll"></span>Payroll<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/transparent.png" border="0" /></a></li>-->
         
<!--        <li class="<?php echo $employee; ?>" ><a href="<?php echo url('employee');  ?>">Employee<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/transparent.png" class="menudropicon" border="0" /></a>  
          <div class="sbmnhldr">
            <span class="sbmntp_arrw"></span>
            <ul class="submenu">
              <li class="first"><a href="<?php echo url('leave'); ?>">Leave</a></li>
              <li><a href="<?php echo url('overtime'); ?>">Overtime</a></li>
              <li><a href="<?php echo url('attendance'); ?>">Attendance</a></li>
              <li><a href="<?php echo url('payslip'); ?>">Payroll</a></li>
              </ul>
            </div>
          <span class="selectedarrow"></span>
        </li>-->

        <li class="last <?php echo $reports; ?>"><span class="rshad"></span><a href="<?php echo url('reports/payroll_management#sss_r1a'); ?>"><span class="menu_icon reports"></span>Reports</a>
            <span class="selectedarrow"></span>
      </li>
  </ul>
</div><!-- #menu -->