<?php
ob_start()
?>

  <style type="text/css">
    @import "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER; ?>themes/default/payslip4.css";
    table tr td{
      mso-number-format:General;
      mso-number-format:"\@";/*force text*/
    }
  </style>

  <?php 
    $logo_url = 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . 'images/artnature-logo.png'; 
  ?>

  <?php
    $c = G_Company_Structure_Finder::findByMainParent();
    $company_name = $c->getTitle();

    $ci = G_Company_Info_Finder::findByCompanyStructureId($c->getId());
    $company_address = $ci->getAddress();
  ?>
  <?php $div_num = 1; ?>
  <?php foreach ($employees as $e): ?>

  <?php
      $employee_id   = $e->getId();
      $employee_code = $e->getEmployeeCode();
      $employee_name = $e->getName();
      $employee_tin  = $e->getTinNumber();
      $employee_sss  = $e->getSssNumber();
      $employee_marital_status = $e->getMaritalStatus();

      $d = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
      if ($d) {
        $department = $d->getName();
      }

      $ea = Employee_Factory::getBothArchiveAndNot($employee_id); 



      $dates = Tools::getBetweenDates($from, $to);
      $attendance = G_Attendance_Finder::findByEmployeeAndPeriod($ea, $from, $to);



      $attendances = G_Attendance_Helper::changeArrayKeyToDate($attendance);

      /*    Utilities::displayArray($ea);      */
      /*Utilities::displayArray($employee_id);*/

      $basic_pay    = $payslips[$employee_id]['basic_pay'];
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

      $payslipDataBuilder = new G_Payslip(); 

      //Utilities::displayArray($payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipDataMatex('other_earnings'));

      $payslip_section_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipDataMatex('earnings');
      $payslip_section_other_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipDataMatex('other_earnings');
      $payslip_section_deduction_loan = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipDataMatex('deductions', array('sss_loan','pagibig_loan','emergency_loan'));
      $payslip_section_deduction_contribution = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipDataMatex('deductions', array('sss','pagibig','philhealth'));
      $payslip_section_breakdown = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipDataMatex('breakdown');
     
      $available_sick_leave     =  G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,1);
      $available_vacation_leave =  G_Employee_Leave_Available_Helper::sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id,2);   
      $leave_acquired_on_cutoff  = G_Employee_Leave_Request_Helper::sqlAcquiredLeaveOnCutOff($employee_id,$from,$to);

      $total_rd_amount       = $payslip_section_earnings['total_rest_day']['value'];
      $payslip_yearly_breakdown = G_Payslip_Helper::computeEmployeeYearlyPayslipBreakdownByEndDate($employee_id, $to);

      $total_regular_pay  = $payslip_section_earnings['absent_amount']['value'] + $payslip_section_earnings['basic_pay']['value'] +  $payslip_section_earnings['late_amount']['value'] + $payslip_section_earnings['undertime_amount']['value'];    
      $total_ot_nd_pay = $payslip_section_breakdown['regular_ot_amount']['value'];
      $daily_rate  = $payslip_section_breakdown['daily_rate']['value'];
  ?>    

<table border=0 cellpadding=0 cellspacing=0 width=1258 style='border-collapse:
 collapse;table-layout:fixed;width:943pt'>
 <col width=224 style='mso-width-source:userset;mso-width-alt:8192;width:168pt'>
 <col width=174 style='mso-width-source:userset;mso-width-alt:6363;width:131pt'>
 <col width=43 style='mso-width-source:userset;mso-width-alt:1572;width:32pt'>
 <col width=87 style='mso-width-source:userset;mso-width-alt:3181;width:65pt'>
 <col width=47 style='mso-width-source:userset;mso-width-alt:1718;width:35pt'>
 <col width=43 style='mso-width-source:userset;mso-width-alt:1572;width:32pt'>
 <col width=100 style='mso-width-source:userset;mso-width-alt:3657;width:75pt'>
 <col width=38 style='mso-width-source:userset;mso-width-alt:1389;width:29pt'>
 <col width=43 style='mso-width-source:userset;mso-width-alt:1572;width:32pt'>
 <col width=69 style='mso-width-source:userset;mso-width-alt:2523;width:52pt'>
 <col width=179 style='mso-width-source:userset;mso-width-alt:6546;width:134pt'>
 <col width=40 span=2 style='mso-width-source:userset;mso-width-alt:1462;
 width:30pt'>
 <col width=68 style='mso-width-source:userset;mso-width-alt:2486;width:51pt'>
 <col width=63 style='mso-width-source:userset;mso-width-alt:2304;width:47pt'>
 <tr height=26 style='height:19.5pt'>
  <td height=26 class=xl88 colspan=2 width=398 style='height:19.5pt;mso-ignore:
  colspan;width:299pt'>Matex Planetary Drive International</td>
  <td class=xl66 width=43 style='width:32pt'></td>
  <td class=xl66 width=87 style='width:65pt'></td>
  <td class=xl67 width=47 style='width:35pt'></td>
  <td class=xl67 width=43 style='width:32pt'></td>
  <td class=xl73 width=100 style='width:75pt'>Payslip</td>
  <td class=xl67 width=38 style='width:29pt'></td>
  <td class=xl67 width=43 style='width:32pt'></td>
  <td class=xl67 width=69 style='width:52pt'></td>
  <td class=xl67 width=179 style='width:134pt'></td>
  <td class=xl67 width=40 style='width:30pt'></td>
  <td class=xl67 width=40 style='width:30pt'></td>
  <td class=xl67 width=68 style='width:51pt'></td>
  <td class=xl67 width=63 style='width:47pt'></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl73 style='height:15.0pt'>#16 Mountain Drive, LISP II</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73>ID Number</td>
  <td class=xl73>: <?php echo $employee_code; ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl73 style='height:15.0pt'>Brgy. La Mesa, Calamba, Laguna</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl73 style='height:15.0pt'></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl89 style='height:15.0pt'>Employee</td>
  <td class=xl73 colspan=2 style='mso-ignore:colspan'>: <?php echo $employee_name; ?></td>
  <td class=xl73>Pay Day</td>
  <td class=xl73>: &nbsp;</td>
  <td></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73>Account N<span style='display:none'>o.</span></td>
  <td class=xl73>:</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl89 style='height:15.0pt'>Department</td>
  <td class=xl73>: <?php echo !empty($department) ? $department : ''; ?></td>
  <td class=xl73></td>
  <td class=xl73>Exception</td>
  <td class=xl73>:</td>
  <td></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73>Cut Period<span style='display:none'><span
  style='mso-spacerun:yes'> </span></span></td>
  <td class=xl73 >: <?php echo $cutoff_code;?> (<?php echo Tools::convertDateFormat($period_start);?> - <?php echo Tools::convertDateFormat($period_end);?>)</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl73 style='height:15.0pt'></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl68 style='height:15.0pt'>Earnings</td>
  <td class=xl69>&nbsp;</td>
  <td class=xl70>&nbsp;</td>
  <td class=xl71 colspan=2 style='mso-ignore:colspan'>Other Earnings</td>
  <td class=xl70>&nbsp;</td>
  <td class=xl71>Deductions</td>
  <td class=xl69>&nbsp;</td>
  <td class=xl70>&nbsp;</td>
  <td class=xl71 colspan="1" rowspan="17">
      <table>
      <tr>
          <th>Date</th>
          <th>Rest</th>
          <th>In</th>
          <th>Out</th>
          <th>OT</th>
          <th>Remarks</th>
      </tr>
      <?php foreach ($dates as $date) { 
          $a = $attendances[$date];
          $t = $a->getTimesheet();
          $is_holiday = $a->isholiday();
          $is_present = $a->isPresent();
          $is_paid = $a->isPaid();
       ?>
        <tr>
          <td><?php if (date('D', strtotime($date)) == 'Sun' ) { ?>
            <span style="color:#999999"><?php echo date('D', strtotime($date));?></span>
          <?php } elseif ($is_holiday) { ?>
            <small>LEGAL HOLIDAY</small>
          <?php } else { ?>
            <span><?php echo date('m/d', strtotime($date)); ?></span>
          <?php } ?></td>

          <td></td>

          <td>
          <?php
          if(($t->getTimeIn() == '00:00:00' || $t->getTimeIn() == '')) {
           
          }else {
            echo $t->getTimeIn();
          }
          ?>
          </td>
          <td>
          <?php 
          if(($t->getTimeOut() == '00:00:00' || $t->getTimeOut() == '')) {
            
          }else {
            echo $t->getTimeOut();
          }
          ?>
          </td>
          <td><?php echo Tools::convertHourToTime($t->getTotalOvertimeHours());?></td>
          <td></td>
        </tr>
      <?php }  ?>
      </table>
  </td>
  <td class=xl71></td>
  <td class=xl71></td>
  <td class=xl71></td>
  <td class=xl72></td>
  <td class=xl73></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Basic Pay</td>
  <td class=xl76><?php echo $payslip_section_breakdown['present_days_with_pay']['value']; ?></td>
  <td class=xl77><?php echo number_format($basic_pay,2,".",","); ?></td>
  <td class=xl73>
   <table>
    <?php foreach ($payslip_section_other_earnings as $key => $value) { ?>
          <tr>
            </td><?php echo $value['label']; ?> :</td>
            </td style="text-align:right;"><?php echo $value['value']; ?></td>
          </tr>
    <?php } ?>
    </table>
  </td>
  <td class=xl76></td>
  <td class=xl77>-</td>
  <td class=xl73>Tax Widthheld</td>
  <td class=xl73></td>
  <td class=xl77 style="text-align:right;"><?php echo number_format($witholding_tax,2,".",","); ?></td>
  <td class=xl78></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Absent</td>
  <td class=xl76><?php echo $payslip_section_breakdown['absent_days_without_pay']['value']; ?></td>
  <td class=xl77><?php echo number_format($payslip_section_earnings['absent_amount']['value'],2,".",",");?></td>
  <td class=xl73></td>
  <td class=xl76>-</td>
  <td class=xl77>-</td>
  <td class=xl73>SSS Premium</td>
  <td class=xl73></td>
  <td class=xl77><?php echo number_format($sss,2,".",","); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Tardy</td>
  <td class=xl76><?php echo $payslip_section_breakdown['late_hours']['value']; ?></td>
  <td class=xl77><?php echo number_format($payslip_section_earnings['late_amount']['value'],2,".",",");?></td>
  <td class=xl73></td>
  <td class=xl76>-</td>
  <td class=xl77>-</td>
  <td class=xl73>Philhealth</td>
  <td class=xl73></td>
  <td class=xl77><?php echo number_format($philhealth,2,".",","); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Undertime</td>
  <td class=xl76><?php echo $payslip_section_breakdown['undertime_hours']['value']; ?></td>
  <td class=xl77><?php echo number_format($payslip_section_earnings['undertime_amount']['value'],2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl76>-</td>
  <td class=xl77>-</td>
  <td class=xl73>Pag-ibig</td>
  <td class=xl73></td>
  <td class=xl77><?php echo number_format($pagibig,2,".",","); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Leave</td>
  <?php foreach ($leave_acquired_on_cutoff as $key => $value) { 
      $leave_amount = $daily_rate * $value['leave_days_acquired'];
    ?>
    <td class=xl76><?php echo $value['leave_days_acquired']; ?></td>
    <td class=xl77><?php echo  number_format($leave_amount,2,".",","); ?></td>
  <?php } ?>
  <td class=xl73></td>
  <td class=xl76>-</td>
  <td class=xl77>-</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73 colspan=2 style='mso-ignore:colspan'>SSS Salary Loan</td>
  <td class=xl77><?php echo number_format($payslip_section_deduction_loan['sss_loan']['value'],2,".",","); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl80 colspan=2 style='mso-ignore:colspan'>Total Other Earning</td>
  <td class=xl70>&nbsp;</td>
  <td class=xl73>Pag-ibig Loan</td>
  <td class=xl73></td>
  <td class=xl77><?php echo number_format($payslip_section_deduction_loan['pagibig_loan']['value'],2,".",","); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl81 style='height:15.0pt'>Total Regular Pay</td>
  <td class=xl82>&nbsp;</td>
    <?php foreach ($leave_acquired_on_cutoff as $key => $value) { 
      $leave_amount = $daily_rate * $value['leave_days_acquired'];
      $total_regular_pay_minus_leave = $total_regular_pay - $leave_amount;
    ?>
  <?php } ?>
  <td class=xl83><?php echo number_format($total_regular_pay_minus_leave,2,'.',',')?></td>
  <td class=xl81 colspan=2 style='mso-ignore:colspan'>Taxable Gross Pay</td>
  <td class=xl83 align=right><?php echo number_format($taxable,2,".",","); ?></td>
  <td class=xl73>MPDI Loan</td>
  <td class=xl73></td>
  <td class=xl77><?php echo number_format($payslip_section_deduction_loan['mpdi_loan']['value'],2,".",","); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl84 style='height:15.0pt'>Overtime</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73>Maxicare</td>
  <td class=xl73></td>
  <td class=xl75 style="text-align:right;"><?php echo number_format($payslip_section_deduction['hmo']['value'],2,'.',','); ?></td>
  <td class=xl79></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Regular</td>
  <td class=xl73 align=right><?php echo $payslip_section_breakdown['regular_ot_hours']['value']; ?></td>
  <td class=xl77 style="text-align:left;"><?php echo number_format($payslip_section_breakdown['regular_ot_amount']['value'],2,".",","); ?></td>
  <td class=xl73>CETA</td>
  <td class=xl73></td>
  <td class=xl75 align=right><?php echo number_format($payslip_section_earnings['total_ceta_amount']['value'],2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>SPL/RD</td>
  <td class=xl73><?php echo $payslip_section_breakdown['restday_special_ot_hours']['value']; ?></td>
  <td class=xl75><?php echo number_format($payslip_section_breakdown['restday_special_ot_amount']['value'],2,".",",");?></td>
  <td class=xl73>SEA</td>
  <td class=xl73></td>
  <td class=xl75 align=right><?php echo number_format($payslip_section_earnings['total_sea_amount']['value'],2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>SPL/RD&gt;8</td>
  <td class=xl73> - <?php //echo $payslip_section_breakdown['restday_special_ot_hours']['value']; ?></td>
  <td class=xl75> - <?php //echo number_format($payslip_section_breakdown['restday_special_ot_amount']['value'],2,".",",");?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Legal</td>
  <td class=xl73 align=right> <?php echo $payslip_section_breakdown['holiday_legal_ot_hours']['value']; ?></td>
  <td class=xl75 align=right> <?php echo number_format($payslip_section_breakdown['holiday_legal_ot_amount']['value'],2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Legal&gt;8</td>
  <td class=xl73> - <?php //echo $payslip_section_breakdown['holiday_legal_ot_hours']['value']; ?></td>
  <td class=xl75> - <?php //echo number_format($payslip_section_breakdown['holiday_legal_ot_amount']['value'],2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Legal/RD</td>
  <td class=xl73 align=right><?php echo $payslip_section_breakdown['restday_legal_hours']['value']; ?></td>
  <td class=xl75 align=right><?php echo number_format($payslip_section_breakdown['restday_legal_amount']['value'],2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl69 colspan=2 style='mso-ignore:colspan'>Total Deductions</td>
  <td class=xl70 align=right><?php echo number_format($total_deductions,2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Legal/RD&gt;8</td>
  <td class=xl73> - <?php //echo $payslip_section_breakdown['restday_legal_ot_hours']['value']; ?></td>
  <td class=xl75> - <?php //echo number_format($payslip_section_breakdown['restday_legal_ot_amount']['value'],2,".",","); ?></td>
  <td class=xl81 colspan=2 style='mso-ignore:colspan'>Non-Tax Earnings</td>
  <td class=xl83>&nbsp;</td>
  <td class=xl82 colspan=2 style='mso-ignore:colspan'>Take Home Pay</td>
  <td class=xl83 align=right><?php echo number_format($net_pay,2,".",","); ?></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>Night Diff.</td>
  <td class=xl73><?php echo $payslip_section_breakdown['regular_ns_hours']['value']; ?></td>
  <td class=xl75><?php echo number_format($payslip_section_breakdown['regular_ns_amount']['value'],2,".",","); ?></td>
  <td class=xl73 colspan=2 style='mso-ignore:colspan'>Total Gross Pay</td>
  <td class=xl75 align=right><?php echo number_format($gross_pay,2,".",","); ?></td>
  <td class=xl74 colspan=2 style='mso-ignore:colspan'>YTD Gross Income</td>
  <td class=xl75><?php echo number_format($payslip_yearly_breakdown['y_gross_pay'],2); ?></td>
  <td class=xl80 colspan=2 style='mso-ignore:colspan'>Leave Balance</td>
  <td class=xl69>&nbsp;</td>
  <td class=xl69>&nbsp;</td>
  <td class=xl70>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl74 style='height:15.0pt'>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73></td>
  <td class=xl75>&nbsp;</td>
  <td class=xl73></td>
  <td class=xl73>VL</td>
  <td class=xl73>: <?php echo $available_sick_leave;?></td>
  <td class=xl73>SL</td>
  <td class=xl73>: <?php echo $available_vacation_leave;?></td>
  <td class=xl73></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl85 style='height:15.0pt'>Total OT/ND Pay</td>
  <td class=xl86>&nbsp;</td>
  <td class=xl87> - </td>
  <td class=xl86>&nbsp;</td>
  <td class=xl86>&nbsp;</td>
  <td class=xl87>&nbsp;</td>
  <td class=xl85 colspan=3 style='mso-ignore:colspan;border-right:.5pt solid black'>YTD Widthholding Tax </td>
  <td class=xl85></td>
  <td class=xl86>&nbsp;</td>
  <td class=xl86>&nbsp;</td>
  <td class=xl86>&nbsp;</td>
  <td class=xl87>&nbsp;</td>
  <td class=xl73></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=224 style='width:168pt'></td>
  <td width=174 style='width:131pt'></td>
  <td width=43 style='width:32pt'></td>
  <td width=87 style='width:65pt'></td>
  <td width=47 style='width:35pt'></td>
  <td width=43 style='width:32pt'></td>
  <td width=100 style='width:75pt'></td>
  <td width=38 style='width:29pt'></td>
  <td width=43 style='width:32pt'></td>
  <td width=69 style='width:52pt'></td>
  <td width=179 style='width:134pt'></td>
  <td width=40 style='width:30pt'></td>
  <td width=40 style='width:30pt'></td>
  <td width=68 style='width:51pt'></td>
  <td width=63 style='width:47pt'></td>
 </tr>
 <![endif]>
</table>
<table table border=0 cellpadding=0 cellspacing=0 width=1258 style='border-collapse:
 collapse;table-layout:fixed;width:943pt'>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align:right"><?php echo number_format($payslip_yearly_breakdown['y_withheld_tax'],2); ?></td>
    <td></td>
    <td></td>
  </tr>
</table>
  <?php
      if ($div_num % 3 != 0) {
          echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
      } else {
          echo '';
      }
  ?>

  <?php $div_num++; ?>
<?php endforeach; ?>

<?php
//header("Content-type: application/vnd.ms-excel;"); //tried adding  charset='utf-8' into header

header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payslip_{$cutoff_code}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
