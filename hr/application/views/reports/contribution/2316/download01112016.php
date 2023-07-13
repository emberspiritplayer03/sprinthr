<?php ob_start();?>
<?php 	
  $counter = 1;
  echo "<body>";
	foreach( $data as $d ){
    
    $annual_excess = 0;
    $total_annual_bonus = $d['13th_month'] + $d['non_taxable_leave_converted'] + $d['bonus'] + $d['service_award'];
    if( $total_annual_bonus > 82000 ){
      $annual_excess = $total_annual_bonus - 82000;
      $total_annual_bonus = 82000;
    }

    //$sum_41 = $d['nd_pay'] + $total_annual_bonus + $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'];
    if( $d['employment_status'] == 'Regular' ){
      $sum_41 = $d['nd_pay'] + $total_annual_bonus + $d['rice_allowance'] + $d['meal_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'];
    }else{
      $sum_41  = $d['basic_pay'] + $d['paid_holiday'] + $d['rotpay'] + $d['nd_pay'] + $total_annual_bonus + $d['rice_allowance'] + $d['transpo_allowance'] + $d['ot_allowance'] + $d['ctpa_sea'] + $d['sss'] + $d['philhealth'] + $d['pagibig'];
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
      $sum_55 = $new_grosspay + $annual_excess + $d['service_award_tax'] + $d['taxable_leave_converted'];
    }else{
      $sum_55 = $d['service_award_tax'] + $d['taxable_leave_converted'];
    } 

    $sum_21 = $sum_41 + $sum_55;
    $sum_22 = $sum_41;
    $sum_23 = $sum_55;
    $sum_28 = $sum_23 - $d['personal_exemption'];
    //$sum_29 = $d['taxwheld'];
    $sum_29 = $d['taxwheld'];
    $hdr_image = dirname(__FILE__) . "/image012.png";    
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

		if( $d['employment_status'] == 'Regular' ){
			include('2316_regular_form.php');
		}else{
			//include('2316_contractual_form.php');
		}
    $counter++;
    
	}
  echo "</body>";
?>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8");
header("Content-Disposition: attachment; filename=" . $filename);  
header("Pragma: no-cache");
header("Expires: 0");
?>

