<?php ob_start(); ?>
<style type="text/css">
table { font-size:11px;}
table td { border:1px solid #666666;}
</style>
<h1><?php echo $header; ?></h1>

<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>    
    <td bgcolor="#e4f5ff">EmpID</td>
    <td bgcolor="#e4f5ff">Hired Date</td>
    <td bgcolor="#e4f5ff">TIN</td>
    <td bgcolor="#e4f5ff">Employment Status</td>
    <td bgcolor="#e4f5ff">Employee Status</td>
    <td bgcolor="#e4f5ff">Date Resigned</td>
    <td bgcolor="#e4f5ff">Endo Date</td>
    <td bgcolor="#e4f5ff">Date Terminated</td>
    <td bgcolor="#e4f5ff">Department</td>
    <td bgcolor="#e4f5ff">Sections</td>
    <td bgcolor="#e4f5ff">Lastname</td>
    <td bgcolor="#e4f5ff">Firstname</td>
    <td bgcolor="#e4f5ff">Middlename</td>    
    <td bgcolor="#e4f5ff">Basic Pay</td>
    <td bgcolor="#e4f5ff">Absences</td>
    <td bgcolor="#e4f5ff">Undertime</td>
    <td bgcolor="#e4f5ff">Tardiness</td>
    <td bgcolor="#e4f5ff">Adjustment</td>
    <td bgcolor="#e4f5ff">SumOfSSS</td>
    <td bgcolor="#e4f5ff">SumOfMed</td>
    <td bgcolor="#e4f5ff">SumOfPagIbig</td>
    <td bgcolor="#e4f5ff">TAXWHELD</td>
    <td bgcolor="#e4f5ff">13th Taxable</td>
    <td bgcolor="#e4f5ff">13th Non-Taxable</td>
    <!-- <td bgcolor="#e4f5ff">HMO Premium</td> -->
    <!-- New -->
    <td bgcolor="#e4f5ff">SSS Maternity Differential Taxable</td>
    <td bgcolor="#e4f5ff">SSS Maternity Differential Non-Taxable</td>
  <!--   <td bgcolor="#e4f5ff">Taxable Compensation Previous Employer</td>
    <td bgcolor="#e4f5ff">Tax Withheld Previous Employer</td> -->
   <!--  <td bgcolor="#e4f5ff">Other Deduction / Other Earnings Taxable</td> -->
    <td bgcolor="#e4f5ff">Other Compensation</td>
    <!-- New -->
    <td bgcolor="#e4f5ff">Bonus</td>
    <td bgcolor="#e4f5ff">Bonus Tax</td>
    <td bgcolor="#e4f5ff">Service Award Non Tax</td>
    <td bgcolor="#e4f5ff">Service Award Tax</td>
    <td bgcolor="#e4f5ff">Leave Con Non Tax</td>
    <td bgcolor="#e4f5ff">Leave Con Tax in excess of 10</td>
    <td bgcolor="#e4f5ff">Union Dues</td>
    <td bgcolor="#e4f5ff">Rice Allowance</td>
    <td bgcolor="#e4f5ff">Position Allowance</td>
    <td bgcolor="#e4f5ff">Sum Paid Holiday</td>
    <td bgcolor="#e4f5ff">Dependents</td>
    <td bgcolor="#e4f5ff">Civil Status</td>
    <td bgcolor="#e4f5ff">Overtime</td>
    <td bgcolor="#e4f5ff">Sum of ND Pay</td>
    <td bgcolor="#e4f5ff">Meal Allowance</td>
    <td bgcolor="#e4f5ff">OT Allowance</td>
    <td bgcolor="#e4f5ff">CTPA/SEA</td>
    <td bgcolor="#e4f5ff">Other Earnings</td>
    <td bgcolor="#e4f5ff">Transpo Allowance</td>
    <td bgcolor="#e4f5ff">Gasoline Allowance</td>
    <td bgcolor="#e4f5ff">Grosspay</td>    
  </tr>
 <?php          
           
        ?>

<?php 
    $counter_rows = 1;    
    $total = array();
    foreach($data as $d){ 

         if($d['13th_month'] > 90000 ){
                $taxable_13th_month  = $d['13th_month'] - 90000;
                $non_taxable_13_month = 90000;
                // echo "taxable";
                // echo $non_taxable_13_month;
                // echo "<br>";
            }else{
                $taxable_13th_month = 0;
                $non_taxable_13_month = $d['13th_month'];
                // echo "none";
                // $non_taxable_13_month;
                // echo "<br>";
            } 
           
        $total['basicpay']     = $total['basicpay'] + $d['basic_pay'];
        $total['absences']     = $total['absences'] + $d['absences'];
        $total['undertime']    = $total['undertime'] + $d['undertime'];
        $total['tardiness']    = $total['tardiness'] + $d['tardiness'];
        $total['adjustment']   = $total['adjustment'] + $d['adjustment'];
        $total['sss']          = $total['sss'] + $d['sss'];
        $total['philhealth']   = $total['philhealth'] + $d['philhealth'];
        $total['pagibig']      = $total['pagibig'] + $d['pagibig'];
        $total['taxwheld']     = $total['taxwheld'] + $d['taxwheld'];
        $total['taxable_13th_month']   = $total['taxable_13th_month'] + $taxable_13th_month;
        $total['non_taxable_13_month']   = $total['non_taxable_13_month'] + $non_taxable_13_month;
        // $total['hmo_premium']  = $d['hmo_premium'];
          // new
        $total['sss_maternity_differential_taxable']  = $total['sss_maternity_differential_taxable'] + $d['sss_maternity_differential_taxable'];
        $total['sss_maternity_differential_nontaxable']  = $total['sss_maternity_differential_nontaxable'] + $d['sss_maternity_differential_nontaxable'];
        // $total['taxable_compensation_previous_employer']  = $total['taxable_compensation_previous_employer'] + $d['taxable_compensation_previous_employer'];
        //     $total['tax_withheld_previous_employer']  = $total['tax_withheld_previous_employer'] + $d['tax_withheld_previous_employer'];
        // $total['other_deductions_earnings_taxable']  = $total['other_deductions_earnings_taxable'] + $d['other_deductions_earnings_taxable'];
            $total['other_deductions_earnings_nontaxable']  = $total['other_deductions_earnings_nontaxable'] + $d['other_deductions_earnings_nontaxable'];
          // new
        $total['bonus']        = $total['bonus'] + $d['bonus'];
        $total['bonus_tax']    = $total['bonus_tax'] + $d['bonus_tax'];
        $total['service_award']               = $total['service_award'] + $d['service_award'];
        $total['service_award_tax']           = $total['service_award_tax'] + $d['service_award_tax'];
        $total['non_taxable_leave_converted'] = $total['non_taxable_leave_converted'] + $d['non_taxable_leave_converted'];
        $total['taxable_leave_converted']     = $total['taxable_leave_converted'] + $d['taxable_leave_converted'];
        $total['union_dues']                  = $total['union_dues'] + $d['union_dues'];
        $total['rice_allowance']              = $total['rice_allowance'] + $d['rice_allowance'];
        $total['position_allowance']          = $total['position_allowance'] + $d['position_allowance'];
        $total['paid_holiday']                = $total['paid_holiday'] + $d['paid_holiday'];
        $total['number_dependent']            = $total['number_dependent'] + $d['number_dependent'];
        $total['civil_status']                = $total['civil_status'] + $d['civil_status'];
        $total['rotpay']            = $total['rotpay'] + $d['rotpay'];
        $total['nd_pay']            = $total['nd_pay'] + $d['nd_pay'];
        $total['meal_allowance']    = $total['meal_allowance'] + $d['meal_allowance'];
        $total['ot_allowance']      = $total['ot_allowance'] + $d['ot_allowance'];
        $total['ctpa_sea']          = $total['ctpa_sea'] + $d['ctpa_sea'];
        $total['other_earnings']    = $total['other_earnings'] + $d['other_earnings'];
        $total['transpo_allowance'] = $total['transpo_allowance'] + $d['transpo_allowance'];
        // new
        $total['special_transpo'] = $total['special_transpo'] + $d['special_transpo'];
        // new
        // $total['grosspay']          = $total['grosspay'] + ($d['grosspay'] + $d['taxable_leave_converted'] + $d['service_award_tax']);
        $total['grosspay']    = $total['grosspay'] + ($d['grosspay']);
 ?>
       
    <tr>        
        <td style="mso-number-format:'\@';"><?php echo $d['employee_id']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['hired_date']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['tin_number']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employment_status']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['employee_status']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['resignation_date']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['endo_date']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['terminated_date']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['department_name']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['section_name']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['lastname']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['firstname']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['middlename']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['basic_pay'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['absences'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['undertime'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['tardiness'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['adjustment'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['sss'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['philhealth'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['pagibig'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['taxwheld'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($taxable_13th_month,2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($non_taxable_13_month,2); ?></td>
      <!--   <td style="mso-number-format:'\@';"><?php echo number_format($d['hmo_premium'],2); ?></td> -->
      <!-- new -->
         <td style="mso-number-format:'\@';"><?php echo number_format($d['sss_maternity_differential_taxable'],2); ?></td>
         <td style="mso-number-format:'\@';"><?php echo number_format($d['sss_maternity_differential_nontaxable'],2); ?></td>
        <!--  <td style="mso-number-format:'\@';"><?php echo number_format($d['taxable_compensation_previous_employer'],2); ?></td>
         <td style="mso-number-format:'\@';"><?php echo number_format($d['tax_withheld_previous_employer'],2); ?></td> -->
          <!-- <td style="mso-number-format:'\@';"><?php echo number_format($d['other_deductions_earnings_taxable'],2); ?></td> -->
         <td style="mso-number-format:'\@';"><?php echo number_format($d['other_deductions_earnings_nontaxable'],2); ?></td>
      <!-- new -->
        <td style="mso-number-format:'\@';"><?php echo number_format($d['bonus'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['bonus_tax'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['service_award'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['service_award_tax'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['non_taxable_leave_converted'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['taxable_leave_converted'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['union_dues'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['rice_allowance'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['position_allowance'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['paid_holiday'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['number_dependent']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo $d['civil_status']; ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['rotpay'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['nd_pay'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['meal_allowance'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['ot_allowance'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['ctpa_sea'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['other_earnings'],2); ?></td>
       <td style="mso-number-format:'\@';"><?php echo number_format($d['transpo_allowance'],2); ?></td>
        <!-- <td style="mso-number-format:'\@';"><?php //echo number_format($d['grosspay'] + $d['taxable_leave_converted'] + $d['service_award_tax'] + $d['bonus_tax'] + $d['hmo_premium'],2); ?></td> -->
     <td style="mso-number-format:'\@';"><?php echo number_format($d['special_transpo'],2); ?></td>
        <td style="mso-number-format:'\@';"><?php echo number_format($d['grosspay'],2); ?></td>
    </tr>  
             

<?php $counter_rows++; } ?>  
    <tr>
        <td colspan="13"><b>Total</b></td>
        <?php foreach( $total as $key => $value ){ ?>
            <td style="mso-number-format:'\@';text-align: right;"><b><?php echo number_format($value,2); ?></b></td>
        <?php } ?>
    </tr>  
</table>
<?php
header("Content-type: application/x-msexcel"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>
