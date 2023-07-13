<?php
ob_start();
?>
<style type="text/css">
  @import "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER; ?>themes/default/payslip2.css";
</style>
<?php
  $c = G_Company_Structure_Finder::findByMainParent();
  $company_name = $c->getTitle();

  $ci = G_Company_Info_Finder::findByCompanyStructureId($c->getId());
  $company_address = $ci->getAddress();

  $line_counter  = 1;
?>
<table border="0">
    <tr>
        <td style="font-size:17pt;" colspan="8"><strong>PAYROLL REGISTER <?= (!empty($employee_type) ? "({$employee_type})" : "" ) ?></strong></td>
    </tr>
    <tr>
        <td style="font-size:17pt;" colspan="8"><strong>PAYROLL PERIOD : <?= date("M d, Y",strtotime($from)) ?> to <?= date("M d, Y",strtotime($to)) ?></strong></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>

<table border="1" cellpadding="0" cellspacing="0">  
<?php
$grand_total  = 0;
$a_grand_total_per_dept = array();
array_multisort($grouped_data, SORT_ASC, SORT_REGULAR); //ksort($grouped_data);
foreach ($grouped_data as $e_status_key => $dept_sect_data) {
  $a_summary_employment_type           = array();
  $total_employees_per_employment_type = 0;
?>
  <tr style="height:50px;">
    <td style="border:1px solid;" width="50">Line #</td>
    <td style="border:1px solid;" width="308">ID NO</td>
    <td style="border:1px solid;" width="141">Surname</td>
    <td style="border:1px solid;" width="173">First Name</td>
    <td style="border:1px solid;" width="80">Basic Pay</td>
    <td style="border:1px solid;" width="80">Night Differential</td>
    <td style="border:1px solid;" width="80">Overtime</td>
    <td style="border:1px solid;" width="80">Absences</td>
    <td style="border:1px solid;" width="80">Undertime</td>
    <td style="border:1px solid;" width="80">Tardiness</td>
    <td style="border:1px solid;" width="80">Adjustment</td>
    <td style="border:1px solid;" width="80">Holiday Pay</td>
    <td style="border:1px solid;" style="border:1px solid;" width="80">Position Allowance</td>
    <td style="border:1px solid;" width="80">Gross Pay</td>
    <td style="border:1px solid;" width="80">Tax Withheld</td>
    <td style="border:1px solid;" width="80">Philhealth</td>
    <td style="border:1px solid;" width="80">SSS Contri</td>
    <td style="border:1px solid;" width="80">Pag-ibig Contri</td>
    <td style="border:1px solid;" width="80">SSS Loan</td>
    <td style="border:1px solid;" width="80">Pag-ibig Loan</td>
    <td style="border:1px solid;" width="80">Company/Emergency Loan</td>
    <td style="border:1px solid;" width="80">Education Loan</td>
    <td style="border:1px solid;" width="80">HMO</td>
    <td style="border:1px solid;" width="80">EO</td>
    <td style="border:1px solid;" width="80">Other Deductions</td>
    <td style="border:1px solid;" width="80">Total Deductions</td>
    <td style="border:1px solid;" width="80">OT Allowance</td>
    <td style="border:1px solid;" width="80">Meal / Transpo Allowance</td>
    <td style="border:1px solid;" width="80">Rice Subsidy</td>
    <td style="border:1px solid;" width="80">CTPA / SEA</td>
    <td style="border:1px solid;" width="80">13th Mo./ Bonus / Leave Con.</td>
    <td style="border:1px solid;" width="80">Other Earnings</td>
    <td style="border:1px solid;" width="80">Total of Other Earnings</td>
    <td style="border:1px solid;" width="80">Net Pay</td>
  </tr>
  <tr style="height:50px;"><td colspan="33"><b><?php echo strtoupper($e_status_key); ?></b></td></tr>  
<?php
  ksort($dept_sect_data)
?>
<?php foreach( $dept_sect_data as $dept_sect_key => $employee ){ ?>  
<?php 
  $line_counter   = 1;
  $sub_total      = array();
  $a_dept_section = explode("-", $dept_sect_key);  
  $dept_name    = trim(mb_convert_case($a_dept_section[0], MB_CASE_UPPER,"UTF-8"));
  $section_name = trim(mb_convert_case($a_dept_section[1], MB_CASE_UPPER,"UTF-8")); 
?>
  <tr style="height:60px;font-size:14pt;"><td colspan="33"><b><?php echo $section_name == '' ? $dept_name : "{$dept_name} - {$section_name}"; ?></b></td></tr>
<?php foreach( $employee as $e  ){ ?>  
  <?php 
    $total_other_deduction_key = 0; 
    $employee_id   = $e['id'];
    $employee_code = $e['employee_code'];
    $lastname      = $e['lastname'];
    $firstname     = $e['firstname'];
    $section_title = $e['section_name'];
    $dept_title    = $e['department_name'];
    $basic_pay     = $payslips[$employee_id]['basic_pay']; //earnings
    $month_13th    = $payslips[$employee_id]['month_13th'];
    $taxable       = $payslips[$employee_id]['taxable'];
    $period_start  = $payslips[$employee_id]['period_start'];
    $period_end    = $payslips[$employee_id]['period_end'];
    $gross_pay     = $payslips[$employee_id]['gross_pay'];
    $total_deductions = $payslips[$employee_id]['total_deductions'];
    $net_pay       = $payslips[$employee_id]['net_pay'];
    $sss           = $payslips[$employee_id]['sss'];
    $philhealth    = $payslips[$employee_id]['philhealth'];
    $pagibig       = $payslips[$employee_id]['pagibig'];
    $witholding_tax = $payslips[$employee_id]['withheld_tax'];

    $obj_earnings = unserialize($payslips[$employee_id]['earnings']);
    $obj_labels   = unserialize($payslips[$employee_id]['labels']);

    $payslip_section_other_earnings = array();
    $payslip_section_earnings       = array();
    $payslip_section_deduction      = array();
    $payslip_section_breakdown      = array();      
    $payslipDataBuilder        = new G_Payslip();
    $payslip_section_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('earnings', 2);      
    $payslip_section_other_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('other_earnings', 2);      
    $payslip_section_deduction = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2);      
    $payslip_section_breakdown = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('breakdown', 2);
    $payslip_section_yearly_bonus  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('yearly_bonus', 2, '', array($period_start, $period_end));

    $emp_loan_balance = G_Employee_Loan_Helper::sqlGetUserLoanBalance($employee_id);
    $payslip_section_loan_leave_balance = $payslipDataBuilder->wrapPayslipArray($emp_loan_balance)->getPayslipData('loan_leave', 2);

    $available_sick_leave     =  G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,1);
    $available_vacation_leave =  G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,2);
    $available_general_leave  = G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,10);

    $payslip_yearly_breakdown = G_Payslip_Helper::computeEmployeeYearlyPayslipBreakdownByEndDate($employee_id, $end_date);
    $total_rd_amount          = $payslip_section_earnings['total_rest_day']['value'];
    $total_overtime_amount    = $payslip_section_earnings['total_regular_ot_amount']['value'] + $payslip_section_earnings['total_regular_ns_ot_amount']['value'] + $payslip_section_earnings['total_legal_ot_amount']['value'] + $payslip_section_earnings['total_legal_ns_ot_amount']['value'] + $payslip_section_earnings['total_special_ns_ot_amount']['value'] + $payslip_section_earnings['total_special_ot_amount']['value'] + $payslip_section_earnings['total_rest_day_ot']['value'] + $payslip_section_earnings['total_rest_day_special_ot']['value'] + $payslip_section_earnings['total_rest_day_special']['value'] + $payslip_section_earnings['total_rest_day_legal_ot']['value'] + $total_rd_amount + $payslip_section_earnings['total_rest_day_ns_ot']['value'] + $payslip_section_earnings['total_rest_day_legal']['value'] + $payslip_section_earnings['total_rest_day_special_ns_ot']['value'];
    $total_holiday_pay        = $payslip_section_earnings['total_legal_amount']['value'] + $payslip_section_earnings['total_special_amount']['value'];
    $total_hrs_worked          = $payslip_section_breakdown['regular_hours']['value'] - ($payslip_section_breakdown['holiday_legal_hours']['value'] + $payslip_section_breakdown['holiday_special_hours']['value'] + $payslip_section_breakdown['restday_hours']['value']);
    $total_nightshift_diff        = $payslip_section_breakdown['regular_ns_amount']['value'] + $payslip_section_breakdown['restday_ns_amount']['value'] + $payslip_section_breakdown['restday_special_ns_amount']['value'] + $payslip_section_breakdown['restday_legal_ns_amount']['value'] + $payslip_section_breakdown['holiday_special_ns_amount']['value'] + $payslip_section_breakdown['holiday_legal_ns_amount']['value'];
    $total_company_emergency_loan = $payslip_section_deduction['company_loan']['value'] + $payslip_section_deduction['salary_loan']['value'] + $payslip_section_deduction['emergency_loan']['value'];
    $earnings_benefits_adjustment = $payslip_section_other_earnings['adjustments']['value'] + $payslip_section_other_earnings['leave_adjustments']['value'];

    //Bonus and Service Award
    $bonus = 0;
    $service_award = 0;
    $to_deduct_earnings = 0;
    $bonus_service_award_witholding_tax = 0;

    if( !$add_bonus_to_earnings ){
      $bonus = $payslip_section_other_earnings['bonus']['value'];
      $service_award = $payslip_section_other_earnings['service_award']['value'];
      $bonus_service_award_witholding_tax = $payslip_section_deduction['bonus_/_service_award_witholding_tax']['value'];

      //$witholding_tax = $witholding_tax - $bonus_service_award_witholding_tax;
      $witholding_tax = $witholding_tax;
      $to_deduct_earnings = $bonus + $service_award;
      $net_pay = $net_pay - $to_deduct_earnings;
    }

    //echo $witholding_tax . ' - ' . $bonus_service_award_witholding_tax;
    //echo '<br />';

    //Earnings and benefits
    $psoe_amount = 0;
    foreach($payslip_section_other_earnings as $psoe_key => $psoe_key_data) {
        $filter_position_allowance_string = explode("_",$psoe_key);
        if($filter_position_allowance_string[0] == 'position' && $filter_position_allowance_string[1] = 'allowance') {
            $psoe_amount = $psoe_key_data['value'];
        }
    }

    $exist_deductions   = array('emergency_loan','salary_loan','hmo','sss_loan', 'adjustment','witholding tax','philhealth','pagibig','sss','absent_amount','undertime_amount','late_amount','company_loan','education_loan','eo');
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
    $position_allowance_earnings = 0;
    $meal_transpo_earnings       = 0;
    $other_bonuses = 0;
    $bonus_leave_converted = 0;

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

    $total_earnings           = $payslip_section_earnings['basic_pay']['value'] + $total_nightshift_diff + $position_allowance + $total_overtime_amount + $payslip_section_other_earnings['adjustments']['value'] + $total_holiday_pay + $payslip_section_other_earnings['adjustments']['value'] + $earnings_benefits_adjustment;
    $total_less_earnings      = $payslip_section_earnings['absent_amount']['value'] + $payslip_section_earnings['undertime_amount']['value'] + $payslip_section_earnings['late_amount']['value'] + $payslip_section_deduction['adjustments']['value'];
    $total_earning_gross_pay  = $total_earnings - $total_less_earnings;
    $total_sum_deductions     = $payslip_section_deduction['witholding tax']['value'] + $payslip_section_deduction['philhealth']['value'] + $payslip_section_deduction['sss']['value'] + $payslip_section_deduction['pagibig']['value'] + ($payslip_section_deduction['sss_loan']['value'] + $payslip_section_deduction['sss_salary_loan']['value'] + $payslip_section_deduction['sss_calamity_loan']['value']) + ($payslip_section_deduction['pagibig_loan']['value'] + $payslip_section_deduction['pagibig_salary_loan']['value'] + $payslip_section_deduction['pagibig_calamity_loan']['value']) + $payslip_section_deduction['education_loan']['value'] + $total_company_emergency_loan + $payslip_section_deduction['hmo']['value'] + $total_other_deduction_key + $payslip_section_deduction['eo']['value'] - $bonus_service_award_witholding_tax;

    if($payslip_section_earnings['total_ceta_amount']['value'] == '0.00' && $payslip_section_earnings['total_sea_amount']['value'] == '0.00') {
      foreach($payslip_section_other_earnings as $cptasea_key => $cptasea_key_data) {
          $filter_ctpa_sea_string = explode("_",$cptasea_key);
          if($filter_ctpa_sea_string[0] == 'ctpa/sea') {
              $cptasea_amount = $cptasea_key_data['value'];
          }
      }
      $total_ctpa_sea = $cptasea_amount;
    } else {
      $total_ctpa_sea = $payslip_section_earnings['total_ceta_amount']['value'] + $payslip_section_earnings['total_sea_amount']['value'];
    }
   
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

  /*$bonus = 0;
  if( isset($payslip_section_other_earnings['13th_month']) ){
    $bonus = $payslip_section_other_earnings['13th_month']['value'];
  }
  
  if( isset($payslip_section_yearly_bonus['yearly_bonus']) ){
    $bonus += $payslip_section_yearly_bonus['yearly_bonus']['value']['amount'];
  }*/

  if($add_bonus_to_earnings) {
    if( isset($payslip_section_yearly_bonus['yearly_bonus']) ){
        $other_bonuses += $payslip_section_yearly_bonus['yearly_bonus']['value']['amount'];  
    }
  }

  if($add_converted_leave) {
    $other_bonuses += $bonus_leave_converted; 
  }

  if($add_bonus_to_earnings == false) {
    if( isset($payslip_section_yearly_bonus['yearly_bonus']) ){
      $net_pay -= $payslip_section_yearly_bonus['yearly_bonus']['value']['amount'];  
    }
  }

  if($add_converted_leave == false) {
    $net_pay -= $bonus_leave_converted; 
  }

  $ctpa_earnings += $payslip_section_earnings['total_ceta_amount']['value'] + $payslip_section_earnings['total_sea_amount']['value'];
  $total_ctpa_sea = $ctpa_earnings;
  //$other_earnings = $other_earnings - $earnings_adjustment_total;
  $other_earnings = $other_earnings - $to_deduct_earnings;
  $total_other_earnings = $payslip_section_other_earnings['ot_allowance']['value'] + $meal_transpo_earnings + $other_earnings + $rice_earnings + $total_ctpa_sea;
  $eo_loan = $payslip_section_deduction['eo']['value'];
  //$total_other_deduction_key = $total_other_deduction_key - $eo_loan;
  $total_other_deductions = $total_other_deductions - $bonus_service_award_witholding_tax;
  $earnings_benefits_adjustment = $earnings_adjustment_total - $deduct_adjustment_total;
  ?>
  <?php 
    $total_employees_per_employment_type++;
    $sub_total['total_employees'] = $line_counter;
    $sub_total['total_basic_pay']      += $payslip_section_earnings['basic_pay']['value'];
    $sub_total['total_nightshift_diff'] += $total_nightshift_diff;
    $sub_total['total_overtime_amount'] += $total_overtime_amount;
    $sub_total['total_absent_amount']   += $payslip_section_earnings['absent_amount']['value'];
    $sub_total['total_undertime_amount'] += $payslip_section_earnings['undertime_amount']['value'];
    $sub_total['total_late_amount']      += $payslip_section_earnings['late_amount']['value'];
    $sub_total['total_earnings_benefits_adjustment'] += $earnings_benefits_adjustment;
    $sub_total['total_holiday_pay'] += $total_holiday_pay;
    $sub_total['total_position_allowance'] += $position_allowance_earnings;
    $sub_total['total_gross_pay'] += $gross_pay;
    $sub_total['total_witholding'] += $witholding_tax;
    $sub_total['total_philhealth'] += $payslip_section_deduction['philhealth']['value'];
    $sub_total['total_sss'] += $payslip_section_deduction['sss']['value'];
    $sub_total['total_pagibig'] += $payslip_section_deduction['pagibig']['value'];
    $sub_total['total_sss_loan'] += ($payslip_section_deduction['sss_loan']['value'] + $payslip_section_deduction['sss_salary_loan']['value'] + $payslip_section_deduction['sss_calamity_loan']['value']);
    $sub_total['total_pagibig_loan'] += ($payslip_section_deduction['pagibig_loan']['value'] + $payslip_section_deduction['pagibig_salary_loan']['value'] + $payslip_section_deduction['pagibig_calamity_loan']['value']);
    $sub_total['total_company_emergency_loan'] += $total_company_emergency_loan;
    $sub_total['total_education_loan'] += $payslip_section_deduction['education_loan']['value'];
    $sub_total['total_hmo_loan'] += $payslip_section_deduction['hmo']['value'];
    $sub_total['total_eo_loan'] += $eo_loan;
    $sub_total['total_other_deductions'] += ($total_other_deduction_key - $bonus_service_award_witholding_tax);
    $sub_total['total_deductions'] += $total_sum_deductions;
    $sub_total['total_ot_allowance'] += $payslip_section_other_earnings['ot_allowance']['value'];
    $sub_total['total_meal_transpo_allowance'] += $meal_transpo_earnings;
    $sub_total['total_rice_allowance'] += $rice_earnings;
    $sub_total['total_ctpa_sea']       += $total_ctpa_sea;
    $sub_total['total_other_bonuses']  += $other_bonuses;
    $sub_total['total_other_earnings'] += $other_earnings;
    $sub_total['total_of_other_earnings'] += $total_other_earnings;
    $sub_total['total_net_pay'] += $net_pay;    
  ?>

  <tr>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:left;border:1px solid;"><?php echo $line_counter; ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:left;border:1px solid;"><?php echo $employee_code; ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:left;border:1px solid;"><?php echo mb_convert_case($lastname, MB_CASE_TITLE, "UTF-8"); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:left;border:1px solid;"><?php echo mb_convert_case($firstname, MB_CASE_TITLE, "UTF-8"); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_earnings['basic_pay']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_nightshift_diff,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_overtime_amount,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_earnings['absent_amount']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_earnings['undertime_amount']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_earnings['late_amount']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($earnings_benefits_adjustment,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_holiday_pay,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($position_allowance_earnings,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($gross_pay,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($witholding_tax,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['philhealth']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['sss']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['pagibig']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['sss_loan']['value'] + $payslip_section_deduction['sss_salary_loan']['value'] + $payslip_section_deduction['sss_calamity_loan']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['pagibig_loan']['value'] + $payslip_section_deduction['pagibig_salary_loan']['value'] + $payslip_section_deduction['pagibig_calamity_loan']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_company_emergency_loan,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['education_loan']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_deduction['hmo']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($eo_loan,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_other_deduction_key - $bonus_service_award_witholding_tax,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_sum_deductions,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($payslip_section_other_earnings['ot_allowance']['value'],2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($meal_transpo_earnings,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($rice_earnings,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_ctpa_sea,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($other_bonuses,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($other_earnings,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($total_other_earnings,2, '.' , ',' ); ?></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;border:1px solid;" align="right"><?php echo number_format($net_pay,2, '.' , ',' ); ?></td>
  </tr>
<?php $line_counter++;}?>
<?php  
  foreach( $sub_total as $key => $value ){
    $a_summary_employment_type[$dept_name][$key] += $value;
    $a_grand_total_per_dept[$dept_name][$key]    += $value;
  }    
?>
  <tr style="height:50px;"><td colspan="33">TOTAL <b><?php echo ($section_name == '' ? $dept_name : "{$dept_name} - {$section_name}"); ?></b></td></tr>
  <tr>
  <?php $sub_total_counter = 1; foreach( $sub_total as $sub_total_key => $sub_total_value ){ ?>
    <?php if( $sub_total_counter == 1 ){ ?>
    <td colspan="4" style="font-size:11pt;mso-number-format:'\@';text-align:left;"><b>EMPLOYEES : <?php echo $sub_total_value; ?></b></td>
    <?php }else{ ?>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($sub_total_value,2, '.' , ',' ); ?></td>
    <?php } ?>
  <?php $sub_total_counter++;} ?>
  </tr>
<?php } ?>
  <tr style="height:50px;"><td colspan="33">TOTAL <b><?php echo strtoupper($e_status_key); ?></b></td></tr>
  <tr style="height:50px;"><td colspan="33"><b>DEPARTMENT</b></td></tr> 

  <?php    
    $group_sub_total = array();   
    foreach($a_summary_employment_type as $key => $value){ 
      $i_summary_employment_type_counter =1; 
      echo "<tr>";
      foreach( $value as $subKey => $subValue ){
        $group_sub_total['group_' . $subKey] += $subValue;
  ?>
      <?php if( $i_summary_employment_type_counter == 1 ){ ?>
        <td colspan="3" style="font-size:11pt;mso-number-format:'\@';text-align:left;"><?php echo $key; ?></td>
        <td style="font-size:11pt;mso-number-format:'\@';text-align:left;"><?php echo $subValue; ?></td>
      <?php }else{ ?>
        <td style="font-size:11pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($subValue,2, '.' , ',' ); ?></td>
      <?php } ?>    
  <?php $i_summary_employment_type_counter++;} ?>    
  <?php echo "</tr>";} ?>  

  <?php 
    $group_sub_total_counter = 1;
    foreach( $group_sub_total as $total ){ 
      if( $group_sub_total_counter == 1 ){
  ?>
    <td colspan="3" style="font-size:11pt;mso-number-format:'\@';text-align:left;"><b><?php echo "TOTAL " . strtoupper($e_status_key) . " EMPLOYEES"; ?></b></td>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:left;"><b><?php echo $total; ?></b></td>
  <?php }else{ ?>
    <td style="font-size:11pt;mso-number-format:'\@';text-align:right;" align="right"><?php echo number_format($total,2, '.' , ',' ); ?></td>
  <?php } ?>
  <?php $group_sub_total_counter++;} ?>
<?php } ?>

   <tr style="height:50px;"><td colspan="33"><b>TOTAL PER DEPARTMENT</b></td></tr> 
   <tr style="height:50px;border:1px solid;">
    <td width="141" style="border:1px solid;" colspan="3"></td>    
    <td width="141" style="border:1px solid;">No. of Employees</td>    
    <td width="80" style="border:1px solid;">Basic Pay</td>
    <td width="80" style="border:1px solid;">Night Differential</td>
    <td width="80" style="border:1px solid;">Overtime</td>
    <td width="80" style="border:1px solid;">Absences</td>
    <td width="80" style="border:1px solid;">Undertime</td>
    <td width="80" style="border:1px solid;">Tardiness</td>
    <td width="80" style="border:1px solid;">Adjustment</td>
    <td width="80" style="border:1px solid;">Holiday Pay</td>
    <td width="80" style="border:1px solid;">Position Allowance</td>
    <td width="80" style="border:1px solid;">Gross Pay</td>
    <td width="80" style="border:1px solid;">Tax Withheld</td>
    <td width="80" style="border:1px solid;">Philhealth</td>
    <td width="80" style="border:1px solid;">SSS Contri</td>
    <td width="80" style="border:1px solid;">Pag-ibig Contri</td>
    <td width="80" style="border:1px solid;">SSS Loan</td>
    <td width="80" style="border:1px solid;">Pag-ibig Loan</td>
    <td width="80" style="border:1px solid;">Company/Emergency Loan</td>
    <td width="80" style="border:1px solid;">Education Loan</td>
    <td width="80" style="border:1px solid;">HMO</td>
    <td width="80" style="border:1px solid;">EO</td>
    <td width="80" style="border:1px solid;">Other Deductions</td>
    <td width="80" style="border:1px solid;">Total Deductions</td>
    <td width="80" style="border:1px solid;">OT Allowance</td>
    <td width="80" style="border:1px solid;">Meal / Transpo Allowance</td>
    <td width="80" style="border:1px solid;">Rice Subsidy</td>
    <td width="80" style="border:1px solid;">CTPA / SEA</td>
    <td width="80" style="border:1px solid;">13th Mo./ Bonus / Leave Con.</td>
    <td width="80" style="border:1px solid;">Other Earnings</td>
    <td width="80" style="border:1px solid;">Total of Other Earnings</td>
    <td width="80" style="border:1px solid;">Net Pay</td>
  </tr>
  <?php
    $counter_grand_total_per_dept =1; 
    $a_grand_total_dept = array();
    foreach( $a_grand_total_per_dept as $key => $value ){
      $counter_grand_total_per_dept = 1;
      echo "<tr style=\"height:50px;\">";
        foreach( $value as $subKey => $subValue ){
          $a_grand_total_dept[$subKey] += $subValue;
          if( $counter_grand_total_per_dept == 1 ){
            echo "<td colspan=\"3\" style=\"font-size:11pt;mso-number-format:'\@';text-align:left;\"><b>{$key}</b></td>";
            echo "<td style=\"font-size:11pt;mso-number-format:'\@';text-align:left;\"><b>{$subValue}</b></td>";
          }else{
            echo "<td style=\"font-size:11pt;mso-number-format:'\@';text-align:right;\" align=\"right\">" . number_format($subValue,2, '.' , ',' ) . "</td>";
          }
          $counter_grand_total_per_dept++;
        }
      echo "</tr>";
    }
    $counter_grand_total_per_dept =1; 
    echo "<tr style=\"height:50px;\">";
    foreach( $a_grand_total_dept as $value ){
      if( $counter_grand_total_per_dept == 1 ){
        echo "<td colspan=\"3\" style=\"font-size:11pt;mso-number-format:'\@';text-align:left;\"><b>TOTAL</b></td>";
        echo "<td style=\"font-size:11pt;mso-number-format:'\@';text-align:left;\">{$value}</b></td>";
      }else{
        echo "<td style=\"font-size:11pt;mso-number-format:'\@';text-align:right;\" align=\"right\">" . number_format($value,2, '.' , ',' ) . "</td>";
      }
      $counter_grand_total_per_dept++;
    }
    echo "</tr>";
  ?>

  <tr style="height:30px;">
    <td width="141" colspan="3" style="font-size:14pt;font-weight:bold;border:1px solid;"></td>    
    <td width="141" style="font-size:14pt;font-weight:bold;border:1px solid;">No. of Employees</td>    
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Basic Pay</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Night Differential</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Overtime</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Absences</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Undertime</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Tardiness</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Adjustment</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Holiday Pay</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Position Allowance</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Gross Pay</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Tax Withheld</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Philhealth</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">SSS Contri</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Pag-ibig Contri</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">SSS Loan</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Pag-ibig Loan</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Company/Emergency Loan</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Education Loan</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">HMO</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">EO</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Other Deductions</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Total Deductions</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">OT Allowance</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Meal / Transpo Allowance</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Rice Subsidy</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">CTPA / SEA</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">13th Mo./ Bonus / Leave Con.</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Other Earnings</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Total of Other Earnings</td>
    <td width="80" style="font-size:14pt;font-weight:bold;border:1px solid;">Net Pay</td>
  </tr>
  <tr style="height:20px;"><td width="141" colspan="33"></td></tr>
  <?php   
    $counter_grand_total_per_dept =1; 
    echo "<tr style=\"height:50px;\">";
    foreach( $a_grand_total_dept as $value ){
      if( $counter_grand_total_per_dept == 1 ){
        echo "<td colspan=\"3\" style=\"font-size:14pt;mso-number-format:'\@';text-align:left;border:1px solid;\"><b>GRAND TOTAL</b></td>";
        echo "<td style=\"font-size:14pt;mso-number-format:'\@';text-align:left;border:1px solid;\">{$value}</b></td>";
      }else{
        echo "<td style=\"font-size:14pt;mso-number-format:'\@';text-align:right;border:1px solid;\" align=\"right\">" . number_format($value,2, '.' , ',' ) . "</td>";
      }
      $counter_grand_total_per_dept++;
    }
    echo "</tr>";
  ?>
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=cost_center.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>
