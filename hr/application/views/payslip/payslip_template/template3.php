<?php
ob_start();
?>
  <style type="text/css">
    @import "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER; ?>themes/default/payslip3.css";
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

      $payslipDataBuilder               = new G_Payslip(); 
      $payslip_section_earnings         = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('earnings', 3);
      $payslip_section_deduction_tax    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 3, array('witholding tax'));
      $payslip_section_deduction_contribution = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 3, array('sss','pagibig','philhealth'));
      $payslip_section_deduction_loan   = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 3, array('sss_loan','pagibig_loan','emergency_loan'));
      $payslip_section_breakdown        = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('breakdown', 3);    
      $d = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2, array('witholding tax'));

      $payslip_yearly_breakdown         = G_Payslip_Helper::computeEmployeeYearlyPayslipBreakdown($employee_id);
  ?>    

  <table width="16652.93" border="0" cellpadding="0" cellspacing="0" style='width:12489.70pt;border-collapse:collapse;table-layout:fixed;'>
   <col width="118" class="xl63" style='mso-width-source:userset;mso-width-alt:3776;'/>
   <col width="116" span="2" class="xl63" style='mso-width-source:userset;mso-width-alt:3712;'/>
   <col width="12" class="xl63" style='mso-width-source:userset;mso-width-alt:384;'/>
   <col width="116" span="2" class="xl63" style='mso-width-source:userset;mso-width-alt:3712;'/>
   <col width="9.93" class="xl63" style='mso-width-source:userset;mso-width-alt:317;'/>
   <col width="154" class="xl63" style='mso-width-source:userset;mso-width-alt:4928;'/>
   <col width="152" class="xl63" style='mso-width-source:userset;mso-width-alt:4864;'/>
   <col width="105" class="xl63" style='mso-width-source:userset;mso-width-alt:3360;'/>
   <col width="22" class="xl63" style='mso-width-source:userset;mso-width-alt:704;'/>
   <col width="64" span="244" class="xl63" style='mso-width-source:userset;mso-width-alt:2048;'/>
   <tr height="15" style='height:11.25pt;'>
    <td class="xl63" height="15" width="118" style='height:11.25pt;width:88.50pt;'></td>
    <td class="xl63" width="116" style='width:87.00pt;'></td>
    <td class="xl63" width="116" style='width:87.00pt;'></td>
    <td class="xl63" width="12" style='width:9.00pt;'></td>
    <td class="xl63" width="116" style='width:87.00pt;'></td>
    <td class="xl63" width="116" style='width:87.00pt;'></td>
    <td class="xl63" width="9.93" style='width:7.45pt;'></td>
    <td class="xl63" width="154" style='width:115.50pt;'></td>
    <td class="xl63" width="152" style='width:114.00pt;'></td>
    <td class="xl63" width="105" style='width:78.75pt;'></td>
    <td class="xl63" width="22" style='width:16.50pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
    <td class="xl63" width="64" style='width:48.00pt;'></td>
   </tr>
   <tr height="15" style='height:11.25pt;'>
    <td class="xl63" height="15" colspan="255" style='height:11.25pt;mso-ignore:colspan;'></td>
   </tr>
   <tr height="15" style='height:11.25pt;'>
    <td class="xl63" height="15" colspan="255" style='height:11.25pt;mso-ignore:colspan;'></td>
   </tr>
   <tr height="24" style='height:18.00pt;'>
    <td class="xl126" height="24" colspan="5" style='height:18.00pt;mso-ignore:colspan;'></td>
    <td class="xl63" colspan="250" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl64" height="17" style='height:12.75pt;' x:str>Name</td>
    <td class="xl63" x:str>: <?php echo $employee_name; ?></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl63" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl64" x:str>Civil Status</td>
    <td class="xl63" x:str>: <?php echo $employee_marital_status;; ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl64" height="17" style='height:12.75pt;' x:str>Employee #</td>
    <td class="xl63" x:str>: <?php echo $employee_code; ?></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl63" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl64" x:str>Tax Status</td>
    <td class="xl63" x:str>:</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl64" height="17" style='height:12.75pt;' x:str>Pay Period</td>
    <td class="xl63" x:str>: <?php echo $cutoff_code;?> (<?php echo Tools::convertDateFormat($period_start);?> - <?php echo Tools::convertDateFormat($period_end);?>)</td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl63" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl64" x:str>Tin No.</td>
    <td class="xl63" x:str>: <?php echo $employee_tin; ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl64" height="17" style='height:12.75pt;' x:str>Department</td>
    <td class="xl63" x:str>: <?php echo $department; ?></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl63" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl64" x:str>SSS No.</td>
    <td class="xl63" x:str>: <?php echo $employee_sss; ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl64" height="17" style='height:12.75pt;' x:str>Section</td>
    <td class="xl63" x:str>:</td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl63" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl64" x:str>MBTC Account No.</td>
    <td class="xl63" x:str>:</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl64" height="17" style='height:12.75pt;' x:str>Rate</td>
    <td class="xl63" x:str>: <?php echo number_format($payslip_section_breakdown['monthly_rate']['value'],2); ?> / <?php echo number_format($payslip_section_breakdown['daily_rate']['value'],2, '.' , ','); ?></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl64"></td>
    <td class="xl63" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl64" x:str>Rate Type</td>
    <td class="xl63" x:str>: <?php echo $payslip_section_breakdown['salary_type']['value'];?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="15" style='height:11.25pt;'>
    <td class="xl63" height="15" colspan="255" style='height:11.25pt;mso-ignore:colspan;'></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl65" height="20" colspan="6" style='height:15.00pt;border-right:none;border-bottom:.5pt solid windowtext;' x:str>EARNINGS</td>
    <td class="xl127"></td>
    <td class="xl67" colspan="2" style='border-right:.5pt solid windowtext;border-bottom:.5pt solid windowtext;' x:str>DEDUCTIONS</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="21.33" style='height:16.00pt;mso-height-source:userset;mso-height-alt:320;'>
    <td class="xl70" height="21.33" style='height:16.00pt;'></td>
    <td class="xl128" colspan="2" style='border-right:none;border-bottom:2.0pt double windowtext;' x:str>TAXABLE</td>
    <td class="xl129"></td>
    <td class="xl128" colspan="2" style='border-right:none;border-bottom:2.0pt double windowtext;' x:str>NON-TAXABLE</td>
    <td class="xl131"></td>
    <td class="xl72"></td>
    <td class="xl174" x:str>AMOUNT</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;'></td>
    <td class="xl132" x:str># of days / hrs</td>
    <td class="xl132" x:str>Amount</td>
    <td class="xl132"></td>
    <td class="xl132" x:str># of days / hrs</td>
    <td class="xl132" x:str>Amount</td>
    <td class="xl132"></td>
    <td class="xl72" x:str>Tax Withheld</td>
    <td class="xl73" align="right" x:num> <?php echo number_format($payslip_section_deduction_tax['witholding tax']['value'],2, '.' , ',' ); ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;'></td>
    <td class="xl133" colspan="4" style='mso-ignore:colspan;'></td>
    <td class="xl71"></td>
    <td class="xl133"></td>
    <td class="xl72"></td>
    <td class="xl175"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Regular Days</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_breakdown['present_days_with_pay']['value'], 2, '.' , ',' )?></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_earnings['basic_pay']['value'],2, '.' , ',' );?></td>
    <td class="xl134"></td>
    <td class="xl135" x:str>CONTRIBUTION</td>
    <td class="xl175"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>VL</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl72" x:str>SSS</td>
    <td class="xl175" align="right" x:num><?php echo $payslip_section_deduction_contribution['sss']['value']; ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>SL</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl72" x:str>PHILHEALTH</td>
    <td class="xl73" align="right" x:num><?php echo $payslip_section_deduction_contribution['philhealth']['value'];?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>OL / Paternity Leave</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl72" x:str>PAGIBIG</td>
    <td class="xl73" align="right" x:num><?php echo number_format($payslip_section_deduction_contribution['pagibig']['value'],2, '.' , ',' ); ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Absences</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_breakdown['absent_days_without_pay']['value'],2, '.' , ','); ?></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_earnings['absent_amount']['value'],2, '.' , ',' )?></td>
    <td class="xl63"></td>
    <td class="xl136" x:str>LOANS</td>
    <td class="xl73"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Lates</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_breakdown['late_hours']['value'],2, '.' , ',' );?></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_earnings['late_amount']['value'],2, '.' , ',' )?></td>
    <td class="xl133"></td>
    <td class="xl72" x:str>SSS (Calamity)</td>
    <td class="xl73" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <?php
      $overtime = $payslip_section_earnings['total_regular_ot_amount']['value'] + $payslip_section_earnings['total_special_amount']['value'] + $payslip_section_earnings['total_special_ot_amount']['value'] + $payslip_section_earnings['total_rest_day_ot']['value'];

      $overtime_hours = $payslip_section_breakdown['regular_ot_hours']['value'] + $payslip_section_breakdown['regular_ns_ot_hours']['value'] + $payslip_section_breakdown['restday_ot_hours']['value'] + $payslip_section_breakdown['restday_ns_ot_hours']['value'] + $payslip_section_breakdown['restday_special_ot_hours']['value'] + $payslip_section_breakdown['restday_special_ns_ot_hours']['value'] + $payslip_section_breakdown['restday_legal_ot_hours']['value'] + $payslip_section_breakdown['restday_legal_ns_ot_hours']['value'] + $payslip_section_breakdown['holiday_special_ot_hours']['value'] + $payslip_section_breakdown['holiday_special_ns_ot_hours']['value'] + $payslip_section_breakdown['holiday_legal_ot_hours']['value'] + $payslip_section_breakdown['holiday_legal_ns_ot_hours']['value'];
   ?>

   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Overtime</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num><?php echo number_format($overtime_hours,2, '.' , ',' ); ?></td>
    <td class="xl133" align="right" x:num><?php echo number_format($overtime,2, '.' , ',' ); ?></td>
    <td class="xl133"></td>
    <td class="xl72" x:str>SSS (Salary)</td>
    <td class="xl73" align="right" x:num><?php echo number_format($payslip_section_deduction_loan['sss_loan']['value'],2, '.' , ',' ); ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Night Shift Differencial</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl72" x:str>PAG-IBIG (Calamity)</td>
    <td class="xl73" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>13th Month Pay</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num><?php echo number_format($month_13th,2, '.' , ',' ); ?></td>
    <td class="xl133"></td>
    <td class="xl72" x:str>PAG-IBIG (Salary)</td>
    <td class="xl73" align="right" x:num><?php echo number_format($payslip_section_deduction_loan['pagibig_loan']['value'],2, '.' , ',' ); ?></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Allowances</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl72" x:str>Emergency Loan</td>
    <td class="xl73" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>CPTA</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_breakdown['ceta_days_with_pay']['value'],2, '.' , ',' )?></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_earnings['total_ceta_amount']['value'],2, '.' , ',' ); ?></td>
    <td class="xl63"></td>
    <td class="xl136" x:str>OTHERS</td>
    <td class="xl73"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>SEA</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_breakdown['sea_days_with_pay']['value'],2, '.' , ',' )?></td>
    <td class="xl133" align="right" x:num><?php echo number_format($payslip_section_earnings['total_sea_amount']['value'],2, '.' , ',' )?></td>
    <td class="xl63"></td>
    <td class="xl137" x:str>Telephone Charges</td>
    <td class="xl73" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>OT Meal</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl63"></td>
    <td class="xl137"></td>
    <td class="xl73"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>Adjustment</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133"></td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl133" align="right" x:num>0</td>
    <td class="xl63"></td>
    <td class="xl137"></td>
    <td class="xl73"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;'></td>
    <td class="xl133" colspan="4" style='mso-ignore:colspan;'></td>
    <td class="xl71"></td>
    <td class="xl133"></td>
    <td class="xl72"></td>
    <td class="xl73"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl138" height="17" style='height:12.75pt;' x:str>TOTAL</td>
    <td class="xl139"></td>
    <td class="xl139"></td>
    <td class="xl139"></td>
    <td class="xl139"></td>
    <td class="xl140" align="right" x:num><?php echo number_format($gross_pay,2, '.' , ',' ); ?></td>
    <td class="xl141"></td>
    <td class="xl142"></td>
    <td class="xl176"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl75" height="17" style='height:12.75pt;'></td>
    <td class="xl143" colspan="4" style='mso-ignore:colspan;'></td>
    <td class="xl71"></td>
    <td class="xl144"></td>
    <td class="xl145"></td>
    <td class="xl73"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl146" height="17" colspan="6" style='height:12.75pt;border-right:none;border-bottom:none;'></td>
    <td class="xl149"></td>
    <td class="xl150" x:str>NET PAY</td>
    <td class="xl177" align="right" x:num><?php echo number_format($net_pay,2, '.' , ',' ); ?></td>
    <td class="xl63" colspan="244" style='mso-ignore:colspan;'></td>
    <td colspan="2" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl151" height="17" style='height:12.75pt;'></td>
    <td class="xl152" colspan="4" style='mso-ignore:colspan;'></td>
    <td class="xl153"></td>
    <td class="xl149"></td>
    <td class="xl63"></td>
    <td class="xl178"></td>
    <td class="xl63" colspan="242" style='mso-ignore:colspan;'></td>
    <td colspan="4" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl154" height="17" style='height:12.75pt;' x:str>YTD Earnings</td>
    <td class="xl155" x:str>:</td>
    <td class="xl155" align="right" x:num><?php echo number_format($payslip_yearly_breakdown['y_total_earnings'],2, '.' , ',' ); ?></td>
    <td class="xl155" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl156" colspan="2" style='mso-ignore:colspan;' x:str>SSS Salary Loan Balance</td>
    <td class="xl71"></td>
    <td class="xl179" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>YTD Taxable Income</td>
    <td class="xl155" x:str>:</td>
    <td class="xl158" align="right" x:num><?php echo number_format($payslip_yearly_breakdown['y_taxable'],2, '.' , ',' ); ?></td>
    <td class="xl158" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl159" colspan="3" style='mso-ignore:colspan;' x:str>SSS Calamity Loan Balance</td>
    <td class="xl179" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl70" height="17" style='height:12.75pt;' x:str>YTD Withholding Tax</td>
    <td class="xl155" x:str>:</td>
    <td class="xl158" align="right" x:num><?php echo number_format($payslip_yearly_breakdown['y_withheld_tax'],2, '.' , ',' ); ?></td>
    <td class="xl158" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl159" colspan="3" style='mso-ignore:colspan;' x:str>PAG-IBIG Salary Loan Balance</td>
    <td class="xl179" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl72" height="17" style='height:12.75pt;' x:str>YTD 13th Month Pay</td>
    <td class="xl155" x:str>:</td>
    <td class="xl158" align="right" x:num><?php echo number_format($payslip_yearly_breakdown['y_month_13th'],2, '.' , ',' ); ?></td>
    <td class="xl158" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl159" colspan="3" style='mso-ignore:colspan;' x:str>PAG-IBIG Calamity Loan Balance</td>
    <td class="xl179" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl71" height="17" style='height:12.75pt;' x:str>TAX FREE</td>
    <td class="xl155" x:str>:</td>
    <td class="xl162" align="right" x:num>-</td>
    <td class="xl162" colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl159" colspan="3" style='mso-ignore:colspan;' x:str>EMERGENCY Loan Balance</td>
    <td class="xl179" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl163" height="17" style='height:12.75pt;'></td>
    <td class="xl164" colspan="4" style='mso-ignore:colspan;'></td>
    <td class="xl159" x:str>Used SIL (# of Days)</td>
    <td class="xl160"></td>
    <td class="xl71"></td>
    <td class="xl179" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl63" height="17" colspan="5" style='height:12.75pt;mso-ignore:colspan;'></td>
    <td class="xl165" x:str>Unused SIL (# of Days)</td>
    <td class="xl166"></td>
    <td class="xl71"></td>
    <td class="xl180" align="right" x:num>0</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl63" height="17" colspan="5" style='height:12.75pt;mso-ignore:colspan;'></td>
    <td class="xl167"></td>
    <td class="xl168"></td>
    <td class="xl85"></td>
    <td class="xl181"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" style='height:12.75pt;'></td>
    <td class="xl170" x:str>RECEIPT</td>
    <td class="xl169" colspan="5" style='mso-ignore:colspan;'></td>
    <td class="xl171" x:str>PAY PERIOD</td>
    <td class="xl182" x:str>: <?php echo Tools::convertDateFormat($period_start);?> - <?php echo Tools::convertDateFormat($period_end);?> </td>
    <td class="xl63" colspan="238" style='mso-ignore:colspan;'></td>
    <td colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl63" colspan="6" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" style='height:12.75pt;'></td>
    <td class="xl63"></td>
    <td class="xl169" colspan="6" style='mso-ignore:colspan;'></td>
    <td class="xl182"></td>
    <td class="xl63" colspan="238" style='mso-ignore:colspan;'></td>
    <td colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl63" colspan="6" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" style='height:12.75pt;'></td>
    <td class="xl169" colspan="5" style='mso-ignore:colspan;' x:str>I ACKNOWLEDGE TO HAVE RECEIVED THE AMOUND<span style='mso-spacerun:yes;'>&nbsp; </span>OF (NET PAY) AND HAVE FURTHER</td>
    <td class="xl169"></td>
    <td class="xl63"></td>
    <td class="xl178"></td>
    <td class="xl63" colspan="238" style='mso-ignore:colspan;'></td>
    <td colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl63" colspan="6" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" style='height:12.75pt;'></td>
    <td class="xl169" colspan="2" style='mso-ignore:colspan;' x:str>CLAIMS FOR SERVICES RENDERED</td>
    <td class="xl169"></td>
    <td class="xl169"></td>
    <td class="xl169"></td>
    <td class="xl169"></td>
    <td class="xl172"></td>
    <td class="xl183"></td>
    <td class="xl63" colspan="238" style='mso-ignore:colspan;'></td>
    <td colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl63" colspan="6" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" colspan="7" style='height:12.75pt;mso-ignore:colspan;'></td>
    <td class="xl169" x:str><?php echo $employee_code; ?></td>
    <td class="xl182" x:str>: <?php echo $employee_name; ?></td>
    <td class="xl63" colspan="238" style='mso-ignore:colspan;'></td>
    <td colspan="2" style='mso-ignore:colspan;'></td>
    <td class="xl63" colspan="6" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" colspan="7" style='height:12.75pt;mso-ignore:colspan;'></td>
    <td class="xl169" x:str>HRAD</td>
    <td class="xl182" x:str>: Administration</td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl169" height="17" colspan="8" style='height:12.75pt;mso-ignore:colspan;'></td>
    <td class="xl182"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
   <tr height="17" style='height:12.75pt;'>
    <td class="xl173" height="17" colspan="8" style='height:12.75pt;mso-ignore:colspan;'></td>
    <td class="xl184"></td>
    <td class="xl63" colspan="246" style='mso-ignore:colspan;'></td>
   </tr>
  </table>


<?php endforeach; ?>

<?php
//header("Content-type: application/vnd.ms-excel;"); //tried adding  charset='utf-8' into header

header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payslip_{$cutoff_code}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
