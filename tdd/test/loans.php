<?php
error_reporting(0);
class Loans extends UnitTestCase {
	/*
        Definition
        Deduction Period : refer to class_g_employee_loan.php
           BI_MONTHLY = Bi Monthly
           MONTHLY    = Monhtly          
    */

    function testCase01()
	{
	    /**
	     * Loan Calculator : Bi-monthly
	     */

        echo "<pre>";

        $start_date     = "2014-08-25";
        $loan_amount    = 5000;
        $interest       = 20; //in percent
        $deduction_type = G_Employee_Loan::BI_MONTHLY;
        $months_to_pay  = 3;

        $data = array();

        $l = new Loan_Calculator();
        $l->setStartDate($start_date);
        $l->setLoanAmount($loan_amount);
        $l->setInterestRate($interest);
        $l->setDeductionType($deduction_type);
        $l->setMonthsToPay($months_to_pay);
        $data = $l->computeLoan();

        $expected['e_end_date']            = '2014-11-25';
        $expected['e_total_amount_to_pay'] = 8000.00;
        $expected['e_monthly_due']         = 1333.33;

        print_r($data);
        print_r($expected);

        $this->assertEqual($data['end_date'], $expected['e_end_date']);
        $this->assertEqual($data['total_amount_to_pay'], $expected['e_total_amount_to_pay']);
        $this->assertEqual($data['monthly_due'], $expected['e_monthly_due']);

	}	

    function testCase02()
    {
        /**
         * Loan Calculator : Monthly
         */

        echo "<pre>";

        $start_date     = "2014-08-25";
        $loan_amount    = 5000;
        $interest       = 20; //in percent
        $deduction_type = G_Employee_Loan::MONTHLY;
        $months_to_pay  = 3;

        $data = array();

        $l = new Loan_Calculator();
        $l->setStartDate($start_date);
        $l->setLoanAmount($loan_amount);
        $l->setInterestRate($interest);
        $l->setDeductionType($deduction_type);
        $l->setMonthsToPay($months_to_pay);
        $data = $l->computeLoan();

        $expected['e_end_date']            = '2014-11-25';
        $expected['e_total_amount_to_pay'] = 8000.00;
        $expected['e_monthly_due']         = 2666.67;

        print_r($data);
        print_r($expected);
        
        $this->assertEqual($data['end_date'], $expected['e_end_date']);
        $this->assertEqual($data['total_amount_to_pay'], $expected['e_total_amount_to_pay']);
        $this->assertEqual($data['monthly_due'], $expected['e_monthly_due']);

    }

    function testCase03()
    {
        /**
         * Loan Calculator : Monthly
         */

        echo "<pre>";

        $start_date     = "2014-01-15";
        $loan_amount    = 10500;
        $interest       = 10; //in percent
        $deduction_type = G_Employee_Loan::MONTHLY;
        $months_to_pay  = 5;

        $data = array();

        $l = new Loan_Calculator();
        $l->setStartDate($start_date);
        $l->setLoanAmount($loan_amount);
        $l->setInterestRate($interest);
        $l->setDeductionType($deduction_type);
        $l->setMonthsToPay($months_to_pay);
        $data = $l->computeLoan();

        $expected['e_end_date']            = '2014-06-15';
        $expected['e_total_amount_to_pay'] = 15750.00;
        $expected['e_monthly_due']         = 3150.00;

        print_r($data);
        print_r($expected);
        
        $this->assertEqual($data['end_date'], $expected['e_end_date']);
        $this->assertEqual($data['total_amount_to_pay'], $expected['e_total_amount_to_pay']);
        $this->assertEqual($data['monthly_due'], $expected['e_monthly_due']);

    }

    function testCase04()
    {
        /**
         * Loan Calculator : Bi-Monthly
         */

        echo "<pre>";

        $start_date     = "2014-03-15";
        $loan_amount    = 20000;
        $interest       = 15; //in percent
        $deduction_type = G_Employee_Loan::BI_MONTHLY;
        $months_to_pay  = 10;

        $data = array();

        $l = new Loan_Calculator();
        $l->setStartDate($start_date);
        $l->setLoanAmount($loan_amount);
        $l->setInterestRate($interest);
        $l->setDeductionType($deduction_type);
        $l->setMonthsToPay($months_to_pay);
        $data = $l->computeLoan();

        $expected['e_end_date']            = '2015-01-15';
        $expected['e_total_amount_to_pay'] = 50000.00;
        $expected['e_monthly_due']         = 2500.00;

        print_r($data);
        print_r($expected);
        
        $this->assertEqual($data['end_date'], $expected['e_end_date']);
        $this->assertEqual($data['total_amount_to_pay'], $expected['e_total_amount_to_pay']);
        $this->assertEqual($data['monthly_due'], $expected['e_monthly_due']);

    }

    function testCase05() {
        /**
         * Employee Loan : Bi-monthly
         */

        echo "<pre>";

        $date                 = date("Y-m-d H:i:s");
        $employee_id          = 3850;
        $company_structure_id = 1;
        $loan_type            = 'Test Case01 : Employee Loan';
        $interest             = 10; //in percent
        $loan_amount          = 10000;
        $months_to_pay        = 10;
        $deduction_type       = G_Employee_Loan::BI_MONTHLY;
        $start_date           = '2014-02-15';                

        $lt = G_Loan_Type_Finder::findByLoanType($loan_type);
        if( empty($lt) ){
            //Create loan type
            $lt = new G_Loan_Type();           
            $lt->setDateCreated($date);
            $lt->setCompanyStructureId($company_structure_id);
            $lt->setLoanType($loan_type);
            $lt->setIsArchive(G_Loan_Type::NO);                                
            $id = $lt->save();
            $lt->setId($id);
        }

        $gel = G_Employee_Loan_Finder::findByCompanyStructureIdEmployeeIdLoanTypeIdAndStartDate($company_structure_id, $employee_id, $lt->getId(), $start_date);

        if( empty($gel) ){            
            $gel = new G_Employee_Loan();            
            $gel->setCompanyStructureId($company_structure_id);
            $gel->setEmployeeId($employee_id);
            $gel->setLoanTypeId($lt->getId());  
            $gel->setInterestRate($interest);               
            $gel->setLoanAmount($loan_amount);
            $gel->setMonthsToPay($months_to_pay);
            $gel->setDeductionType($deduction_type);     
            $gel->setStartDate($start_date);                         
            $gel->setDateCreated($date);  
            $loan_data = $gel->saveEmployeeLoan();   

            $gel = G_Employee_Loan_Finder::findById($loan_data['last_id']);
        }

        $data['end_date']                  = $gel->getEndDate();
        $data['total_amount_to_pay']       = $gel->getTotalAmountToPay();
        $data['monthly_due']               = $gel->getDeductionPerPeriod();

        $expected['e_end_date']            = '2014-12-15';
        $expected['e_total_amount_to_pay'] = 20000.00;
        $expected['e_monthly_due']         = 1000.00;

        print_r($data);
        print_r($expected);

        $this->assertEqual($data['end_date'], $expected['e_end_date']);
        $this->assertEqual($data['total_amount_to_pay'], $expected['e_total_amount_to_pay']);
        $this->assertEqual($data['monthly_due'], $expected['e_monthly_due']);
    }

    function testCase06() {
        /**
         * Employee Loan : Monthly
         */

        echo "<pre>";

        $date                 = date("Y-m-d H:i:s");
        $employee_id          = 3850;
        $company_structure_id = 1;
        $loan_type            = 'Test Case06 : Employee Loan';
        $interest             = 20; //in percent
        $loan_amount          = 25000;
        $months_to_pay        = 5;
        $deduction_type       = G_Employee_Loan::MONTHLY;
        $start_date           = '2014-01-30';        

        $lt = G_Loan_Type_Finder::findByLoanType($loan_type);
        if( empty($lt) ){
            //Create loan type
            $lt = new G_Loan_Type();           
            $lt->setDateCreated($date);
            $lt->setCompanyStructureId($company_structure_id);
            $lt->setLoanType($loan_type);
            $lt->setIsArchive(G_Loan_Type::NO);                                
            $id = $lt->save();
            $lt->setId($id);
        }

        $gel = G_Employee_Loan_Finder::findByCompanyStructureIdEmployeeIdLoanTypeIdAndStartDate($company_structure_id, $employee_id, $lt->getId(), $start_date);

        if( empty($gel) ){            
            $gel = new G_Employee_Loan();            
            $gel->setCompanyStructureId($company_structure_id);
            $gel->setEmployeeId($employee_id);
            $gel->setLoanTypeId($lt->getId());  
            $gel->setInterestRate($interest);               
            $gel->setLoanAmount($loan_amount);
            $gel->setMonthsToPay($months_to_pay);
            $gel->setDeductionType($deduction_type);     
            $gel->setStartDate($start_date);                         
            $gel->setDateCreated($date);  
            $loan_data = $gel->saveEmployeeLoan();   

            $gel = G_Employee_Loan_Finder::findById($loan_data['last_id']);
        }

        $data['end_date']                  = $gel->getEndDate();
        $data['total_amount_to_pay']       = $gel->getTotalAmountToPay();
        $data['monthly_due']               = $gel->getDeductionPerPeriod();

        $expected['e_end_date']            = '2014-06-30';
        $expected['e_total_amount_to_pay'] = 50000.00;
        $expected['e_monthly_due']         = 10000.00;

        print_r($data);
        print_r($expected);

        $this->assertEqual($data['end_date'], $expected['e_end_date']);
        $this->assertEqual($data['total_amount_to_pay'], $expected['e_total_amount_to_pay']);
        $this->assertEqual($data['monthly_due'], $expected['e_monthly_due']);
    }

    function testCase07() {
        /**
         * Add to Employee Loan History
        */

        $date                 = date("Y-m-d H:i:s");
        $employee_id          = 3850;
        $company_structure_id = 1;
        $loan_type            = 'Test Case06 : Employee Loan';
        $interest             = 20; //in percent
        $loan_amount          = 25000;
        $months_to_pay        = 5;
        $deduction_type       = G_Employee_Loan::MONTHLY;
        $start_date           = '2014-01-30';        

        $reference_number     = "Test Case07 Sample Reference Number"; //optional
        $remarks              = "Test Case07 Sample Remarks"; //optional   

        $lt  = G_Loan_Type_Finder::findByLoanType($loan_type);

        if( empty($lt) ){
            //Create loan type
            $lt = new G_Loan_Type();           
            $lt->setDateCreated($date);
            $lt->setCompanyStructureId($company_structure_id);
            $lt->setLoanType($loan_type);
            $lt->setIsArchive(G_Loan_Type::NO);                                
            $id = $lt->save();
            $lt->setId($id);
        }

        $gel = G_Employee_Loan_Finder::findByCompanyStructureIdEmployeeIdLoanTypeIdAndStartDate($company_structure_id, $employee_id, $lt->getId(), $start_date);
        if( $gel ){
            $gel->setReferenceNumber($reference_number);
            $gel->setRemarks($remarks);
            $gel->setDatePaid($date);
            $data = $gel->addToHistory();
        }else{
            $gel = new G_Employee_Loan();

            $gel->setCompanyStructureId($company_structure_id);
            $gel->setEmployeeId($employee_id);
            $gel->setLoanTypeId($lt->getId());  
            $gel->setInterestRate($interest);               
            $gel->setLoanAmount($loan_amount);
            $gel->setMonthsToPay($months_to_pay);
            $gel->setDeductionType($deduction_type);     
            $gel->setStartDate($start_date);                         
            $gel->setDateCreated($date);  
            $loan_data = $gel->saveEmployeeLoan();   

            $gel = G_Employee_Loan_Finder::findById($loan_data['last_id']);
            $gel->setReferenceNumber($reference_number);
            $gel->setRemarks($remarks);
            $gel->setDatePaid($date);
            $data = $gel->addToHistory();
        }

        $l = new Loan_Calculator();
        $l->setStartDate($start_date);
        $l->setLoanAmount($loan_amount);
        $l->setInterestRate($interest);
        $l->setDeductionType($deduction_type);
        $l->setMonthsToPay($months_to_pay);
        $loan = $l->computeLoan();

        $total_payments_made = G_Employee_Loan_Payment_History_Helper::sqlCountEntriesByEmployeeLoanId($gel->getId());

        $expected['e_new_balance'] = $gel->getTotalAmountToPay() - ($total_payments_made * $loan['monthly_due']);
        $expected['e_amount_paid'] = $loan['monthly_due'];

        print_r($data);
        print_r($expected);

        $this->assertEqual($data['new_balance'], $expected['e_new_balance']);
        $this->assertEqual($data['amount_paid'], $expected['e_amount_paid']);
    }

    function testCase08() {
        /**
         * Add to Employee Loan To Payslip
        */

        $date                 = date("Y-m-d H:i:s");

        //Employee
        $employee_id          = 3846;
        $e                    = G_Employee_Finder::findByEmployeeCode($employee_id);
        $company_structure_id = 1;

        //Loan
        $loan_type            = 'Test Case07 : Employee Loan to Payslip';
        $interest             = 20; //in percent
        $loan_amount          = 25000;
        $months_to_pay        = 10;
        $deduction_type       = G_Employee_Loan::BI_MONTHLY;
        $start_date           = '2014-09-05';        

        //Loan History
        $reference_number     = "Test Case08 Sample Reference Number"; //optional
        $remarks              = "Test Case08 Sample Remarks"; //optional   
        $cutoff_start         = "2014-09-06";
        $cutoff_end           = "2014-09-20";

        //Payroll
        $cutoff_number        = 1;
        $month                = date("m",strtotime($cutoff_start));        
        $year                 = date("Y",strtotime($cutoff_start));

        $lt  = G_Loan_Type_Finder::findByLoanType($loan_type);

        if( empty($lt) ){
            //Create loan type
            $lt = new G_Loan_Type();           
            $lt->setDateCreated($date);
            $lt->setCompanyStructureId($company_structure_id);
            $lt->setLoanType($loan_type);
            $lt->setIsArchive(G_Loan_Type::NO);                                
            $id = $lt->save();
            $lt->setId($id);
        }

        if( empty($e) ) {
            $c->hireEmployee('2014-GLEENT-TESTCASE-A', 'Jongjong', 'Jang', 'Jing', '1985-11-01', 'Male', 'Married',
                0, '2014-01-01', 'Software Engineer', 'Software Engineer', 'Regular', '12000', "Monthly");
            $e = G_Employee_Finder::findByEmployeeCode('2014-GLEENT-TESTCASE-A');
        }

        $gel = G_Employee_Loan_Finder::findByCompanyStructureIdEmployeeIdLoanTypeIdAndStartDate($company_structure_id, $employee_id, $lt->getId(), $start_date);
        if( $gel ){
            $gel->setReferenceNumber($reference_number);
            $gel->setRemarks($remarks);
            $gel->setDatePaid($date);
            $data = $gel->addToHistory();
        }else{
            //Add Loan
            $gel = new G_Employee_Loan();
            $gel->setCompanyStructureId($company_structure_id);
            $gel->setEmployeeId($employee_id);
            $gel->setLoanTypeId($lt->getId());  
            $gel->setInterestRate($interest);               
            $gel->setLoanAmount($loan_amount);
            $gel->setMonthsToPay($months_to_pay);
            $gel->setDeductionType($deduction_type);     
            $gel->setStartDate($start_date);                         
            $gel->setDateCreated($date);  
            $gel->saveEmployeeLoan();               
        }

        //Cutoff Period
        $is_cutoff_exists = G_Cutoff_Period_Helper::sqlIsCutoffPeriodExists($cutoff_start, $cutoff_end);
        if( $is_cutoff_exists <= 0 ){
            $year        = date("Y", strtotime($cutoff_start));
            $payout_date = date("Y-m-d", strtotime("+2 month",strtotime($cutoff_end)));
            $day_start   = date("d",strtotime($cutoff_start));

            if( $day_start <= 15 ){
                $salary_cycle    = 1;
                $salary_cycle_id = 2;
            }else{
                $salary_cycle    = 2;
                $salary_cycle_id = 2;
            }

            $c = new G_Cutoff_Period();
            $c->setYearTag($year);
            $c->setStartDate($cutoff_start);
            $c->setEndDate($cutoff_end);
            $c->setPayoutDate($payout_date);
            $c->setCutoffNumber($salary_cycle);
            $c->setSalaryCycleId($salary_cycle_id);
            $c->setIsPayrollGenerated("");
            $c->setIsLock("No");
            $c_last_id = $c->save();
            $c->setId($c_last_id);
        }

        $c = new G_Company;
        $c->generatePayslip($month, $cutoff_number, $year);

        if( $gel->getStatus() == G_Employee_Loan::IN_PROGRESS ){
            $data['is_deducted_to_payroll'] = true;
        }else{
            $data['is_deducted_to_payroll'] = false;
        }

        $expected['is_deducted_to_payroll'] = true;

        print_r($data);
        print_r($expected);

        $this->assertEqual( $data['is_deducted_to_payroll'] = true,  $expected['is_deducted_to_payroll'] = true);        
    }
}
?>