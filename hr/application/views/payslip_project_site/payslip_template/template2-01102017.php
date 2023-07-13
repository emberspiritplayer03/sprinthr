<?php
ob_start();
?>
<style type="text/css">
  @import "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER; ?>themes/default/payslip2.css";
</style>

<?php
  //$logo_url = 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . 'images/daiichi-logo.png';
?>

<?php
  $c = G_Company_Structure_Finder::findByMainParent();
  $company_name = $c->getTitle();

  $ci = G_Company_Info_Finder::findByCompanyStructureId($c->getId());
  $company_address = $ci->getAddress();
?>
<?php
  $row_number = 1;
?>
<?php foreach ($employees as $e): ?>
<?php $total_other_deduction_key = ''; ?>
<?php
    $employee_id   = $e->getId();
    $employee_code = $e->getEmployeeCode();
    $employee_name = $e->getName();
    $employee_sec  = $e->getSectionId();

    $d = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
    $sec = G_Company_Structure_Finder::findById($employee_sec);
    if($sec) {
      $p = G_Company_Structure_Finder::findParentId($sec->getParentId());
      $sec_title = $sec->getTitle();
      
      if($p) {
        $parent_sec_title = $p->getTitle();
      }
    }
    

    if ($d) {
      $department = $d->getName();
     }

    $basic_pay    = $payslips[$employee_id]['basic_pay']; //earnings
    $month_13th   = $payslips[$employee_id]['month_13th'];
    $taxable      = $payslips[$employee_id]['taxable'];
    $period_start = $payslips[$employee_id]['period_start'];
    $period_end   = $payslips[$employee_id]['period_end'];
    $gross_pay    = $payslips[$employee_id]['gross_pay'];
    $total_deductions = $payslips[$employee_id]['total_deductions'];
    $net_pay      = $payslips[$employee_id]['net_pay'];
    $sss          = $payslips[$employee_id]['sss'];
    $philhealth   = $payslips[$employee_id]['philhealth'];
    $pagibig      = $payslips[$employee_id]['pagibig'];
    $witholding_tax = $payslips[$employee_id]['withheld_tax'];

    $obj_earnings = unserialize($payslips[$employee_id]['earnings']);
    $obj_labels = unserialize($payslips[$employee_id]['labels']);

    $payslip_section_other_earnings = array();
    $payslip_section_earnings       = array();
    $payslip_section_deduction      = array();
    $payslip_section_breakdown      = array();

    $payslipDataBuilder = new G_Payslip();
    $payslip_section_earnings        = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('earnings', 2);
    $payslip_section_other_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('other_earnings', 2);
    $payslip_section_deduction       = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2);
    $payslip_section_breakdown       = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('breakdown', 2);
    $payslip_section_loan_balance    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('loan_balance', 2, '', array($from, $to));
    $payslip_section_yearly_bonus    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('yearly_bonus', 2, '', array($from, $to));

    $total_present_days    = $payslip_section_breakdown['present_days']['value'];
    $days_present_rest_day = $payslip_section_breakdown['days_present_rest_day']['value'];

    //Bonus and Service Award
    $custom_bonus_a = 0;
    $service_award = 0;
    $bonus_service_award_witholding_tax = 0;

    $custom_bonus_a = $payslip_section_other_earnings['bonus']['value'];
    $service_award = $payslip_section_other_earnings['service_award']['value'];
    $bonus_service_award_witholding_tax = $payslip_section_deduction['bonus_/_service_award_witholding_tax']['value'];
    $net_pay = $net_pay - ($custom_bonus_a + $service_award);

    //Loan Balance
    $pagibig_loan_balance = 0;
    $sss_loan_balance     = 0;
    $salary_loan_balance  = 0;
    $hmo_balance          = 0;
    $eo_balance           = 0;
    $emergency_loan_balance   = 0;    
    $educational_loan_balance = 0;
    foreach( $payslip_section_loan_balance as $key => $loan_balance ){
      switch ($key) {
        case 'pagibig_loan':
          $pagibig_loan_balance = $loan_balance['value'] - $payslip_section_deduction['pagibig_loan']['value'];          
          break;
        case 'sss_loan':
          $sss_loan_balance = $loan_balance['value'] - $payslip_section_deduction['sss_loan']['value'];
          break;
        case 'salary_loan':
          $salary_loan_balance = $loan_balance['value'] - $payslip_section_deduction['salary_loan']['value'];
          break;
        case 'hmo':
          $hmo_balance = $loan_balance['value'] - $payslip_section_deduction['hmo']['value'];
          break;
        case 'emergency_loan':
          $emergency_loan_balance = $loan_balance['value'] - $payslip_section_deduction['emergency_loan']['value'];
          break;
        case 'educational_loan':
          $educational_loan_balance = $loan_balance['value'] - $payslip_section_deduction['education_loan']['value'];
          break;
        case 'eo_loan':
          $eo_balance = $loan_balance['value'] - $payslip_section_deduction['eo']['value'];
        default:
          break;
      }
    }
    //End loan balance

    $emp_loan_balance = G_Employee_Loan_Helper::sqlGetUserLoanBalance($employee_id);

    $payslip_section_loan_leave_balance = $payslipDataBuilder->wrapPayslipArray($emp_loan_balance)->getPayslipData('loan_leave', 2);

    $available_sick_leave     =  G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,1);
    $available_vacation_leave =  G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,2);

    $available_general_leave  = G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,10);

    //$payslip_yearly_breakdown = G_Payslip_Helper::computeEmployeeYearlyPayslipBreakdown($employee_id);
    $payslip_yearly_breakdown = G_Payslip_Helper::computeEmployeeYearlyPayslipBreakdownByEndDate($employee_id, $end_date);
    $total_rd_amount       = $payslip_section_earnings['total_rest_day']['value'];
    $total_overtime_amount = $payslip_section_earnings['total_regular_ot_amount']['value'] + $payslip_section_earnings['total_regular_ns_ot_amount']['value'] + $payslip_section_earnings['total_legal_ot_amount']['value'] + $payslip_section_earnings['total_legal_ns_ot_amount']['value'] + + $payslip_section_earnings['total_special_ns_ot_amount']['value'] + $payslip_section_earnings['total_rest_day_ot']['value'] + $payslip_section_earnings['total_special_ot_amount']['value'] + $total_rd_amount + $payslip_section_earnings['total_rest_day_ns_ot']['value'] + $payslip_section_earnings['total_rest_day_special']['value'] + $payslip_section_earnings['total_rest_day_legal']['value'] + $payslip_section_earnings['total_rest_day_legal_ot']['value'] + $payslip_section_earnings['total_rest_day_special_ns_ot']['value'] + $payslip_section_earnings['total_rest_day_special_ot']['value'];
    $total_holiday_pay     = $payslip_section_earnings['total_legal_amount']['value'] + $payslip_section_earnings['total_special_amount']['value'];

    if ($row_number % 2 == 0) {
        $row_status = 'even';
    }else{
        $row_status = 'odd';
  }
?>

<table border=0 cellpadding=0 cellspacing=0 width=1000 style='border-collapse:
collapse;table-layout:fixed;width:752pt'>
<col class=xl96 width=201 style='mso-width-source:userset;mso-width-alt:7350;
width:151pt'>
<col class=xl96 width=117 style='mso-width-source:userset;mso-width-alt:4278;
width:88pt'>
<col class=xl96 width=250 style='mso-width-source:userset;mso-width-alt:9142;
width:188pt'>
<col class=xl96 width=138 style='mso-width-source:userset;mso-width-alt:5046;
width:104pt'>
<col class=xl96 width=170 style='mso-width-source:userset;mso-width-alt:6217;
width:128pt'>
<col class=xl96 width=124 style='mso-width-source:userset;mso-width-alt:4534;
width:93pt'>
<?php if($row_status == 'odd') { ?>
    <!--<tr>
    <td class=xl96 style="font-size:14.5pt;">&nbsp;</td>
    <td class=xl96></td>
    <td class=xl96></td>
    <td class=xl96></td>
    <td class=xl96></td>
    <td class=xl96></td>
    </tr>
    <tr>
    <td class=xl96 style='font-size:14.5pt; width: 151pt;'></td>
    <td class=xl96></td>
    <td class=xl96></td>
    <td class=xl96></td>
    <td class=xl96></td>
    <td class=xl96></td>
    </tr>-->
<?php } ?>
<tr>
<td class=xl69 style='font-size:14.5pt; width: 151pt;'>EMPLOYEE NUMBER :</td>
<td colspan=2 class=xl96 style="font-size:14.5pt; text-align:left;"><?php echo $employee_code;?></td>
<td class=xl69 style="font-size:14.5pt;">DEPARTMENT :</td>
<td colspan=2 class=xl96 style="font-size:14pt;"><?php echo $department; ?> / <?php echo $sec_title ."-". $parent_sec_title;?></td>
</tr>

<tr>
<td height=20 class=xl69 style='height:15.0pt; font-size:14.5pt; width: 151pt;'>EMPLOYEE NAME :</td>
<td colspan=2 class=xl96 style="font-size:14.5pt;"><?php echo $employee_name;?></td>
<td class=xl69 style="font-size:14.5pt;">PAY PERIOD :</td>
<td colspan=2 class=xl96 style="font-size:14.5pt;"><?php echo $cutoff_code;?> (<?php echo Tools::convertDateFormat($period_start);?> - <?php echo Tools::convertDateFormat($period_end);?>)</td>
</tr>

<tr>
<td colspan=6 class=xl107 style='font-size:13.5pt;'>&nbsp;</td>
</tr>

<tr>
<td colspan=2 class=xl108 style='border-right:.5pt solid black; font-size:13pt;'>EARNINGS</td>
<td colspan=2 class=xl108 style='border-right:.5pt solid black;border-left:none'>DEDUCTIONS</td>
<td colspan=2 class=xl108 style='border-right:.5pt solid black;border-left:none'>BREAKDOWN</td>
</tr>
<tr>
<td class=xl97 style='font-size:13.5pt; width: 151pt;'>BASIC PAY :</td>
<td class=xl96 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($payslip_section_earnings['basic_pay']['value'],2, '.' , ',' ); ?></td>
<td class=xl97 style="font-size:13.5pt;">TAX WITHHELD :</td>
<td class=xl96 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format(($payslip_section_deduction['witholding tax']['value']),2, '.' , ',' ); ?></td>
<td class=xl98 style="font-size:14pt;">TOTAL HRS. WORK</td>
<?php
  //$total_hrs_worked = $payslip_section_breakdown['regular_hours']['value'] - ($payslip_section_breakdown['holiday_legal_hours']['value'] + $payslip_section_breakdown['holiday_special_hours']['value'] + $payslip_section_breakdown['restday_hours']['value'] + $payslip_section_breakdown['regular_ot_hours']['value'] + $payslip_section_breakdown['regular_ns_ot_hours']['value'] + $payslip_section_breakdown['restday_ot_hours']['value'] + $payslip_section_breakdown['restday_ns_hours']['value'] + $payslip_section_breakdown['restday_ns_ot_hours']['value'] + $payslip_section_breakdown['holiday_special_ot_hours']['value'] + $payslip_section_breakdown['holiday_special_ns_hours']['value'] + $payslip_section_breakdown['holiday_special_ns_ot_hours']['value'] + $payslip_section_breakdown['restday_special_ot_hours']['value'] + $payslip_section_breakdown['holiday_legal_ot_hours']['value'] + $payslip_section_breakdown['holiday_legal_ns_hours']['value'] + $payslip_section_breakdown['holiday_legal_ns_ot_hours']['value'] + $payslip_section_breakdown['restday_legal_hours']['value'] + $payslip_section_breakdown['restday_legal_ns_hours']['value']);
  $total_hrs_worked = (($total_present_days - $days_present_rest_day) * 8.75);
?>
<td class=xl99 style="font-size:13.5pt;">: <?php echo number_format($total_hrs_worked,2, '.' , ',' ); ?></td>
</tr>
<?php

    $total_nightshift_diff = $payslip_section_breakdown['regular_ns_amount']['value'] + $payslip_section_breakdown['restday_ns_amount']['value'] + $payslip_section_breakdown['restday_special_ns_amount']['value'] + $payslip_section_breakdown['restday_legal_ns_amount']['value'] + $payslip_section_breakdown['holiday_special_ns_amount']['value'] + $payslip_section_breakdown['holiday_legal_ns_amount']['value'];

?>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>NIGHT DIFFERENTIAL :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total_nightshift_diff,2, '.' , ',' ); ?></td>
<td class=xl97 style="font-size:14pt;">PHILHEALTH :</td>
<td class=xl96 style='font-size:14pt;' align="right"><?php echo number_format($payslip_section_deduction['philhealth']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>SND</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['regular_ns_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='width: 151pt; font-size:14pt;'>OVERTIME :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($total_overtime_amount,2, '.' , ',' ); ?></td>
<td class=xl97 style='font-size:14pt;'>SSS :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_deduction['sss']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>REG. OT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['regular_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>ABSENCES :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_earnings['absent_amount']['value'],2); ?></td>
<td class=xl97 style='font-size:14pt;'>PAG-IBIG :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_deduction['pagibig']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>REG. OT ND</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['regular_ns_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>UNDERTIME :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_earnings['undertime_amount']['value'],2, '.' , ',' ); ?></td>
<td class=xl97 style='font-size:14pt;'>SSS LOAN :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_deduction['sss_loan']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>RD</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>TARDINESS :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_earnings['late_amount']['value'],2, '.' , ',' ); ?></td>
<td class=xl97 style='font-size:14pt;'>PAG-IBIG LOAN :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align=right><?php echo number_format($payslip_section_deduction['pagibig_loan']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>RD OT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<?php
  $total_company_emergency_loan = $payslip_section_deduction['company_loan']['value'] + $payslip_section_deduction['salary_loan']['value'] + $payslip_section_deduction['emergency_loan']['value'];
?>
<tr>
<td height=19 class=xl97 style='font-size:14pt; width: 151pt;'>ADJUSTMENTS :</td>
<?php
  //Adjustment add
  $earnings_key = array('adjustment');
  $earnings_adjustment_total = 0;
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    foreach( $earnings_key as $key ){
      if( stripos($earning_key, $key) !== false ){
        $earnings_adjustment_total += $payslip_section_other_earnings[$earning_key]['value'];    
      }
    }
  }

  //Adjustment deduct
  $deduct_key = array('adjustment');
  $deduct_adjustment_total = 0;
  foreach($payslip_section_deduction as $deduction_key => $e_deduction) {
    foreach( $earnings_key as $key ){
      if( stripos($deduction_key, $key) !== false ){
        $deduct_adjustment_total += $payslip_section_deduction[$deduction_key]['value'];    
      }
    }
  }
?>

<?php 
  //$earnings_benefits_adjustment = $payslip_section_other_earnings['adjustments']['value'] + $payslip_section_other_earnings['leave_adjustments']['value']; 
  $earnings_benefits_adjustment = $earnings_adjustment_total;
?>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right">+ <?php echo number_format($earnings_adjustment_total,2, '.' , ',' ); ?></td>
<td class=xl97 style='font-size:14pt;'>COMPANY/EMERGENCY LOAN :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total_company_emergency_loan,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>RD ND</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_ns_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'></td>
<td class=xl96 style='font-size:14pt;' align="right">- <?php echo number_format($deduct_adjustment_total,2, '.' , ',' ); ?></td>
<td class=xl97 style='font-size:14pt;'>EDUCATIONAL LOAN :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($payslip_section_deduction['education_loan']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>RD NDOT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_ns_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>HOLIDAY PAY :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total_holiday_pay,2); ?></td>
<td class=xl97 style='font-size:14pt;'>HMO:</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($payslip_section_deduction['hmo']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>SH</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_special_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<?php
  /*$psoe_amount = 0;
  foreach($payslip_section_other_earnings as $psoe_key => $psoe_key_data) {
      $filter_position_allowance_string = explode("_",$psoe_key);
      if($filter_position_allowance_string[0] == 'position' && $filter_position_allowance_string[1] = 'allowance') {
          $psoe_amount = $psoe_key_data['value'];
      }
  }*/
  $exist_deductions   = array('emergency_loan','salary_loan','hmo','sss_loan','adjustment','witholding tax','philhealth','pagibig','sss','absent_amount','undertime_amount','late_amount','company_loan','education_loan');
  foreach($payslip_section_deduction as $deduction_key => $e_deduction) {
    $is_exists = false;
    foreach( $exist_deductions as $key ){                   
      if( stripos($deduction_key, $key)  !== false ){                       
        $is_exists = true;
      }
    }
    if( !$is_exists ){
       $total_other_deduction_key = $total_other_deduction_key + $payslip_section_deduction[$deduction_key]['value'];       
    }
  }

  $other_earnings = 0;
  $rice_earnings  = 0;
  $bonus          = 0;
  $position_allowance_earnings = 0;
  $meal_transpo_earnings       = 0;
  $bonus_leave_converted       = 0;


  $earnings_key_rice = array('rice');
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    foreach( $earnings_key_rice as $key ){
      if( stripos($earning_key, $key) !== false ){
        $rice_earnings += $payslip_section_other_earnings[$earning_key]['value'];    
      }
    }
  }

  $earnings_key_pos_allowance = array('position_allowance');
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    foreach( $earnings_key_pos_allowance as $key ){
      if( stripos($earning_key, $key) !== false ){
        $position_allowance_earnings += $payslip_section_other_earnings[$earning_key]['value'];    
      }
    }
  }

  $earnings_key_meal_transpo = array('meal_allowance','transpo_allowance');
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    foreach( $earnings_key_meal_transpo as $key ){
      if( stripos($earning_key, $key) !== false ){
        $meal_transpo_earnings += $payslip_section_other_earnings[$earning_key]['value'];    
      }
    }
  }
  
  $leave_conversion = array('non_taxable_converted_leave','taxable_converted_leave');
  foreach( $leave_conversion as $key ){
    if( array_key_exists($key, $payslip_section_other_earnings) ){
      $bonus_leave_converted += $payslip_section_other_earnings[$key]['value'];  
    }
  }

  $earnings_ot_ctpa      = array('ot_allowance','ctpa/sea','ctpa','ctpa_sea','13th_month','non_taxable_converted_leave','taxable_converted_leave');  
  $earnings_key_others   = array_merge($earnings_key,$earnings_key_pos_allowance,$earnings_key_rice,$earnings_key_meal_transpo,$earnings_ot_ctpa);  
  $is_within_earning_others_array     = false;
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    foreach( $earnings_key_others as $key ){
      if( stripos($earning_key, $key) !== false ){
        $is_within_earning_others_array = true;
        break;
      }
    }
    /*if( !in_array($earning_key, $earnings_key_others) ){
      $other_earnings += $payslip_section_other_earnings[$earning_key]['value'];   
    }*/   

    if( !$is_within_earning_others_array ){
      $other_earnings += $payslip_section_other_earnings[$earning_key]['value'];   
    }
    $is_within_earning_others_array     = false;
  }

  $bonus = 0;
  /*if( isset($payslip_section_other_earnings['13th_month']) ){
    $bonus = $payslip_section_other_earnings['13th_month']['value'];
  }*/
  $yearly_bonus_amount = 0;
  if( isset($payslip_section_yearly_bonus['yearly_bonus']) ){
    /*
      Note: Remove the 13th month pay or bonus on the computation
    */
    //$bonus += $payslip_section_yearly_bonus['yearly_bonus']['value']['amount'];
    //$yearly_bonus_amount = $payslip_section_yearly_bonus['yearly_bonus']['value']['amount'];
  }

  if($show_converted_leaves_only == 1) {
    $bonus += $bonus_leave_converted;   
  }

  /*
    $net_pay (need to minus the 13th month pay or bonus)
  */
  if($show_converted_leaves_only == 1) {
    $net_pay -= $payslip_section_yearly_bonus['yearly_bonus']['value']['amount'];
  } else {
    $net_pay -= ($payslip_section_yearly_bonus['yearly_bonus']['value']['amount'] + $bonus_leave_converted);
  }
  

  $earnings_ctpa_sea = array('ctpa/sea','ctpa','ctpa_sea');
  $ctpa_earnings     = 0;
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    foreach( $earnings_ctpa_sea as $key ){
      if( stripos($earning_key, $key) !== false ){       
        $ctpa_earnings += $payslip_section_other_earnings[$earning_key]['value'];    
        break;
      }
    }
  }

  $ctpa_earnings += $payslip_section_earnings['total_ceta_amount']['value'] + $payslip_section_earnings['total_sea_amount']['value'];
  $other_earnings = $other_earnings - ($custom_bonus_a + $service_award);
  $total_other_deduction_key = $total_other_deduction_key - $bonus_service_award_witholding_tax;

  /*$other_earnings = 0;
  $exist_earnings = array('total_ceta_amount','total_sea_amount','ot_allowance','meal_allowance','transpo_allowance','rice_allowance');  
  foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
    $filter_ctpa_sea_string = explode("_",$earning_key);
    if (!in_array ($earning_key, $exist_earnings) && $filter_ctpa_sea_string[0] != 'ctpa/sea' && strpos($earning_key,'position_allowance') === false ) {
      $other_earnings += $payslip_section_other_earnings[$earning_key]['value'];      
    }
  }*/

?>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>POSITION ALLOWANCE:</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($position_allowance_earnings,2, '.' , ',' ); ?></td>
<td class=xl97 style='font-size:14pt;'>OTHER DEDUCTIONS :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format(empty($total_other_deduction_key) ? '0.00' : $total_other_deduction_key,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>SH OT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_special_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<?php
  $total_earnings           = $payslip_section_earnings['basic_pay']['value'] + $total_nightshift_diff + $position_allowance + $total_overtime_amount + $payslip_section_other_earnings['adjustments']['value'] + $total_holiday_pay + $payslip_section_other_earnings['adjustments']['value'] + $earnings_benefits_adjustment;
  $total_less_earnings      = $payslip_section_earnings['absent_amount']['value'] + $payslip_section_earnings['undertime_amount']['value'] + $payslip_section_earnings['late_amount']['value'] + $payslip_section_deduction['adjustments']['value'];
  $total_earning_gross_pay  = $total_earnings - $total_less_earnings;
  $total_sum_deductions     = $payslip_section_deduction['witholding tax']['value'] + $payslip_section_deduction['philhealth']['value'] + $payslip_section_deduction['sss']['value'] + $payslip_section_deduction['pagibig']['value'] + $payslip_section_deduction['sss_loan']['value'] + $payslip_section_deduction['pagibig_loan']['value'] + $payslip_section_deduction['education_loan']['value'] + $total_company_emergency_loan + $payslip_section_deduction['hmo']['value'] + $total_other_deduction_key;
?>
<tr>
<td class=xl100 style='font-size:13.5pt; width: 151pt;'>GROSS PAY :</td>
<td class=xl96 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($gross_pay,2, '.' , ',' ); ?></td>
<td class=xl101 style='font-size:13.5pt;'>TOTAL DEDUCTIONS :</td>
<td class=xl96 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total_sum_deductions,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>SH ND</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_special_ns_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td colspan=2 class=xl108 style='border-right:.5pt solid black; font-size:12.5pt;'>LOAN/LEAVE BALANCE</td>
<td colspan=2 class=xl108 style='border-right:.5pt solid black;border-left:none; font-size:12.5pt'>OTHER EARNINGS/DEDUCTIONS</td>
<td class=xl98 style='font-size:14pt;'>SH NDOT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_special_ns_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:13.5pt; width: 151pt;'>LEAVE BALANCE :</td>
<td class=xl99 style='font-size:13.5pt;' align="right"><?php echo number_format($available_general_leave,2, '.' , ',' ); ?></td>
<td class=xl96 style='font-size:13.5pt;'>OT ALLOWANCE :</td>
<td class=xl96 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($payslip_section_other_earnings['ot_allowance']['value'],2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>SHRD</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_special_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>PAG-IBIG LOAN :</td>
<td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($pagibig_loan_balance,2, '.' , ',' ); ?></td>
<td class=xl96 style='font-size:14pt;'>MEAL/TRANSPO ALLOWANCE :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($meal_transpo_earnings,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:13.5pt;'>SHRDNDOT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_special_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>SSS LOAN :</td>
<td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($sss_loan_balance,2, '.' , ',' ); ?></td>
<td class=xl96 style='font-size:14pt;'>RICE:</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($rice_earnings,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>LH</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_legal_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<?php
  /*if($payslip_section_earnings['total_ceta_amount']['value'] == '0.00' && $payslip_section_earnings['total_sea_amount']['value'] == '0.00') {
    foreach($payslip_section_other_earnings as $cptasea_key => $cptasea_key_data) {
        $filter_ctpa_sea_string = explode("_",$cptasea_key);
        if($filter_ctpa_sea_string[0] == 'ctpa/sea') {
            $cptasea_amount = $cptasea_key_data['value'];
        }
    }
    $total_ctpa_sea = $cptasea_amount;
  } else {
    $total_ctpa_sea = $payslip_section_earnings['total_ceta_amount']['value'] + $payslip_section_earnings['total_sea_amount']['value'];
  }*/
  $total_ctpa_sea = $ctpa_earnings;
?>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>SALARY/EMERGENCY LOAN :</td>
<!-- <td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format(($salary_loan_balance + $emergency_loan_balance) - $total_company_emergency_loan,2, '.' , ',' ); ?></td> -->
<td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format(($salary_loan_balance + $emergency_loan_balance) ,2, '.' , ',' ); ?></td>
<td class=xl96 style='font-size:14pt;'>CTPA/SEA:</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total_ctpa_sea,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>LH OT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_legal_ot_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>HMO :</td>
<td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($hmo_balance,2, '.' , ',' ); ?></td>
<td class=xl96 style='font-size:14pt;'>13TH MO./BONUS/LEAVE CON:</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($bonus,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>LH ND</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_legal_ns_hours']['value'],2, '.' , ',' ); ?> </td>
</tr>
<tr>
<td height=19 class=xl97 style='font-size:14pt; width: 151pt;'>EDUCATIONAL LOAN:</td>
<td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($educational_loan_balance,2, '.' , ',' )?></td>
<td class=xl96 style='font-size:14pt;'>OTHERS :</td>
<td class=xl96 style="font-size:14pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($other_earnings,2); ?></td>
<td class=xl98 style='font-size:14pt;'>LH NDOT</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['holiday_legal_ns_ot_hours']['value'],2, '.' , ',' ); ?>  </td>
</tr>
<tr>
<td height=21 class=xl97 style='font-size:13.5pt; width: 151pt;'>OTHERS</td>
<?php
  $total_other_loan_balance = $eo_balance;
?>
<td class=xl99 style="font-size:14pt;mso-number-format:'\@';text-align:right;"><?php echo number_format($total_other_loan_balance,2, '.' , ',' )?></td>
<td class=xl69 style='font-size:13.5pt;'>TOTAL :</td>

<?php if($show_converted_leaves_only == 1) { ?>
        <?php
          $total_other_earnings = ($payslip_section_other_earnings['ot_allowance']['value'] + $meal_transpo_earnings + $other_earnings + $rice_earnings + $total_ctpa_sea + $bonus_leave_converted);
        ?>
<?php } else { ?>
        <?php
          $total_other_earnings = ($payslip_section_other_earnings['ot_allowance']['value'] + $meal_transpo_earnings + $other_earnings + $rice_earnings + $total_ctpa_sea + $yearly_bonus_amount);
        ?>
<?php } ?>

<td class=xl96 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total_other_earnings,2, '.' , ',' ); ?></td>
<td class=xl98 style='font-size:14pt;'>LHRD</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['restday_legal_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td colspan=2 class=xl108 style='font-size:13pt; border-right:.5pt solid black;'>YEAR-TO-DATE</td>
<td colspan=2 class=xl111 style='font-size:13pt; border-right:.5pt solid black;border-left:none'>&nbsp;</td>
<td class=xl98 style='font-size:14pt;'>LHRDNDOT</td>
<td class=xl99 style="font-size:14pt;">: <?php echo number_format($paysilp_section_breakdown['restday_legal_ns_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:13.5pt; width: 151pt;'>GROSS INCOME :</td>
<td class=xl99 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($payslip_yearly_breakdown['y_gross_pay'],2, '.' , ',' ); ?></td>
<td class=xl115 style='font-size:13.5pt;'>NET SALARY :</td>
<?php if($show_converted_leaves_only == 1) { ?>
        <td class=xl116 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($net_pay,2); ?></td>
<?php }else { ?>
        <td class=xl116 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($net_pay,2); ?></td>
<?php } ?>
<td class=xl98 style='font-size:13.5pt;'>ABSENCES</td>
<td class=xl99 style='font-size:13.5pt;'>: <?php echo number_format($payslip_section_breakdown['absent_days_without_pay']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl97 style='font-size:14pt; width: 151pt;'>&nbsp;</td>
<td class=xl99 style='font-size:14pt;'>&nbsp;</td>
<td colspan=2 class=xl113 style='border-right:.5pt; font-size:14pt; solid black; border-left:none;'>&nbsp;</td>
<td class=xl98 style='font-size:14pt;' style='border-right:.5px; solid black;'>UNDERTIME</td>
<td class=xl99 style='font-size:14pt;'>: <?php echo number_format($payslip_section_breakdown['undertime_hours']['value'],2, '.' , ',' ); ?></td>
</tr>
<tr>
<td class=xl102 style='font-size:13.5pt; width: 151pt;'>TAX WITHHELD :</td>
<td class=xl103 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($payslip_yearly_breakdown['y_withheld_tax'],2, '.' , ',' ); ?></td>
<td class=xl104 style='font-size:13.5pt;'>TOTAL SALARY:</td>
<td class=xl105 style="font-size:13.5pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($net_pay,2); ?></td>
<td class=xl106 style='font-size:13.5pt;'>TARDINESS</td>
<td class=xl103 style='font-size:13.5pt;'>: <?php echo number_format($payslip_section_breakdown['late_hours']['value'],2 , '.' , ',' ); ?></td>
</tr>

<?php if($row_status == 'odd'){  ?> 
  <tr>
  <td class=xl96 style='font-size:17.0pt; width: 151pt;'>&nbsp;</td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  </tr>
  <tr>
  <td class=xl96 style='font-size:17.0pt; width: 151pt;'>&nbsp;</td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  </tr>
  <tr>
  <td class=xl96 style='font-size:17.0pt; width: 151pt;'></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  </tr>
  <tr>
  <td class=xl96 style='font-size:17.0pt; width: 151pt;'></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  </tr>
  <tr>
  <td class=xl96 style='font-size:17.0pt; width: 151pt;'></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  </tr> 
  <tr>
  <td class=xl96 style='font-size:17.0pt; width: 151pt;'></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  <td class=xl96></td>
  </tr>

<?php } ?>
</table>
<?php
if ($row_number % 2 == 0) {
   echo "<br />";
}else{
  echo "<br />";
}
?>
<?php $row_number++; ?>
<?php endforeach; ?>


<?php
//header("Content-type: application/vnd.ms-excel;"); //tried adding  charset='utf-8' into header
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payslip_{$cutoff_code}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
