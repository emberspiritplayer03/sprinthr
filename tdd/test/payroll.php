<?php
error_reporting(0);

define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

class TestTimesheetErrors extends UnitTestCase {

    function cutoffDetails() {

        $details['employee_code']     = 7983; //5476;
        $details['cutoff_start_date'] = '2018-02-26';
        $details['cutoff_end_date']   = '2018-03-10';

        $details['month']             = 03;
        $details['cutoff_number']     = 1;
        $details['year']              = 2018;

        return $details;
    }

    function expectedPayslipOutput() {
        /* Earnings */
        $output['basic_pay']                    = 6860;
        $output['total_regular_ot_amount']      = 2671.13;
        $output['total_regular_ns_ot_amount']   = 131.50;
        $output['total_regular_ns_amount']      = 52.61;
        $output['total_legal_amount']           = 1052.1472392638;
        $output['total_legal_ot_amount']        = 256.46;
        $output['total_legal_ns_amount']        = 157.82;
        $output['total_legal_ns_ot_amount']     = 75.23;
        $output['total_ceta_amount']            = 0.00;
        $output['total_sea_amount']             = 0.00;

        /* Other Earnings */
        $output['POSITION ALLOWANCE :500.00']   = 250;
        $output['Meal Allowance']               = 330;
        $output['Transpo Allowance']            = 330;
        $output['Rice Allowance With 4 Absents']= 650;
        $output['OT Allowance']                 = 180;
        $output['Rice Allowance With 1 Absent'] = 700;
        $output['13th Month Bonus']             = "";

        /* Deductions */
        $output['late_amount']      = 65.76;
        $output['undertime_amount'] = 1249.27;
        $output['absent_amount']    = 0;
        $output['sss']              = 581.30;
        $output['pagibig']          = 0.00;
        $output['philhealth']       = 188.65;
        $output['witholding tax']   = 0.00;

        /* SUMMARY */
        $output['total_earnings']   = 11452.63;
        $output['total_deductions'] = 2084.97;
        $output['net_pay']          = 9367.70;

        /* Other Deductions */
        $output['tax_bonus_service_award']  = 0;

        return $output;
    }      

    function testPayrollComputation() {
        $is_generate     = true;
        $cutoff_details  = $this->cutoffDetails();
        $expected_output = $this->expectedPayslipOutput();

        echo "<br /><b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Payroll Computation</b><br />";
        echo " <b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Cutoff: ". $cutoff_details['cutoff_start_date'] ." to " . $cutoff_details['cutoff_end_date'] . " </b> ";
        echo  "<br />Note: red with error<hr />";

        $employee_code = $cutoff_details['employee_code'];
        $e = G_Employee_Finder::findByEmployeeCode($employee_code);

        if($e) {
            $month              = $cutoff_details['month'];
            $cutoff_number      = $cutoff_details['cutoff_number'];
            $year               = $cutoff_details['year'];
            $selected_employee  = $e->getId();

            if ($year == '') {
                $year = Tools::getGmtDate('Y');
            } 

            $additional_qry  = "";
            
            $c = new G_Company;
            $c->setFilteredEmployeeId($selected_employee);
            $c->setAdditionalQuery($additional_qry);

            if($is_generate) {
                $payslips = $c->generatePayslip($month, $cutoff_number, $year);
            } else {
                $payslips = G_Payslip_Finder::findByEmployeeIdAndCutoffPeriod($selected_employee, $cutoff_details['cutoff_start_date'], $cutoff_details['cutoff_end_date']);   
            }
            
            /*echo '<pre>';
            print_r($payslips);
            echo '</pre>';*/

            if($payslips) {
                //echo "Payroll has been successfully generated.<hr />";
                if($is_generate) {
                    $payslip = $payslips[0];
                    $employee_earnings        = $payslip->getBasicEarnings();
                } else {
                    $payslip = $payslips;
                    $employee_earnings      = $payslip->getEarnings();
                }
                
                $employee_other_earnings  = $payslip->getOtherEarnings();
                $employee_deductons       = $payslip->getDeductions();
                $employee_other_deductons = $payslip->getOtherDeductions();
                $employee_labels          = $payslip->getLabels();

                $labels_array = array();
                foreach($employee_labels as $labl)  {
                    if($labl->getVariable() == 'monthly_rate') {
                        $labels_array['monthly_rate'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'regular_ot_hours') {
                        $labels_array['regular_ot_hours'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'hourly_rate') {
                        $labels_array['hourly_rate'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'regular_ns_ot_hours') {
                        $labels_array['regular_ns_ot_hours'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'present_days_with_pay') {
                        $labels_array['present_days_with_pay'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'late_hours') {
                        $labels_array['late_hours'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'hourly_rate') {
                        $labels_array['hourly_rate'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'undertime_hours') {
                        $labels_array['undertime_hours'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'absent_days_without_pay') {
                        $labels_array['absent_days_without_pay'] = $labl->getValue();
                    }elseif($labl->getVariable() == 'daily_rate') {
                        $labels_array['daily_rate'] = $labl->getValue();
                    }
                }

                echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Earnings: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
                echo "
                            <table width='100%'' class=''>  
                              <tr style='background-color:#dad3d3'>
                                <td>&nbsp;</td>
                                <td><strong>Earnings</strong></td>
                                <td><strong>Days/Hours</strong></td>
                                <td><strong>Actual</strong></td>
                                <td><strong>Expected</strong></td>
                                <td><strong>Computation</strong></td>
                              </tr>
                    ";  

                //echo '<pre>';
                //print_r($employee_labels);
                //print_r($employee_deductons);
                //echo '</pre>';

                foreach($employee_earnings as $ee_key => $eed) {

                    if($eed['amount'] != '') {
                        $label                = $eed["label"];
                        $days_horus           = isset($eed['total_days']) ? $eed['total_days'] . ' day/s' : $eed['total_hours'] . ' hr/s';
                        $actual_computation   = preg_replace('/\s+/', ' ', $eed['amount']); 
                        $expected_computation = preg_replace('/\s+/', ' ', $expected_output[$ee_key]); 

                        $computations = ""; 
                        if($ee_key == 'basic_pay') {
                             $computations = "Monthly : Monthly Rate (" . $labels_array['monthly_rate'] . ") / " . 2 . "<br />"; 
                           $computations .= "Daily Rate: (Present Days With Pay * Daily Rates";
                        }elseif($ee_key == 'total_regular_ot_amount') {
                            $compute_total = ($labels_array['regular_ot_hours'] * $labels_array['hourly_rate'] * 1.25);
                            $computations = 'Total Regular OT ('. $labels_array['regular_ot_hours'] . ") * Hourly Rate (" . $labels_array['hourly_rate'] . ") * " . 1.25 . " = " . $compute_total;
                        }elseif($ee_key == 'total_regular_ns_ot_amount') {
                            $computations = "Hourly Rate (" . $labels_array['hourly_rate'] . ") * 0.1) * (125 / 100) * Nightshift OT Hrs (" . $labels_array['regular_ns_ot_hours'] . ") = ";
                            $computations .= ($labels_array['hourly_rate'] * 0.1) * (125 / 100) * $labels_array['regular_ns_ot_hours'];
                        }                    

                        $error_color = "";

                        if(!empty($expected_computation) && !empty($actual_computation)) {
                            if($expected_computation != $actual_computation) {
                                $error_color = "#f04d4d";
                            }
                        } 

                        echo '
                                <tr style="background-color: #f9f7f7;">
                                    <td>&nbsp;</td>
                                    <td style="background-color: '. $error_color .'">' . $label . '<br /> <div style="font-size: 12px;">' . $ee_key. '</div> </td>
                                    <td style="background-color: '. $error_color .'">' . $days_horus . '</td>
                                    <td style="background-color: '. $error_color .'">' . $actual_computation . '</strong></td>
                                    <td style="background-color: '. $error_color .'">' . $expected_computation . ' </td>
                                    <td>' . $computations . '</td>
                                </tr>
                            ';    

                        $this->assertEqual($actual_computation, $expected_computation); 
                    }

                }   

                echo "</table>";

                echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Other Earnings: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
                echo '
                            <table width="100%" class="">  
                              <tr>
                                <td>&nbsp;</td>
                                <td><strong>Earnings</strong></td>
                                <td><strong>Actual Computation</strong></td>
                                <td><strong>Expected Computation</strong></td>
                                <td><strong>Computation</strong></td>
                              </tr>
                    ';      

                foreach($employee_other_earnings as $eoe_key => $eoed) {

                    if($eoed->getAmount() != '') {
                        $label = $eoed->getLabel();
                        $actual_ocomputation   = preg_replace('/\s+/', ' ', $eoed->getAmount());
                        $expected_ocomputation = preg_replace('/\s+/', ' ', $expected_output[$eoed->getVariable()]); 

                        $error_color = "";
                        if(!empty($expected_ocomputation) && !empty($actual_ocomputation)) {
                            if($expected_ocomputation != $actual_ocomputation) {
                                $error_color = "#f04d4d";
                            }   
                        } else {
                            $error_color = "#f04d4d";
                        }   

                        $computations = "";
                        $benefit_d = G_Settings_Employee_Benefit_Finder::findByName($eoed->getVariable());

                        if($benefit_d) {
                            if($benefit_d->getMultipliedBy() == 'present_days') {
                                $computations = $benefit_d->getAmount() . " * Present Days (" . $labels_array['present_days_with_pay'] . ")";
                            } else {
                                $computations = $benefit_d->getAmount();
                            }
                            
                        }

                        echo '
                                <tr>
                                    <td>&nbsp;</td>
                                    <td style="background-color: '. $error_color .'">' . $label . '</td>
                                    <td style="background-color: '. $error_color .'">' . $actual_ocomputation . '</strong></td>
                                    <td style="background-color: '. $error_color .'">' . $expected_ocomputation . '</td>
                                    <td>' . $computations . '</td>
                                </tr>
                            ';    

                        $this->assertEqual($actual_ocomputation, $expected_ocomputation);
                    }

                }           

                echo "</table>";
                echo '<br />';
                echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Deductions: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
                echo '
                            <table width="100%" class="">  
                              <tr>
                                <td>&nbsp;</td>
                                <td><strong>Deductions</strong></td>
                                <td><strong>Actual Computation</strong></td>
                                <td><strong>Expected Computation</strong></td>
                                <td><strong>Computation</strong></td>
                              </tr>
                    ';

                $tax_table            = Tax_Table_Factory::getRevisedTax(Tax_Table::SEMI_MONTHLY);
                $total_taxable_income = $payslip->getTaxable();
                foreach($tax_table as $tkey => $tax_range) {
                    if($total_taxable_income >= $tax_range['from'] && $total_taxable_income <= $tax_range['to']) {
                        $_tax_range = $tax_range;
                    }
                }  
                $witholding_tax_computation = '(' .$total_taxable_income . '-' . $_tax_range['from'] . ') * (' . $_tax_range['rate'] . '/100) + '.  $_tax_range['fixed'];

                foreach($employee_deductons as $ed_key => $edd) {
                    $label = $edd->getLabel();
                    $actual_dcomputation   = preg_replace('/\s+/', ' ', $edd->getAmount());
                    $expected_dcomputation = preg_replace('/\s+/', ' ', $expected_output[$edd->getVariable()]); 

                    $error_color = "";

                    //if(!empty($actual_dcomputation) && !empty($expected_dcomputation)) {
                        if($actual_dcomputation != $expected_dcomputation) {
                            $error_color = "#f04d4d";
                        }                       
                    //} else {
                    //    $error_color = "#f04d4d";
                   // }

                    $computation = "";
                    if($edd->getVariable() == "late_amount") {
                        $computation = "Late Hours (" . $labels_array['late_hours'] . ") * Hourly Rate (" . $labels_array['hourly_rate'] . ") = " . $labels_array['late_hours'] * $labels_array['hourly_rate'];
                    }elseif($edd->getVariable() == "undertime_amount") {
                        $computation = "Undertime Hours (" . $labels_array['undertime_hours'] . ") * Hourly Rate (" . $labels_array['hourly_rate'] . ") = " . $labels_array['undertime_hours'] * $labels_array['hourly_rate'];
                    }elseif($edd->getVariable() == "absent_amount") {
                        $computation = "Days Absent (" . $labels_array['absent_days_without_pay'] . ") * Daily Rate (" . $labels_array['daily_rate'] . ") = " . $labels_array['absent_days_without_pay'] * $labels_array['daily_rate'];
                    }elseif($edd->getVariable() == "sss") {
                        $computation = "Refer to Government Table";
                    }elseif($edd->getVariable() == "pagibig") {
                        $computation = "Refer to Government Table";
                    }elseif($edd->getVariable() == "philhealth") {
                        $computation = "Refer to Government Table";
                    }elseif($edd->getVariable() == "witholding tax") {
                        $computation = $witholding_tax_computation . " = " . $actual_dcomputation . " (Note: please refer to tax table)";
                    } else {
                        $computation = $edd->getVariable();
                    }

                    echo '
                            <tr>
                                <td>&nbsp;</td>
                                <td style="background-color: '. $error_color .'">' . $label . '<br /> <div style="font-size: 12px;">' . $edd->getVariable() . '</td>
                                <td style="background-color: '. $error_color .'">' . $actual_dcomputation . '</strong></td>
                                <td style="background-color: '. $error_color .'">' . $expected_dcomputation . ' </td>
                                <td>' . $computation . '</td>
                            </tr>
                        ';  

                    $this->assertEqual($actual_dcomputation, $expected_dcomputation); 
                }

                echo "</table>";
                echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Other Deductions: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
                echo '
                            <table width="100%" class="">  
                              <tr>
                                <td>&nbsp;</td>
                                <td><strong>Deductions</strong></td>
                                <td><strong>Actual Computation</strong></td>
                                <td><strong>Expected Computation</strong></td>
                                <td><strong>--</strong></td>
                              </tr>
                    ';

                    
                foreach($employee_other_deductons as $eod_key => $eodd) {
                    $label = $eodd->getLabel();
                    $actual_odcomputation   = preg_replace('/\s+/', ' ', $eodd->getAmount());
                    $expected_odcomputation = preg_replace('/\s+/', ' ', $expected_output[$eodd->getVariable()]); 

                    $error_colord = ""; 
                    if(!empty($actual_odcomputation) && !empty($expected_odcomputation)) {
                        if($actual_odcomputation != $expected_odcomputation) {
                            $error_colord = "#f04d4d";
                        }                       
                    } else {}

                    echo '
                            <tr>
                                <td>&nbsp;</td>
                                <td style="background-color: '. $error_colord .'">' . $label . '<br /> <div style="font-size: 12px;">' . $eodd->getVariable() . '</td>
                                <td style="background-color: '. $error_colord .'">' . $actual_odcomputation . '</strong></td>
                                <td style="background-color: '. $error_colord .'">' . $expected_odcomputation . ' </td>
                                <td>--</td>
                            </tr>
                        ';  

                    $this->assertEqual($actual_odcomputation, $expected_odcomputation); 
                }

                echo "</table>";

            } else {
                echo 'Payslip Not Found';
            }
        }
    }
    
    function testCutoffPayslip() {  

        $cutoff_details  = $this->cutoffDetails();
        $expected_output = $this->expectedPayslipOutput();

        $employee_code = $cutoff_details['employee_code'];
        $from          = $cutoff_details['cutoff_start_date'];
        $to            = $cutoff_details['cutoff_end_date'];

        $e = G_Employee_Finder::findByEmployeeCode($employee_code);

        if($e) {
            $employee_id = $e->getId();
            $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $cutoff_end_date);
            $p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);

            if($p) {

                $ph             = new G_Payslip_Helper($p);
                $new_earnings   = $p->getBasicEarnings();
                $new_deductions = $p->getTardinessDeductions();
                $payslip_info   = $p->getEmployeeBasicPayslipInfo();
                $other_earnings = $p->getOtherEarnings();
                $other_deductions = $p->getOtherDeductions();
                $total_earnings   = $ph->computeTotalEarnings();
                $total_deductions = $ph->computeTotalDeductions();      
                $net_pay          = $p->getNetPay();        

                echo "<br /><b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Payslip: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";        
                echo "Salary Type: " . $payslip_info['salary_type'] . "/ Monthly Rate :" . $payslip_info['monthly_rate'] . "/ Daily Rate :" . $payslip_info['daily_rate'] . "/ Hourly Rate :" . $payslip_info['hourly_rate'] . "<hr />";

                echo "<b style='text-shadow: 0 1px #ffffff; font-size: 20px;'>Earnings: " . "</b><br />";       
                echo '
                            <table width="100%" class="">  
                              <tr style="background-color: #6fd7ff;">
                                <td><strong>Name</strong></td>
                                <td><strong>Days/Hours</strong></td>
                                <td><strong>Amount</strong></td>
                              </tr>
                    ';  

                foreach($new_earnings as $earning) {

                    $total_hrs  = number_format($earning['total_hours'],2);
                    $total_days = number_format($earning['total_days'],0);
                    if( $total_days > 0 ){
                      $to_string = "{$total_days} days";
                    }elseif( $total_hrs > 0 ){
                      $to_string = "{$total_hrs} hrs";
                    }else{
                      $to_string = "";
                    }

                    if( $payslip_info['salary_type'] == 'Monthly' && $earning['label'] == 'Basic Pay' ){
                      $to_string = "";
                    }

                    echo '
                            <tr style="background-color: #e3e3e3;">
                                <td>' . $earning['label'] . '</td>
                                <td>' . $to_string . '</td>
                                <td>' . Tools::currencyFormat($earning['amount']) . '</td>
                            </tr>
                        ';                  

                }

                foreach ($other_earnings as $oear){
                if($oear->getAmount() != 0) {
                    echo '
                            <tr style="background-color: #e3e3e3;">
                                <td>' . $oear->getLabel() . '</td>
                                <td>' . '' . '</td>
                                <td>' . Tools::currencyFormat($oear->getAmount()) . '</td>
                            </tr>
                        ';                  
                    }                   
                }


                echo '
                        <tr style="background-color: #e3e3e3;">
                            <td>' . '' . '</td>
                            <td style="float:right;">' . 'Total Earnings: ' . '</td>
                            <td>' . Tools::currencyFormat($total_earnings) . '</td>
                        </tr>
                    ';                  

                echo '</table>';                

                echo "<b style='text-shadow: 0 1px #ffffff; font-size: 20px;'>Deductions: " . "</b><br />"; 
                echo '
                            <table width="100%" class="">  
                              <tr style="background-color: #6fd7ff;">
                                <td><strong>Name</strong></td>
                                <td><strong>Hours</strong></td>
                                <td><strong>Amount</strong></td>
                              </tr>
                    ';  

                foreach($new_deductions as $deduction) {
                    $total_hrs  = number_format($deduction['total_hours'],2);
                    $total_days = $deduction['total_days'];

                    if( $total_hrs > 0 ){
                        $to_string = "<b>({$total_hrs} hrs)</b>";
                    }elseif( $total_days > 0 ){
                        $to_string = "<b>({$total_days} days)</b>";
                    }else{
                        $to_string = "";
                    }           

                    echo '
                            <tr style="background-color: #e3e3e3;">
                                <td>' . $deduction['label'] . '</td>
                                <td>' . $to_string . '</td>
                                <td>' . Tools::currencyFormat($deduction['amount']) . '</td>
                            </tr>
                        ';  

                }

                foreach ($other_deductions as $oear){
                if($oear->getAmount() != 0) {
                    echo '
                            <tr style="background-color: #e3e3e3;">
                                <td>' . $oear->getLabel() . '</td>
                                <td>' . '' . '</td>
                                <td>' . Tools::currencyFormat($oear->getAmount()) . '</td>
                                <td>-</td>
                            </tr>
                        ';                  
                    }                   
                }   
                
                echo '
                        <tr style="background-color: #e3e3e3;">
                            <td>' . '' . '</td>
                            <td style="float:right;">' . 'Total Deductions: ' . '</td>
                            <td>' . Tools::currencyFormat($total_deductions) . '</td>
                        </tr>
                    ';                              
                    
                echo '</table>';                                            

                if($total_earnings != $expected_output['total_earnings']) {
                    $error_color_earnings = "#f04d4d";
                }  

                if($total_deductions != $expected_output['total_deductions']) {
                    $error_color_deductions = "#f04d4d";
                } 

                if($net_pay != $expected_output['net_pay']) {
                    $error_color_netpay = "#f04d4d";
                }   

                echo "<b style='text-shadow: 0 1px #ffffff; font-size: 20px;'>Summary: " . "</b><br />";
                echo '
                            <table width="100%" class="">  
                              <tr style="background-color: #6fd7ff;">
                                <td><strong>Earnings</strong></td>
                                <td><strong>Deduction</strong></td>
                                <td><strong>Net Pay</strong></td>
                              </tr>
                    ';      

                echo '  <tr style="background-color: #e3e3e3;">
                            <td style="background-color: '. $error_color_earnings .'"><strong>' . Tools::currencyFormat($total_earnings) . '</strong></td>
                            <td style="background-color: '. $error_color_deductions .'"><strong>' . Tools::currencyFormat($total_deductions) . '</strong></td>
                            <td style="background-color: '. $error_color_netpay .'"><strong>' . Tools::currencyFormat($net_pay) . '</strong></td>
                         </tr>
                    ';

                echo '
                    <tr style="background-color: #6fd7ff;">
                        <td><strong>Expected Earnings</strong></td>
                        <td><strong>Expected Deduction</strong></td>
                        <td><strong>Expected Net Pay</strong></td>
                    </tr>
                ';                    

                echo '  <tr style="background-color: #e3e3e3;">
                            <td style="background-color: '. $error_color_earnings .'"><strong>' . Tools::currencyFormat($expected_output['total_earnings']) . '</strong></td>
                            <td style="background-color: '. $error_color_deductions .'"><strong>' . Tools::currencyFormat($expected_output['total_deductions']) . '</strong></td>
                            <td style="background-color: '. $error_color_netpay .'"><strong>' . Tools::currencyFormat($expected_output['net_pay']) . '</strong></td>
                         </tr>
                    ';                    

                echo '</table>';                                    

            }

        }       

        $this->assertEqual($total_earnings, $expected_output['total_earnings']);
        $this->assertEqual($total_deductions, $expected_output['total_deductions']);
        $this->assertEqual($net_pay, $expected_output['net_pay']);
    }    
    
}
