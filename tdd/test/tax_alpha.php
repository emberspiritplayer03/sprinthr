<?php
error_reporting(1);
@ini_set('display_errors', 0);
define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

class Tax_Alpha extends UnitTestCase {
    function testcase01() {               
        $monthly_salary = 45000;
        $months_stayed  = 12;
        $dependents     = 2;
        $overtime       = 5000;
        $_13thmonth     = 45000;

        $limit_taxable_allowance = 30000;
        $non_taxable_allowance   = 30000;
        $taxable_allowance       = 12000;
        
        //Limit non taxable allowance
        if( $non_taxable_allowance > $limit_taxable_allowance ){
            $non_taxable_allowance = $limit_taxable_allowance;
        }

        $sss            = 3800;
        $philhealth     = 3800;
        $pagibig        = 3800;

        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        $tax_withheld     = 118082;
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();

        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 113180.00;       
        $expected['tax_refund']   = 4902.00;
        $expected['tax_payable']  = 0.00;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase02() {               
        $monthly_salary = 45000;
        $months_stayed  = 7;
        $dependents     = 1;
        $overtime       = 15000;
        $_13thmonth     = 28000;

        $limit_taxable_allowance = 30000;
        $non_taxable_allowance = 30000;
        $taxable_allowance     = 18000;
        
        //Limit non taxable allowance
        if( $non_taxable_allowance > $limit_taxable_allowance ){
            $non_taxable_allowance = $limit_taxable_allowance;
        }

        $sss            = 2450;
        $philhealth     = 2450;
        $pagibig        = 2450;

        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        $tax_withheld     = 50000;
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 54095.00;       
        $expected['tax_refund']   = 0.00;
        $expected['tax_payable']  = 4095.00;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase03() {               
        $monthly_salary = 45000;
        $months_stayed  = 7;
        $dependents     = 1;
        $overtime       = 15000;
        $_13thmonth     = 28000;

        $non_taxable_allowance = 30000;
        $taxable_allowance     = 18000;
        
        $sss            = 2450;
        $philhealth     = 2450;
        $pagibig        = 2450;
        $tax_withheld   = 50000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 54095.00;       
        $expected['tax_refund']   = 0.00;
        $expected['tax_payable']  = 4095.00;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase04() {               
        $monthly_salary = 30000;
        $months_stayed  = 8;
        $dependents     = 3;
        $overtime       = 3000;
        $_13thmonth     = 20000;

        $non_taxable_allowance = 5000;
        $taxable_allowance     = 10000;
        
        $sss            = 4650.40;
        $philhealth     = 3000;
        $pagibig        = 800;
        $tax_withheld   = 10000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 21409.92;       
        $expected['tax_refund']   = 0.00;
        $expected['tax_payable']  = 11409.92;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase05() {               
        $monthly_salary = 20000;
        $months_stayed  = 12;
        $dependents     = 0;
        $overtime       = 5000;
        $_13thmonth     = 15000;

        $non_taxable_allowance = 0;
        $taxable_allowance     = 2000;
        
        $sss            = 6975.60;
        $philhealth     = 3000;
        $pagibig        = 1200;
        $tax_withheld   = 37000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 37706.10;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 706.10;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase06() {               
        $monthly_salary = 15000;
        $months_stayed  = 7;
        $dependents     = 1;
        $overtime       = 2000;
        $_13thmonth     = 10000;

        $non_taxable_allowance = 2000;
        $taxable_allowance     = 5000;
        
        $sss            = 3815;
        $philhealth     = 1312.50;
        $pagibig        = 700;
        $tax_withheld   = 3000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 3875.88;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 875.88;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }


     function testcase07() {               
        $monthly_salary = 18000;
        $months_stayed  = 12;
        $dependents     = 0;
        $overtime       = 6000;
        $_13thmonth     = 15000;

        $non_taxable_allowance = 10000;
        $taxable_allowance     = 2000;
        
        $sss            = 6975.60;
        $philhealth     = 2700;
        $pagibig        = 1200;
        $tax_withheld   = 30000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 29531.10;       
        $expected['tax_refund']   = 468.90;
        $expected['tax_payable']  = 0;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase08() {               
        $monthly_salary = 25000;
        $months_stayed  = 12;
        $dependents     = 2;
        $overtime       = 5000;
        $_13thmonth     = 20000;

        $non_taxable_allowance = 2000;
        $taxable_allowance     = 5000;
        
        $sss            = 6975.60;
        $philhealth     = 3750;
        $pagibig        = 1200;
        $tax_withheld   = 40000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 41518.60;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 1518.60;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase09() {               
        $monthly_salary = 12000;
        $months_stayed  = 12;
        $dependents     = 0;
        $overtime       = 7000;
        $_13thmonth     = 12000;

        $non_taxable_allowance = 1000;
        $taxable_allowance     = 3000;
        
        $sss            = 5232;
        $philhealth     = 1800;
        $pagibig        = 1200;
        $tax_withheld   = 15000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 15853.60;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 853.60;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase10() {               
        $monthly_salary 		= 10000;
        $months_stayed  		= 9;
        $dependents     		= 1;
        $overtime       		= 8020;
        $_13thmonth     		= 10000;       
        $taxable_allowance  	= 4000;
        
        $sss            		= 3269.70;
        $philhealth     		= 1125;
        $pagibig        		= 900;
        $non_taxable_allowance 	= 3000;

        $tax_withheld   		= 2000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 2372.53;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 372.53;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase11() {               
        $monthly_salary 		= 8000;
        $months_stayed  		= 10;
        $dependents     		= 1;
        $overtime       		= 6000;
        $_13thmonth     		= 8000;       
        $taxable_allowance  	= 4500;
        
        $sss            		= 2907;
        $philhealth     		= 1000;
        $pagibig        		= 1000;
        $non_taxable_allowance 	= 1000;

        $tax_withheld   		= 1300;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 1259.30;       
        $expected['tax_refund']   = 40.70;
        $expected['tax_payable']  = 0;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase12() {               
        $monthly_salary 		= 13000;
        $months_stayed  		= 5;
        $dependents     		= 1;
        $overtime       		= 5000;
        $_13thmonth     		= 13000;       
        $taxable_allowance  	= 4900;
        
        $sss            		= 2361.50;
        $philhealth     		= 812.50;
        $pagibig        		= 500;
        $non_taxable_allowance 	= 5000;

        $tax_withheld   		= 210;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 211.30;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 1.30;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase13() {               
        $monthly_salary 		= 43000;
        $months_stayed  		= 7;
        $dependents     		= 2;
        $overtime       		= 15000;
        $_13thmonth     		= 43000;       
        $taxable_allowance  	= 5300;
        
        $sss            		= 4069.10;
        $philhealth     		= 3062.50;
        $pagibig        		= 700;
        $non_taxable_allowance 	= 3000;

        $tax_withheld   		= 50000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 51040.52;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 1040.52;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase14() {               
        $monthly_salary 		= 80000;
        $months_stayed  		= 9;
        $dependents     		= 3;
        $overtime       		= 20000;
        $_13thmonth     		= 80000;       
        $taxable_allowance  	= 5700;
        
        $sss            		= 5231.70;
        $philhealth     		= 3937.50;
        $pagibig        		= 900;
        $non_taxable_allowance 	= 2000;

        $tax_withheld   		= 185000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 185361.86;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 361.86	;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase15() {               
        $monthly_salary 		= 70000;
        $months_stayed  		= 11;
        $dependents     		= 0;
        $overtime       		= 8000;
        $_13thmonth     		= 70000;       
        $taxable_allowance  	= 6100;
        
        $sss            		= 6394.30;
        $philhealth     		= 4812.50;
        $pagibig        		= 1100;
        $non_taxable_allowance 	= 1000;

        $tax_withheld   		= 218000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 218053.82;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 53.82;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase16() {               
        $monthly_salary 		= 90000;
        $months_stayed  		= 3;
        $dependents     		= 0;
        $overtime       		= 15000;
        $_13thmonth     		= 90000;       
        $taxable_allowance  	= 6500;
        
        $sss            		= 1743.90;
        $philhealth     		= 1312.50;
        $pagibig        		= 300;
        $non_taxable_allowance 	= 10000;

        $tax_withheld   		= 70000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 70443.08;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 443.08;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase17() {               
        $monthly_salary 		= 100000;
        $months_stayed  		= 5;
        $dependents     		= 0;
        $overtime       		= 5000;
        $_13thmonth     		= 100000;       
        $taxable_allowance  	= 6900;
        
        $sss            		= 2906.50;
        $philhealth     		= 2187.50;
        $pagibig        		= 500;
        $non_taxable_allowance 	= 30000;

        $tax_withheld   		= 130000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 133417.92;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 3417.92;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase18() {               
        $monthly_salary 		= 120000;
        $months_stayed  		= 2;
        $dependents     		= 1;
        $overtime       		= 12000;
        $_13thmonth     		= 120000;       
        $taxable_allowance  	= 7300;
        
        $sss            		= 1162.60;
        $philhealth     		= 875;
        $pagibig        		= 200;
        $non_taxable_allowance 	= 30000;

        $tax_withheld   		= 56000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 56618.72;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 618.72;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

    function testcase19() {               
        $monthly_salary 		= 55000;
        $months_stayed  		= 6;
        $dependents     		= 1;
        $overtime       		= 10000;
        $_13thmonth     		= 55000;       
        $taxable_allowance  	= 7700;
        
        $sss            		= 3487.80;
        $philhealth     		= 2625;
        $pagibig        		= 600;
        $non_taxable_allowance 	= 2000;

        $tax_withheld   		= 70000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 70696.16;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 696.16;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }

     function testcase20() {               
        $monthly_salary 		= 50000;
        $months_stayed  		= 7;
        $dependents     		= 2;
        $overtime       		= 20000;
        $_13thmonth     		= 50000;       
        $taxable_allowance  	= 8100;
        
        $sss            		= 4069.10;
        $philhealth     		= 3062.50;
        $pagibig        		= 700;
        $non_taxable_allowance 	= 20000;

        $tax_withheld   		= 65000;


        //Exemptions
        $fixed_exemption = 50000;
        $amount_exemption_multiplier = 25000;
        $additional_exemption = $dependents * $amount_exemption_multiplier;

        $total_gross = $monthly_salary * $months_stayed;
        $total_net   = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
        $total_deductions = $sss + $pagibig + $philhealth + $non_taxable_allowance;

        $total_taxable_compensation = $total_net - $total_deductions;
        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemption + $additional_exemption);

        
        
        $nt = new G_Net_Taxable_Table();
        $nt->setNetTaxableCompensation($net_taxable_compensation);
        $nt->setWithholdingTax($tax_withheld);
        $data = $nt->getTaxDue();
       
        Utilities::displayArray($data);
        echo "Total Taxable Compensation : {$total_taxable_compensation} / Total Net Compensation : {$net_taxable_compensation}<br />";

        $output['tax_due']     = $data['tax_due'];
        $output['tax_refund']  = $data['tax_refund'];
        $output['tax_payable'] = $data['tax_payable'];

        $expected['tax_due']      = 65080.52;       
        $expected['tax_refund']   = 0;
        $expected['tax_payable']  = 80.52;

        foreach($expected as $key => $value){
            $result          = number_format($output[$key],2);
            $expected_result = number_format($expected[$key],2);
            $this->assertEqual($result, $expected_result);           
        }
    }
}
?>