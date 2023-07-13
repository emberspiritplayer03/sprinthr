<?php ob_start();?>
<?php   
  $counter = 1;
  echo "<body>";
  echo "<table id='mainTable' border=0 cellpadding=0 cellspacing=0 width=718 style='border-collapse:collapse;table-layout:fixed;width:553pt'>";

  foreach( $data as $d ){

    $year_hired_date  = date("Y",strtotime($d['hired_date']));
    $month_hired_date = date("m",strtotime($d['hired_date'])); 
    $day_hired_date   = date("d",strtotime($d['hired_date']));     

    if( $year_hired_date == $year_selected ){
      $e_start_period = "{$month_hired_date}/{$day_hired_date}";             
    }else{
      $e_start_period = "01/01";
    }  

    $e_end_period = '12/31';

    if( $d['endo_date'] != '0000-00-00') {
      $year_hired_date  = date("Y",strtotime($d['endo_date']));
      $month_hired_date = date("m",strtotime($d['endo_date'])); 
      $day_hired_date   = date("d",strtotime($d['endo_date'])); 
      $e_end_period   = "{$month_hired_date}/{$day_hired_date}";   
    }

    if( $d['resignation_date'] != '0000-00-00') {
      $year_hired_date  = date("Y",strtotime($d['resignation_date']));
      $month_hired_date = date("m",strtotime($d['resignation_date'])); 
      $day_hired_date   = date("d",strtotime($d['resignation_date']));

      
      $e_end_period   = "{$month_hired_date}/{$day_hired_date}"; 
    }

    if( $d['terminated_date'] != '0000-00-00') {
      $year_hired_date  = date("Y",strtotime($d['terminated_date']));
      $month_hired_date = date("m",strtotime($d['terminated_date'])); 
      $day_hired_date   = date("d",strtotime($d['terminated_date']));
      $e_end_period   = "{$month_hired_date}/{$day_hired_date}"; 
    }

    $hmo_premium   = 0;
    $annual_excess = 0;

    $thirteenth_month_plus_bonus = $d['13th_month'] + $d['bonus'] + $d['bonus_tax'];

    if( $thirteenth_month_plus_bonus > 90000 ){
      $annual_excess = $thirteenth_month_plus_bonus - 90000;
      $thirteenth_month_plus_bonus = 90000;
    }

    if($d['13th_month'] > 90000 ){
      $custom_taxable_13th_month  = $d['13th_month'] - 90000;
       $custom_non_taxable_13_month = 90000;
    }else{
                $custom_taxable_13th_month = 0;
                $custom_non_taxable_13_month = $d['13th_month'];
   } 

    $total_annual_bonus = $thirteenth_month_plus_bonus + $d['non_taxable_leave_converted'] + $d['service_award'];

    $total_annual_bonus_non_taxable = $custom_non_taxable_13_month + $d['bonus'];
    $total_annual_bonus_taxable = $custom_taxable_13th_month + $d['bonus_tax'];

    $sum_35      = 0;
    $hmo_premium = $d['hmo_premium'];

    if($d['salary_type'] == 'Daily') {
      if($d['daily_rate'] <= $default_minimum_rate) {
        $sum_35      = $d['nd_pay'];  
      }
      
    }

    //$sum_41 = $d['nd_pay'] + $total_annual_bonus + $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'];
    if( $d['employment_status'] == 'Regular' ){
      $sum_41 = $sum_35 + $total_annual_bonus + $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'] + $d['union_dues'];
    }elseif($d['employment_status'] == 'Full Time') {
      $sum_41 = $sum_35 + $total_annual_bonus + $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'] + $d['union_dues'];
    }else{
      //$sum_41 = $d['basic_pay'] + $d['paid_holiday'] + $d['rotpay'] + $sum_35 + $total_annual_bonus + $d['rice_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'] + $d['union_dues'];
      $sum_41 = $sum_35 + $total_annual_bonus + $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'] + $d['union_dues'];
    }

    $is_mimum_wage = false;
    $working_days  = $d['year_working_days'];
    $sv                 = new G_Sprint_Variables('minimum_rate');
    $minimum_rate_value = $sv->getVariableValue();
    if( $d['employment_status'] == 'Regular' ){
      $per_day = ($d['present_salary'] * 12) / $working_days;
    }else{
      $per_day = $d['present_salary'];
    }

    if( $per_day <= $minimum_rate_value ){
      $is_mimum_wage = "X";
    }

    $non_taxable_income = $d['13th_month']+$d['bonus']+$d['non_taxable_leave_converted']+$d['service_award']+$d['rice_allowance']+$d['meal_allowance']+$d['transpo_allowance']+$d['ctpa_sea']+$d['ot_allowance']+$d['sss']+$d['philhealth']+$d['pagibig'];
    $employee_name = $d['lastname'] . ", " . $d['firstname'] . " " . $d['middlename'];
    $employee_name = strtoupper($employee_name);
    $rdo_code     = 57;
    //$new_grosspay = $non_taxable_income + $d['basic_pay'];
    $left_grosspay = $non_taxable_income + $d['basic_pay'];
    //$new_grosspay  = $d['grosspay'] - ($d['sss'] + $d['philhealth'] + $d['pagibig']) - ($d['service_award_tax'] + $d['taxable_leave_converted']);    
    $new_grosspay  = ($d['grosspay'] + $d['taxable_leave_converted'] + $d['service_award_tax']) - $d['service_award_tax'] - $d['sss'] - $d['philhealth'] - $d['pagibig'] - $d['taxable_leave_converted'];     

    if( $d['employment_status'] == 'Regular' ){
      $sum_55 = $new_grosspay + $annual_excess + $d['service_award_tax'] + $d['taxable_leave_converted'] + $hmo_premium;
    }else{
      $sum_55 = $new_grosspay + $annual_excess + $d['service_award_tax'] + $d['taxable_leave_converted'] + $hmo_premium;
    } 

    //echo $new_grosspay . ' + ' . $annual_excess . ' + ' . $d['service_award_tax'] . ' + ' . $d['taxable_leave_converted'] . ' + ' . $hmo_premium;
    //echo '<hr />';

    $sum_21 = $sum_41 + $sum_55;
    $sum_22 = $sum_41;
    $sum_23 = $sum_55;
    $sum_28 = $sum_23 - $d['personal_exemption'];
    //$sum_29 = $d['taxwheld'];

    // new computation

    $temp_basic_non_tax = $d['basic_pay'];
   $non_tax = 250000;
    
   if($temp_basic_non_tax < $non_tax){
   $new_27 =  $temp_basic_non_tax;
   }else{
    $new_27 =  0;
   }

    //bypass new27
   $new_27 = 0;
    // $d['service_award']
    $new_32 = $total_annual_bonus_non_taxable + $d['service_award'];
    
    $new_33 = $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['non_taxable_leave_converted'] + $d['special_transpo'];
    
    $govt_union_total = $d['sss'] + $d['philhealth'] + $d['pagibig'] + $d['union_dues'];



    $new_34 =  $govt_union_total;

    $new_35 = $d['other_deductions_earnings_nontaxable'];

    $new_36_sum_non_taxable = $new_27 + $new_32 + $new_33 + $new_34 + $new_35;




    //old new_grosspay
   $temp_basic_tax =$d['basic_pay'];
   $basic_taxable = 250000;
   // $basic_taxable = number_format( $basic_taxable,2);
   $no_basic_taxalbe = 0;
      
   if($temp_basic_tax < $basic_taxable){
    $new_37 =  $no_basic_taxalbe;
   }else{
   
    // $basic_taxable_salary = $d['basic_pay'] - $basic_taxable;
    $basic_taxable_salary = $d['basic_pay'];
    $new_37 = $basic_taxable_salary;
   }

    //bypass new37
    // $new_37 = $d['basic_pay'] - $new_34;


    $new_42A = $d['position_allowance'];
    $new_42B = $d['taxable_leave_converted'];
    $new_46  = $total_annual_bonus_taxable;
    $new_48  = $d['nd_pay'] + $d['rotpay'] + $d['paid_holiday'];

    $new_37 = $d['grosspay'] - ($new_42A + $new_34 + $new_48);
   
    // var_dump($d['grosspay']);
    // echo "<br>";
    // var_dump($new_42A);
    //  var_dump($new_42B);
    //   var_dump($new_34);
    //    var_dump($new_48);

    // exit();

    $new_49A = $d['service_award_tax'];
    $new_49B = $d['sss_maternity_differential_taxable'];

    $new_50_sum_taxable = $new_37 + $new_42A + $new_42B + $new_46 + $new_48 + $new_49A + $new_49B;
    $new_19 = $new_36_sum_non_taxable + $new_50_sum_taxable;
    // $new_20 = $new_19 - $new_36_sum_non_taxable;
    $new_22 = $d['taxable_compensation_previous_employer']; 
    $new_23 = $new_50_sum_taxable + $new_22;
    $new_20 = $new_36_sum_non_taxable;
    // $new_25A =  $d['withheld_tax'];
    // $new_25A =  $d['tax_withheld_payroll'];
    $new_25A = $d['taxwheld'];
    $new_25B = $d['tax_withheld_previous_employer'];
    $new_24 = $d['taxwheld'];
    $new_26 = $new_25A + $new_25B;
    // new computation
      // echo $d['taxwheld'];

    if($sum_28 <= 0) {
      $sum_29 = 0;  
    } else {
      $sum_29 = $d['taxwheld'];
    }
    
    $sum_52 = $hmo_premium;

    $hdr_image = dirname(__FILE__) . "/image8.png";    
    //$hdr_image = "http://sprinthr/hr/application/views/reports/contribution/2316/image012.png";
    $single_box = '';
    if( $d['civil_status'] == 'Single' ){
      $single_box = 'X';
    }

    $married_box = '';
    if( $d['civil_status'] == 'Married' ){
      $married_box = 'X';
    }

    $month_today = date("m");
    $day_today   = date("d");
    $year_today  = date("Y");



    /*if( $d['employment_status'] == 'Regular' ){
      include('2316_regular_form.php');
    }else{
      include('2316_contractual_form.php');
    }*/

    if( $d['employment_status'] == 'Contractual' ){
      include('2316_contractual_form.php');
    }else{
      include('2316_regular_form.php');
    }

    $counter++;
  }



  
  echo "</table>";
  echo "</body>";
  
  header("Content-type: application/x-msexcel;charset=UTF-8");
  header("Content-Disposition: attachment; filename=" . $filename);  
  header("Pragma: no-cache");
  header("Expires: 0");
?>