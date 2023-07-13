<?php
ob_start();
?>
<style type="text/css">
  @import "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER; ?>themes/default/cost_center_stylesheet.css";
</style>
<table border=0 cellpadding=0 cellspacing=0 width=2545 style='border-collapse:
 collapse;table-layout:fixed;width:1911pt'>
 <col width=64 style='width:48pt'>
 <col class=xl65 width=85 style='mso-width-source:userset;mso-width-alt:3108;
 width:64pt'>
 <col class=xl65 width=64 style='width:48pt'>
 <col width=177 style='mso-width-source:userset;mso-width-alt:6473;width:133pt'>
 <col width=64 style='width:48pt'>
 <col class=xl81 width=70 style='mso-width-source:userset;mso-width-alt:2560;
 width:53pt'>
 <col width=101 style='mso-width-source:userset;mso-width-alt:3693;width:76pt'>
 <col width=79 style='mso-width-source:userset;mso-width-alt:2889;width:59pt'>
 <col width=76 style='mso-width-source:userset;mso-width-alt:2779;width:57pt'>
 <col width=86 style='mso-width-source:userset;mso-width-alt:3145;width:65pt'>
 <col width=82 style='mso-width-source:userset;mso-width-alt:2998;width:62pt'>
 <col width=72 style='mso-width-source:userset;mso-width-alt:2633;width:54pt'>
 <col width=107 style='mso-width-source:userset;mso-width-alt:3913;width:80pt'>
 <col width=91 style='mso-width-source:userset;mso-width-alt:3328;width:68pt'>
 <col width=78 span=2 style='mso-width-source:userset;mso-width-alt:2852;
 width:59pt'>
 <col width=83 style='mso-width-source:userset;mso-width-alt:3035;width:62pt'>
 <col width=64 span=17 style='width:48pt'>
 <tr height=30 style='height:22.5pt'>
  <td colspan=17 height=30 class=xl67 width=1457 style='height:22.5pt;
  width:1095pt'>Sagara Metro Plastics Industrial Corporation</td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <tr height=25 style='height:18.75pt'>
  <td colspan=17 height=25 class=xl69 style='height:18.75pt'>Finance &amp;
  Accounting Dept. - Payroll (Detailed)</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
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
  <td colspan=17 height=20 class=xl71 style='height:15.0pt'>Payroll Computation
  for the period of: <?= date("M d, Y",strtotime($from)) ?> to <?= date("M d, Y",strtotime($to)) ?></td>
  <td colspan=17 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl65></td>
  <td class=xl65></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl81></td>
  <td colspan=28 style='mso-ignore:colspan'></td>
 </tr>
<tr height=41 style='height:30.75pt'>
  <td height=41 class=xl66 width=64 style='height:30.75pt;width:48pt'>DATE HIRED</td>
  <td class=xl66 width=85 style='border-left:none;width:64pt'>EMP STATUS</td>
  <td class=xl66 width=85 style='border-left:none;width:64pt'>COST CENTER</td>
  <td class=xl66 width=64 style='border-left:none;width:48pt'>EMPLOYEE NAME</td>
  <td class=xl66 width=177 style='border-left:none;width:133pt'>PAY STATUS</td>
  <td class=xl66 width=64 style='border-left:none;width:48pt'>SLVL</td>

  <td class=xl66 width=64 style='border-left:none;width:48pt'>LEGAL HOLIDAY</td>
  <td class=xl66 width=64 style='border-left:none;width:48pt'>BIL</td>

  <!-- <td class=xl66 width=64 style='border-left:none;width:48pt'>HOLIDAY FALLS ON SUNDAY</td>
  <td class=xl66 width=64 style='border-left:none;width:48pt'>BILSUNDAY /PL/UL/AL/BRL</td> -->

  <td class=xl66 width=64 style='border-left:none;width:48pt'>Rice Subsidy</td>
  <td class=xl66 width=64 style='border-left:none;width:48pt'>RETRO Basic</td>

  <td class=xl66 width=70 style='border-left:none;width:53pt'>PRIOR DAYS</td>
  <td class=xl66 width=101 style='border-left:none;width:76pt'>TOTAL BASIC PAY</td>
  <td class=xl66 width=101 style='border-left:none;width:76pt'>TOTAL NIGHTSHIFT</td>
  <td class=xl66 width=79 style='border-left:none;width:59pt'>TOTAL OVERTIME</td>

  <td class=xl66 width=76 style='border-left:none;width:57pt'>MPF_ATTENDANCE</td>
  <td class=xl66 width=76 style='border-left:none;width:57pt'>Seniority</td>
  <td class=xl66 width=76 style='border-left:none;width:57pt'>TRANSPO</td>
  <td class=xl66 width=76 style='border-left:none;width:57pt'>MEAL</td>
  <td class=xl66 width=76 style='border-left:none;width:57pt'>COLA/allowance</td>
  <td class=xl66 width=76 style='border-left:none;width:57pt'>Prior Transpo</td>
  <td class=xl66 width=76 style='border-left:none;width:57pt'>Prior Allowance/CTPA</td>

  <td class=xl66 width=76 style='border-left:none;width:57pt'>STAFF BENEFIT</td>
  <td class=xl66 width=86 style='border-left:none;width:65pt'>GROSS PAY</td>
  <td class=xl66 width=82 style='border-left:none;width:62pt'>TAXABLE INCOME</td>

  <td class=xl66 width=82 style='border-left:none;width:62pt'>GOV'T DEDUCT & UNION DUES</td>
  <td class=xl66 width=82 style='border-left:none;width:62pt'>PH</td>
  <td class=xl66 width=78 style='border-left:none;width:59pt'>SSS</td>

  <td class=xl66 width=72 style='border-left:none;width:54pt'>PAGIBIG</td>
  <td class=xl66 width=107 style='border-left:none;width:80pt'>WITHHOLDING TAX</td>
  <td class=xl66 width=91 style='border-left:none;width:68pt'>HDMF LOAN</td>
  <td class=xl66 width=91 style='border-left:none;width:68pt'>HDMF CALAMITY</td>
  <td class=xl66 width=91 style='border-left:none;width:68pt'>SSS LOAN</td>
  
  <td class=xl66 width=78 style='border-left:none;width:59pt'>ARAP CLEARING</td>
  <td class=xl66 width=83 style='border-left:none;width:62pt'>PAYROLL CLEARING</td>
  <td colspan=17 style='mso-ignore:colspan'></td>
</tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl65></td>
  <td class=xl65></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl81></td>
  <td colspan=28 style='mso-ignore:colspan'></td>
 </tr>
<?php 
  $grand_total  = array();;
  array_multisort($grouped_data, SORT_ASC, SORT_REGULAR); 
?>
<?php foreach ($grouped_data as $cost_center_key => $cost_center_data) { ?>
        <?php 
          ksort($cost_center_data);
          $sub_total     = array();
          $employee_taxable_earnings = 0;
        ?>
        <?php foreach($cost_center_data as $cdata) { ?>
        <?php 
          $employee_id    = $cdata['id'];
          $employee_code  = $cdata['employee_code'];
          $lastname       = $cdata['lastname'];
          $firstname      = $cdata['firstname'];
          $payslip        = $payslips[$employee_id];
          $taxable        = $payslips[$employee_id]['taxable'];
          $witholding_tax = $payslips[$employee_id]['withheld_tax'];
          $pagibig        = $payslips[$employee_id]['pagibig'];
          $sss            = $payslips[$employee_id]['sss'];
          $philhealth     = $payslips[$employee_id]['philhealth'];     

          $obj_earnings = unserialize($payslips[$employee_id]['earnings']);
          $obj_other_earnings = unserialize($payslips[$employee_id]['other_earnings']);
          $obj_labels   = unserialize($payslips[$employee_id]['labels']);

          $payslip_section_earnings  = array();

          $payslipDataBuilder              = new G_Payslip();
          $payslip_section_earnings        = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('earnings', 2);                
          $payslip_section_other_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('other_earnings', 2);      
          $payslip_section_deduction       = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2);

          $leave_attendance     = G_Attendance_Finder::findAttendanceWithLeaveAndPaidByEmployeeAndPeriod($employee_id, $from, $to);

          $sick_and_vacation_leave = 0;
          $birthday_leave          = 0;
          foreach($leave_attendance as $la) {
            if($la->getLeaveId() == 1 || $la->getLeaveId() == 2) {
              $sick_and_vacation_leave++;
            }

            if($la->getLeaveId() == 5) {
              $birthday_leave++;
            }
          }

          $emp_daily_rate           = 0;
          $emp_legal_holiday_amount = 0;
          $emp_salary_type = '';
          foreach($obj_labels as $obj) {
            
            if($obj->getVariable() == 'daily_rate') {
              $emp_daily_rate = $obj->getValue();
            }

            if($obj->getVariable() == 'holiday_legal_amount') {
              $emp_legal_holiday_amount += $obj->getValue();
            }

            
            if($obj->getVariable() == 'salary_type') {
              $emp_salary_type = $obj->getValue();
            }            
          }

          foreach($obj_earnings as $te) {
            if($te->getTaxType() == 1) {
              $employee_taxable_earnings += $te->getAmount();
            }
          }             

          foreach($obj_other_earnings as $oe) {
            if($oe->getTaxType() == 1) {
              $employee_taxable_earnings += $oe->getAmount();
            }
          }  

          /* Earnings / Benefits */

          $earnings_benefits_seniority_total = 0;
          $earnings_benefits_seniority = array('seniority_allowance', 'seniority_pay');
          foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
            foreach( $earnings_benefits_seniority as $key ){
              if( stripos($earning_key, $key) !== false ){
                $earnings_benefits_seniority_total += $payslip_section_other_earnings[$earning_key]['value'];
              }
            }
          }

          $earnings_benefits_transpo_total = 0;
          $earnings_benefits_transpo = array('transportation_allowance');
          foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
            foreach( $earnings_benefits_transpo as $key ){
              if( stripos($earning_key, $key) !== false ){
                $earnings_benefits_transpo_total += $payslip_section_other_earnings[$earning_key]['value'];
              }
            }
          }  

          $earnings_benefits_meal_total = 0;
          $earnings_benefits_meal = array('meal');
          foreach($payslip_section_other_earnings as $earning_key => $e_deduction) {
            foreach( $earnings_benefits_meal as $key ){
              if( stripos($earning_key, $key) !== false ){
                $earnings_benefits_meal_total += $payslip_section_other_earnings[$earning_key]['value'];
              }
            }
          }     

          /* Earnings / Benefits - End */

          $other_earnings = 0;
          foreach($payslip_section_other_earnings as $o_key => $o_data) {
            if($o_data['value'] != '' && $o_data['value'] > 0) {
              $other_earnings += $o_data['value'];
            }
          }

          $total_govt_deductions_unions = 0;
          $total_govt_deductions_unions = $pagibig + $sss + $philhealth;

          $staff_benefits = $other_earnings;
          $slvl = ($sick_and_vacation_leave * $emp_daily_rate);
          $bil  = ($birthday_leave * $emp_daily_rate); 

          $overtime_amount     = $payslip_section_earnings['total_regular_ot_amount']['value'] + $payslip_section_earnings['total_regular_ns_ot_amount']['value'] + $payslip_section_earnings['total_legal_ot_amount']['value'] + $payslip_section_earnings['total_legal_ns_ot_amount']['value'] + $payslip_section_earnings['total_special_ns_ot_amount']['value'] + $payslip_section_earnings['total_special_ot_amount']['value'] + $payslip_section_earnings['total_rest_day_ot']['value'] + $payslip_section_earnings['total_rest_day_special_ot']['value'] + $payslip_section_earnings['total_rest_day_special']['value'] + $payslip_section_earnings['total_rest_day_legal_ot']['value'] + $total_rd_amount + $payslip_section_earnings['total_rest_day_ns_ot']['value'] + $payslip_section_earnings['total_rest_day_legal']['value'] + $payslip_section_earnings['total_rest_day_special_ns_ot']['value'];
          $basic_pay           = $payslip_section_earnings['basic_pay']['value'];
          $nightshift          = $payslip_section_earnings['total_regular_ns_amount']['value'] + $payslip_section_earnings['total_special_ns_amount']['value'] + $payslip_section_earnings['total_legal_ns_amount']['value'] + $payslip_section_earnings['total_rest_day_ns']['value'] + $payslip_section_earnings['total_rest_day_special_ns']['value'];
          $holiday_legal       = $payslip_section_earnings['total_legal_amount']['value'];

          $gross_pay           = $basic_pay + $overtime_amount + $staff_benefits;

          $hdmf_loan           = $payslip_section_deduction['pagibig_loan']['value'] + $payslip_section_deduction['pagibig_salary_loan']['value'];
          $hdmf_calamity       = $payslip_section_deduction['pagibig_calamity_loan']['value'];
          $sss_loan            = $payslip_section_deduction['sss_loan']['value'] + $payslip_section_deduction['sss_salary_loan']['value'] + $payslip_section_deduction['sss_calamity_loan']['value'];

          $sub_total['slvl']            += $slvl;
          $sub_total['legal_holiday']   += $emp_legal_holiday_amount;
          $sub_total['bil']             += $bil;

          $sub_total['seniority']       += $earnings_benefits_seniority_total;
          $sub_total['tranpo']          += $earnings_benefits_transpo_total;
          $sub_total['meal']            += $earnings_benefits_meal_total;

          $sub_total['basic_pay']       += $basic_pay;
          $sub_total['nightshift']      += $nightshift;
          $sub_total['overtime_amount'] += $overtime_amount;
          $sub_total['gross_pay']       += $gross_pay;
          $sub_total['taxable_earnings']+= $employee_taxable_earnings;
          $sub_total['staff_benefits']  += $staff_benefits;
          $sub_total['witholding_tax']  += $witholding_tax;
          $sub_total['pagibig']         += $pagibig;
          $sub_total['philhealth']      += $philhealth;
          $sub_total['sss']             += $sss;
          $sub_total['total_govt_deductions_unions'] += $total_govt_deductions_unions;

          $sub_total['hdmf_loan']     += $hdmf_loan;
          $sub_total['hdmf_calamity'] += $hdmf_calamity;
          $sub_total['sss_loan']      += $sss_loan;

          $salary_type = '';
          if($emp_salary_type == 'Monthly') {
            $salary_type = 'M';
          } else if($emp_salary_type == 'Daily') {
            $salary_type = 'D';
          }

        ?>
                <tr height=20 style='height:15.0pt'>
                  <td height=20 class=xl72 style='height:15.0pt'>&nbsp;<?php echo $cdata['hired_date']; ?></td>
                  <td class=xl73 style='border-left:none'>&nbsp;<?php echo $cdata['employment_status']; ?></td>
                  <td class=xl73 style='border-left:none'><?php echo $cdata['cost_center']; ?></td>
                  <td class=xl73 style='border-left:none'>&nbsp;<?php echo $lastname; ?>, <?php echo $firstname; ?></td>
                  <td class=xl72 style='border-left:none; text-align: center;'>&nbsp;<?php echo $salary_type; ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp;<?php echo number_format($slvl,2, '.' , ',' ); ?></td>

                  <td class=xl77 style='border-left:none'>&nbsp;<?php echo number_format($emp_legal_holiday_amount,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp;<?php echo number_format($bil,2, '.' , ',' ); ?></td>

                  <!-- <td class=xl77 style='border-left:none'>-</td>
                  <td class=xl77 style='border-left:none'>-</td> -->

                  <td class=xl77 style='border-left:none'>-</td>
                  <td class=xl77 style='border-left:none'>-</td>

                  <td class=xl79 style='border-left:none'>-</td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($basic_pay,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($nightshift,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($overtime_amount,2, '.' , ',' ); ?></td>

                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format(0,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($earnings_benefits_seniority_total,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($earnings_benefits_transpo_total,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($earnings_benefits_meal_total,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format(0,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format(0,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format(0,2, '.' , ',' ); ?></td>

                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($staff_benefits,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($gross_pay,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($employee_taxable_earnings,2, '.' , ',' ); ?></td>

                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($total_govt_deductions_unions,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($philhealth,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($sss,2, '.' , ',' ); ?></td>

                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($pagibig,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($witholding_tax,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($hdmf_loan,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($hdmf_calamity,2, '.' , ',' ); ?></td>
                  <td class=xl77 style='border-left:none'>&nbsp; <?php echo number_format($sss_loan,2, '.' , ',' ); ?></td>
                  
                  <td class=xl77 style='border-left:none'>-</td>
                  <td class=xl77 style='border-left:none'>-</td>
                  <td colspan=17 style='mso-ignore:colspan'></td>
                </tr>
      <?php } ?>

        <tr height=20 style='height:15.0pt'>
          <td height=20 colspan="3" class=xl74 style='height:15.0pt;border-top:none'>&nbsp; <strong><?php echo $cost_center_key; ?> Total: </strong></td>
          <!-- <td class=xl75 style='border-top:none;border-left:none'>Total:</td> -->
          <td class=xl76 style='border-top:none;border-left:none'>&nbsp;</td>
          <td class=xl74 style='border-top:none;border-left:none'>&nbsp;</td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['slvl'],2, '.' , ',' ); ?></strong></td>

          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['legal_holiday'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['bil'],2, '.' , ',' ); ?></strong></td>

          <!-- <td class=xl78 style='border-top:none;border-left:none'>--</td>
          <td class=xl78 style='border-top:none;border-left:none'>--</td> -->

          <td class=xl78 style='border-top:none;border-left:none'>--</td>
          <td class=xl78 style='border-top:none;border-left:none'>--</td>

          <td class=xl80 style='border-top:none;border-left:none'>&nbsp;</td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['basic_pay'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['nightshift'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['overtime_amount'],2, '.' , ',' ); ?></strong></td>

          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong>-</strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['seniority'],2, '.' , ',' ) ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['tranpo'],2, '.' , ',' ) ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['meal'],2, '.' , ',' ) ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong>-</strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong>-</strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong>-</strong></td>

          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['staff_benefits'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['gross_pay'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['taxable_earnings'],2, '.' , ',' ); ?></strong></td>

          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['total_govt_deductions_unions'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['philhealth'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['sss'],2, '.' , ',' ); ?></td>

          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['pagibig'],2, '.' , ',' ); ?></strong> </td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['witholding_tax'],2, '.' , ',' ); ?></strong></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['hdmf_loan'],2, '.' , ',' ); ?></td>
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['hdmf_calamity'],2, '.' , ',' ); ?></td>  
          <td class=xl78 style='border-top:none;border-left:none'>&nbsp; <strong><?php echo number_format($sub_total['sss_loan'],2, '.' , ',' ); ?></td>  
        
          <td class=xl78 style='border-top:none;border-left:none'>--</td>
          <td class=xl78 style='border-top:none;border-left:none'>--</td>
          <td colspan=17 style='mso-ignore:colspan'></td>
        </tr>  

        <?php 
          $grand_total['slvl']            += $sub_total['slvl'];
          $grand_total['legal_holiday']   += $sub_total['legal_holiday'];
          $grand_total['bil']             += $sub_total['bil'];
          $grand_total['basic_pay']       += $sub_total['basic_pay'];
          $grand_total['nightshift']      += $sub_total['nightshift'];
          $grand_total['overtime_amount'] += $sub_total['overtime_amount'];

          $grand_total['seniority']       += $sub_total['seniority'];
          $grand_total['tranpo']          += $sub_total['tranpo'];
          $grand_total['meal']            += $sub_total['meal'];

          $grand_total['staff_benefits']  += $sub_total['staff_benefits'];
          $grand_total['gross_pay']       += $sub_total['gross_pay'];
          $grand_total['taxable_earnings']+= $sub_total['taxable_earnings'];
          $grand_total['total_govt_deductions_unions'] += $sub_total['total_govt_deductions_unions'];
          $grand_total['pagibig']         += $sub_total['pagibig'];
          $grand_total['witholding_tax']  += $sub_total['witholding_tax'];
          $grand_total['philhealth']      += $sub_total['philhealth'];
          $grand_total['sss']             += $sub_total['sss'];

          $grand_total['hdmf_loan']       += $sub_total['hdmf_loan'];
          $grand_total['hdmf_calamity']   += $sub_total['hdmf_calamity'];
          $grand_total['sss_loan']        += $sub_total['sss_loan'];
        ?>     

<?php } ?>

<tr height=20 style='height:15.0pt'>
  <td height=20 style='height:15.0pt'></td>
  <td class=xl65></td>
  <td class=xl65></td>
  <td colspan=1 style='mso-ignore:colspan'></td>
  <td class=xl81></td>
  <td colspan=28 style='mso-ignore:colspan'></td>
</tr>

<tr height=20 style='height:15.0pt'>
  <td height=20 class=xl82 style='height:15.0pt'>&nbsp;</td>
  <td class=xl82>&nbsp;</td>
  <td class=xl83>Grand Total:</td>
  <td class=xl82>&nbsp;</td>
  <td class=xl82>&nbsp;</td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['slvl'],2, '.' , ',' ); ?></strong></td>

  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['legal_holiday'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['bil'],2, '.' , ',' ); ?></strong></td>

  <!-- <td class=xl82>&nbsp;</td>
  <td class=xl82>&nbsp;</td> -->

  <td class=xl82>&nbsp;</td>
  <td class=xl82>&nbsp;</td>

  <td class=xl84>&nbsp;</td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['basic_pay'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['nightshift'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['overtime_amount'],2, '.' , ',' ); ?></strong></td>

  <td class=xl82>&nbsp; <strong>-</strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['seniority'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['tranpo'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['meal'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong>-</strong></td>
  <td class=xl82>&nbsp; <strong>-</strong></td>
  <td class=xl82>&nbsp; <strong>-</strong></td>

  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['staff_benefits'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['gross_pay'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['taxable_earnings'],2, '.' , ',' ); ?></strong></td>

  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['total_govt_deductions_unions'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['philhealth'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['sss'],2, '.' , ',' ); ?></strong></td>

  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['pagibig'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['witholding_tax'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['hdmf_loan'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['hdmf_calamity'],2, '.' , ',' ); ?></strong></td>
  <td class=xl82>&nbsp; <strong><?php echo number_format($grand_total['sss_loan'],2, '.' , ',' ); ?></strong></td>
  
  <td class=xl82>&nbsp;</td>
  <td class=xl82>&nbsp;</td>
  <td colspan=17 style='mso-ignore:colspan'></td>
</tr>

<![if supportMisalignedColumns]>
<tr height=0 style='display:none'>
  <td width=64 style='width:48pt'></td>
  <td width=85 style='width:64pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=177 style='width:133pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=70 style='width:53pt'></td>
  <td width=101 style='width:76pt'></td>
  <td width=79 style='width:59pt'></td>
  <td width=76 style='width:57pt'></td>
  <td width=86 style='width:65pt'></td>
  <td width=82 style='width:62pt'></td>
  <td width=72 style='width:54pt'></td>
  <td width=107 style='width:80pt'></td>
  <td width=91 style='width:68pt'></td>
  <td width=78 style='width:59pt'></td>
  <td width=78 style='width:59pt'></td>
  <td width=83 style='width:62pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
</tr>
<![endif]>

</table>
<?php
  /*header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=cost_center.xls");
  header("Pragma: no-cache");
  header("Expires: 0");*/
?>
