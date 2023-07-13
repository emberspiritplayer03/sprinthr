<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table.tbl-border td { border:1px solid #666666;}
p{font-size: 14px;font-weight: bold;}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td colspan="4"><p><?php echo $header1; ?></p></td></tr>  
</table>
<br />
<table class="tbl-border" width="100%" border="1" cellspacing="0" cellpadding="0">



  <?php

      $all_cutoff = G_Weekly_Cutoff_Period_Finder::findAllByYear($year);
      $period_container = array();

     if($all_cutoff){
            foreach($all_cutoff as $c){

                  $cutoff_character = $c->getCutoffCharacter();
                  $month = $c->getMonth();
                  $period_start = $c->getStartDate();
                  $period_end = $c->getEndDate();

                  $period = $month.'-'.$cutoff_character;

                  $cutoff[$month][] = array('period'=>$period, 'tardiness'=>'Tardiness', 'period_start'=>$period_start, 'period_end'=>$period_end);

            }
     }

     //utilities::displayArray($cutoff); exit();

  ?>



  <tr>       
    <td bgcolor="#CCCCCC"><b>Cutoff Given</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Code</b></td>
    <td bgcolor="#CCCCCC"><b>Employee Name</b></td>
    <td bgcolor="#CCCCCC"><b>Department Name</b></td>
    <td bgcolor="#CCCCCC"><b>Sections</b></td>
     <td bgcolor="#CCCCCC"><b>Project Site</b></td>
     <td bgcolor="#CCCCCC"><b>Hired Date</b></td>
    <td bgcolor="#CCCCCC"><b>Status</b></td>
    <td bgcolor="#CCCCCC"><b>Position</b></td>
    <td bgcolor="#CCCCCC"><b>Current Salary Rate</b></td>
    

     <?php
        foreach($cutoff as $key => $subvalue){

            foreach($subvalue as $value){
      ?>

             <td bgcolor="#CCCCCC"><b><?php echo $value['period']; ?></b></td>
             <td bgcolor="#CCCCCC"><b><?php echo $value['tardiness']; ?></b></td>

      <?php
            } ?>
            <td bgcolor="#3498db"><b>Worked days</b></td>
      <?php
        }
     ?>



     <td bgcolor="#74b9ff"><b>Total worked days</b></td>
    <td bgcolor="#74b9ff"><b>Total basic pay amount</b></td>
    <td bgcolor="#74b9ff"><b>Total absent amount</b></td>    
    <td bgcolor="#74b9ff"><b>13th month</b></td>  
    <td bgcolor="#74b9ff"><b>Percentage</b></td>  
  </tr>



    <?php
      $grand_total_bonus  = 0;
      $grand_total_absent = 0;
      $grand_total_basic  = 0;
      foreach( $data as $key => $subData ){        
        foreach( $subData as $subKey => $d ){
           $lastname  = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           $firstname = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           $department = strtr(utf8_decode($d['department_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
           $section    = strtr(utf8_decode($d['section_name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');          

           $cost_center    = strtr(utf8_decode($d['cost_center']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');   

           $hired_date = date("F j, Y", strtotime($d['hired_date']));    

          $grand_total_bonus  += $d['yearly_bonus'] - $d['tax_bonus_service_award'];
          $grand_total_absent += $d['deducted_amount'];
          $grand_total_basic  += $d['total_basic_pay'];

          $frequency_id = $d['frequency_id'];

          $employee_id = $d['employee_pkid'];
          //$start_date = $year.'-01-01';
          //$end_date = $year.'-12-31';

          $start_date = $d['payroll_start_date'];
          $end_date = $d['cutoff_end_date'];

          $deduction_month_container = array();

          $deduction_month_start = 0;
          $deduction_month_end = 0;

          $deduction_month_start = $d['deduction_month_start'];
          $deduction_month_end = $d['deduction_month_end'];

          if($deduction_month_start > 0 && $deduction_month_end > 0){

             for($x = $deduction_month_start; $x <= $deduction_month_end; $x++){
               array_push($deduction_month_container, $x);
              }

          }
         

          $deducted_amount= 0;
          $percentage = 0;

          $e = G_Employee_finder::findById($employee_id);

          $y_cutoff_start = $d['cutoff_start_date'];
          $y_cutoff_end = $d['cutoff_end_date'];

          $gy_data = G_Yearly_Bonus_Release_Date_Finder::FindByEmployeeIdAndYearAndStartandEndDate($employee_id,$year,$y_cutoff_start,$y_cutoff_end);

          //utilities::displayArray($gy_data);exit();

          if($gy_data){
             $percentage = $gy_data->getPercentage();
             $deducted_amount = $gy_data->getDeductedAmount();
          }

          if($frequency_id == 2){
              $payslips = G_Weekly_Payslip_Finder::findByEmployeeAndDateRange3($e,$start_date,$end_date);
          }
          else{
            $payslips = G_Payslip_Finder::findByEmployeeAndDateRange3($e,$start_date,$end_date);
          }

          //utilities::displayArray($payslips);exit();


          unset($data2);
            
          if($payslips){

              foreach($payslips as $p){

                     $basic_pay = 0;
                     $tardiness = 0;
                     $worked_days = 0;
                     $basic_pay2 = 0;

                    $period_start = $p->getStartDate();
                    $period_end = $p->getEndDate();

                    $basic_pay = $p->getBasicPay();
                    $tardiness = $p->getTardinessAmount();

                    $obj_labels = $p->getLabels();

                    foreach($obj_labels as $obj){

                        if($obj->getVariable() == 'present_days'){
                            $worked_days = $obj->getValue();
                        }

                    }


                    if($frequency_id == 2){
                        $cutoff_info = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
                      }
                      else{
                        $cutoff_info = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end); 
                      }   

                      if($cutoff_info){
                          $cutoff_character = $cutoff_info->getCutoffCharacter();
                          $month = $cutoff_info->getMonth();
                          $month_number = date("m", strtotime($month));

                          $period = $month.'-'.$cutoff_character;
                          //var_dump($month.'-'.$cutoff_character);exit();
                        }



                    $basic_pay2 = $basic_pay;

                    if(!empty($deduction_month_container)){
                       
                        if(in_array($month_number, $deduction_month_container)){
                           
                            $basic_pay = $basic_pay - $tardiness;
                            $bonus = $basic_pay / 12;
                        }
                        else{
                            $bonus = $basic_pay/12;
                            $tardiness = 0;
                        }
                    }
                    else{

                         $bonus = $basic_pay/12;
                         $tardiness = 0;
                    }


                     /*if($deducted_amount > 0){

                      $basic_pay = $basic_pay - $tardiness;
                      $bonus = $basic_pay / 12;

                      }
                      else{
                            $bonus = $basic_pay/12;
                            $tardiness = 0;
                      }*/

                     

                     $data2[$period] = array('period'=>$period,'basic_pay'=>$basic_pay2, 'tardiness' => $tardiness, 'worked_days'=>$worked_days);
                       //insert to array



              }//end of foreach

          }

          //$test = $cutoff;

         //utilities::displayArray($data2);exit();

      ?>
     

       <tr>        
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo $d['cutoff_start_date'] . ' to ' . $d['cutoff_end_date']; ?></td>
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo $d['employee_code']; ?></td>
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo mb_convert_case($firstname . " " . $lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo mb_convert_case($department,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo mb_convert_case($section,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo mb_convert_case($cost_center,  MB_CASE_TITLE, "UTF-8"); ?></td>
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo $hired_date; ?></td>  
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo $d['employee_status']; ?></td>        
        <td style="mso-number-format:'\@';font-weight: bold;"><?php echo $d['position']; ?></td>

         <td style="mso-number-format:'0.00';font-weight: bold;"><?php echo $d['salary_rate']; ?></td>



        <?php

          $total_worked_days = 0;

          foreach($cutoff as $key => $subvalue){

                $worked_days = 0;

               foreach($subvalue as $value){
                      
                      $basic_pay = 0;
                      $tardiness = 0;
                      $worked_days2 = 0;

                      $period = $value['period'];
                      if(array_key_exists($period, $data2)){
                          $basic_pay = $data2[$period]['basic_pay'];
                          $tardiness = $data2[$period]['tardiness'];
                          $worked_days2 = $data2[$period]['worked_days'];
                      }
                      
                      $worked_days += $worked_days2;

                       $subtotal[$period]['total_basic_pay'] += $basic_pay;
                       $subtotal[$period]['total_tardiness'] += $tardiness;

                ?>

                   <td style="mso-number-format:'0.00';"><?php echo $basic_pay; ?></td>
                   <td style="mso-number-format:'0.00';"><?php echo $tardiness; ?></td>

                <?php

              }//end of inner foreach
                
                 $total_worked_days += $worked_days;
                 $subtotal[$key]['worked_days'] += $worked_days;
              ?>
                 <td style="mso-number-format:'0.00';"><?php echo $worked_days; ?></td>
          <?php
          }


        ?>

         <td style="mso-number-format:'0.00';text-align:right;font-weight: bold;"><?php echo $total_worked_days; ?> </td> 
        <td style="mso-number-format:'\@';text-align:right;font-weight: bold;"><?php echo number_format($d['total_basic_pay'],2); ?> </td>        
        <td style="mso-number-format:'\@';text-align:right;font-weight: bold;"><?php echo number_format($d['deducted_amount'],2); ?> </td>        
        <td style="mso-number-format:'\@';text-align:right;font-weight: bold;"><?php echo number_format($d['yearly_bonus'] - $d['tax_bonus_service_award'],2); ?> </td>
        <td style="mso-number-format:'\@';text-align:right;font-weight: bold;"><?php echo $percentage; ?></td>


      </tr> 
      <?php
        }

      }

       //utilities::displayArray($data2);exit();

    ?>
    <tr>
      <td colspan="10" style="text-align: right;"><strong>GRAND TOTAL:</strong></td>

      <?php

           foreach($cutoff as $key => $subvalue){
              foreach($subvalue as $value){
                    $period = $value['period'];

              ?>
                 <td style="mso-number-format:'0.00';text-align:right;"><strong><?php echo $subtotal[$period]['total_basic_pay']; ?></strong></td>
                 <td style="mso-number-format:'0.00';text-align:right;"><strong><?php echo $subtotal[$period]['total_tardiness']; ?></strong></td>
              <?php
              }
              ?>
              <td style="mso-number-format:'0.00';text-align:right;"><strong><?php echo $subtotal[$key]['worked_days']; ?></strong></td>
      <?php
           
                $grandtotal['worked_days'] += $subtotal[$key]['worked_days'];
            }

      ?>
      <td style="mso-number-format:'0.00';text-align:right;"><strong><?php echo $grandtotal['worked_days']; ?></strong></td>
      <td style="mso-number-format:'\@';text-align:right;"><strong><?php echo number_format($grand_total_basic,2);?></strong></td>
      <td style="mso-number-format:'\@';text-align:right;"><strong><?php echo number_format($grand_total_absent,2); ?></strong></td>
      <td style="mso-number-format:'\@';text-align:right;"><strong><?php echo number_format($grand_total_bonus,2);?></strong></td>
    </tr>   


 
</table>
<?php
  header('Content-type: application/ms-excel');
  header("Content-Disposition: attachment; filename=yearly_bonus.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>