<?php
class Benchmark_Payroll_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appUtilities();

		Loader::appScript('startup.js');
		Loader::appStyle('style.css');
		Loader::appScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appStyle('jquerytimepicker/jquery.timepicker.css');
		$this->c_date  = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		$this->var['settings'] = 'current';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}

	function index() {

		$employee_code = !empty($_GET['employee_code']) ? $_GET['employee_code'] : 7983;
		$e 			   = G_Employee_Finder::findByEmployeeCode($employee_code);
		$from   	   = !empty($_GET['from']) ? $_GET['from'] : '2017-09-16';
		$to   		   = !empty($_GET['to']) ? $_GET['to'] : '2017-09-30';

		$update_attendance = $_GET['update_attendance'];
		$generate_payroll  = $_GET['generate_payroll'];

		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);

		$cutoff_details = G_Cutoff_Period_Finder::findByPeriod($from, $to);

		if($update_attendance == 'true') {
			//ini_set("memory_limit", "999M");
			//set_time_limit(999999999999999999999);
			
			$is_updated = G_Attendance_Helper::updateAttendanceByEmployeeAndPeriodWithCheckAttendanceValidation($e, $from, $to);
			if($is_updated) {
				echo 'Attendance Updated..';
				echo '<hr />';
			}
		}

		//Generate Employee Payroll - Start
		if($generate_payroll == 'true') {

			if($p) {
		        $month 		   = date('m', strtotime($cutoff_details->getStartDate()));
		        $cutoff_number = $cutoff_details->getCutoffNumber();
		        $year          = $cutoff_details->getYearTag();

		        $ids[] = $e->getId();
		        $selected_employee = implode(",",$ids);

		        $additional_qry = "";

		        $c = new G_Company;
		        $c->setFilteredEmployeeId($selected_employee);
		        $c->setAdditionalQuery($additional_qry);
		        $payslips = $c->generatePayslip($month, $cutoff_number, $year);	        
		        echo 'Payslip Generated..';
			}

		}
		//Generate Employee Payroll - End

		/* Payslip - Start */
        if (!$p) {
            echo 'no payslip found';
            exit;
        }

		$ph = new G_Payslip_Helper($p);

		$new_earnings   = $p->getBasicEarnings();
		$new_deductions = $p->getTardinessDeductions();
		$payslip_info   = $p->getEmployeeBasicPayslipInfo();

		$this->var['payslip_info']     = $payslip_info;
		$this->var['new_earnings']     = $new_earnings;
		$this->var['new_deductions']   = $new_deductions;	
		$this->var['total_earnings']   = $total_earnings = $ph->computeTotalEarnings();
		$this->var['total_deductions'] = $total_deductions = $ph->computeTotalDeductions();
		$this->var['net_pay'] = $total_earnings - $total_deductions;
		
		$this->var['earnings'] = $earnings = (array) $p->getEarnings();

		$this->var['other_earnings'] = $other_earnings = (array) $p->getOtherEarnings();		
		$this->var['deductions'] = $deductions = (array) $p->getDeductions();
		$this->var['other_deductions'] = $other_deductions = (array) $p->getOtherDeductions();

		$this->var['net_pay'] = $p->getNetPay();
		$this->var['gross_pay'] = $p->getGrossPay();
		$this->var['gov_deductions'] = array('SSS','Philhealth','Pagibig');
		/* Payslip - End */

		/* Timesheet - Start */
		$cutoff_periods = G_Cutoff_Period_Finder::findAll();
		$cutoff_periods_array = G_Cutoff_Period_Helper::convertToArray($cutoff_periods);

		$this->var['start_date'] = $start_date = (empty($from)) ? $cutoff_periods[0]->getStartDate() : $from;
		$this->var['end_date']   = $end_date = (empty($to)) ? $cutoff_periods[0]->getEndDate() : $to;			

		$this->var['dates'] 	  = Tools::getBetweenDates($start_date, $end_date);
		$attendance 			  = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		$attendance 			  = G_Attendance_Helper::changeArrayKeyToDateConstructed($attendance);		
		$this->var['employee_id'] = $employee_id;
		$this->var['attendance']  = $attendance;
		/* Timesheet - End */

		/* Payroll Register - Start */
		$data 				   = $_POST;
		$remove_resigned   	   = false;
		$remove_terminated 	   = false;
		$remove_endo       	   = false;
		$remove_inactive   	   = false;
		$add_bonus_to_earnings = false;
		$add_13th_month        = false;
		$add_converted_leave   = false;
		$qry_employee_type 	   = '';

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if( $remove_resigned ){
			$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
		}

		if( $remove_endo ){
			$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
		}

		if( $remove_terminated ){
			$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
		}

		if( $remove_inactive ){
			$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
		}			

		$qry_add_on[] = "(e.employee_code = ".$employee_code.")";

		if( !empty($qry_add_on) ){
			$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
		}

		$qry = new Query_Builder();
		$qry_string = $qry->setQueryOptions($data)->usePrefix('p')->setLogicalOperator('AND')->buildSQLQuery();
		$fields   = array("e.id","e.employee_code","e.lastname","e.firstname","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.department_company_structure_id LIMIT 1)AS department_name","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.section_id LIMIT 1)AS section_name ","(SELECT status FROM ".EMPLOYMENT_STATUS." WHERE id = e.employment_status_id) AS employment_status");
		$employees = G_Employee_Helper::sqlGetPayslipPeriodWithCustomQuery($from, $to, $is_confidential_qry, $qry_string, $fields, $order_by);
		$payslips  = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);

		$grouped_data = array();
		foreach( $employees as $employee ){
			$grouped_data[$employee['employment_status']][$employee['department_name'] ." - ". $employee['section_name']][] = $employee;
		}

		$this->var['employee_details']      = $e;
		$this->var['add_bonus_to_earnings'] = $add_bonus_to_earnings;
		$this->var['add_13th_month'] 		= $add_13th_month;
		$this->var['add_converted_leave'] 	= $add_converted_leave;
		$this->var['employee_type'] 		= $qry_employee_type;
		$this->var['grouped_data'] 			= $grouped_data;
		$this->var['employees'] 			= $employees;			
		$this->var['payslips']  			= $payslips;
		$this->var['total_employees'] 		= count($employees);
		/* Payroll Register - End */

		$this->view->noTemplate();
		$this->view->render('benchmark/index.php', $this->var);		
	}

	function generate_payslip() {

	}

	function update_attendance() {

	}

	
	
}
?>