<?php
ob_start();
?>
<?php //if (!empty($records)):?>
<style type="text/css">
.font-size {
  font-size: xx-small;
}
</style>

  <?php
//$limit = 4;
//$i = 1;
      $c = G_Company_Structure_Finder::findByMainParent();
      $company_name = $c->getTitle();

      $ci = G_Company_Info_Finder::findByCompanyStructureId($c->getId());
      $company_address = $ci->getAddress();

  foreach ($employees as $e):
        //if ($i > $limit) {
        //    break;
        //}
      $employee_id = $e->getId();
      $employee_code = $e->getEmployeeCode();
      $employee_name = mb_convert_encoding($e->getName(), "HTML-ENTITIES", "UTF-8");
      $employee_cost_center = $e->getCostCenter();

      //project site 
      $project_site_id = $e->getProjectSiteId();

      $project = G_Project_Site::findAllProjectSite($project_site_id);
      if(!empty($project)){

         foreach($project as $key=> $value){
          $employee_cost_center = $value['name'];
          }


      }
     


      $d = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
      if ($d) {
        $department = $d->getName();
      }

      $basic_pay = $payslips[$employee_id]['basic_pay'];
      $month_13th = $payslips[$employee_id]['month_13th'];
      $taxable = $payslips[$employee_id]['taxable'];
      $period_start = $payslips[$employee_id]['period_start'];
      $period_end = $payslips[$employee_id]['period_end'];
      $gross_pay = $payslips[$employee_id]['gross_pay'];
      $total_earnings = $payslips[$employee_id]['total_earnings'];
      $total_deductions = $payslips[$employee_id]['total_deductions'];
      $net_pay = $payslips[$employee_id]['net_pay'];
      $sss = $payslips[$employee_id]['sss'];
      $philhealth = $payslips[$employee_id]['philhealth'];
      $pagibig = $payslips[$employee_id]['pagibig'];
      $witholding_tax = $payslips[$employee_id]['withheld_tax'];

      $obj_labels = unserialize($payslips[$employee_id]['labels']);
      foreach ($obj_labels as $label) {
          $variable = strtolower($label->getVariable());
          $labels[$variable]['label'] = $label->getLabel();
          $labels[$variable]['value'] = $label->getValue();
      }
      $obj_earnings = unserialize($payslips[$employee_id]['earnings']);
      foreach ($obj_earnings as $earning) {
          $variable = strtolower($earning->getVariable());
          $labels[$variable]['label'] = $earning->getLabel();
          $labels[$variable]['value'] = $earning->getAmount();
      }
      $obj_other_earnings = unserialize($payslips[$employee_id]['other_earnings']);
      foreach ($obj_other_earnings as $other_earning) {
          $variable = strtolower($other_earning->getVariable());
          $labels[$variable]['label'] = $other_earning->getLabel();
          $labels[$variable]['value'] = $other_earning->getAmount();
      }
      $obj_deductions = unserialize($payslips[$employee_id]['deductions']);
      foreach ($obj_deductions as $deduction) {
          $variable = strtolower($deduction->getVariable());
          $labels[$variable]['label'] = $deduction->getLabel();
          $labels[$variable]['value'] = $deduction->getAmount();
      }
      $obj_other_deductions = unserialize($payslips[$employee_id]['other_deductions']);
      foreach ($obj_other_deductions as $other_deduction) {
          $variable = strtolower($other_deduction->getVariable());
          $labels[$variable]['label'] = $other_deduction->getLabel();
          $labels[$variable]['value'] = $other_deduction->getAmount();
      }

      $position = $labels['position']['value'];
      $salary_type = $labels['salary_type']['value'];
      $salary_rate = $labels['salary_rate']['value'];

      $earnings = $obj_earnings;
      $other_earnings = $obj_other_earnings;

      $deductions = $obj_deductions;
      $other_deductions = $obj_other_deductions;

       if ($frequency_id == 2) {
          $payslipDataBuilder = new G_Weekly_Payslip();
           $p = G_Weekly_Payslip_Finder::findByEmployeeAndPeriod($e, $period_start, $period_end);
        }

          else if ($frequency_id == 3) {
          $payslipDataBuilder = new G_Monthly_Payslip();
           $p = G_Monthly_Payslip_Finder::findByEmployeeAndPeriod($e, $period_start, $period_end);
        }
        else {
          $payslipDataBuilder = new G_Payslip();
          $p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $period_start, $period_end);
        }

       $payslip_section_loan_balance    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('loan_balance', 2, '', array($from, $to));
       $payslip_section_deduction       = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2);

       $loan_balance_container = array();

       foreach ($payslip_section_loan_balance as $key => $l) {
            $loan_balance_container[$key]['label'] = $l['label'];
            $loan_balance_container[$key]['value'] = $l['value'] - $payslip_section_deduction[$key]['value']; 
       }


        $new_earnings   = $p->getBasicEarnings();
        $new_deductions = $p->getTardinessDeductions();

       
       
      
  ?>
<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid black; font-size: xx-small;">
    <tr>
        <td>
            <table width="100%" style="font-size: xx-small;">
                <tr>
                    <td colspan="7"><div style="text-align: center"><?php echo $company_name;?></div></td>
                </tr>
                <tr>
                    <td colspan="7"><div style="text-align: center"><?php echo $company_address;?></div></td>
                </tr>
                <tr>
                    <td colspan="7"><div style="text-align: center"><strong>PAY SLIP</strong></div></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" style="font-size: xx-small;">
                <tr>
                    <td colspan="3">Employee No.: <?php echo $employee_code;?></td>
                    <td colspan="3">Payroll Period: <?php echo $cutoff_code;?> (<?php echo Tools::convertDateFormat($period_start);?> - <?php echo Tools::convertDateFormat($period_end);?>)</td>
                </tr>
                <tr>
                    <td colspan="3">Employee Name: <?php echo $employee_name;?></td>
                    <td colspan="3">Salary Rate: <?php echo number_format($salary_rate);?> (<?php echo $salary_type;?>)</td>
                </tr>
                <tr>
                    <td colspan="3">Department: <?php echo $department;?></td>
                    <td colspan="3">Project Site: <?php echo $employee_cost_center; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Position: <?php echo $position;?></td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td>
            <table width="100%" style="font-size: xx-small; border:0px solid black">

              <tr>
                <td colspan="3">
                <table width="100%" style="font-size: xx-small; border:0px solid black">
                  <tr><td colspan="3"><strong>Earnings</strong></td></tr>

                   <?php foreach ($new_earnings as $earning):?>
                    <?php
                        $to_show = true;
                        if($earning['amount']==0 && $earning['label'] != 'Basic Pay')
                        $to_show = false;
                    ?>
                    <?php if($to_show){?>

                       <?php 
      
                          $total_hrs  = number_format($earning['total_hours'],2);
                          $total_days = number_format($earning['total_days'],0);

                          if( $total_days > 0 ){
                            $to_string = "({$total_days} days)";
                          }elseif( $total_hrs > 0 ){
                            $to_string = "({$total_hrs} hrs)";
                          }else{
                            $to_string = "";
                          }

                          if( $salary_type == 'Monthly' && $earning['label'] == 'Basic Pay' ){
                            $to_string = "";
                          }
                        ?>

                    <tr>
                        <td width="80"><?php echo $earning['label'];?> &nbsp; <?php echo $to_string;?> </td>
                        <td width="50"></td>
                        <td width="50"><?php echo number_format($earning['amount'], 2, '.' , ',' );?></td>
                    </tr>
                    <?php } ?>
                    <?php endforeach;?>
                    <?php foreach ($other_earnings as $other_earning):?>
                      <?php if($other_earning->getAmount() != 0){?>
                        <tr>
                            <td width="80"><?php echo $other_earning->getLabel();?></td>
                            <td width="50"></td>
                            <td width="50"><?php echo number_format($other_earning->getAmount(), 2, '.' , ',' );?></td>
                        </tr>
                      <?php } ?>
                    <?php endforeach;?>
                    <tr>
                        <td><strong>Gross Pay</strong></td>
                        <td></td>
                        <td><strong> <?php echo number_format($total_earnings, 2, '.' , ',' );?></strong></td>
                    </tr>

                    <!-- loan balance -->
                    <tr>
                      <td colspan="3"><b>Loan Balance:</b></td>
                    </tr>

                    <?php foreach($loan_balance_container as $l){ ?> 
                      <?php if($l['value'] != 0){ ?>
                      <tr>
                        <td><?php echo $l['label']; ?></td>
                        <td></td>
                        <td><?php echo number_format($l['value'],2); ?></td>
                        
                      </tr>


                    <?php }
                     }
                     ?>

                 <!--end loan balance -->



                </table>

              </td>
              <td width="50"></td>
              <td rowspan="">
               <table width="100%" style="font-size: xx-small; border:0px solid black">
                            <tr><td colspan="3"><strong>Deductions</strong></td></tr>
                            <?php foreach ($new_deductions as $deduction):?>
                                <?php
                                  $to_show = true;
                                  if( in_array($deduction['label'], $gov_contri) && $deduction['amount'] <= 0 ){
                                    $to_show = false;
                                  }
                                  if($deduction['amount']==0)
                                  $to_show = true;
                                ?>


                                 <?php 
                                    $total_hrs  = number_format($deduction['total_hours'],2);
                                    //$total_days = number_format($deduction['total_days'],0);
                                    $total_days = $deduction['total_days'];

                                    if( $total_hrs > 0 ){
                                      $to_string = "( {$total_hrs} hrs )";
                                    }elseif( $total_days > 0 ){
                                      $to_string = "( {$total_days} days )";
                                    }else{
                                      $to_string = "";
                                    }
                                  ?>


                                <?php if( $to_show){ ?>
                                <tr>
                                    <td width="80"><?php echo $deduction['label'];?> <?php echo $to_string;?></td>
                                    <td width="50"></td>
                                    <td width="50"><?php echo number_format($deduction['amount'], 2, '.' , ',' );?></td>
                                </tr>
                                <?php } ?>
                                
                            <?php endforeach;?>
                            <?php foreach ($other_deductions as $other_deduction):?>
                               <?php if($other_deduction->getAmount() != 0){?>
                                <tr>
                                    <td width="80"><?php echo $other_deduction->getLabel();?></td>
                                    <td width="50"></td>
                                    <td width="50"><?php echo number_format($other_deduction->getAmount(), 2, '.' , ',' );?></td>
                                </tr>
                              <?php } ?>
                            <?php endforeach;?>
                            <tr>
                                <td><strong>Total Deductions</strong></td>
                                <td></td>
                                <td><strong><?php echo number_format($total_deductions, 2, '.' , ',' );?></strong></td>
                            </tr>
                             <tr>
                              <td>&nbsp;</td>
                            </tr>
                             <tr>
                              <td>&nbsp;</td>
                            </tr>
                             <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                               <table style="font-size: xx-small; border:0px solid black;">
                                  <tr>
                                      <td colspan="3">
                                          <table style="font-size: xx-small; border:1px solid black">
                                              <tr>
                                                  <td colspan="2">NET PAY:</td>
                                                  <td><?php echo number_format($net_pay, 2, '.' , ',' );?></td>
                                              </tr>
                                              <tr>
                                                  <td>Received By: _________________</td>
                                                  <td></td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                              </table>
                            </tr>
                             <tr>
                              <td>&nbsp;</td>
                            </tr>
               </table>
              </td>
              </tr>

            </table>
        </td>
    </tr>
</table>
    <?php //$i++;?>
  <?php endforeach; ?>

<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payslip_{$cutoff_code}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php //endif; ?>