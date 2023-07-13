<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Compensation</h2>
<div class="section_container">
    <div id="compensation_table_wrapper">
    <div id="form_main" class="employee_form">
    <div id="form_default">
    <table>
      <tr>
        <td style="color:#777777; width:170px;">Pay Rate:</td>
        <td><?php echo $employee_rate->job_level; ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px;">Minimum Salary:</td>
        <td><?php echo $employee_rate->minimum_salary; ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px;">Maximum Salary:</td>
        <td><?php echo $employee_rate->maximum_salary; ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px;">Type:</td>
        <td><?php echo Tools::friendlyTitle($employee_salary->type); ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px;">Basic Salary:</td>
        <td><?php echo number_format($employee_salary->basic_salary,2); ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px;">Pay Frequency:</td>
        <td><?php echo $employee_pay_period->pay_period_name; ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px;">Cut Off</td>
        <td><?php echo $employee_pay_period->cut_off; ?></td>
      </tr>
    </table>
    </div><!-- #form_default -->
    </div><!-- #form_main.employee_form -->
    </div><!-- #compensation_table_wrapper -->
</div><!-- .section_container -->

<h2 class="field_title">Compensation History</h2>
<div id="compensation_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Type</th>
          <th width="150" align="left" valign="middle" scope="col">Basic Salary</th>
          <th width="109" align="left" valign="middle" scope="col">Start Date</th>
          <th width="109" align="left" valign="middle" scope="col">End Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($compensation_history as $key=>$e) { ?>
        <tr>
          <td align="left"><?php echo Tools::friendlyTitle($e->type); ?></td>
          <td align="left"><?php echo number_format($e->basic_salary,2); ?></td>
          <td align="left"><?php echo Date::convertDateIntIntoDateString($e->start_date) ; ?></td>
          <td align="left"><?php echo ($e->end_date=='')? 'Present' : Date::convertDateIntIntoDateString($e->end_date) ; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="4"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>