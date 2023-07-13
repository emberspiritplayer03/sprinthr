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
echo '<table width=100%>';
$limit = 0;
$pages = 0;
// $i = 0;
  foreach ($employees as $e):
    // if ($i == 4) { break; }
        //if ($i > $limit) {
        //    break;
        //}
      $employee_id = $e->getId();
      $employee_code = $e->getEmployeeCode();
      $employee_name = $e->getName();
      $employee_cost_center = $e->getCostCenter();


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
  ?>
  <?php
  $limit++;
    if($limit == 1){
        echo '<tr>';
    }
  ?>
  <td valign="top" style="border:1px solid black; "><div style="max-height:200px;">
    <table width="80" height="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: xx-small;">
        <tr>
            <td>
                <table width="100%" style="font-size: xx-small;">
                    <tr>
                        <td  colspan="4"><div style="text-align: center"><?php echo $company_name;?></div></td>
                    </tr>
                    <tr>
                        <td  colspan="4"><div style="text-align: center"><?php echo $company_address;?></div></td>
                    </tr>
                    <tr>
                        <td  colspan="4"><div style="text-align: center"><strong>PAY SLIP</strong></div></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" style="font-size: xx-small;">
                    <tr>
                        <td   colspan="2">Employee No.: <?php echo $employee_code;?></td>
                        <td   colspan="2">Payroll Period: <?php echo $cutoff_code;?> (<?php echo Tools::convertDateFormat($period_start);?> - <?php echo Tools::convertDateFormat($period_end);?>)</td>
                    </tr>
                    <tr>
                        <td   colspan="2">Employee Name: <?php echo $employee_name;?></td>
                        <td   colspan="2">Salary Rate: <?php echo number_format($salary_rate);?> (<?php echo $salary_type;?>)</td>
                    </tr>
                    <tr>
                        <td   colspan="2">Department: <?php echo $department;?></td>
                        <td   colspan="2">Project Site: <?php echo $employee_cost_center; ?></td>
                    </tr>
                    <tr>
                        <td   colspan="2">Position: <?php echo $position;?></td>
                        <td   colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><br></tr>
        <tr>
            <td>
                <table width="100%" style="font-size: xx-small; border:0px solid black">
                    <tr>
                        <td colspan="3"><strong>Earnings</strong></td>
                        <!-- <td width="50"></td> -->
                        <td rowspan="10">

                            <table width="80%" style="font-size: xx-small; border:0px solid black">
                                <tr><td colspan="3"><strong>Deductions</strong></td></tr>
                                <?php foreach ($deductions as $deduction):?>
                                    <?php
                                    $to_show = true;
                                    if( in_array($deduction->getLabel(), $gov_contri) && $deduction->getAmount() <= 0 ){
                                        $to_show = false;
                                    }
                                    ?>

                                    <?php if( $to_show ){ ?>
                                    <tr>
                                        <td width="80"><?php echo $deduction->getLabel();?></td>
                                        <!-- <td width="50"></td> -->
                                        <td width="50"><?php echo number_format($deduction->getAmount(), 2, '.' , ',' );?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                <?php endforeach;?>
                                <?php foreach ($other_deductions as $other_deduction):?>
                                    <tr>
                                        <td width="80"><?php echo $other_deduction->getLabel();?></td>
                                        <!-- <td width="50"></td> -->
                                        <td width="50"><?php echo number_format($other_deduction->getAmount(), 2, '.' , ',' );?></td>
                                    </tr>
                                <?php endforeach;?>
                                <!--<tr>
                                    <td width="80">Regular Hours2</td>
                                    <td width="20"></td>
                                    <td width="50">P4,212.00</td>
                                </tr>
                                <tr>
                                    <td>Regular Overtime</td>
                                    <td>33</td>
                                    <td>P1,809.84</td>
                                </tr>
                                <tr>
                                    <td >Rest Day</td>
                                    <td>8</td>
                                    <td>P456.30</td>
                                </tr>
                                <tr>
                                    <td>Legal Holiday (worked)</td>
                                    <td>8</td>
                                    <td>P351.00</td>
                                </tr>
                                <tr>
                                    <td>Legal Holiday Pay</td>
                                    <td>8</td>
                                    <td>P351.00</td>
                                </tr>
                                <tr>
                                    <td>Meal allowance</td>
                                    <td></td>
                                    <td>P518.00</td>
                                </tr>
                                <tr>
                                    <td>Productivity Incentive</td>
                                    <td></td>
                                    <td>P280.00</td>
                                </tr>-->
                                <tr>
                                    <td><strong>Total Deductions</strong></td>
                                    <!-- <td></td> -->
                                    <td><strong><?php echo number_format($total_deductions, 2, '.' , ',' );?></strong></td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    <?php foreach ($earnings as $earning):?>
                    <tr>
                        <td width="80"><?php echo $earning->getLabel();?></td>
                        <!-- <td width="50"></td> -->
                        <td width="50"><?php echo number_format($earning->getAmount(), 2, '.' , ',' );?></td>
                    </tr>
                    <?php endforeach;?>
                    <?php foreach ($other_earnings as $other_earning):?>
                        <tr>
                            <td width="80"><?php echo $other_earning->getLabel();?></td>
                            <!-- <td width="50"></td> -->
                            <td width="50"><?php echo number_format($other_earning->getAmount(), 2, '.' , ',' );?></td>
                        </tr>
                    <?php endforeach;?>
                    <tr>
                        <td><strong>Gross Pay</strong></td>
                        <!-- <td></td> -->
                        <td><strong> <?php echo number_format($total_earnings, 2, '.' , ',' );?></strong></td>
                    </tr>
                    <!--
                    <tr>
                        <td>Regular Overtime</td>
                        <td>33</td>
                        <td>P1,809.84</td>
                    </tr>
                    <tr>
                        <td>Rest Day</td>
                        <td>8</td>
                        <td>P456.30</td>
                    </tr>
                    <tr>
                        <td>Legal Holiday (worked)</td>
                        <td>8</td>
                        <td>P351.00</td>
                    </tr>
                    <tr>
                        <td>Legal Holiday Pay</td>
                        <td>8</td>
                        <td>P351.00</td>
                    </tr>
                    <tr>
                        <td>Meal allowance</td>
                        <td></td>
                        <td>P518.00</td>
                    </tr>
                    <tr>
                        <td>Productivity Incentive</td>
                        <td></td>
                        <td>P280.00</td>
                    </tr>
                    <tr>
                        <td>Perfect Attendance</td>
                        <td></td>
                        <td>P500.00</td>
                    </tr>
                    <tr>
                        <td><strong>Gross Pay</strong></td>
                        <td></td>
                        <td><strong>P8,478.14</strong></td>
                    </tr>-->
                </table>
            </td>
        </tr>
        <tr><br></tr>
        <tr>
            <td>
                <table style="font-size: xx-small; border:0px solid black">
                    <tr>
                        <td></td>
                        
                        <td >
                            <table style="font-size: xx-small; border:1px solid black">
                                <tr>
                                    <td colspan="2">NET PAY:</td>
                                    <td><?php echo number_format($net_pay, 2, '.' , ',' );?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Received By: _________________</td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td></td></tr>
    </table>
    
<!-- </div> -->
  </td>
<?php
    if($limit == 2){
        echo '</tr>';
        $pages++;
        if($pages == 2){
            echo '<tr> </tr>' ;
            $pages = 0;
        }
    }
  ?>
    <?php
        if($limit == 2){
            // echo '<p style="page-break-after: always;"></p>';
            $limit = 0;
        }
        
    ?>
  <?php $i++; endforeach; ?>
</table>;
<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payslip_{$cutoff_code}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php //endif; ?>