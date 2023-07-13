<style type="text/css">
  @import "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER; ?>themes/default/lastpay.css";
</style>

<style>
<style>
<!--table
  {mso-displayed-decimal-separator:"\.";
  mso-displayed-thousand-separator:"\,";}
@page
  {margin:.75in .7in .75in .7in;
  mso-header-margin:.3in;
  mso-footer-margin:.3in;}
-->
</style>  
</style>
<?php
  $basic_pay                    = $data['basic_pay'];
  $night_differential_amount    = $data['night_differential_amount'];
  $night_diff_total_hours       = $data['night_differential_hrs'];
  $overtime_pay_amount          = $data['overtime_pay_amount'];
  $other_earnings_amount        = $data['other_earnings_amount'];
  $absent_late_undertime_amount = $data['absent_late_undertime_amount'];
  $total_gross_pay              = ($data['total_earnings'] - $absent_late_undertime_amount - $data['month_13th_amount']);
  $withheld_tax                 = $data['withheld_tax'];
  $sss                          = $data['sss'];
  $philhealth                   = $data['philhealth'];
  $pagibig                      = $data['pagibig'];
  $total_earnings               = $data['total_earnings'];
  $date_resigned                = $d['resignation_date'];
  $monthly_rate                 = $data['monthly_rate'];
  
  $loan_decuction = 0;
  foreach($data['other_deductions_arr'] as $od_arr) {
    $loan_decuction += $od_arr->getAmount();
  }

  $tdeduct       = ($withheld_tax + $sss + $philhealth + $pagibig);
  $total_net_pay = ($total_earnings - ($tdeduct + $absent_late_undertime_amount + $loan_decuction + $data['month_13th_amount']) );

?>
<table border=0 cellpadding=0 cellspacing=0 width=975 style='border-collapse:
 collapse;table-layout:fixed;width:732pt'>
 <col width=336 style='mso-width-source:userset;mso-width-alt:12288;width:252pt'>
 <col width=46 style='mso-width-source:userset;mso-width-alt:1682;width:35pt'>
 <col width=46 style='mso-width-source:userset;mso-width-alt:1682;width:35pt'>
 <col width=209 style='mso-width-source:userset;mso-width-alt:7643;width:157pt'>
 <col width=64 span=6 style='width:48pt'>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl66 width=336 style='height:15.0pt;width:252pt'>LAGUNA
  DAI-ICHI, INC.</td>
  <td height=20 class=xl66 width=336 style='height:15.0pt;width:252pt'>&nbsp;</td>
  <td class=xl66 width=46 style='width:35pt'></td>
  <td class=xl66 width=209 style='width:157pt; font-weight:normal;'><strong>Last Salary:</strong> <?php echo $data['date_last_salary']; ?></td>
  <td class=xl67 width=64 style='width:48pt'></td>
  <td class=xl67 width=64 style='width:48pt'></td>
  <td class=xl67 width=64 style='width:48pt'></td>
  <td class=xl67 width=64 style='width:48pt'></td>
  <td class=xl67 width=64 style='width:48pt'></td>
  <td class=xl67 width=64 style='width:48pt'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>RESIGNED EMPLOYEES
  ACCOUNTABILITY SUMMARY</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td class=xl65></td>
  <td class=xl65 style="font-weight: normal;"><strong>Last Attendance:</strong> <?php echo $data['last_attendance']; ?></td>
  <td class=xl65></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Name</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl76><?php echo $d['lastname']; ?>, <?php echo $d['firstname']; ?> <?php echo $d['middlename'][0]; ?>.</td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Position</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo $d['position']; ?></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Badge No.</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72>
    <?php
      if($e->getEmployeeCode()) {
        echo $employee_code = $e->getEmployeeCode();
      }
    ?>    
  </td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Date Hired</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl77><?php echo date('F d, Y',strtotime($d['hired_date'])); ?></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Date Resigned</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl77><?php echo ($d['resignation_date'] !== '0000-00-00' ? date('F d, Y',strtotime($d['resignation_date'])) : '0000-00-00'); ?></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Department</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo $d['department']; ?></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Tax Status</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72>
    <?php 
        if($withheld_tax <= 0) {
            echo "Z";
        }elseif($d['number_dependent'] == 0) {
            if($d['marital_status'] == "Single" || $d['marital_status'] == "Separated" || $d['marital_status'] == "Widowed") {
                echo "S";
            }elseif($d['marital_status'] = "Married") {
                echo "ME";
            }
        }elseif($d['number_dependent'] >= 1) {
            if($d['number_dependent'] > 4) {
                $d['number_dependent'] = 4;
            }

            if($d['marital_status'] == "Single" || $d['marital_status'] == "Separated" || $d['marital_status'] == "Widowed") {
                echo "S".$d['number_dependent'];
            }elseif($d['marital_status'] = "Married") {
                echo "ME".$d['number_dependent'];
            }
        }
    ?>    
  </td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Rate</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo $data['employee_rate']; ?></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>1. Gross Earnings Computation</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td class=xl65></td>
  <td colspan=3 class=xl72></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Basic Pay</td>
  <td height=20 class=xl71 style='height:15.0pt'>Half Salary</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($basic_pay,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Night Differential</td>
  <td height=20 class=xl71 style='height:15.0pt'> <?php echo !empty($night_diff_total_hours) ? number_format($night_diff_total_hours,2) . ' Hrs ' : number_format(0,2) . ' Hrs'; ?> </td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo  number_format($night_differential_amount,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Overtime Pay</td>
  <td height=20 class=xl71 style='height:15.0pt'><?php echo !empty($data['overtime_pay_label']) ? $data['overtime_pay_label']. '' : ''; ?></td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($overtime_pay_amount,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Others</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>  
  <td colspan=3 class=xl72><?php echo number_format($other_earnings_amount,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Absent/TRD/UT</td>
  <td height=20 class=xl71 style='height:15.0pt'><?php echo !empty($data['absent_late_undertime_label']) ? $data['absent_late_undertime_label'] . '' : ''; ?></td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($absent_late_undertime_amount,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Gross Pay</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl78><strong><?php echo number_format($total_gross_pay,2); ?></strong></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'></td>
  <td height=20 class=xl67 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td class=xl72></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'><strong>Less: Gov't. &amp; Taxes</strong></td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68></td>
  <td colspan=3 class=xl72>&nbsp;</td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Withholding Taxes</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($withheld_tax,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>SSS</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($sss,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Philhealth</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($philhealth,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Pag-ibig</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><?php echo number_format($pagibig,2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl71 style='height:15.0pt'>Total Deductions</td>
  <td height=20 class=xl71 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl80><?php echo number_format(($tdeduct),2); ?></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl73 style='height:15.0pt'>Net Pay</td>
  <td height=20 class=xl73 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl72><strong><?php echo number_format($total_net_pay,2); ?></strong></td>
  <td></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>2. Unused Leave</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <?php $total_unused_leave        = 0; ?>
 <?php $total_unused_leave_amount = 0; ?>
 <?php foreach($leave_availables as $l) { ?>
      <?php if($l['leave_name'] != 'Birthday leave' && $l['no_of_days_available'] > 0) { ?>
              <tr height=20 style='height:15.0pt'>
                <td height=20 style='height:15.0pt'><?php echo $resigned_year . ' ' . $l['leave_name']; ?></td>
                <td height=20 style='height:15.0pt'><?php echo $l['no_of_days_available']; ?> day/s</td>
                <td class=xl68>:</td>
                <td colspan=3 class=xl74><?php echo number_format($l['no_of_days_available'] * $data['daily_rate'],2); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
       
              <?php $total_unused_leave += $l['no_of_days_available']; ?>
              <?php $total_unused_leave_amount += $l['no_of_days_available'] * $data['daily_rate']; ?>
       <?php } ?>
 <?php } ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl70 style='height:15.0pt'>Total</td>
  <td height=20 class=xl70 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl69><strong><?php echo number_format($total_unused_leave_amount,2); ?></strong></td>
  <td class=xl67></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td class=xl67></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>3. Deduction</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <?php 
    $total_other_deduction = 0; 
    $total_loan_balance    = 0; 
 ?>
 <?php foreach($employee_pending_loan as $epl_key => $epl_data) { ?>
         <?php
            $total_loan_balance = $epl_data->getLoanAmount() - $epl_data->getAmountPaid();
         ?>
         <?php if($total_loan_balance > 0) { ?>
                <tr height=20 style='height:15.0pt'>
                  <td height=20 style='height:15.0pt'><?php echo $epl_data->getLoanTitle(); ?></td>
                  <td height=20 style='height:15.0pt'>&nbsp;</td>
                  <td class=xl68>:</td>
                  <td colspan=3 class=xl74><?php echo number_format($total_loan_balance,2) ; ?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
         <?php } ?>
         <?php $total_other_deduction += $total_loan_balance; ?>
 <?php } ?>

 <?php foreach($data['other_deductions_arr'] as $od_arr) { ?>
        <?php if($od_arr->getVariable() != 'employee_deduction' && $od_arr->getAmount() > 0) { ?>
           <tr height=20 style='height:15.0pt'>
            <td height=20 style='height:15.0pt'><?php echo $od_arr->getLabel(); ?></td>
            <td height=20 style='height:15.0pt'>&nbsp;</td>
            <td class=xl68>:</td>
            <td colspan=3 class=xl74><?php echo number_format($od_arr->getAmount(),2) ; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
           </tr>
           <?php $total_other_deduction += $od_arr->getAmount(); ?>
        <?php } ?>
 <?php } ?>

  <?php
    $month_resigned             = date("m", strtotime($date_resigned));
    $montly_compensation_total  = 0;
    $th13_month_overpayment     = 0;

    for( $inc = 1; $inc <= $month_resigned; $inc++ ) {

      if($inc != $month_resigned) {
        $montly_compensation_total += str_replace(',', '', $monthly_rate);
      } else {
        $day_resigned = date("j", strtotime($date_resigned));
        $lastday      = date('t',strtotime($date_resigned));
        $percent1     = $day_resigned / $lastday;
        $th13_month_compute = ($percent1 * str_replace(',', '', $monthly_rate));
        $th13_month_total = $th13_month_compute / $month_resigned;        

        $montly_compensation_total += $th13_month_total;
      }

    }
    $th13_month_overpayment = ($montly_compensation_total / 12 - $data['month_13th_amount']);

  ?>

  <?php if( $th13_month_overpayment <= 0 ) { ?>
          <?php 
            $singlesplit = array('value' => abs($th13_month_overpayment));
            $th13_month_overpayment_total = $singlesplit['value'];
          ?>
          <tr height=20 style='height:15.0pt'>
            <td height=20 style='height:15.0pt'>13th Month Overpayment</td>
            <td height=20 style='height:15.0pt'>&nbsp;</td>
            <td class=xl68>:</td>
            <td colspan=3 class=xl74><?php echo number_format($th13_month_overpayment_total,2); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php $total_other_deduction += $th13_month_overpayment_total; ?>
  <?php } ?>
  
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl70 style='height:15.0pt'>Total</td>
  <td height=20 class=xl70 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl69><strong><?php echo number_format($total_other_deduction, 2); ?></strong></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>4. Other Earnings</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <?php $total_other_earnings = 0; ?>
 <?php foreach($data['other_earnings_array'] as $o_earnings) {?>
        <?php if($o_earnings->getLabel() != '13th Month Bonus' && $o_earnings->getAmount() > 0) { ?>
               <tr height=20 style='height:15.0pt'>
                <td height=20 style='height:15.0pt'><?php echo $o_earnings->getLabel(); ?></td>
                <td height=20 style='height:15.0pt'>&nbsp;</td>
                <td class=xl68>:</td>
                <td class=xl74><?php echo number_format($o_earnings->getAmount(),2); ?></td>
                <td class=xl74></td>
                <td class=xl74></td>
                <td class=xl74></td>
                <td></td>
                <td></td>
                <td></td>
               </tr>
               <?php 
                  $total_other_earnings += $o_earnings->getAmount();
               ?>
        <?php } ?>
 <?php } ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl70 style='height:15.0pt'>Total</td>
  <td height=20 class=xl70 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td class=xl69><strong><?php echo number_format($total_other_earnings,2); ?></strong></td>
  <td class=xl69>&nbsp;</td>
  <td class=xl69>&nbsp;</td>
  <td class=xl74></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>5. 13th Month Pay</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>

  <?php
    $month_resigned             = date("m", strtotime($date_resigned));
    $montly_compensation_total  = 0;
  ?>
  <?php for( $inc = 1; $inc <= $month_resigned; $inc++ ) { ?>
          <?php if($inc != $month_resigned) { ?>
                  <?php
                    $compensation_date = date("Y") . "-" . sprintf("%02d", $inc) . "-" . date("d", strtotime($date_resigned));
                    // var_dump($date_resigned);
                    $compensation      = G_Employee_Basic_Salary_History_Finder::findByEmployeeIdAndDate($e->getId(), $date_resigned);
                    $monthly_rate      = $compensation->getBasicSalary();
                    // echo "<pre>";
                    // var_dump($compensation);
                    // echo "</pre>";
                  ?>          
                  <tr height=20 style='height:15.0pt'>
                    <td height=20 style='height:15.0pt'><?php echo $resigned_year; ?> <?php echo date("M", mktime(0, 0, 0, $inc, 10)); ?>. Basic Pay</td>
                    <td height=20 style='height:15.0pt'>&nbsp;</td>
                    <td class=xl68>:</td>
                    <td class=xl74><?php echo $monthly_rate; ?></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <?php $montly_compensation_total += str_replace(',', '', $monthly_rate); ?>
          <?php } else { ?>
                  <?php 
                    $day_resigned = date("j", strtotime($date_resigned));
                    $lastday      = date('t',strtotime($date_resigned));
                    $percent1     = number_format( $day_resigned / $lastday,2 );
                    $th13_month_total = ($percent1 * str_replace(',', '', $monthly_rate));
                    //$th13_month_total = $th13_month_compute / $month_resigned;
                  ?>
                  <tr height=20 style='height:15.0pt'>
                    <td height=20 style='height:15.0pt'><?php echo $resigned_year; ?> <?php echo date("M", mktime(0, 0, 0, $inc, 10)); ?>. Basic Pay</td>
                    <td height=20 style='height:15.0pt'>&nbsp;</td>
                    <td class=xl68>:</td>
                    <td class=xl74><?php echo number_format($th13_month_total,2); ?></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>         
                  <?php $montly_compensation_total += $th13_month_total; ?>         
          <?php } ?>
  <?php } ?>
   <tr height=20 style='height:15.0pt'>
    <td height=20 style='height:15.0pt'>Basic Pay Total</td>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td class=xl68>:</td>
    <td class=xl69><?php echo number_format(($montly_compensation_total),2); ?></td>
    <td class=xl69>&nbsp;</td>
    <td class=xl69>&nbsp;</td>
    <td class=xl74></td>
    <td></td>
    <td></td>
    <td></td>
   </tr>
   <tr height=20 style='height:15.0pt'>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td class=xl68>&nbsp;</td>
    <td class=xl74>/12</td>
    <td class=xl74>&nbsp;</td>
    <td class=xl74>&nbsp;</td>
    <td class=xl74></td>
    <td></td>
    <td></td>
    <td></td>
   </tr>     
   <tr height=20 style='height:15.0pt'>
    <td height=20 style='height:15.0pt'>Total</td>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td class=xl68>:</td>
    <td class=xl69><?php echo number_format(($montly_compensation_total / 12),2); ?></td>
    <td class=xl69>&nbsp;</td>
    <td class=xl69>&nbsp;</td>
    <td class=xl74></td>
    <td></td>
    <td></td>
    <td></td>
   </tr>
   <tr height=20 style='height:15.0pt'>
    <td height=20 style='height:15.0pt'>Advance 13th Month</td>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td class=xl68>:</td>
    <td class=xl74><?php echo number_format($data['1st_bunos_13th_month_amount'],2); ?></td>
    <td class=xl74></td>
    <td class=xl74></td>
    <td class=xl74></td>
    <td></td>
    <td></td>
    <td></td>
   </tr>  
   <?php
      $sub_total_13th_month_pay = ($montly_compensation_total / 12) - $data['1st_bunos_13th_month_amount'];
   ?>
   <tr height=20 style='height:15.0pt'>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td height=20 style='height:15.0pt'>&nbsp;</td>
    <td class=xl68>:</td>
    <td class=xl69><strong><?php echo number_format($sub_total_13th_month_pay,2); ?></strong></td>
    <td class=xl69></td>
    <td class=xl69></td>
    <td class=xl74></td>
    <td></td>
    <td></td>
    <td></td>
   </tr>        

 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <?php 
    $total_leave_conversion = ($total_unused_leave - 10);
    if($total_leave_conversion <= 0)  {
      $total_leave_conversion = 0;
    }

    $annual_sss_pagibig_philhealth = $annual_income_data['sss_amount'] + $annual_income_data['philhealth_amount'] + $annual_income_data['pagibig_amount'];
    $annual_tax_witheld            = $annual_income_data['withheld_tax_amount'];
    $annual_gross_pay              = $annual_income_data['gross_amount'];    

    //$total_compensation = ($total_leave_conversion * $data['daily_rate']) + ($total_earnings - $data['absent_late_undertime_amount']);
    $total_compensation = $annual_gross_pay + ($total_earnings - $data['month_13th_amount'] - $data['absent_late_undertime_amount']);

    $less_gove_deduct = ($data['sss'] + $data['philhealth'] + $data['pagibig']) + $annual_sss_pagibig_philhealth;

    $tax_exemption = 50000 + (25000 * $data['number_of_dependents']);

    // $taxable_income = ($total_compensation - $less_gove_deduct) - $tax_exemption;
    $taxable_income = ($total_compensation - $less_gove_deduct) - $tax_exemption;


       //old
    // $rangeArray = array(
    //     array( 'min' => 0,  'max' => 250000,   '1st' => 0,       'percent' => '0',   'excess' => 0),
    //     array( 'min' => 10001,  'max' => 30000,   '1st' => 500,     'percent' => '0.10',  'excess' => 10000),
    //     array( 'min' => 30001,  'max' => 70000,   '1st' => 2500,    'percent' => '0.15',  'excess' => 30000),
    //     array( 'min' => 70001,  'max' => 140000,  '1st' => 8500,    'percent' => '0.20',  'excess' => 70000),
    //     array( 'min' => 140001, 'max' => 250000,  '1st' => 22500,   'percent' => '0.25',  'excess' => 140000),
    //     array( 'min' => 250001, 'max' => 500000,  '1st' => 50000,   'percent' => '0.30',  'excess' => 250000),
    //     array( 'min' => 500001, 'max' => 1000000, '1st' => 125000,  'percent' => '0.35',  'excess' => 500000),
    //     );

    $rangeArray = array(
        array( 'min' => 0,  'max' => 250000,   '1st' => 0,       'percent' => '0',   'excess' => 0),
        array( 'min' => 250001,  'max' => 400000,   '1st' => 0,     'percent' => '0.20',  'excess' => 250000),
        array( 'min' => 400001,  'max' => 800000,   '1st' => 30000,    'percent' => '0.25',  'excess' => 400000),
        array( 'min' => 800001,  'max' => 2000000,  '1st' => 130000,    'percent' => '0.30',  'excess' => 800000),
        array( 'min' => 2000001, 'max' => 8000000,  '1st' => 490000,   'percent' => '0.32',  'excess' => 2000000),
        array( 'min' => 8000001, 'max' => 9999999,  '1st' => 2410000,   'percent' => '0.35',  'excess' => 8000000),
        
        );



  // $tax_table[0]['from']  = 0; //min
  //   $tax_table[0]['to']    = 250000; //max
  //   $tax_table[0]['fixed'] = 0; //1st
  //   $tax_table[0]['rate']  = 0; //percent
  //   excess = 0

  //   $tax_table[1]['from']  = 250000; //min
  //   $tax_table[1]['to']    = 400000; //max
  //   $tax_table[1]['fixed'] = 0; //1st
  //   $tax_table[1]['rate']  = 20; //percent
  //     excess = 250000
  //   $tax_table[2]['from']  = 400000; //min
  //   $tax_table[2]['to']    = 800000; //max
  //   $tax_table[2]['fixed'] = 30000; //1st
  //   $tax_table[2]['rate']  = 25; //percent
  //     excess = 400000
//     $tax_table[3]['from']  = 800000; //min
//     $tax_table[3]['to']    = 2000000; //max
//     $tax_table[3]['fixed'] = 130000; //1st
//     $tax_table[3]['rate']  = 30; //percent
// excess = 800000
//     $tax_table[4]['from']  = 2000000; //min
//     $tax_table[4]['to']    = 8000000; //max
//     $tax_table[4]['fixed'] = 490000; //1st
//     $tax_table[4]['rate']  = 32; //percent
// excess = 2000000
    // $tax_table[5]['from']  = 8000000; //min
    // $tax_table[5]['to']    = 9999999; //max
    // $tax_table[5]['fixed'] = 2410000; //1st
    // $tax_table[5]['rate']  = 35; //percent
    // excess = 8000000
    foreach($rangeArray as $current)
    {
        if( $taxable_income > $current['min'] && $taxable_income < $current['max'] ) {
          $tax_first   = $current['1st'];
          $tax_percent = $current['percent'];
          $tax_excess  = $current['excess'];
          break;
        }
    } 

    $total_med_13th_year_end_bonus = ($data['midyear_bonus'] + $data['yearly_total_13th_amount'] + $data['year_end_bonus']);

 ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'>6. Annual Income Tax Due Computation</td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td height=20 style='height:15.0pt; text-align:right;'>SSS, Philhealth & Pag-IBIG</td>
  <td style="text-align:right;">Tax W/held</td>
  <td colspan=3 class=xl74>Gross Pay</td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'><?php echo date("M", mktime(0, 0, 0, 1, 10)) ?> 1 to <?php echo date('F j, Y',(strtotime ( '-1 day' , strtotime ( $data['period_start_date']) ) )); ?></td>
  <td height=20 style='height:15.0pt'><?php echo number_format(($annual_income_data['sss_amount'] + $annual_income_data['philhealth_amount'] + $annual_income_data['pagibig_amount']),2); ?></td>
  <td><?php echo number_format($annual_income_data['withheld_tax_amount'],2); ?></td>
  <td colspan=3 class=xl74><?php echo number_format($annual_income_data['gross_amount'],2); ?></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>

 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'><?php echo date("M", strtotime($data['period_start_date'])) . ' ' . date("j", strtotime($data['period_start_date'])); ?> to <?php echo date("F j, Y", strtotime($data['period_end_date'])); ?></td>
  <td height=20 style='height:15.0pt'><?php echo number_format(($pagibig + $philhealth + $sss),2); ?></td>
  <td><?php echo number_format($withheld_tax,2); ?></td>
  <td colspan=3 class=xl74><?php echo number_format(($total_earnings - $data['month_13th_amount'] - $data['absent_late_undertime_amount']),2); ?></td>
  <td></td>
  <td></td>
  <td></td>
 </tr> 

 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Leave Conversion (in excess of 10 days)</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <?php
    $tax_table = Tax_Table_Factory::get(Tax_Table::SEMI_MONTHLY);
    $tax       = new Tax_Calculator;
    $tax->setTaxTable($tax_table);
    $tax->setTaxableIncome($total_leave_conversion * $data['daily_rate']);
    
    if ($e->getNumberDependent() > 4) {
        $dependents = 4;
    } else {
        $dependents = $e->getNumberDependent();
    }

    $tax->setNumberOfDependent($dependents);
    $total_leave_conversion_tax = round($tax->compute(), 2);
  ?>  
  <td class=xl68><?php echo number_format($total_leave_conversion_tax, 2); ?></td>
  <td colspan=3 class=xl74><?php echo number_format($total_leave_conversion * $data['daily_rate'],2); ?> </td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>

 <?php if($total_med_13th_year_end_bonus > 85000) { ?>
          <?php $excess_of_85thousand = $total_med_13th_year_end_bonus - 85000; ?>
          <tr height=20 style='height:15.0pt'>
          <td height=20 style='height:15.0pt'>-Bonus & 13th Month in Excess of 85000</td>
          <td height=20 style='height:15.0pt'>&nbsp;</td>
          <td class=xl68>:</td>
          <td colspan=3 class=xl74><?php echo number_format($excess_of_85thousand, 2) ?></td>
          <td class=xl74></td>
          <td class=xl74></td>
          <td></td>
          <td></td>
         </tr>
 <?php } else { ?>
          <?php if($data['midyear_bonus'] > 0) { ?>
                  <tr height=20 style='height:15.0pt'>
                    <td height=20 style='height:15.0pt'>-Midyear Bonus</td>
                    <td height=20 style='height:15.0pt'>&nbsp;</td>
                    <td class=xl68>:</td>
                    <td colspan=3 class=xl74><?php echo number_format($data['midyear_bonus'], 2) ?></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td></td>
                    <td></td>
                  </tr>
          <?php } ?>

          <?php if($data['yearly_total_13th_amount'] > 0) { ?>
                  <tr height=20 style='height:15.0pt'>
                    <td height=20 style='height:15.0pt'>-13th month pay</td>
                    <td height=20 style='height:15.0pt'>&nbsp;</td>
                    <td class=xl68>:</td>
                    <td colspan=3 class=xl74><?php echo number_format($data['yearly_total_13th_amount'], 2); ?></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td></td>
                    <td></td>
                  </tr>
          <?php } ?>

          <?php if($data['year_end_bonus'] > 0) { ?>
                  <tr height=20 style='height:15.0pt'>
                    <td height=20 style='height:15.0pt'>-Year-end Bonus</td>
                    <td height=20 style='height:15.0pt'>&nbsp;</td>
                    <td class=xl68>:</td>
                    <td colspan=3 class=xl74><?php echo number_format($data['year_end_bonus'],2); ?></td>
                    <td class=xl74></td>
                    <td class=xl74></td>
                    <td></td>
                    <td></td>
                  </tr>
        <?php } ?>
 <?php } ?>

 <!-- <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-Non-taxable portion</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl74>-</td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr> -->   


 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Total Compensation</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl74><?php echo number_format($total_compensation + ($total_leave_conversion * $data['daily_rate']), 2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>

 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-Less: SSS, Philhealth &amp; Pag-IBIG</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl74><?php echo number_format($less_gove_deduct, 2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
<!--  <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Tax Exemption</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl74><?php echo number_format($tax_exemption, 2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr> -->
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Taxable Income</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
 <!--  <td colspan=3 class=xl74><?php echo number_format($taxable_income,2); ?></td> -->
  <td colspan=3 class=xl74><?php echo number_format(($total_compensation + ($total_leave_conversion * $data['daily_rate']) - $less_gove_deduct ),2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Tax Due</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td colspan=3 class=xl74></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-- on the 1st <!-- P --> <?php //echo number_format($tax_first,2); ?></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
 <!--  <td colspan=3 class=xl74><?php echo number_format(($taxable_income + $tax_excess),2); ?></td> -->
  <td colspan=3 class=xl74><?php echo number_format($tax_first,2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
 <?php 
    $tax_percent_explode = explode(".", $tax_percent);
    $total_excess_percent = ($taxable_income - $tax_excess) * $tax_percent;
 ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-- excess at <?php echo $tax_percent_explode[1]; ?>%</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <!-- <td colspan=3 class=xl74><?php //echo number_format(($taxable_income + $tax_excess) * $tax_percent,2); ?></td> -->
  <td colspan=3 class=xl74><?php echo number_format($total_excess_percent,2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
 <?php
    $total_tax_due = ( ($taxable_income - $tax_excess) * $tax_percent ) + $tax_first;
 ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Total Tax Due</td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl74><?php echo number_format($total_tax_due,2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'>-
  Tax Withheld<span style='mso-spacerun:yes'> </span></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <td colspan=3 class=xl81><?php echo number_format( ($data['withheld_tax'] + $annual_tax_witheld), 2); ?></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
  <?php
    $total_tax_still_due = $total_tax_due - ($data['withheld_tax'] + $annual_tax_witheld); 
  ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl70 style='height:15.0pt'>- Tax Still due/(refundable)</td>
  <td height=20 class=xl70 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <?php if($total_tax_still_due < 0) { ?>
            <td colspan=3 style="text-align: left !important;" class=x174><strong>(<?php echo number_format(abs($total_tax_still_due), 2); ?>)</strong>&nbsp;</td>
  <?php } else { ?>
            <td colspan=3 style="text-align: left !important;" class=x174><strong><?php echo number_format($total_tax_still_due, 2); ?></strong>&nbsp;</td>
  <?php } ?>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>

 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl70 style='height:15.0pt'></td>
  <td height=20 class=xl70 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68></td>
  <td colspan=3 class=xl81></td>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>
 <?php
    if($total_tax_still_due < 0) {
      $add_tax_still_due = abs($total_tax_still_due);  
    } else { $add_tax_still_due = 0; } 
    
    $grand_total = ( $total_net_pay + ( ($montly_compensation_total / 12) - $data['1st_bunos_13th_month_amount']) + $total_unused_leave_amount + $total_other_earnings ) - ($total_other_deduction + $add_tax_still_due);
 ?>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl70 style='height:15.0pt'>- Grand Total</td>
  <td height=20 class=xl70 style='height:15.0pt'>&nbsp;</td>
  <td class=xl68>:</td>
  <?php if($grand_total < 0) { ?>
          <td colspan=3 style="text-align: left !important;" class=x174>
            <strong>(<?php echo number_format(abs($grand_total), 2); ?>)&nbsp;</strong>
          </td>
  <?php } else { ?>
          <td colspan=3 style="text-align: left !important;" class=x174>
            <strong><?php echo number_format($grand_total, 2); ?></strong>
          </td>
  <?php } ?>
  <td class=xl74></td>
  <td class=xl74></td>
  <td></td>
  <td></td>
 </tr>


 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td height=20 style='height:15.0pt'>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'>Prepared by:</td>
  <td height=20 class=xl67 style='height:15.0pt'>&nbsp;</td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Checked by:</td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'>Approved by:</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'></td>
  <td height=20 class=xl67 style='height:15.0pt'>&nbsp;</td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'></td>
  <td height=20 class=xl67 style='height:15.0pt'>&nbsp;</td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td class=xl67></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl65 style='height:15.0pt'><?php echo $prepared_by_d['employee_name']; ?></td>
  <td height=20 class=xl65 style='height:15.0pt'>&nbsp;</td>
  <td class=xl65 colspan=2 style='mso-ignore:colspan'><?php echo $checked_by_d['employee_name']; ?></td>
  <td class=xl65 colspan=2 style='mso-ignore:colspan'><?php echo $approved_by_d['employee_name']; ?></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl67 style='height:15.0pt'><?php echo $prepared_by_d['position']; ?></td>
  <td height=20 class=xl67 style='height:15.0pt'>&nbsp;</td>
  <td class=xl67 colspan=2 style='mso-ignore:colspan'><?php echo $checked_by_d['position']; ?></td>
  <td class=xl67><?php echo $approved_by_d['position']; ?></td>
  <td class=xl67></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>   
</table>