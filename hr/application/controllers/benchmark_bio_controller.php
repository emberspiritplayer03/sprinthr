<?php
class Benchmark_Bio_Controller extends Controller {
	function __construct() {
		parent::__construct();
		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);
	}

	function yearlyBonus()
	{
		$year = date("Y");
		$e = new G_Employee();
		$query['year'] = $year;
		$add_query 	   = '';
		$data = $e->getEmployeesYearlyBonusByYear($query, $add_query);
		Utilities::displayArray($data);
	}

	function testString() {
		$str = "ref id= 213,100.00 443.50 -4,578.00 0.00 -4,578.00 2,310.00 225.25 2,535.25 894.60 -92.20 802.40 1,240.35";
		if( preg_match("/ref id/",$str) ){
			echo "Exists";
		}else{
			echo "Does not exists!";
		}
	}

	function processYearlyBonus()
	{
		$year   = date("Y");
		$options_action   = 'add_selected';
		$options_selected = Array(
            0 => 145,
            1 => 141,
            2 => 90,
            3 => 11
        );

		$yearly_bonus_data = ['year' => $year, 'action' => 2, 'selected' => array()];
		$bonus  = new G_Yearly_Bonus();
		$data   = $bonus->processYearlyBonus($yearly_bonus_data);		

		/*if( $options_action == 'add_selected' ){
			$return = $bonus->addToEarnings(1,$options_selected);
        }else{
        	$return = $bonus->addToEarnings(2);
        }*/

        Utilities::displayArray($data);
    }
    
	function employeeLeaveCreditHistory()
	{
		$eid = 99;
		$leave_id = 99;
		$added = 99;
		$h = new G_Employee_Leave_Credit_History();
		$h->setEmployeeId($eid);
		$h->setLeaveId($leave_id);
		$h->setCreditsAdded($added);
		$return = $h->addToHistory();

		Utilities::displayArray($return);
	}

	function perfectAttendanceQuery()
	{
		$year  = 2015;
		$month = 8;

		$from = "{$year}-{$month}-01";
		$to   = date("Y-m-t",strtotime($from));

		$att   = G_Attendance_Helper::perfectAttendanceDataByDateRange($from, $to);
		Utilities::displayArray($att);
	}

	function processIncentiveLeave()
	{
		$year  = 2015;
		$month = 8;

		$il = new G_Incentive_Leave_History();
		$il->setYear($year);
		$il->setMonthNumber($month);
		$data = $il->process()->addToCredits();
		//$data = $il->process();
		Utilities::displayArray($data);

	}

	function updateEmployeeDependents()
	{
		Loader::appStyle('style.css');
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('benchmark/update_dependents.php',$this->var);	
	}

	function reportIncentiveReport()
	{
		$data['incentive_leave_year']      = 2015;
		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$qry_employee_type = '';		

		if( $remove_resigned ){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_terminated ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_endo ){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}

		if( !empty($qry_add_on) ){
			$is_additional_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

		$data['leave_id'] = 11;
		$e = new G_Employee();			
		$incentive_leave = $e->getEmployeeIncentiveReport($data, $is_additional_qry);	

		$group_incentive_leave = array();
		foreach( $incentive_leave as $il ){
			$month  = date("F",strtotime($il['date']));
			$group_incentive_leave[$il['employee_pkid']]['employee_details'] = array();
			$group_incentive_leave[$il['employee_pkid']]['leave_credit'][$month] += $il['credits_added'];			

		}

		Utilities::displayArray($group_incentive_leave);	

	}

	function _update_employee_dependents()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['file_employee_dependents']['tmp_name'];		
        if (!is_file($file)) {
            echo "Please select a file";
            exit;
        }

        $e = new G_Employee_Import($file);
        $e->setDateCreated($this->c_date);       
		$is_imported = $e->importUpdateEmployeeDetails();

	
	}
	
	function cutoffPeriods()
	{
		$month = 9;
		$year  = 2015;

		$date = "{$year}-{$month}-01";

		$sv = new G_Cutoff_Period();
		$periods = $sv->getMonthCutoffPeriods($month, $year);
		Utilities::displayArray($periods);
        //$sv->generateCutoffPeriodByMonthAndYear( $month, $year );
        //$periods = $sv->getValidCutOffPeriodsByMonthAndYear($month, $year);
	}

	function sss_report()
	{
		$date_from = "2015-05-01";
		$date_to   = "2015-05-15";

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getSSSContributions();

		Utilities::displayArray($data);
	}

	function philhealth_report()
	{
		$date_from = "2015-05-01";
		$date_to   = "2015-05-15";

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getPhilhealthContributions();

		Utilities::displayArray($data);
	}

	function pagibig_report()
	{
		$date_from = "2015-05-01";
		$date_to   = "2015-05-15";

		$r = new G_Report();				
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getPagibigContributions();

		Utilities::displayArray($data);
	}

	function loansManagement()
	{
		$data = array(			    		   
		    'loan_type_id' => 2,
		    'loan_amount' => 8000,
		    'government_deduction_frequency' => 'Bi-monthly',
		    'government_start_date' => Array
		        (
		            'year' => 2015,
		            'month' => 'September',
		            'cutoff' => 'b'
		        ),
		    'government_deduction_amount' => 8000,
		    'government_months_to_pay' => 5,
		    'company_loan_amount' => 8000,
		    'interest_rate' => 0,
		    'deduction_frequency' => 'Bi-monthly',
		    'start_date' => Array
		        (
		            'year' => 2015,
		            'month' => 'September',
		            'cutoff' => 'a'
		        ),
		    'months_to_pay' => 5,
		    'total_amount_to_pay' => 8000,
		    'deduction_per_period' => 800,
		    'date_end' => '2016-08-30'
		);

		if( !empty($data) ){
			$employee_id = 244;
			$loan_id     = $data['loan_type_id'];	
			$company_id  = Utilities::decrypt($this->company_structure_id);
			$interest_rate  = $data['interest_rate'];
			
			$lt = new G_Loan_Deduction_Type();
			$government_loan_ids = $lt->government_loan_type_ids;

			if( in_array($loan_id, $government_loan_ids) ){
				$amount 		= $data['loan_amount'];
				$deduction_type = $data['government_deduction_frequency'];
				$period = $data['government_start_date'];
				$deduction_per_period = $data['government_deduction_amount'];
				$months_to_pay  = $data['government_months_to_pay'];
				$loan = new G_Employee_Loan();
				$loan->setCompanyStructureid($company_id);		
				$loan->setEmployeeId($employee_id);
				$loan->setMonthsToPay($months_to_pay);
				$loan->setDeductionPerPeriod($deduction_per_period);
				$loan->setLoanTypeId($loan_id);
				$loan->setInterestRate(0);
				$loan->setLoanAmount($amount);					
				$loan->setCutoffPeriod($period);		
				$loan->setDeductionType($deduction_type);			
				$loan->setAsPending();
				$loan->setAsLock();
				$loan->setAsIsNotArchive();					
				$json = $loan->createGovernmentLoanDetails()->createGovernmentLoanSchedule()->saveEmployeeLoanDetails()->saveEmployeeLoanSchedules();
				//$json = $loan->createGovernmentLoanDetails()->createGovernmentLoanSchedule();
				//$json = $loan->createGovernmentLoanDetails();
			}else{
				$amount 		= $data['company_loan_amount'];
				$deduction_type = $data['deduction_frequency'];
				$period 		= $data['start_date'];
				$months_to_pay  = $data['months_to_pay'];
				$loan = new G_Employee_Loan();
				$loan->setCompanyStructureid($company_id);		
				$loan->setEmployeeId($employee_id);
				$loan->setLoanTypeId($loan_id);
				$loan->setLoanAmount($amount);		
				$loan->setMonthsToPay($months_to_pay);		
				$loan->setInterestRate($interest_rate);
				$loan->setCutoffPeriod($period);		
				$loan->setDeductionType($deduction_type);			
				$loan->setAsPending();
				$loan->setAsLock();
				$loan->setAsIsNotArchive();			
				$json = $loan->createLoanDetails()->createLoanSchedule()->saveEmployeeLoanDetails()->saveEmployeeLoanSchedules();
				//$json = $loan->createLoanDetails()->createLoanSchedule();
			}
		}

		Utilities::displayArray($json);
	}

	function dateTimeDebug()
	{
		$date = '2016-01-01';
		$datetime = new DateTime($date);
		$month_x   = date("m",$date);
		$new_date = $datetime->modify("1 month")->format("Y-m-01");	
		/*if( $month_x == 1 ){
			$new_date = $datetime->modify("28 days")->format("Y-m-01");					
		}else{
								
		}*/

		echo $new_date;	
	}

	function getAllSections()
	{	
		$department_id = 12;
		$c = G_Company_Structure_Finder::findById($department_id);
		if( $c ){
			$sections = $c->getAllDepartmentSections();
		}	

		/*$c = new G_Company_Structure();
		$sections = $c->getAllSections();*/

		Utilities::displayArray($sections);
	}

	function stringExists() 
	{
		$a = "POSITION ALLOWANCE :750.00";
		$a = strtolower($a);
		if (strpos($a,'position allowance') !== false) {
		    echo 'true';
		}else{
			echo 'Not exists!';
		}
	}

	function previousCutoff() 
	{
		$start_date = '2015-05-01';
		$c = new G_Cutoff_Period();
		$data = $c->getPreviousCutOffByDate($start_date);
		Utilities::displayArray($data);
	}

	function arrayMerge()
	{
		 $earnings_key_meal_transpo = array('meal_allowance','transpo_allowance');
		 $earnings_key_pos_allowance = array('position_allowance');
		 $earnings_ot_ctpa      = array('ot_allowance','ctpa/sea','ctpa','ctpa_sea'); 
		 $earnings_key_rice = array('rice');
		 $exist_deductions   = array('emergency_loan','salary_loan','hmo','sss_loan','adjustment','witholding tax','philhealth','pagibig','sss','absent_amount','undertime_amount','late_amount','company_loan','education_loan');
		 $earnings_key = array('adjustment');
		 $earnings_key_others   = array_merge($earnings_key,$earnings_key_pos_allowance,$earnings_key_rice,$earnings_key_meal_transpo,$earnings_ot_ctpa);
		 //$earnings_key_others = array_merge($earnings_key_others,$earnings_ot_ctpa);
		 Utilities::displayArray($earnings_key_others);
	}

	function payslip()
	{
		$from = "2016-01-01";
		$to   = "2016-01-15";
		$employee_id = 120;				
		$payslips    = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
		$cutoff 	 = array($from, $to);		
		if( $payslips ){			
			$payslipDataBuilder = new G_Payslip();
			$payslip_section_deduction 		 = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2);	
			$payslip_section_earnings  		 = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('earnings', 2);
			$payslip_section_other_earnings  = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('other_earnings', 2);		
			$payslip_section_breakdown 		 = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('breakdown', 2);
			$payslip_section_loan_balance    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('loan_balance', 2, '', $cutoff);			
			$payslip_section_yearly_bonus    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('yearly_bonus', 2, '', $cutoff);
			
			//$payslip_section_loans	 		 = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('loan_balance', 2);

			$end_date = "2015-05-16";
			$payslip_yearly_breakdown        = G_Payslip_Helper::computeEmployeeYearlyPayslipBreakdownByEndDate($employee_id, $end_date);

			$emp_loan_balance = G_Employee_Loan_Helper::sqlGetUserLoanBalance($employee_id);			
			$payslip_section_loan_leave_balance = $payslipDataBuilder->wrapPayslipArray($emp_loan_balance)->getPayslipData('loan_leave', 2);											
		}
		
		$bonus += $bonus_leave_converted; 

		  $leave_conversion = array('non_taxable_converted_leave','taxable_converted_leave');
		  foreach( $leave_conversion as $key ){
		  	if( array_key_exists($key, $payslip_section_other_earnings) ){
		  		$bonus_leave_converted += $payslip_section_other_earnings[$key]['value'];  
		  	}
		  }
		 
		//echo $bonus_leave_converted;

		//$total_hrs_worked = $payslip_section_breakdown['regular_hours']['value'] - ($payslip_section_breakdown['holiday_legal_hours']['value'] + $payslip_section_breakdown['holiday_special_hours']['value'] + $payslip_section_breakdown['restday_hours']['value']);

		//echo "Total HRS Worked : {$total_hrs_worked}";

		/*$exist_deductions   = array('emergency_loan','salary_loan','hmo','sss_loan','adjustment','witholding tax','philhealth','pagibig','sss','absent_amount','undertime_amount','late_amount','company_loan','education_loan');
	    $i_other = 0;	    
	    foreach($payslip_section_deduction as $deduction_key => $e_deduction) {
	      $is_exists = false;
	      foreach( $exist_deductions as $key ){	      	 	      	
	        if( stripos($deduction_key, $key)  !== false ){	        		        		        	
	        	$is_exists = true;
	        	exit;
	        }
	      }
	      if( !$is_exists ){
	      	 echo $payslip_section_deduction[$deduction_key]['value'];   
	      	 $total_other_deduction_key = $total_other_deduction_key + $payslip_section_deduction[$deduction_key]['value'];       
	      }
	    }*/


		//echo "Total HRS Worked : {$total_hrs_worked}";
		//Utilities::displayArray($payslips);
		//Utilities::displayArray($payslip_section_deduction);
		Utilities::displayArray($payslip_section_earnings);
		//Utilities::displayArray($payslip_yearly_breakdown);
		//Utilities::displayArray($payslip_section_other_earnings);
		//Utilities::displayArray($payslip_section_yearly_bonus);
		//Utilities::displayArray($payslip_section_loan_balance);
		//Utilities::displayArray($payslip_section_loans);
		//Utilities::displayArray($payslips[$employee_id]);
	}

	function getEmployeeLogs() 
	{	
		$data = array();
		$id = 300;
		$e  = G_Employee_Finder::findById($id);
		if( !empty($e) ){
			$employee_code = $e->getEmployeeCode();
			$date 		   = "2015-05-07";
			$fp   = new G_Fp_Attendance_Logs($date);
			$data = $fp->setEmployeeCode($employee_code)->getEmployeeLogs()->groupData()->getProperty('logs');
		}

		Utilities::displayArray($data[$date]);
	}

	function expectedCutoffPeriodsByYear()
	{
		$year = date("Y");
		$c    = new G_Cutoff_Period();
		$data = $c->expectedCutoffPeriodByYear($year);
		Utilities::displayArray($data);

	}


	function reportLoans()
	{		
		$data = Array
		(
		    'loan_type' => 'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s',
		    'loan_report_type' => 1,
		    'year_tag' => Array
		        (
		            1 => 2015,
		            2 => 2015
		        ),
		    'period' => Array
		        (
		            1 => '2015-04-01/2015-05-30',
		            2 => 'June'
		        ),
		    'loans_employee_id' => 'CZjC9YtprN8Syspanu4JkeyYwQDFr3XOjg9h4Sf52kg,Uin05LSmRsxlRo8SgBWEuiRvRLIiEPQKgfUxIulGWDA,q4iBLJkpRsMlXJp6s-OU7KrIEnUo3g7uY7-mXaeLTs0',
		    'loans_dept_section_id' => 'zyiG86Ya90sOgfDl6iM8YH6zT2IEld3H9dnzaxkeESc',
		    'loans_employment_status_id' => 'bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE'
		);

		$rep = new G_Report();
		$data = $rep->loanReport($data);
	}

	function editTimesheetInOut()
	{
		
		$data = Array
		(
		    'in' => Array
		        (
		            '1' => Array
		                (
		                	'date' => '2015-05-04',
		                    'time' => '07:11:00',
		                    'type' => 'In'
		                ),
		            '2' => Array
		                (
		                	'date' => '2015-05-04',
		                    'time' => '19:14:00',
		                    'type' => 'Out'
		                ),
		            '3' => Array
		                (
		                	'date' => '2015-05-04',
		                    'time' => '08:14:00',
		                    'type' => 'In'
		                )
		        ),
		    'out' => Array
		        (
		            '4' => Array
		                (
		                	'date' => '2015-05-04',
		                    'time' => '20:11:00',
		                    'type' => 'Out'
		                )
		        ),
		    'employee_code' => '8658'

		);				
		$at = new G_Attendance_Log();
		$at->setEmployeeCode($data['employee_code']);
		$result = $at->updateFpLogsEntries($data)->updateAttendance();		
		Utilities::displayArray($result);
		
	}

	function getEmployeeEarnings()
	{
		$data = array();
		$e = G_Employee_Finder::findById(20);				
		if( $e ){
			$date = date("Y-m-d");
			//$date = "2014-03-22";
			$c = new G_Cutoff_Period();
			$cutoff_data = $c->getCurrentCutoffPeriod($this->c_date);

			$cutoff['id']   = 98;
			$cutoff['from'] = '2015-03-21';
			$cutoff['to']   = '2015-04-05';
			
			$data = $e->getEmployeeEarningsByCutoffPeriod($cutoff);
		}
	}	

	function importBenefits()
	{
		Loader::appStyle('style.css');
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('benchmark/import_benefits.php',$this->var);	
	}

	function importEmployeeBenefits()
	{
		Loader::appStyle('style.css');
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('benchmark/import_employee_benefits.php',$this->var);	
	}

	function testFunction02()
	{
		$dept_string = "Production,Finance";
		$a_dept = explode(",", $dept_string);
		foreach( $a_dept as $dept ){
			$a_new_dept[] = Model::safeSql(trim($dept));
		}
		$new_dept_string  = implode(",", $a_new_dept);
		echo $new_dept_string;
	}

	function testFunction03()
	{
		$int_array = array(1,2,3);
		if( in_array(2, $int_array) ){
			echo "In Array";
		}else{
			echo "Not in array";
		}
	}

	function _import_benefits()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['file_benefits']['tmp_name'];
		//$e = new Employee_Import($file);
        if (!is_file($file)) {
            echo "Please select a file";
            exit;
        }

		$bf = new G_Settings_Employee_Benefit();
		$bf->setDateCreated($this->c_date);
		//$data = $bf->setImportFile($file)->createImportBulkData()->importBulkSave();
		$data = $bf->setImportFile($file)->createImportBulkData()->importBulkSave;
		//Utilities::displayArray($data);
	}

	function _import_employee_benefits()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['file_benefits']['tmp_name'];
		//$e = new Employee_Import($file);
        if (!is_file($file)) {
            echo "Please select a file";
            exit;
        }

        $fields = array("company_structure_id","benefit_id","applied_to","employee_department_id","description");
        $bf = new G_Employee_Benefits_Main();
		$bf->setDateCreated($this->c_date);
		$bf->setCompanyStructureid($this->company_structure_id);
		//$data = $bf->setImportFile($file)->createImportBulkData()->importBulkSave();
		$data = $bf->setImportFile($file)->createImportBulkData()->importBulkSettingsBenefits()->bulkSave(array(),$fields);		
		Utilities::displayArray($data);
	}

	function payslipMinuteComputation() 
	{
		$id = 2;
		$e  = G_Employee_Finder::findById($id);
		if( $e ){
			$end_date = "2015-05-01";
			$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $end_date);
			Utilities::displayArray($s);
		}
	}

	function timeDiff()
	{
		$time_in = "2015-05-05";
	}

	function checkBreakTimeDeductibles()
	{
		$e = G_Employee_Finder::findById(244);
		if( $e ){
			$schedule['schedule_in']  = "2015-05-04 7:30 pm";
            $schedule['schedule_out'] = "2015-05-05 5:00 am";
			$data = $e->getTotalBreakTimeHrsDeductible($schedule);   
			Utilities::displayArray($data);
		}
	}

	function newLeaveRequest()
	{
		$date_start = "2015-06-08";
		$date_end   = "2015-06-12";
		$date_applied = "2015-05-29";
		$time_applied = "20:04:00";
		$eid 		= 1;
		$leave_id   = 1;
		$is_halfday = false;
		$comment    = "Test Comment";
		$is_paid    = true;
		$created_by = "Admin";
		$status     = G_Employee_Leave_Request::PENDING;
		$is_halfday = 'No';
        
		$e = G_Employee_Finder::findById($eid);
		if( $e ){			
			$el = new G_Employee_Leave_Request();        
			$el->setCompanyStructureId(1);
			$el->setEmployeeId($e->getId());
			$el->setLeaveId($leave_id);
			$el->setDateApplied($date_applied);
	        $el->setTimeApplied($time_applied);	
			$el->setDateStart($date_start);
			$el->setDateEnd($date_end);
			$el->setApplyHalfDayDateStart($is_halfday);		
			$el->setLeaveComments($comment);
			$el->setIsApproved($status);
			$el->setIsPaid($is_paid);
			$el->setCreatedBy($created_by);	
			$el->setAsIsNotArchive();		
			//$el->setAsPending();			
			$data = $el->checkGeneralRule()->filterValidDates()->createLeaveBulkRequests()->deductLeaveToLeaveCredits()->bulkSaveRequests();
			Utilities::displayArray($data);
		}		
	}

	function reportIncorrectShift()
	{
		$data = Array
			(
			    'date_from' => '2015-01-01',
			    'date_to' => '2015-05-31',
			    'employee_id' => 'bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE',
			    'all_employees' => 0,
			    'dept_section_id' => 'NCP5wFwOpXauz5m5QtPWyMUjnMJnefaRGjOjkv5dG1Q',
			    'employment_status_id' => ''			    
			);

		$employee_ids 		   = $data['employee_id'];
		$dept_section_ids      = $data['dept_section_id'];
		$employment_status_ids = $data['employment_status_id'];
		$date_from = $data['date_from'];
		$date_to   = $data['date_to'];


		$rep = new G_Report();
		$rep->setFromDate($date_from);
		$rep->setToDate($date_to);
		$rep->setEmployeeIds($employee_ids);
		$rep->setDepartmentIds($dept_section_ids);
		$rep->setEmploymentStatusIds($employment_status_ids);
		if( isset($data['all_employees']) && $data['all_employees'] ){
			$data = $rep->allIncorrectShift();
		}else{						
			$data = $rep->incorrectShift();	
		}
		
		Utilities::displayArray($data);
	}

	function addRequestApprovers()
	{
		$approvers = array
        (
            1 => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4',
            2 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg',
            3 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg'
        );
        
        $request_id = 1;
        $request_type = G_Request::PREFIX_LEAVE;
        $requestor_id = 1;

        $r = new G_Request();
        $r->setRequestorEmployeeId($requestor_id);
        $r->setRequestId($request_id);
        $r->setRequestType($request_type);
        $return = $r->saveEmployeeRequest($approvers);

        Utilities::displayArray($return);
	}


	function cutoffPeriodByYear()
	{
		$current_year = date("Y");
		$cutoff = new G_Cutoff_Period();
		$data = $cutoff->setYearTag($current_year)->getCutoffPeriodsByYear();
		$data = Tools::encryptMulitDimeArrayIndexValue('id', $data);
		Utilities::displayArray($data);
	}

	function newEearnings()
	{
		$data = Array(		    
		    'e_title' => 'test 1234',
		    'e_employee_id' => 'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s,bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE',
		    'e_department_section_id' => '',
		    'e_employment_status_id' => '',
		    'e_earning_type' => 1,
		    'e_amount' => 0,
		    'e_percentage' => 10,
		    'e_percentage_selection' => 1,
		    'e_is_taxable' => 1,
		    'e_apply_to_all' => 0,
		    'e_cutoff_period' => 'bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE',
		    'e_remarks' => 'test 123'
		);

		$apply_to_ids['employee']   = $data['e_employee_id'];
		$apply_to_ids['department'] = $data['e_department_section_id'];
		$apply_to_ids['employment_status'] = $data['e_employment_status_id'];
		$earning_type = $data['e_earning_type'];		
		$company_structure_id = 1;

		$ea = new G_Employee_Earnings();	
		$ea->setCompanyStructureId($company_structure_id);	
		$ea->setTitle($data['e_title']);
		$ea->setAmount($data['e_amount']);
		$ea->setPercentage($data['e_percentage']);
		$ea->setPercentageMultiplier($data['e_percentage_selection']);
		$ea->setEarningType($data['e_earning_type']);
		$ea->setIsTaxable($data['e_is_taxable']);
		$ea->setPayrollPeriodId(Utilities::decrypt($data['e_cutoff_period']));
		$ea->setRemarks($data['e_remarks']);
		$ea->setAsNotArchive();
		$ea->setAsPending();
		$ea->setDateCreated($this->c_date);
		if( isset($data['e_apply_to_all']) && $data['e_apply_to_all'] == 1 ){
			$ea_data = $ea->createApplyToAllEarningData()->save();
		}else{
			$ea_data = $ea->setApplyToIds($apply_to_ids)->createBulkEarningData()->bulkInsertData();			
		}

		Utilities::displayArray($ea_data);
	}


	function bulkAddEmergencyContact()
	{
		$return = array();
		$emergency_contact[] = "Sisa | Mother | Blk1 lot 11, Golden City | Landline : 0234323, Mobile : 3333";
		$emergency_contact[] = "Basilio | Father | Blk1 lot 11, Golden City | Landline : 231121, Mobile : 56435, Work Telephone : 3342";
		$e = G_Employee_Finder::findById(57);
		if( $e ){			
			//$return = $e->createBulkEmergencyContactArray($emergency_contact);	
			$return = $e->createBulkEmergencyContactArray($emergency_contact)->bulkAddEmergencyContact();			
		}
 		Utilities::displayArray($return);
	}

	function bulkAddEducation() 
	{
		$return = array();
		$education[] = "STI | BSIT | 2010-05-01 to 2012-03-03 | 5.0";
		$education[] = "STI | COE | 2012-05-01 to 2015-03-03 | 4.0";

		$e = G_Employee_Finder::findById(2);
		if( $e ){
			$return = $e->createBulkEducationArray($education)->bulkAddEducation();			
		}
	}

	function saveOTAllowance() 
	{
		/*$data = Array
			(
			   
			    'employee_id' => 'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s:e,bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE
			:e',
			    'department_id' => 'NCP5wFwOpXauz5m5QtPWyMUjnMJnefaRGjOjkv5dG1Q:d',
			    'employment_status_id' => 'bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE:es',
			    'day_type' => Array
			        (
			            'day_type1' => 1,
			            'day_type3' => 1			            
			        ),
			    'ot_allowance' => 100,
			    'multiplier' => 2,
			    'max_ot_allowance' => 100,
			    'date_start' => '2015-05-31'
			);*/
		$data = Array
			(
			   
			    'employee_id' => '',
			    'department_id' => '',
			    'employment_status_id' => 'bsOU40aExAZJs2vxnQ4eIzXgphUxyjvOW_sQjSrttSE:es',
			    'day_type' => Array
			        (
			            'day_type1' => 1,
			            'day_type3' => 1			            
			        ),
			    'ot_allowance' => 100,
			    'multiplier' => 2,
			    'max_ot_allowance' => 100,
			    'date_start' => '2015-05-31'
			);

		$a_obj_ids[] = $data['employee_id'];
		$a_obj_ids[] = $data['department_id'];
		$a_obj_ids[] = $data['employment_status_id'];
		$s_obj_ids   = implode(",", array_filter($a_obj_ids));

		$data['applied_to_ids'] = $s_obj_ids;		

		$oa = new G_Overtime_Allowance();
		$return = $oa->createValidDayTypeArray($data['day_type'])->addOtAllowance($data);		


		Utilities::displayArray($return);
	}

	function summaryReportDTR()
	{
		$data = Array
		(
		    'date_from' => '2015-05-01',
		    'date_to' => '2015-05-31',
		    'report_type' => 'summarized',
		    'search_field' => 'all',
		    'search' => '',
		    'birthdate' => '',
		    'department_applied' => 'all',
		    'button' => 'Search'
		);

		$e = new G_Employee();			
		if($data['report_type'] == DETAILED){			
			$daily_time_record = $e->getDailyTimeRecordData($data);
		}else{
			$daily_time_record = $e->getDailyTimeRecordSummarizedData($data);
		}

		Utilities::displayArray($daily_time_record);
	}

	function queryBuilder()
	{
		$data = Array
		(
		    'cutoff_period' => '2015-03-26/2015-04-10',
		    'qry_fields' => Array
		        (
		            1 => 'basic_pay',
		            2 => 'Net Pay'
		        ),
		    'qry_options' => Array
		        (
		            1 => '=',
		            2 => '>='
		        ),
		    'qry_values' => Array
		        (
		            1 => 200,
		            2 => 150
		        )
		);

		$a_periods = explode("/", $data['cutoff_period']);
		$from      = $a_periods[0];
		$to        = $a_periods[1];
		unset($data['cutoff_period']);
		$qry = new Query_Builder();
		$qry_string = $qry->setQueryOptions($data)->usePrefix('e')->setLogicalOperator('OR')->buildSQLQuery();
		echo "Query String : {$qry_string}";

	}


	function restDay()
	{
		$date = date("Y-m-d");
		$e  = G_Employee_Finder::findById(2);
		$ss = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
		$s  = G_Schedule_Finder::findByEmployeeAndDate($e, $date);
        $rd = G_Restday_Finder::findByEmployeeAndDate($e, $date);
        Utilities::displayArray($e);
	}

	function copyDefaultRestDayToGroup()
	{
		$group_id = 9;
		$rd = new G_Group_Restday();
		$data = $rd->setGroupId($group_id)->getAllDefaultRestDay()->saveRestDaysToDepartment();

		Utilities::displayArray($data);
	}

	function copyDefaultRestDayToEmployee()
	{
		$employee_id = 2;
		$rd = new G_Restday();
		//$data = $rd->setEmployeeId($employee_id)->getAllDefaultRestDay()->convertArrayToObject()->saveDefaultRestDays();
		$new_data = $rd->getAllDefaultRestDay()->a_rest_day;		
		Utilities::displayArray($new_data);
		foreach( $new_data as $value ){
			echo $value['date'];
		}
		//Utilities::displayArray($data);
	}

	function employeePayslipByPeriod()
	{
		$from = '2015-03-26';
		$to   = '2015-04-10';
		$is_confidential_qry = '';
		$order_by = "ORDER BY (SELECT title FROM " . COMPANY_STRUCTURE . " cs WHERE cs.id = e.department_company_structure_id ) ASC ";
		$fields   = array("e.id","e.employee_code","e.lastname","e.firstname","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.department_company_structure_id LIMIT 1)AS department_name","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.section_id LIMIT 1)AS section_name");
		$employees = G_Employee_Helper::sqlGetPayslipPeriodWithOptions($from, $to, $is_confidential_qry, $fields, $order_by);
		//Group by department 
		$grouped_data = array();
		foreach( $employees as $employee ){
			$grouped_data[$employee['department_name']][] = $employee;
		}
		Utilities::displayArray($grouped_data);

	}

	function testLogic02()
	{
		$time = "17:00:00";
		$a_time = explode(":", $time);
		$a_time[1] = '00';
		Utilities::displayArray($a_time);
	}

	function cashFileGenerator()
	{
		$s_from = "2015-01-21";
		$s_to   = "2015-02-05";
		$employee_type = "non-confidential";
		//$employee_ids  = array();
		$employee_ids = array(1,12,3);

		$report = new G_Report();
		$data = $report->setFromDate($s_from)->setToDate($s_to)->setEmployeeType($employee_type)->setEmployeeIds($employee_ids)->cashFileReport();

		Utilities::displayArray($data);
	}

	function attendanceChecking()
	{
		$s_from = "2015-03-26";
		$s_to   = "2015-04-20";
		$report = new G_Report();
		$report->setFromDate($s_from);
		$report->setToDate($s_to);
		$data = $report->employeesWorkAgainstSchedule();

		Utilities::displayArray($data);
	}

	function attendanceSummary()
	{
		$s_from = "2015-03-26";
		$s_to   = "2015-04-20";
		$report = new G_Report();
		$report->setFromDate($s_from);
		$report->setToDate($s_to);
		$data = $report->summaryWorkAgainstSchedule();

		Utilities::displayArray($data);
	}

	function testFunctionDate()
	{
		echo $s_from = date("Y-m-d",strtotime("-1 day"));
	}

	function getCurrentCutOffPeriod()
	{
		$date = date("Y-m-d");		
		$c = new G_Cutoff_Period();
		$data = $c->getCurrentCutoffPeriod($this->c_date);

		Utilities::displayArray($data);
	}

	function payrollLoans()
	{
		$id = 6;		

	}

	function debugCutoffPeriods()
	{
		$cStartDate = '2015-03-11';
		$cEndDate   = '2015-03-25';
		$cutoff_periods = G_Cutoff_Period_Finder::findAll();
		$cutoff_periods_array = G_Cutoff_Period_Helper::convertToArray($cutoff_periods);	

		$start_date = (empty($cStartDate)) ? $cutoff_periods[0]->getStartDate() : $cStartDate;
		$end_date   = (empty($cEndDate)) ? $cutoff_periods[0]->getEndDate() : $cEndDate;	

		$new_start_date = date("Y-m-d",strtotime("-1 month", strtotime($end_date)));

		$c = new G_Cutoff_Period();
		$data = $c->getCurrentCutoffPeriod($new_start_date);

		echo "Start Date : {$start_date} / End Date : {$end_date} / New Start Date : {$new_start_date}";
		Utilities::displayArray($data);
	}

	function getPreviousCutoff()
	{
		$date = '2015-05-11';
		$c = new G_Cutoff_Period();
		$data = $c->getPreviousCutOffByDate($date);

		Utilities::displayArray($data);
	}

	function getNextCutoff()
	{
		$date = '2015-03-11';
		$c = new G_Cutoff_Period();
		$data = $c->getNextCutOffByDate($date);

		Utilities::displayArray($data);
	}

	function getCutOffPeriodsByMonthYearCutoffPeriod()
	{
		$month_number = 5;
		$period       = 1;
		$year         = 2015;
		$c = new G_Cutoff_Period();
		$data = $c->expectedCutOffPeriodsByMonthAndYear($month_number, $year);
		Utilities::displayArray($data);
		/*if( $period == 1 ){
			Utilities::displayArray($data[0]);
		}else{
			Utilities::displayArray($data[1]);	
		}*/
	}

	function getPreviousNextCutoff()
	{
		$c  = new G_Cutoff_Period();
		$c->setId($cutoff_id);
		$next_cutoff_data     = $c->getNextCutOff();
		$previous_cutoff_data = $c->getPreviousCutOff();
	}

	function addLeaveCreditsToEmployees()
	{
		$gela = new G_Employee_Leave_Available();
        $return = $gela->addLeaveCreditsToEmployees();
        Utilities::displayArray($return);
	}

	function employeeLeaveCredit()
	{
		$return['is_success'] = false;
		$return['message']    = 'No records updated';

		$date = "2015-12-12";

        $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR);
        $fiscal_year = $sv->getVariableValue();        
        $fiscal_year = date("Y-m-d", strtotime($fiscal_year . " " . date("Y")));

        $a_fiscal_year = explode("-", $fiscal_year);
        $day   = date("d",strtotime($date));
        $month = date("m",strtotime($date));

        if( $a_fiscal_year[1] == $month && $a_fiscal_year[2] == $day ){
        	$slg 		= new G_Settings_Leave_General();
        	$updateTrue = $slg->getAllUnusedLeaveCreditLastYear()->applyGeneralRule()->applyCredits(true);	
        	$slg->addLeaveCreditsToAllEmployees(8, 1); //Add bday leave
	        if(!empty($updateTrue->employee_list_with_leave_increase)) {
	        	$this->var['is_credit_upgraded'] 			= true;
	        	$this->var['employee_with_leave_increase']	= $updateTrue->employee_list_with_leave_increase;
	        }
        }

        Utilities::displayArray($return);
	}

	function getDefaultVariable()
	{
		$sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
        $working_days = $sv->getVariableValue();
        echo $working_days;
	}

	function correctFiscalYear()
	{
		$sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR);
        $fiscal_year = $sv->getVariableValue();
        $fiscal_year = date("Y-m-d", strtotime($fiscal_year . " " . date("Y")));
        echo $fiscal_year;
	}

	function payrollCetaSea()
	{
		$eid = 6;
		
		$start_date = '2015-03-26';
		$end_date   = '2015-04-10';

		$e   = G_Employee_Finder::findById($eid);
		$a   = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);		
		$data   = array(
			"attendance" => $a,
			"daily_rate" => 337.00
		);

		$return = $e->generateCetaSea($data);

		Utilities::displayArray($return);
	}

	function dateTimeLastDay(){
		$date = "2015-02-01";
		$new_date = date("Y-m-t",strtotime($date));
		echo $new_date;
	}

	function getNSHours(){
		 $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
		 $value = $sv->getVariableValue(); 
		 if( $value != "" ){
		 	$a_time_in_out = explode("to", $value);		 	
		 	if( count($a_time_in_out) >= 2){		 		
		 		$time_in  = trim($a_time_in_out[0]);
		 		$time_out = trim($a_time_in_out[1]);
		 		$format   = "H:i:s";
		 		if( Tools::isValidDateTime($time_in,$format) && Tools::isValidDateTime($time_out,$format) ){
		 			echo "Time in : {$time_in} / Time out : {$time_out}";
		 		}
		 	}
		 }

		 //echo $value;
	}

	function checkIfValidDateTime(){
		$date_time = "25:01:00:00";
		$format   = "H:i:s";
		$is_valid = Tools::isValidDateTime($date_time,$format);
		if( $is_valid ){
			echo "Is valid";
		}else{
			echo "Is not valid";
		}
	}

	function sprintVariables()
	{
		//Load default contri variables
		/*$sv = new G_Sprint_Variables();
		$return = $sv->loadDefaultPayrollComputationVariables();				
		Utilities::displayArray($return);*/

		//Get variable value
		/*$sv    = new G_Sprint_Variables('sss_is_taxable');
		$value = $sv->getVariableValue();
		echo $value;*/

		//Get payroll contri variables
		$sv = new G_Sprint_Variables();
		$sv->loadDefaultWorkingDays();
		$sv->loadDefaultCetaAndSea();
		$sv->loadDefaultMinimumRate();		
		$sv->loadDefaultNightShiftHours();
		$sv->loadDefaultFiscalYear();
		$sv->loadDefaultLoansGrossLimit();
		echo "Variables was successfully created";
		
		
	}

	function employeeDyanamicFieldBuilder()
	{
		$data = array(
			2 => array(
				"Employee Category" => "Agency",
				"another category" => "Sample Category 01A"
			),
			3 => array(
				"employee category" => "Direct",
				"Another Category" => "Sample Category 02"
			)
		);
		$eid = 2;
		$e   = new G_Employee();
		$return = $e->createDynamicField($data);

		Utilities::displayArray($return);
	}

	function saveDynamicField()
	{	
		
		$data = array(
			4 => array(
					1 => array("other_details_label" => "Employee Categorya", "other_details_value" => "Agencya"),
					2 => array("other_details_label" => "Another Categoryab", "other_details_value" => "Another Agenecyab")
			),
			3 => array(
					1 => array("other_details_label" => "Employee Categoryaa", "other_details_value" => "Agencyaa"),
					2 => array("other_details_label" => "Another Categoryabab", "other_details_value" => "Another Agenecyabab")
			)
			
		);

		$e   = new G_Employee();		
		$return = $e->createDynamicField($data);
	}

	function multipleSaveDynamicField()
	{
		$dynamic_field_data = array();
		$employee_id  = 6;
		$other_fields = "Employee Type = Agency / Other Email Address = myotheremail@yahoo.com";
		$arr_other_fields = explode("/", $other_fields);

		foreach( $arr_other_fields as $field ){
			$field_array = explode("=", $field);
			$label = trim($field_array[0]);
			$value = trim($field_array[1]);
			if( $label != "" && $value != "" ){ 					
				$dynamic_field_data[$employee_id][] = array("other_details_label" => $label, "other_details_value" => $value);
			}
		} 
		Utilities::displayArray($dynamic_field_data);
		$ed = new G_Employee_Dynamic_Field();
		$ed->bulkInsertDynamicField($dynamic_field_data);

	}

	function testArray()
	{
		$data = Array
		(
		    'other_details_label' => 'Other Details 01',
		    'other_details_value' => 'Other Details Value 01'
		);

		echo $data['other_details_label'];
	}

	function testFunction()
	{
		$sec = 03;
		$time_format = date("H:i",strtotime("11:{$sec}"));

		echo $time_format;
	}

	function autoOvertimeRequest()
	{		
		$id   = 203;
		$date = "2015-05-11";
		$e = G_Employee_Finder::findById($id);
		if( $e ){
			$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);			
			$ot  = new G_Overtime();			
			$result = $ot->autoFileRequest($e, $a);	
		} 
		

		Utilities::displayArray($result);
	}

	function getEmployeeDynamicFields()
	{
		$eid = 6;
		$e = G_Employee_Finder::findById($eid);
		if( $e ){
			$dynamic_fields = $e->getDynamicFields();
		}

		Utilities::displayArray($dynamic_fields);

		$edf = new G_Employee_Dynamic_Field();
		foreach( $dynamic_fields as $field ){
			$label = $field['title'];
			if( !array_key_exists($label, $data) ){
				$data[$label] = $edf->getLabelValidOptions($label);
			}
		}

		Utilities::displayArray($data);
	}

	function arrayUnique()
	{
		$a_data[0] = 'absent';
		$a_data[1] = 'late';
		$a_data[2] = 'absent';
		$a_new_data = array_unique($a_data);
		Utilities::displayArray($a_new_data);
 	}

	function showPayslip()
	{
		$id = 5;
		$from ='2015-03-26';
		$to   ='2015-04-10';

		$e  = G_Employee_Finder::findById($id);
		$p  = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$payslip_info = $p->getEmployeeBasicPayslipInfo();
		$earnings   = $p->getBasicEarnings();
		$deductions = $p->getTardinessDeductions();

		Utilities::displayArray($deductions);
		//Utilities::displayArray($earnings);
		//Utilities::displayArray($deductions);
	}

	function generatePayslip()
	{
		$month = 4;
		$cutoff_number = 1;
		$year  = 2015;
		$c = new G_Company;        
        $payslips = $c->generatePayslip($month, $cutoff_number, $year);
        Utilities::displayArray($payslips);
	}

	function updateSprintVariables()
	{
		$data['default_total_working_days'] = 222;
		$sv = new G_Sprint_Variables();
		$data = $sv->updateVariableValue($data);

		Utilities::displayArray($data);
	}

	function getWeekWorkingDaysNumDays()
	{
		$week_working_days = '7 days a week';
		$sv = new G_Sprint_Variables();
		$num_days = $sv->getWorkingDaysDescriptionNumberOfDays($week_working_days);
		echo $num_days;
	}

	function workingDaysVariables()
	{
		$sv = new G_Sprint_Variables();
		$working_days_options = $sv->optionsWorkingDays();

		Utilities::displayArray($working_days_options);
	}

	function createCutoffPeriodsByMonthAndYear()
	{
		$month = 6;
        $year  = 2015;

		$cutoff = new G_Cutoff_Period();
		$data = $cutoff->generateCutoffPeriodByMonthAndYear( $month, $year );

        Utilities::displayArray($data);
	}

	function getValidCutoffPeriods()
	{
		$month = 2;
		$year  = 2015;

		$period = new G_Cutoff_Period();
		$data = $period->getValidCutOffPeriodsByMonthAndYear($month, $year);
		Utilities::displayArray($data);

	}

	function expectedCutoffPeriods()
	{
		$month = 10;
		$year  = 2015;

		$period = new G_Cutoff_Period();
		$data = $period->expectedCutOffPeriodsByMonthAndYear($month, $year);
		Utilities::displayArray($data);
	}

	function generateMissingCutoff()
	{
		$month = 6;
		$year  = 2015;

		$period = new G_Cutoff_Period();
		$data = $period->generateCutoffPeriodByMonthAndYear($month, $year);
		Utilities::displayArray($data);

	}

	function defaultPayPeriod()
	{
		$total_periods = 1;
		$fields = array("cut_off");
    	$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields); 
    	Utilities::displayArray($default_pay_period);
    	$cutoff_data = explode(",", $default_pay_period['cut_off']);
    	$to_generate_cutoff = $cutoff_data[$total_periods - 1];
    	$days_cutoff        = explode("-", $to_generate_cutoff);
    	echo $to_generate_cutoff;
    	Utilities::displayArray($days_cutoff);
	}

	function getVariableDescription()
	{
		$variable_name = 'default_total_working_days';
		
		$sv = new G_Sprint_Variables($variable_name);
        $valid_working_days_options  = $sv->validWorkingDaysOptions();       
        $default_year_working_days   = $sv->getVariableValue(); 
        $valid_working_days_variable = array_search($default_year_working_days, $valid_working_days_options);
        $default_week_working_days   = $sv->getVariableDescription();


		echo $default_week_working_days;
	}

	function sprintDefaultPayrollVariables()
	{
		$sv = new G_Sprint_Variables();				
		$payroll_variables = $sv->getPayrollDefaultVariables();
		Utilities::displayArray($payroll_variables);
	}

	function workingDaysSprintVariables()
	{
		$sv = new G_Sprint_Variables();
		$data = $sv->loadDefaultWorkingDays();

		Utilities::displayArray($data);
	}

	function defaultFiscalYear()
	{
		$sv = new G_Sprint_Variables();
		$data = $sv->loadDefaultFiscalYear();
		$data = $sv->loadDefaultLoansGrossLimit();

		Utilities::displayArray($data);
	}


	function getEmployeeSalary()
	{
		$id = 2;
		$e  = G_Employee_Finder::findById($id);

		if( $e ){
			$data = $e->getEmployeeSalary();
		}

		Utilities::displayArray($data);
	}

	function getEmployeeContributions()
	{
		$id = 6;
		$e  = G_Employee_Finder::findById($id);

		//$salary['basic_pay'] = 375.28;
		//$salary['gross_pay'] = 6246.08;
		
		//$salary['basic_pay'] = 375.28;
		//$salary['gross_pay'] = 8134.35;

		//$salary['basic_pay'] = 375.28;
		//$salary['gross_pay'] = 5937.98;

		$salary['basic_pay'] = 375.28;
		$salary['gross_pay'] = 10000.00;

		$cutoff_number       = 1;

		if( $e ){
			$contri = $e->getEmployeeContributionsByCutoffNumber( $salary, $cutoff_number );
		}

		Utilities::displayArray($contri);
	}

	function getValidWorkingDaysOptions()
	{
		$sv = new G_Sprint_Variables();
		$options = $sv->validWorkingDaysOptions();
		Utilities::displayArray($options);
	}

	function getSalaryContributions()
	{
		$d = new G_Settings_Deduction_Breakdown();
		$data = $d->getActiveContributionsBreakDown();

		Utilities::displayArray($data);
	}

	function addGroupRestDay()
	{
		$group_id = 9;
		$month = 02;
        $day   = 06;
        $year  = 2015;
        $date  = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
		$c = G_Company_Structure_Finder::findById($group_id);
		if( $c ){
			$data = $c->addRestDay($date);
		}

		Utilities::displayArray($data);
	}

	function groupScheduleBreaktime()
	{
		$id = 19;
		$s = G_Schedule_Group_Finder::findById($id);
		if( $s ){
			$data = $s->getAttachedBreaktimeSchedules();
		}

		Utilities::displayArray($data);
	}

	function deleteGroupRestDay()
	{
		$group_id = 9;
		$month = 02;
        $day   = 06;
        $year  = 2015;
        $date  = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
		$c = G_Company_Structure_Finder::findById($group_id);
		if( $c ){
			$data = $c->deleteRestDay($date);
		}

		Utilities::displayArray($data);
	}

	function getDepartmentGroupSchedules()
	{
		$id = 9;
		$cs = new G_Company_Structure();
		$cs->setId($id);
		$schedules = $cs->getSchedules();

		Utilities::displayArray($schedules);
	}

	function groupRemoveSchedule()
	{
		$schedule_id = 16;
		$id = 9;

		$c = G_Company_Structure_Finder::findById($id);
		if( $c ){
			$return = $c->removeSchedule($schedule_id);

			Utilities::displayArray($return);
		}	
	}

	function groupAddSchedule()
	{
		$data  = array(
			'geid' => 'zyiG86Ya90sOgfDl6iM8YH6zT2IEld3H9dnzaxkeESc',
		    'token' => '4fc72d0822d790f9b2c3406cbdcc0685',
		    'schedule' => Array(
			    'name' => 'TEST 123',		    
			    'start_date' => '2015-02-03',
			    'end_date' => '2015-03-31',
			    'time_in' => Array
			        (
			            'mon' => '8:00 am',
			            'tue' => '8:00 am',
			            'wed' => '8:00 am',
			            'thu' => '8:00 am',
			            'fri' => '8:00 am',
			            'sat' => '8:00 am',
			            'sun' => ''
			        ),
			    'time_out' => Array
			        (
			            'mon' => '5:00 pm',
			            'tue' => '5:00 pm',
			            'wed' => '5:00 pm',
			            'thu' => '5:00 pm',
			            'fri' => '5:00 pm',
			            'sat' => '5:00 pm',
			            'sun' => ''
			        )
			)
		);
		$eid   = $data['geid'];
		$id    = Utilities::decrypt($eid);
		$group = G_Company_Structure_Finder::findById($id);
		$schedule = $data['schedule'];
		if( $group ){
			$return = $group->addSchedule($schedule);
		}

		Utilities::displayArray($return);
	}

	function breakTimeSchedule()
	{
		$id   = 11;
		$date = '2015-02-18';
		$e    = G_Employee_Finder::findById($id);

		$a    = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if( !empty($a) ){
			$data = $a->groupTimesheetData();	
			Utilities::displayArray($data);
		}else{
			echo "No attendance data found";
		}	
		
	}

	function checkDuplicates()
	{
		$break_time_schedules = array( 
            0 => Array
                (
                    'break_in' => '10:00 am',
                    'break_out' => '10:15 pm',
                    'is_deducted' => 1
                ),
            1 => Array
                (
                    'break_in' => '10:00 am',
                    'break_out' => '10:15 pm'
                )

        );
		$is_with_duplicate = false;
		foreach( $break_time_schedules as $schedule ){
			$breakin[]  = $schedule['break_in'];
			$breakout[] = $schedule['break_out'];
		}

		$duplicates       = array_diff_key($breakout, array_unique(array_map('strtolower', $breakout)));
		$filterDuplicates = array_unique($duplicates);
		Utilities::displayArray($filterDuplicates);
	}

	function updateBreakTimeSchedule()
	{
		$data = array(
			'token' => '67410acb3cbb08e68b42649dc2386192',
		    'heid' => 'nj9DdvCC_Tdq3WR7orLS28GiakoiEzSB6ePlKs6lQXE',
		    'schedule_in' => '08:00 AM',
		    'schedule_out' => '05:00 PM',
		    'date_start' => '2015-03-01',
		    'breaktime' => Array
		        (
		            1 => Array
		                (
		                    'break_in' => '8:00 am',
		                    'break_out' => '8:30 am',
		                    'is_deducted' => 1
		                ),

		            2 => Array
		                (
		                    'break_in' => '12:00 pm',
		                    'break_out' => '1:00 pm'
		                )
		        )
		);

		$id = Utilities::decrypt($data['heid']);		
		$schedule_in    = $data['schedule_in'];
		$schedule_out   = $data['schedule_out'];
		$breaktime      = $data['breaktime'];		
		$date_start     = $data['date_start'];

		$br = G_Break_Time_Schedule_Header_Finder::findById($id);
		if( $br ){
			$br->setScheduleIn($schedule_in);
			$br->setScheduleOut($schedule_out);						
			$br->setDateStart($date_start);
			$data = $br->updateBreakTimeSchedule($breaktime);
		}
		
		Utilities::displayArray($data);
	}

	function getEmployeeBreakTimeSchedule(){
		$employee_id = 76;
		//$day_type[] = "applied_to_legal_holiday";	
		//$day_type[] = "applied_to_regular_day";		
		$day_type[] = "applied_to_restday";		
		$schedule['schedule_in']  = "2015-05-10 07:30:00";
		$schedule['schedule_out'] = "2015-05-10 17:00:00";

		$e = new G_Employee();
		$e->setId($employee_id);
		$data   = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);
		$deduct = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);		
		Utilities::displayArray($data);
		echo $deduct;
	}

	function scheduleObjData() {
		$header_id = 1;
		$details   = new G_Break_Time_Schedule_Details();
		$details->setHeaderId($header_id);
		$data = $details->getObjDataByHeaderId();

		Utilities::displayArray($data);
	}

	function getNextCutoffData() {
		$id = 2;
		$c  = new G_Cutoff_Period();
		$c->setId($id);
		$data = $c->getNextCutOff();

		Utilities::displayArray($data);
	}

	function getPreviousCutoffData() {
		$id = 3;
		$c  = new G_Cutoff_Period();
		$c->setId($id);
		$data = $c->getPreviousCutOff();

		Utilities::displayArray($data);
	}

	function addBreakTimeSchedule()	{
		$data = Array
			(
			    
			    'schedule_in' => '8:00 am',
			    'schedule_out' => '5:00 pm',
			    'breaktime' => Array
			        (
			            0 => Array
			                (			                
			                    'break_in' => '10:00 am',
			                    'break_out' => '10:15 am',
			                    'applied_to_day_type' => array('regular_day' => 1, 'restday' => 1),
			                    'is_deducted' => 1
			                ),
			            1 => Array
			                (
			                    'break_in' => '10:15 am',
			                    'break_out' => '10:20 pm',
			                    'applied_to_day_type' => array('regular_day' => 1, 'legal_holiday' => 1),
			                    'is_deducted' => 1
			                )

			        ),
			    'breaktime_applied_to' => 'CscSL-hGVsmC4xz8zJ7wuz0S5eKfzpxrPR40iVylW7Q:d,NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg:e',
			    'breaktime_applied_to_all' => 1,
			    'date_start' => '2015-02-28'
			);

		$schedule_in  = $data['schedule_in'];
		$schedule_out = $data['schedule_out'];
		$breaktime    = $data['breaktime'];
		$applied_to   = $data['breaktime_applied_to'];
		$applied_to_all = $data['breaktime_applied_to_all'];
		$date_start   = $data['date_start'];

		$br = new G_Break_Time_Schedule_Header();
		$br->setScheduleIn($schedule_in);
		$br->setScheduleOut($schedule_out);
		$br->setAppliedTo($applied_to);
		$br->setIsAppliedToAll($applied_to_all);
		$br->setDateStart($date_start);
		$br->setDateCreated($this->c_date);
		$data = $br->addBreakTimeSchedule($breaktime);

		Utilities::displayArray($data);

	}

	function showBreaktimeScheduleData()
	{
		$header_id = 1;
		$br = G_Break_Time_Schedule_Header_Finder::findById($header_id);
		if( $br ){
			$data = $br->getBreakTimeHeaderAndDetailsData();
		}

		Utilities::displayArray($data);

	}

	function newManPowerReport()
	{
		$data =	array(
		    'date_from' => '2014-02-01',
		    'date_to' => '2015-02-26',		    
		    'manpower' => array(
		    	'department_id' => 'CscSL-hGVsmC4xz8zJ7wuz0S5eKfzpxrPR40iVylW7Q',
		    	'gender' => array('Male','Female'),
			    'groups' => array
			        (
			            9 => 'Sample Logistics Group 01',
			            14 => 'Sample Logistics Group 02'
			        ),
			    'employment_status' => array
			        (
			            0 => 'Full Time',
			            1 => 'Probationary'
			        ),
			    'skills' => array
			        (
			            0 => 'Computer/Technical Literacy',
			            1 => 'Flexibility/Adaptability/Managing Multiple Priorities',
			            2 => 'Analytical/Research'
			        ),
			    'educational_courses' => array
			        (
			            0 => 'Sample Course 02',
			            1 => 'Sample Course 04'
			        ),
			    //'report_type' => 'Late'
			    //'report_type' => 'Leave'
			    //'report_type' => 'Present'
			    //'report_type' => 'Official Business'
			    //'report_type' => 'Overtime'
			    'report_type' => 'Manpower'
			)
		);		

Utilities::displayArray($data);
		$date_from = $data['date_from'];
		$date_to   = $data['date_to'];
		$data      = $data['manpower'];		

		$report = new G_Report();
		$report->setFromDate($date_from);
		$report->setToDate($date_to);
		$result = $report->generateManpowerReport($data);

		Utilities::displayArray($result);

	}

	function validateEmployeeOvertime()
	{
		$eid           = 2;
		$date_overtime = "2015-02-02";
		$ot_time_in    = "06:00 PM";
		$ot_time_out   = "08:00 PM";

		$ot = new G_Overtime();
		$ot->setEmployeeId($eid);
		$ot->setDate($date_overtime);
		$ot->setTimeIn($ot_time_in);
		$ot->setTimeOut($ot_time_out);
		$data = $ot->validateRequest();

		Utilities::displayArray($data);

	}

	function getAllValidDepartments()
	{
		$fields = array('id','title');
		$ordery_by = "ORDER BY title ASC";
		$cs = new G_Company_Structure();
        $departments = $cs->getAllIsNotArchiveDepartments($fields, $order_by);

        Utilities::displayArray($departments);

	}

	function getAllEmploymentStatus()
	{
		$fields = array('id','code','status');
		$ordery_by = "ORDER BY code ASC";
		$es = new G_Settings_Employment_Status();
        $data = $es->getAllEmploymentStatus($fields, $order_by);

        Utilities::displayArray($data);
	}

	function getAllValidEducationalCourse()
	{
		$fields = array('DISTINCT(course)AS course');
		$ordery_by = "ORDER BY code ASC";
		$ee = new G_Employee_Education();
        $data = $ee->getAllUniqueCourse($fields, $order_by);

        Utilities::displayArray($data);
	}

	function getAllSkills()
	{
		$fields = array('DISTINCT(skill)AS skill');
		$ordery_by = "ORDER BY skill ASC";
		$ss = new G_Settings_Skills();
        $data = $ss->getAllUniqueSkills($fields, $order_by);

        Utilities::displayArray($data);
	}

	function getAllReportOptions()
	{
		$report = new G_Report();
		$data = $report->getReportOptions();
		Utilities::displayArray($data);
	}

	function dateFormat()
	{
		//$from = date("Y-m-01");
        //$to   = date("Y-m-t");
        $date = "1/2/2015  6:00:00 PM";
        echo  date("H:i:s",strtotime($date));

        //echo $from . "/" . $to;
	}

	function getEmployeeLeaveTypeAvailable()
	{
		$employee_id = 1;
		$data = G_Employee_Leave_Available_Helper::sqlEmployeeLeaveTypeAvailable($employee_id);
		Utilities::displayArray($data); 
	}

	function getEmployeeRequestApprovers()
	{
		$id = 1;
		$e  = G_Employee_Finder::findById($id);
		if( !empty($e) ){
			$approvers = $e->getRequestApprovers();
		}

		Utilities::displayArray($approvers);
	}

	function ePayslipData()
	{
		$employee_id = 1;
		$from = '2015-01-16';
		$to   = '2015-01-31';
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		Utilities::displayArray($p);
	}

	function filterArray()
	{
		$approvers = Array
			(
			    1 => Array
			        (
			            0 => Array
			                (
			                    'employee_id' => 1,
			                    'employee_name' => 'Flores, Rose Ann Magnaye'
			                ),

			            1 => Array
			                (
			                    'employee_id' => 15,
			                    'employee_name' => 'Castillo, Maria Isabel Saralde'
			                )

			        ),

			    2 => Array
			        (
			            0 => Array
			                (
			                    'employee_id' => 20,
			                    'employee_name' => 'Doctora, Maileen Jeresano'
			                )

			        )

			);

		Utilities::displayArray($approvers);
	}

	function detectDuplicateApprovers()
	{
		$approvers[1] = 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg';
		$approvers[2] = 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg';
		$approvers[3] = 'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s';
		$approvers[4] = 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg';
		$approvers[5] = 'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s';
		$filterArray  = array_unique(array_map("strtoupper", $approvers));
		
		foreach($approvers as $value){						
			$arApprovers = array();
			$arApprovers = explode(",", $value);
			foreach($arApprovers as $approver){
				$newApprovers[] = $approver;	
			}			
		}
		
		$duplicates   = array_diff_key($newApprovers, array_unique(array_map('strtolower', $newApprovers)));
		$filterDuplicates = array_unique($duplicates);
		Utilities::displayArray($filterDuplicates);
	}

	function detectDuplicateApproversSub()
	{
		$approvers = Array(
		    1 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg,wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s',
		    2 => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4,dePHPO84eWKFRFfsZD3yb5ZMozJjdsp1ykV5jQvM3w8,NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg'
		);
		foreach($approvers as $value){						
			$arApprovers = array();
			$arApprovers = explode(",", $value);
			foreach($arApprovers as $approver){
				$newApprovers[] = $approver;	
			}			
		}

		$duplicates   = array_diff_key($newApprovers, array_unique(array_map('strtolower', $newApprovers)));
		$filterDuplicates = array_unique($duplicates);
		Utilities::displayArray($filterDuplicates);
	}

	function payoutDate()
	{
		$date    = "2014-12-01";
		$cycle	 = G_Salary_Cycle_Finder::findDefault();	
		$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());	
		Utilities::displayArray($current);
		$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());
		echo "Date : {$date} / Payout Date : {$payout_date}";
	}

	function updateRequestApproversData()
	{
		$data = array(
			'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg' => array('status' => 'Approved', 'remarks' => 'Test'),
			'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s' => array('status' => 'Approved', 'remarks' => 'Test123')
		);

		$id = 1;
		$request_type = G_Request::PREFIX_LEAVE;

		Utilities::displayArray($data);

		$r = new G_Request();
		$r->setRequestId($id);
		$r->setRequestType($request_type);
		$data = $r->updateRequestApproversDataById($data); //Update request approvers data
		$r->updateRequestStatus();

		Utilities::displayArray($data);
	}

	function newEnrollEmployeeToBenefit()
	{	

		$employee_ids      = "CEi8xKw5y7Xw5YYFHYFt6V5DsF3aSU5y8WZ9-mM-mz4,CEi8xKw5y7Xw5YYFHYFt6V5DsF3aSU5y8WZ9-mM-mz4,zyiG86Ya90sOgfDl6iM8YH6zT2IEld3H9dnzaxkeESc";
		$dept_section_ids  = "zyiG86Ya90sOgfDl6iM8YH6zT2IEld3H9dnzaxkeESc";
		$employment_status = "";

		$custom_criteria['No Absent'] = true;
		$custom_criteria['No Late']   = true;
		$criteria = implode(",", array_keys($custom_criteria)); 		
		
		$a_enrollees['employees']         = explode(",", $employee_ids);
		$a_enrollees['dept_section']      = explode(",", $dept_section_ids);
		$a_enrollees['employment_status'] = explode(",", $employment_status);
		$decrypt_data = true;	

		$b = new G_Employee_Benefits_Main();
		$b->setCompanyStructureid(1);
		$b->setBenefitId(1);
		$json = $b->setCriteria($criteria)->setBulkEnrollees($a_enrollees)->sanitizeBulkEnrollees( $decrypt_data )->removeDuplicateBulkEnrollees()->createBulkSaveArray()->deleteDuplicateData()->bulkSave();
		Utilities::displayArray($json);
	}

	function customCriteria()
	{
		$b = new G_Employee_Benefits_Main();
		$cc = $b->convertToArrayCustomCriteria("Days Absent : 2 / 1 to 10,Days Leave : 3 / 15 to 30");
		Utilities::displayArray($cc);
	}

	function employeeBenefits()
	{
		$benefits    = array();
		$employee_id = 76;
		$start_date  = '2015-05-01';
		$end_date    = '2015-05-15';

		$e = G_Employee_Finder::findById($employee_id);		
		if( $e ){
			$a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);			
			if( $a ){								
	        	$b          = new G_Employee_Benefits_Main();		        	
	        	$b->setCutoffEndDate($end_date);       	
	        	$applied_to = $b->validAppliedToOptions();	        	
	        	$present_days = 3;	     

	        	$a_criteria = array(G_Employee_Benefits_Main::CRITERIA_NO_UNDERTIME,G_Employee_Benefits_Main::CRITERIA_NO_LATE,G_Employee_Benefits_Main::CRITERIA_NO_ABSENT,G_Employee_Benefits_Main::CRITERIA_NO_LEAVE);

	        	$criteria = implode(",", $a_criteria);
       			$criteria = trim($criteria);
	        	$custom_criteria = array(G_Employee_Benefits_Main::CUSTOM_CRITERIA_ABSENT_DAYS => 0, G_Employee_Benefits_Main::CUSTOM_CRITERIA_LEAVE_DAYS => 0); 
	        	$cutoff          = array(G_Settings_Employee_Benefit::OCCURANCE_FIRST_CUTOFF, G_Settings_Employee_Benefit::OCCURANCE_ALL);
	        	$benefits = $b->setCriteria($criteria)->setEmployeeCustomCriteria($custom_criteria)->getEmployeeBenefitsWithCriteria($e, $applied_to, $cutoff)->convertBenefitsToEarningsArray();
	        	//Utilities::displayArray($benefits);
	        	/*foreach( $benefits as $b ){
	        		$amount = $b->getAmount();
	        		$a_amount = explode("/", $amount);
	        		if( !empty($a_amount) ){
	        			$new_amount = $a_amount[0] * $present_days;
	        			$b->setAmount($new_amount);
	        		} 
	        	}*/
	        	//$benefits   = $b->setCriteria($criteria)->getEmployeeBenefits($e, $applied_to, $cutoff);
	        	//$a_benefits = $benefits->a_employee_benefits;        	
	        	//$benefits = $b->getAllEmployeeBenefits()->getAl
	        }
    	}

    	Utilities::displayArray($benefits);
	}

	function employeeIds()
	{
		$fields = array("id");
		$employee_ids = G_Employee_Helper::sqlAllActiveEmployee($fields);
		foreach( $employee_ids as $employee ){
			$employees[] = $employee['id'];
		}
		Utilities::displayArray($employees);

	}

	function enrollEmployeeToBenefit()
	{
		$employee_id          = 1;		
		$benefits 			  = array("wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s","rPn_n_eXTu4htnooJyAx-2NRCcVhfRIF0rvjqQz3q-I","QmHY-wZzstfI65vHWXaZ0by1yeWrVD6v41kRWzmCUow");
		$company_structure_id = 1;
		$e = G_Employee_Finder::findById($employee_id);
		if( $e ){
			$return = $e->enrollToBenefits($benefits, $company_structure_id);
		}

		Utilities::displayArray($return);
	}

	function requestApproversData()
	{
		$id = 2;
		$ra = new G_Request_Approver();
		$ra->setId($id);
		$data = $ra->getDataById();

		Utilities::displayArray($data);
	}

	function deleteRequestApprovers()
	{
		$id = 7;
		$ra = new G_Request_Approver();
		$ra->setId($id);
		$data = $ra->deleteRequestApprovers();
		Utilities::displayArray($data);
	}

	function employeeContri()
	{
		$employee_id = 2;
		$e      = G_Employee_Finder::findById($employee_id);
		$contri = G_Employee_Contribution_Finder::findCurrentContribution($e);
		if( $contri ){
			$to_deduct =  unserialize($contri->getToDeduct());
		}

		

		Utilities::displayArray($to_deduct);
	}

	function enrollEmployeesToBenefit()
	{
		$benefit_id = 1;
		$company_structure_id   = 1;
		//$apply_to 				= Employee_Benefits_Main::EMPLOYEE;
		$apply_to 				= Employee_Benefits_Main::ALL_EMPLOYEE;
		$encrypted_employee_ids = "wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s,NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg";

		$b = new G_Employee_Benefits_Main();
		$b->setCompanyStructureid($company_structure_id);
		$b->setBenefitId($benefit_id);
		$b->setAppliedTo($apply_to);
		$data = $b->enrollToBenefit($encrypted_employee_ids);	

		Utilities::displayArray($data);
	}

	function cutoffPeriod()
	{
		$cp = new G_Cutoff_Period();
		$cp->generateCurrentCutoffPeriod();
	}

	function processAttendanceLogById()
	{
		$attendance_log_pkid = 3;
		$at = G_Attendance_Log_Finder::findById($attendance_log_pkid);
		if( $at ){
			//Create timesheet data	
			$process = false;	
			if( $at->getType() == G_Attendance_Log::TYPE_IN ){
				$index_inout = "in";
				$process     = true;
			}elseif( $at->getType() == G_Attendance_Log::TYPE_OUT  ){
				$index_inout = "out";
				$process     = true;
			}    		

			if( $process ){
				$raw_timesheet[$at->getEmployeeId()][$index_inout][$at->getDate()] = array($at->getTime() => $at->getDate());
				$r = new G_Timesheet_Raw_Logger($raw_timesheet);
				$r->logTimesheet();

				$tr = new G_Timesheet_Raw_Filter($raw_timesheet);
				$tr->filterAndUpdateAttendance();
			}
		}
	}

	function parseURL()
	{
		$url =  $_SERVER['QUERY_STRING'];
		//$url = "http://localhost/products/version/v1/beta/hr/index.php/reports/payroll_management#payslip";
		$data = parse_url($url);
		Utilities::displayArray($data);

	}

	function validCutOffDays()
	{
		$dates = array();
		$cp    = new G_Settings_Pay_Period();
		$cutoff_days = $cp->getValidCutoffDays();

		echo "<pre>";
		print_r($cutoff_days);
	}

	function switchToMenu()
	{		
		$permissions['hr']      = $this->global_user_hr_actions;
		$permissions['payroll'] = $this->global_user_payroll_actions;
		$permissions['dtr']     = $this->global_user_dtr_actions;

		$menu = new Sprint_Menu_Builder($permissions);
		$switch_to = $menu->buildSwitchToMenu();
		echo $switch_to;
	}

	function headerMenu()
	{
		$menu = new Sprint_Menu_Builder($this->global_user_hr_actions, G_Sprint_Modules::HR, '');
		$header_menu = $menu->buildHeaderMenu();

		echo $header_menu;
		//Utilities::displayArray($header_menu);		
	}

	function redirectNoAccess()
	{	
		$module = 'settings';
		$mod = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$mod->validateUserCanAccessModule($this->global_user_hr_actions, $module);
	}

	function getAllUserIsNotArchive() 
	{
		$u = new G_Employee_User();
		$data = $u->getAllUserIsNotArchive();
		$data = $u->encryptId($data);

		Utilities::displayArray($data);
	}

	function usernameSpecialCharDetector() 
	{
		$username = "ab_234";
		$u = new G_Employee_User();
		$u->setUsername($username);
		$is_exists = $u->isUserNameWithSpecialChar();

		echo $is_exists;
	}

	function addUser()
	{
		$employee_id 		  = 2;
		$username    		  = "test222";
		$password    		  = "abc123";
		$role_id     		  = 5;
		$company_structure_id = 1;

		$u = new G_Employee_User();
		$u->setCompanyStructureId($company_structure_id);
        $u->setEmployeeId($employee_id);        
        $u->setUsername($username);                
        $u->setPassword($password);                
        $u->setRoleId($role_id);                
        $u->setDateCreated($this->c_date);                
		$return = $u->addUser();

		Utilities::displayArray($return);
	}

	function rolesList()
	{
		$role_id = 2;
		$hr_search      = "employees";
		$payroll_search = "";

		$ra = new G_Role_Actions();
		$ra->setRoleId($role_id);
		$hr_data      = $ra->getAllHRRoleActionsByRoleId();
		$payroll_data = $ra->getAllPayrollRoleActionsByRoleId();

		echo "<pre>";
		echo "HR";
		print_r($hr_data);
		echo "Payroll";
		print_r($payroll_data);
		echo "</pre>";

		if( array_key_exists($hr_search, $hr_data) ){
			echo "Key exists!";
		}else{
			echo "Key does not exists!";
		}
	}

	function addDefaultDependents()
	{
		$num_dependents = 3;
		$employee_id    = 1;
		$hired_date     = "2014-01-23";

		$dependent = new G_Employee_Dependent();
        $dependent->setEmployeeId($employee_id);
        $dependent->setRelationship('Sibling');
        $dependent->setBirthdate($hired_date);
        $dependent->defaultDependents($num_dependents);
	}

	function getEarnings()
	{
		$cutoff = G_Cutoff_Period_Finder::findById(126);
		$e      = G_Employee_Finder::findByEmployeeCode('GC005');
		$other_earnings = G_Employee_Earnings_Helper::getOtherEarnings($e, $cutoff);
		echo "<pre>";
		print_r($other_earnings);
	}

	function employeeLeave()
	{
		echo "<pre>";
		print_r($_SESSION['sprint_hr']);
		exit;
		$employee_id 		  = 2;
		$company_structure_id = 1;

        $leave_id     = G_Leave::ID_SICK;
        $date_applied = "2014-09-22";
        $time_applied = "17:11:11";
        $date_start   = "2014-09-22";
        $date_end     = "2014-10-23";       
        $comment      = "Sample Leave";
        $status       = G_Employee_Leave_Request::APPROVED;
        $is_paid      = G_Employee_Leave_Request::YES;
        $is_halfday   = G_Employee_Leave_Request::YES;
        $created_by   = "Admin";

        $el = new G_Employee_Leave_Request();        
		$el->setCompanyStructureId($company_structure_id);
		$el->setEmployeeId($employee_id);
		$el->setLeaveId($leave_id);
		$el->setDateApplied($date_applied);
        $el->setTimeApplied($time_applied);
		$el->setDateStart($date_start);
		$el->setDateEnd($date_end);
		$el->setApplyHalfDayDateStart($is_halfday);		
		$el->setLeaveComments($comment);
		$el->setIsApproved($status);
		$el->setIsPaid($is_paid);
		$el->setCreatedBy($created_by);
		$return = $el->saveRequest();

		print_r($return);
	}

	function objectViewerArray()
	{
		echo "<pre>";
		
		$cstructure    = G_Company_Structure_Finder::findById(1);	
		$job           = G_Job_Helper::sqlFindByCompanyStructureId(1, $order_by,10); //$cstructure->getId()
		$total_records = G_Job_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		//print_r($job);
		/*foreach ($job as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}	*/

		print_r($job);
	}

	function isWithinCutoff()
	{
		echo "<pre>";		
		
		$today 		  = date("Y-m-d");
		$holiday_date = "2015-02-18";
		echo $today;

		$cp = new G_Cutoff_Period();
		$data = $cp->getCurrentCutoffPeriod($today);

		if( $holiday_date >= $data['period_start'] && $holiday_date <= $data['period_end'] ){
			echo "Is within cutoff period";
		}else{
			echo "Not within cutoff period";
		}

		print_r($data);
	}

	function cutoffPeriodByDate()
	{
		$holiday_date = "2015-02-18";
		$cp = new G_Cutoff_Period();
		$data = $cp->getCurrentCutoffPeriod($holiday_date);
		Utilities::displayArray($data);		
	}

	function getEmployeeSSSContributions()
	{
		echo "<pre>";

		$date_from      = "2014-09-01";
		$date_to        = "2014-09-30";
		
		$employee_ids   = array("all");
		$department_ids   = array("all");

		//$employee_ids   = array(3847,3846);
		//$department_ids = array(8,3); 

		$r = new G_Report();
		//$r->setEmployeeIds($employee_ids);
		$r->setDepartmentIds($department_ids);
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getEmployeeSSSContributions();

		print_r($data);

	}
	
	function saveDefaultRequirements()
	{
		$a_id = 5;
		$gar = new G_Applicant_Requirements();
		$gar->setApplicantId($a_id);
		$gar->loadDefaultRequirements();
	}

	function addEmployeeInOut() 
	{
		$return = array();

		$employee_pkid = 3;		
		$date_in  = "2014-08-22";
		$time_in  = "10:45 AM";

		$date_out = "2014-08-22";
		$time_out = "09:00 PM";

		$date_time_in  = array($date_in => $time_in);
		$date_time_out = array($date_out => $time_out); 

		$at = new G_Attendance_Log();
		$at->setEmployeeId($employee_pkid);				
		$at->setDateTimeIn($date_time_in);
		$at->setDateTimeOut($date_time_out);
		$return = $at->addAttendanceLog();

		echo "<pre>";
		print_r($return);
	}
	
	function getEmployeeBenefits()
	{
		$eid = 26;
		$benefits = G_Employee_Benefit_Helper::getAllEmployeeBenefits($eid);
		echo "<pre>";
		print_r($benefits);
	}

	function decryptPw()
	{
		$pw = "11KvZoWjf07-TVMReIoJ0DYNrOIW0JVK0U9_VLF6uVfE";
		echo Utilities::decrypt($pw);
	}
	
	function assignCompanyBenefit()
	{
		$eid = "QmHY-wZzstfI65vHWXaZ0by1yeWrVD6v41kRWzmCUow,opH4mUMyDJ0AK3LA_xz5PRvtHrceaTk2nrm4UOvo3GI";
		$bid = 1;
		echo "<pre>";
		$e_array = explode(",",$eid);
		echo $this->c_date;
		foreach($e_array as $e){
			$eid = Utilities::decrypt($e);
			$e   = G_Employee_Finder::findById($eid);
			if($e){
				$b = new G_Employee_Benefit();				
				$b->setBenefitId(G_Employee_Benefit::ALL_EMPLOYEE);
				$b->setEmployeeId($e->getId());
				$b->setDateCreated($this->c_date);
				$b->save();
			}			
		}
		//print_r($e_array);
	
	}
	
	function attendanceDateFormat()
	{
		$inout = "2013-02-21 18:50:31";
		$newinout = date("m/d/Y G:i",strtotime($inout));
		echo $newinout;
	}
	
	function tardinessData()
	{
		echo "<pre>";
		$query['date_from'] = "2013-02-16";
		$query['date_to']   = "2013-02-25";
		
		$query['field']  = "job_id";
		$query['search'] = "15";
		
		$a    = new G_Attendance();
		$data = $a->getTardinessData($query);
		print_r($data);
	}
	
	function attendanceAbsenceData()
	{
		echo "<pre>";
		
		$query['date_from'] = "2013-02-16";
		$query['date_to']   = "2013-02-28";
		
		//$query['field']  = "job_id";
		//$query['search'] = "15";
		
		$a = new G_Attendance();
		$data_sub = $a->getTardinessData($query);
		$data     = $a->countTardinessData($query);
		//$data = $a->countAttendanceAbsenceData($query);
		print_r($data_sub);
		echo "<br /><br />";
		print_r($data);
	}
	
	function employeeScheduleArrayGenerator()
	{
		$eid    = "5,25";
		$eArray = explode(",",$eid);
		
		$schedule = new G_Schedule();
		$sArray = $schedule->loadArrayEmployeeSchedule($eArray);
		foreach($sArray as $key => $value){
			echo $key;
			foreach($value as $v){
				print_r($v);
			}
		}
		//echo "<pre>";
		//print_r($sArray);
		
		
	}
	
	function testSQL() 
	{
		$eid = 25;
		$egsA = G_Employee_Group_Schedule_Helper::getEmployeeGroupSchedule($eid);
		echo "<pre>";
		print_r($egsA);
	}
	
	function generatePayrollPeriodArray()
	{
		//$cycle = G_Salary_Cycle_Finder::findDefault();
		//print_r($cycle);
		//exit;
		$gcp     = new G_Cutoff_Period();
		$cutoffs = $gcp->generatePayrollPeriodByYear(date("Y"));		
		echo "<pre>	";
		print_r($cutoffs);
		foreach($cutoffs as $node){
			foreach($node as $n){
				echo $n['start'] . ' ' . $n['end'] . '<br />';
			}
		}
	}
	
	function savePayrollPeriodArray()
	{
		$gcp     = new G_Cutoff_Period();
		$cutoffs = $gcp->savePayrollPeriodByYear(date("Y"));				
	}
	
	function activeToTerminated()
	{
		$e = G_Employee_Finder::findById(5);		
		if($e){
			$data['memo'] = 'Terminated';
			$data['terminated_date'] = date('Y-m-d');
			$e->activeToTerminated($data);
			echo "Updated";
		}
	}
	
	function unSerializeData()
	{
		$string = 'a:5:{s:20:"required_2x2_picture";s:0:"";s:7:"medical";s:0:"";s:3:"sss";s:0:"";s:3:"tin";s:0:"";s:6:"test_1";s:0:"";}';
		$data = unserialize($string);
		print_r($data);
	}
	
	function serializeData()
	{
		$string = 'required_2x2_picture,medical,tin';
		$data = serialize($string);
		echo $data;
		
	}
	
	function create_xml()
	{
		header("Content-Type:text/xml");
		$xml = new Xml;
		$xml->setNode('questions');
		//----test object----		
		$xmlObj =  $xml->toXml('1234');
	}
	
	function loan_payments()
	{
		$gel = G_Employee_Loan_Finder::findById(4);		
		$total_payments = G_Employee_Loan_Helper::getTotalLoanPayments($gel);		
		$new_balance    = $gel->getLoanAmount() - $total_payments;
		$gel->setBalance($new_balance);
		$gel->save();
		echo $new_balance;
	}
	
	function testEarnings()
	{
		$earnings = G_Employee_Earnings_Finder::findById(1);
		$eArr     = Tools::convertStringToArray(",",unserialize($earnings->getEmployeeId()));
		echo '<pre>';
		print_r($eArr);
		foreach($eArr as $key => $value){
			echo $value;
		}
	}
	
	function computePerformanceAverage()
	{
		echo 1;
	}
	
	function testUrlSubFolder()
	{
		echo payroll_url('test/test');
	}
	
	function testBranch()
	{
		$cstructure = G_Company_Structure_Finder::findById(1);	
		$gcb = new G_Company_Branch();
		$gcb->setIsArchive(G_Company_Branch::NO);	
		$gcb->setName('test');
		$gcb->setProvince('test');	
		$gcb->setCity('test');				
		$gcb->setAddress('test');
		$gcb->setZipCode('test');
		$gcb->setPhone('test');
		$gcb->setFax('test');
		$gcb->setLocationId(1);
		$id = $gcb->save($cstructure);
		echo 'id : ' . $id;
	}
	
	function populateTaxTable()
	{
		$company_structure_id = 1;
		
		$gtt = new G_Tax_Table();		
		$gtt->setCompanyStructureId($company_structure_id);
		$gtt->setPayFrequency(G_Tax_Table::MONTHLY);
		$gtt->setStatus("ME4/S4");				
		$gtt->setD0("150.0");				
		$gtt->setD1("1");
		$gtt->setD2("12500");
		$gtt->setD3("13333");
		$gtt->setD4("15000");
		$gtt->setD5("18333");
		$gtt->setD6("24167");
		$gtt->setD7("33333");
		$gtt->setD8("54167");		
		$id = $gtt->save();
		echo $id;
	}
	
	function testOBRequestReport()
	{
		echo '<pre>';
		$from = '2012-12-01';
		$to   = '2012-12-15';
		$company_structure_id = 1;
		$requests = G_Employee_Official_Business_Request_Helper::getAllByPeriodAndCompanyStructureId($from,$to,$company_structure_id);
		print_r($requests);
		foreach($requests as $r){
			echo $r['emp_name'];
		}
	}
	
	function testSerialize()
	{
		$string    = "All Employees";
		$serialize = serialize($string);
		echo $serialize;
		echo '<br>';
		echo unserialize($serialize); 
	}
	
	function testCountEmp()
	{
		$date_start = '01/01/2012';
		$date_to    = '12/31/2012';
		echo '<pre>';
		/*$total_hired      = G_Employee_Helper::getTotalHiredByYearAndMonth();
		$total_terminated = G_Employee_Helper::getTotalTerminatedByYearAndMonth();		
		$total_employee   = array_merge((array)$total_hired,(array)$total_terminated);
		//print_r($total_employee);
		foreach($total_employee as $key=>$val) {
			$to_hired += $val['total_hired'];
			$to_hired -= $val['total_terminated'];
			//if($val['year']==$year) {
				$month_string[] = Date::getMonthName($val['month']);
				$no_terminated[] = $val['total_terminated'];
				$no_hired[] = $total_hired; //$val['total_hired'];
				//echo $val['year'] . ' '. Date::getMonthName($val['month'])	.'<br> Total Hired: '. $val['total_hired']. '<br> Total Terminated: ' . $val['total_terminated']. '<br>';	
			//}
		}
		
		foreach($no_hired as $key=>$hired) {
			$hired = ($hired) ? $hired : 0 ;
			print_r($hired);
			$strXml .= "     <set value='".$hired	."' />";	
		}*/
		
		$status = G_Employee_Job_History_Helper::getTotalEmployeeStatusByDateRange($date_start,$date_to,1);
		foreach($status as $key=>$val) {
			foreach($val as $key_sub=>$sub_val){					
				if($key_sub != 'year' && $key_sub != 'month'){
					$total[$key_sub] += $sub_val; 					
				}
			}			
			$month_string[] = Date::getMonthName($val['month']);
		
		}
		
		/*foreach($total as $key=>$value){
			foreach($status as $key_status=>$val_status){
				foreach($val as $key_status_sub=>$sub_val){						
					if($key == $key_status_sub){
						echo $key_status_sub . ' ' . $val_status[$key] . '<br>';
					}
				}
			}
		}		*/
		//$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId();
		
		
		foreach($total as $key=>$value){					
			$title = str_replace("_"," ",strtoupper($key));
			$strXml .= "2<dataset seriesName='" . $title . "'>";			
				foreach($status as $key_status=>$val_status){
					foreach($val as $key_status_sub=>$sub_val){	
						if($key == $key_status_sub){
							echo $key . '=' . $key_status_sub . '=' . $val_status['month'] .  '<br>';							
							$chartArr['year']  = $val_status['year'];
							$chartArr['month'] = $val_status['month'];
							$chartArr['total'] = $val_status[$key];
							$strXml .= "<set value='". $val_status[$key] ."'></set>";	
						}
					}
				}			
			$strXml .= "   </dataset>";
		}
		echo $strXml;
		
	}
	
	function printEmployementStatus()
	{
		echo '<pre>';
		$eid = 1;
		$e   = G_Employee_Finder::findById($eid);
		print_r($e);
		//Subdivision
		$subdivisions = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($e->getId());
		$this->var['subdivisions'] = $subdivisions;
		
		//Job History
		$j_history    = G_Employee_Job_History_Finder::findByEmployeeId($e->getId());
		$this->var['j_history'] = $j_history;
		print_r($j_history);
	}
	
	
	function sumTotalLoanPayment()
	{
		$geld = G_Employee_Loan_Details_Finder::findById(1);				
		echo $total_payments_made  = G_Employee_Loan_Payment_Breakdown_Helper::sumTotalPaymentsByLoanPaymentId($geld);
		$new_period_due_amount= $geld->getAmount() - $total_payments_made;
	}
	
	function quarter_dates()
	{
		echo Tools::getQuarterByMonth(9);
	}
	
	function save_loan_payment_breakdown()
	{
		$gel  = G_Employee_Loan_Finder::findById(1);
		$geld = new G_Employee_Loan_Details();
		$geld->deleteAllUnpaidPaymentByLoanId($gel);
		$gel->saveLoanPaymentBreakDown();
	}
	
	function convert1530()
	{
		$start_date = '11/7/2012';
		$ddate = Tools::convertDate1530($start_date);
		echo date("F d, o",$ddate);	
	}
	
	function loan_schedule()
	{
		$gel 	       = G_Employee_Loan_Finder::findById(3);
		$n_installment = $gel->getNoOfInstallment();
		$start_date    = $gel->getStartDate();		
		for($x=1;$x<=$n_installment;$x++){
			$start_date =  strtotime(date("Y-m-d", strtotime($start_date)) . " +1 day");
			//Validate if Saturday or Sunday
				$day 		= date("l",$start_date);
				$start_date =  date("F d, o",$start_date);
				if($day != 'Saturday' && $day != 'Sunday'){					
					echo $start_date . '<br>';
				}	
			//
		}
	}
	
	function test_int()
	{
		
		$string = intval("10,000");
		echo number_format($string,2);
		
	}
	
	function get_loan_by_pay_date()
	{
		$eid = 14;
		$e   = G_Employee_Finder::findById($eid);
		
		$loans = G_Employee_Loan_Finder::findAllIsApprovedByEmployeeAndPayDate($e,'2012-10-18');
		print_r($loans);
	}
	
	function testStringSearch()
	{
		$mystring = 'abc';
		$findme   = 'ub';
		$pos = strpos($mystring, $findme);
		if ($pos === false) {
			echo "The string '$findme' was not found in the string '$mystring'";
		} else {
			echo "The string '$findme' was found in the string '$mystring'";
			echo " and exists at position $pos";
		}

	}
	
	function test_array()
	{
		$pos = "5Vi9sW9b3oQRFogWNHNoFfzeUDWyBJ6e24Mz7e9H7hE,NzonNsnlXgLm4QHfOMzThwO1QpiifoSPNzxc7t3W6uk";
		$arPos  = explode(",",$pos);
		foreach($arPos as $p){
			$pos = G_Job_Finder::findById(Utilities::decrypt($p));
			$newArPos[] = $pos->getId();			
			$arTitle[]  = $pos->getTitle() . "<br>";
		}
		print_r($newArPos);
		
		$pos_ids = implode(",",$newArPos);
		$desc = implode(",",$arTitle);	
		echo $desc;	
	}
	
	function _tree_view_asyn()
	{		
		$tree = Tree_View::asynCompanyStructure($_POST['root']);
		echo $tree;
	}
	
	function extract_array()
	{
		$pos = "pos-CscSL-hGVsmC4xz8zJ7wuz0S5eKfzpxrPR40iVylW7Q,pos-VM47nUhS6fk2Yg1OOatSSu1zL_Z9aX-TekUr98fPxBc,dept-0ff9N4H6GcKA4KaIBCxOElpzmFqv_e_uSgD6pEnlDD4";
		$arPos  = explode(",",$pos);
		
		foreach($arPos as $p){			
			if (strpos($p,'emp-')!== false) {				
				$employees[] = str_replace("emp-","",$p);
			}
			
			if (strpos($p,'pos-')!== false) {
				$positions[] = str_replace("pos-","",$p);
			}
			
			if (strpos($p,'dep-')!== false) {
				$departments[] = str_replace("dep-","",$p);
			}
		}
		echo '<pre>';
		print_r($positions);
		echo implode(",",$positions);
	}
	
	function compute_number_of_days()
	{
		$num_days = Tools::getDayDifference('2012-10-02','2012-10-04') + 1;
		echo $num_days = $num_days - 0.5;
	}
	
	function add_leave_credits()
	{
		$a = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId(83,1);
			$_POST['leave_credit'] = 1;
			if(empty($a)){
				$a = new G_Employee_Leave_Available();
				$available = $_POST['leave_credit'];
				$alloted   = $_POST['leave_credit'];				
			}else{
				$available = $a->getNoOfDaysAvailable() + $_POST['leave_credit'];
				$alloted   = $a->getNoOfDaysAlloted() + $_POST['leave_credit'];				
			}
			
			echo 'Available:' . $available . '<br>Alloted:' . $alloted;
			
			$a->setEmployeeId(83);
			$a->setLeaveId(1);
			$a->setNoOfDaysAlloted($alloted);
			$a->setNoOfDaysAvailable($available);
			$a->save();
	}

	function migrateTempData()
	{
		$columns = array('SumOfSSS','SumOfMed','SumOfPagIbig','TAXWHELD','13th','GROSS PAY');
	}

	function processYearlyBonusV2()
	{
		$data = Array(			    
		    'start_month' => 01,
		    'end_month' => 10,
		   	'cutoff_period' => '2015-12-16/2015-12-31',
		    'use-import-file' => false
		);

		$year   = date('Y');
		$bonus  = new G_Yearly_Bonus();
		$bonus->setMonthStart($data['start_month']);
		$bonus->setMonthEnd($data['end_month']);			

		if( $data['use-import-file'] ){	
		 	$yearly_bonus_data = ['year' => $year, 'cutoff' => $data['cutoff_period'], 'file' => 2];					
			$data   = $bonus->processYearlyBonus($yearly_bonus_data);
		}else{			
			$yearly_bonus_data = ['year' => $year, 'cutoff' => $data['cutoff_period'], 'action' => 2, 'selected' => array()];					
			$data   = $bonus->processYearlyBonus($yearly_bonus_data);
		}

		Utilities::displayArray($data);
	}

	function importTempData()
	{
		Loader::appStyle('style.css');
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('benchmark/import_temp_data.php',$this->var);	
	}

	function _import_temp_data()
	{		
		$migrate = new G_Migrate_Data();
		$result  = $migrate->importTempData($_FILES['temp_data']['tmp_name']);
		Utilities::displayArray($result);
		exit;
	}

	function convertLeaveCredit()
	{
		$cutoff_period = '2015-11-01/2015-11-15';
		$slg  = new G_Settings_Leave_General();        	
        $result = $slg->getAllUnusedLeaveCreditLastYear()->isCutoffPeriodLock($cutoff_period)->applyGeneralRule();        		

        Utilities::displayArray($result);
	}

	function getEmployeeCetaSeaRate()
	{
		$e = G_Employee_Finder::findById(20);
		$ceta_sea_rate = $e->getEmployeeCetaSeaRate();
		$daily_rate = $e->getEmployeeDailyRate();
		$daily_rate = str_replace(',', '', $daily_rate);
		//echo $rate;
		//echo $daily_rate;
		$amount = 3 * ($daily_rate + $ceta_sea_rate);
		echo $amount;
	}

	function getLeaveConversion()
	{
		$e = new G_Employee();
		$query['year'] = 2015;			
		$report_data = $e->getEmployeesYearlyConvertedLeave($query, $add_query);

		Utilities::displayArray($report_data);
	}

	function annualizeTax()
	{		

		$data = Array(
		    'year' => 2015,
		    'e_employee_id' => 'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s'
		);


		/*$data = array
		(		   
			'cutoff_period' => '2015-07-16/2015-07-31', 
		    'start_month' => 12,
		    'start_year' => 2014,
		    'end_month' => 11,
		    'end_year' => 2015
		);*/

		$cutoff_period = '2015-11-16/2015-11-30';
		
		$tax = new G_Annualize_Tax();
		$tax->setYear(date("Y"));	
		$tax->setCutoffPeriod($cutoff_period);	
		//$tax->setDateRange( array('start_month' => $data['start_month'], 'start_year' => $data['start_year'], 'end_month' => $data['end_month'], 'end_year' => $data['end_year']));
		$return = $tax->setDefaultFromAndEndDate()->validate($cutoff_period)->annualizeTax($employee_ids);

		Utilities::displayArray($return);
	}

	function alphaListReport(){
		$year = 2015;
		//$options['add_query'] = " AND (e.is_confidential = 1) ";	
		$options['add_query'] = " AND (e.is_confidential = 0) ";

		$report = new G_Report();
		$alpha_data = $report->alphaListReport($year, $options);
		Utilities::displayArray($alpha_data);
	}

	function otherEarningsReport() 
	{
		$data = Array(
		    'cutoff_period' => '2015-12-01/2015-12-15',
		    //'chk_all_earnings' => 'on',
		    'earnings_list' => 'Bonus3',
		    'earnings' => Array
		        (
		            0 => 'Bonus',
		            1 => 'Bonus3'
		        )

		);
		
		if( $data['chk_all_earnings'] ){
			$earnings = array();
		}else{			
			$earnings = $data['earnings'];
		}
		
		$param = array();
		$report = new G_Report();
		$data = $report->getOtherEarningsReport($data['cutoff_period'], $earnings);
		Utilities::displayArray($data);
	}

	function bir_2316(){
		$year = 2015;
		$options = array();

		$report = new G_Report();
		$alpha_data = $report->alphaListReport($year, $options);

		$eid = 404;

		$fields = array('id','employment_status_id');
		$a_employee = G_Employee_Helper::sqlGetEmployeeDetailsById($eid, $fields);
		//Utilities::displayArray($a_employee);

		switch ($a_employee['employment_status_id']) {
			case 3:
				//Regular
				$bir_2316 = $alpha_data[$eid];
				break;			
			case 5:
				//Contractual
				$bir_2316 = $alpha_data[$eid];
				break;
			default:				
				break;
		}
		Utilities::displayArray($bir_2316);
		//Utilities::displayArray($alpha_data);

		//2316 

	}

	function reportEmployee201(){
		$employee  = 'all';
		$employees = array(1,2);
	}

	function getEmployeeSchedule() {
		$date = '2016-02-16';		
		$employees   = G_Employee_Helper::getAllActiveEmployee();		

		/*$sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
        $night_shift_hr = $sv->getVariableValue();
        $a_nightshift   = explode("to", $night_shift_hr);
        $ns_start       = strtotime($a_nightshift[0]);*/
        $ns_start = "06:00:00";
        
        $night_sched = array();
        $day_sched   = array();

		foreach( $employees as $e ){
			$ss = G_Schedule_Specific_Finder::findEmployeeScheduleByEmployeeIdAndDate($e['id'], $date);        
			if( $ss ){	
				$end = $ss->getTimeOut();
				if( $end <= $ns_start ){
					$night_sched[$e['id']]['employee'] = $e;
					$night_sched[$e['id']]['schedule'] = $ss;
					$night_sched[$e['id']]['diff'] = array('fixed' => $ns_start, 'actual' => $end);
				}else{
					$day_sched[$e['id']]['employee'] = $e;
					$day_sched[$e['id']]['schedule'] = $ss;
					$day_sched[$e['id']]['diff'] = array('fixed' => $ns_start, 'actual' => $end);
				}						
			}else{
				 $s = G_Schedule_Finder::findScheduleByEmployeeIdAndDate($e['id'], $date);
				 if( $s ){
				 	$end = $s->getTimeOut();
					if( $end <= $ns_start ){
						$night_sched[$e['id']]['employee'] = $e;
						$night_sched[$e['id']]['schedule'] = $s;
						$night_sched[$e['id']]['diff'] = array('fixed' => $ns_start, 'actual' => $end);
					}else{
						$day_sched[$e['id']]['employee'] = $e;
						$day_sched[$e['id']]['schedule'] = $s;
						$day_sched[$e['id']]['diff'] = array('fixed' => $ns_start, 'actual' => $end);
					}	
				 }
			}
		}

		Utilities::displayArray($day_sched);		
		//Utilities::displayArray($night_sched);		
	}


	function testTime(){
		$time1 = "06:00:00";
		$time2 = "05:00:00";
		if( $time1 < $time2 ){
			echo "{$time1} is less {$time2}";
		}else{
			echo "{$time2} is less {$time1}";
		}
	}

	function newTimeDiff() {
		$schedIn  = '2016-03-07 06:30:00';
		$schedOut = '2016-03-08 06:00:00';

		$start_date = new DateTime($schedIn);
		$since_start = $start_date->diff(new DateTime($schedOut));
		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		$hours   = $minutes / 60;
		echo $hours.' hours';	
	}

	function isDateRestDay() {
		$is_restday = G_Attendance_Helper::sqlIsDateRdByEmployeeIdAndDate(157,'2016-02-20');
		
	}

	function updateEmployeeAttendanceByDateRange() {
		$employee_id = 531;
		$e = Employee_Factory::get($employee_id);
		$from = '2016-03-24';
		$to   = '2016-03-25';
		$is_updated = G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $from, $to);
	}

	function testA(){
		$pw = "1KvZoWjf07-TVMReIoJ0DYNrOIW0JVK0U9_VLF6uVfE";
		echo Utilities::decrypt($pw);
	}

	function manpowerCount(){
		$date_from = "2016-06-01";
		$date_to   = "2016-06-25";
		$report = new G_Report();
		$report->setFromDate($date_from);
		$report->setToDate($date_to);
		$result = $report->generateManpowerReport($manpower_data);

		Utilities::displayArray($result);
		exit;
	}

	function debugDate() {
		$date = "2016-03-14";
		$prev_date = date("F Y",strtotime($date . " -1 month"));		
		echo $prev_date;exit;
		/*$year  = "2016";
		$month = 8;		
		$start_date   = date("Y-m-d",strtotime($year . "-" . $month . "-" . "01"));		
		$month_string = date("F Y",strtotime($start_date));
		$end_day      = date("t",strtotime($month_string));			
		$end_date     = date("Y-m-d",strtotime($year . "-" . $month . "-" . $end_day));

		$groups = G_Schedule_Group_Finder::findAllInBetweenDate($start_date, $end_date);

		Utilities::displayArray($groups);
		echo $start_date . " / " . $end_date;*/
	}
}
?>