<?php
class Payroll_Controller extends Controller
{
	function __construct()
	{
		$this->login();
		parent::__construct();		
		Loader::appMainUtilities();			

		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

	function download_cash_file() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);

		$s_from = $_GET['from'];
		$s_to   = $_GET['to'];
		$frequency_id   = $_GET['frequency'];

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$show_yearly_bonus = $_GET['show_yearly_bonus']; //
		$bonus_service_award = false; //
		$add_13th_month    = false; //
		$show_yearly_bonus_only = false;
		$add_bonus_service_award = false;
		$payslip_converted_leaves = false;
		$is_filter_by_cost_center = false;

		$is_filter_by_project_site = false;

		/*if( (isset($_GET['cost_center']) && $_GET['cost_center'] != '' && $_GET['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
		}*/

		if( (isset($_GET['project_site_id']) && $_GET['project_site_id'] != '' && $_GET['project_site_id'] != 'all')){
			$is_filter_by_project_site   = true;
		}

		if( isset($_GET['remove_resigned']) && $_GET['remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($_GET['remove_terminated']) && $_GET['remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($_GET['remove_endo']) && $_GET['remove_endo'] == 1 ){
			$remove_endo = true;	
		}

		if( isset($_GET['remove_inactive']) && $_GET['remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}	

		if( isset($_GET['bonus_service_award']) && $_GET['bonus_service_award'] == 1 ){
			$bonus_service_award = true;	
		}

		if( isset($_GET['add_13th_month_pay']) && $_GET['add_13th_month_pay'] == 1 ){
			$add_13th_month = true;	
		}	

		if( isset($_GET['show_yearly_bonus']) && $_GET['show_yearly_bonus'] == 1 ){
			$show_yearly_bonus_only = true;	
		}	

		if( isset($_GET['add_bonus_service_award']) && $_GET['add_bonus_service_award'] == 1 ){
			$add_bonus_service_award = true;
		}

		if( isset($_GET['add_converted_leaves']) && $_GET['add_converted_leaves'] == 1 ){
			$add_converted_leaves = true;
		}

		

		if (strtotime($s_from) && strtotime($s_to)) {
			$employee_type   = "";		
			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				$employee_type = trim(strtolower($_GET['q']));	
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$employee_type = "confidential";				
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$employee_type = "non-confidential";				
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}	

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}	

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}	

			//remove archive employee in report
			$qry_add_on[] = "(e.e_is_archive = 'No')";

			if($is_filter_by_project_site){
				$qry_add_on[] = "(e.project_site_id = '".$_GET['project_site_id']."')";
			}
			
			//$qry_add_on[] = "(e.employee_code = 225)";
			$employee_ids  = array();
			$data   	   = array();
			$report 	   = new G_Report();



			if($bonus_service_award == true && $add_13th_month != true && $show_yearly_bonus_only != true) {
				$data_bonus_service_award = $report->setFromDate($s_from)->setToDate($s_to)->setEmployeeType($employee_type)->setEmployeeIds($employee_ids)->cashFileReportBonusServiceAwardOnlyFilterStatus($qry_add_on, $show_yearly_bonus_only,$s_from,$s_to, $frequency_id);		
				
			} else {
				$data = $report->setFromDate($s_from)->setToDate($s_to)->setEmployeeType($employee_type)->setEmployeeIds($employee_ids)->cashFileReport($qry_add_on, $show_yearly_bonus_only, $frequency_id);	


			}	

		}

		$fields = array('title');
		$gc = new G_Company_Structure(G_Company_Structure::PARENT_ID);
		$company = $gc->getDepartmentDetailsById($fields);

		$s_hdr_from = date("F, d, Y",strtotime($s_from));
		$s_hdr_to   = date("F, d, Y",strtotime($s_to));
		
		$header['company_name']   = $company['title'];
		$header['report_name']    = 'PAYROLL BANK ADVICE LIST';
		$header['payroll_period'] = "{$s_hdr_from} to {$s_hdr_to}";
		$header['run_date']       = date("F, d, Y", strtotime($this->c_date));

		$this->var['frequency'] = $frequency_id;
		$this->var['s_from'] = $s_from;
		$this->var['s_to']   = $s_to;
		$this->var['add_13th_month']      = $add_13th_month;
		$this->var['bonus_service_award'] = $bonus_service_award;
		$this->var['show_yearly_bonus_only'] = $show_yearly_bonus_only;
		$this->var['add_bonus_service_award'] = $add_bonus_service_award;
		$this->var['add_converted_leaves'] = $add_converted_leaves;
		$this->var['header']      = $header;
		$this->var['filename']    = "cash_file_report.xls";


		//General Reports / Shr Audit Trail
        if($frequency_id == '1'){
	  		$frequency_name = 'Bi-Monthly';
	  	}
	  	else{
	  		$frequency_name = 'Weekly';	
	  	}

	  	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_GENERATE, ' Cash File Report of ', $frequency_name .' Employee type of '.$employee_type, $s_from, $s_to, 1, '', '');
		
		if($bonus_service_award == true && $add_13th_month != true && $show_yearly_bonus_only != true) {
		
			$this->var['cash_file'] = $data_bonus_service_award;
		
			$this->view->render('reports/cash_file/cash_file_bonus_and_service_award_only.php', $this->var);
		} else {
			
		
			$this->var['a_cash_file'] = $data;			

			if($show_yearly_bonus_only == true) {
				
				$this->view->render('reports/cash_file/cash_file_thirteenth_month_only.php', $this->var);
			} else {

				$this->view->render('reports/cash_file/cash_file.php', $this->var);
			}
			
		}
		
	}

	function download_payroll_register_new(){
		ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);

        $remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$qry_employee_type = '';

		$a_periods = explode("/", $_POST['cutoff_period']);
		$from      = trim($a_periods[0]);
		$to        = trim($a_periods[1]);

		if( isset($_POST['q']) ){
			$qry_employee_type = trim(strtolower($_POST['q']));	
		}

		if( isset($_POST['remove_resigned']) && $_POST['remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($_POST['remove_terminated']) && $_POST['remove_terminated'] == 1 ){
			$remove_terminated = true;
		}
		if( isset($_POST['remove_endo']) && $_POST['remove_endo'] == 1 ){
			$remove_endo = true;
		}

        $this->var['from']       = $from;
        $this->var['to']         = $to;
        $this->var['gov_contri'] = array('SSS','Philhealth','Pagibig');
        if (strtotime($from) && strtotime($to)) {
            $c = G_Cutoff_Period_Finder::findByPeriod($from, $to);
            $year = $c->getYearTag();
            $month = date('d', strtotime($c->getStartDate()));
            $code = $c->getCutoffCharacter();

            $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_endo ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}
			
			$this->var['end_date']			= $_POST['to'];
            $this->var['cutoff_code'] 		= $year .'-'. $month .'-'. $code;
            //$this->var['employees'] 		= $employees = G_Employee_Finder::findByPayslipPeriod($from, $to, $is_confidential_qry);
            $this->var['employees'] 		= $employees = G_Employee_Finder::findByPayslipPeriodOrderByDepartmentCompanyStructure($from, $to, $is_confidential_qry);

            $payslips 						= G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
            $this->var['payslips'] 			= $payslips;
            $this->var['total_employees'] 	= count($employees);   

        }

        $d_template = G_Payslip_Template_Helper::defaultTemplate();

        if(!empty($d_template)) {
        	$this->var['default_template'] = $d_template;
        } else { $this->var['default_template'] = null; }

        $this->view->noTemplate();
        $this->view->render('payroll/download_payroll_register.php', $this->var);	
	}

	function download_payroll_register() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;

		$a_periods = explode("/", $data['cutoff_period']);
		$from      = trim($a_periods[0]);
		$to        = trim($a_periods[1]);

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$add_bonus_to_earnings = false;
		$add_13th_month        = false;
		$add_converted_leave   = false;
		$show_13th_month_only  = false;
		$add_bonus_service_award = false;
		$is_filter_by_cost_center = false;
		$qry_employee_type = '';

		if( isset($data['q']) ){
			$qry_employee_type = trim(strtolower($data['q']));	
		}
		if( (isset($data['cost_center']) && $data['cost_center'] != '' && $data['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
		}
		if(isset($data['show_13th_month_only']) && $data['show_13th_month_only'] == 1){
			$show_13th_month_only = true;
		}

		if( isset($data['remove_resigned']) && $data['remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['remove_terminated']) && $data['remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['remove_endo']) && $data['remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['remove_inactive']) && $data['remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}
		if( isset($data['add_bonus_service_award']) && $data['add_bonus_service_award'] == 1 ){
			$add_bonus_to_earnings = true;	
		}
		if( isset($data['add_13th_month']) && $data['add_13th_month'] == 1 ){
			$add_13th_month = true;	
		}
		if( isset($data['add_converted_leave']) && $data['add_converted_leave'] == 1 ){
			$add_converted_leave = true;	
		}


		$this->var['from'] = $from;
		$this->var['to']   = $to; 
		$this->var['frequency']   = $data['frequency']; 

		if (strtotime($from) && strtotime($to)) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}	

			if($is_filter_by_cost_center){
				$qry_add_on[] = "(e.cost_center = '".$data['cost_center']."')";
			}
		
			//$qry_add_on[] = "(e.employee_code = 6402)"; //43 and 1886
			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}
			
			//unset($data['cutoff_period']);

			$qry = new Query_Builder();
			$qry_string = $qry->setQueryOptions($data)->usePrefix('p')->setLogicalOperator('AND')->buildSQLQuery();
			$fields   = array("e.id","e.employee_code","e.lastname","e.firstname","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.department_company_structure_id LIMIT 1)AS department_name","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.section_id LIMIT 1)AS section_name ","(SELECT status FROM ".EMPLOYMENT_STATUS." WHERE id = e.employment_status_id) AS employment_status");

			if ($data['frequency'] == 2) {
				$employees = G_Employee_Helper::sqlGetWeeklyPayslipPeriodWithCustomQuery($from, $to, $is_confidential_qry, $qry_string, $fields, $order_by);
				$payslips  = G_Weekly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
			}
			else {
				$employees = G_Employee_Helper::sqlGetPayslipPeriodWithCustomQuery($from, $to, $is_confidential_qry, $qry_string, $fields, $order_by);
				$payslips  = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
			}

			//Group data by department
			$grouped_data = array();
			foreach( $employees as $employee ){
				$grouped_data[$employee['employment_status']][$employee['department_name'] ." - ". $employee['section_name']][] = $employee;
			}

			/*Utilities::displayArray($grouped_data);
			exit;*/

			//Utilities::displayArray($payslips);        
            //Utilities::displayArray($grouped_data);
			
			$this->var['add_bonus_to_earnings'] = $add_bonus_to_earnings;
			$this->var['add_13th_month'] 		= $add_13th_month;
			$this->var['show_13th_month_only'] 	= $show_13th_month_only;
			$this->var['add_converted_leave'] 	= $add_converted_leave;
			$this->var['employee_type'] 		= $qry_employee_type;
			$this->var['grouped_data'] 			= $grouped_data;
			$this->var['employees'] 			= $employees;			
			$this->var['payslips']  			= $payslips;
			$this->var['total_employees'] = count($employees);
		}

		if(
			isset($data['show_13th_month_only']) && $data['show_13th_month_only'] == 1){
			$this->var['show_13th_month_only'] = $data['show_13th_month_only'];
			$this->var['add_13th_month'] 		= true;
			
			$this->view->render('payroll/download_payroll_register_13th_month_only.php', $this->var);
		}else{
		
			if( isset($data['bonus_and_service_award_only']) && $data['bonus_and_service_award_only'] == 1 ){
				
			$this->var['bonus_service_award_only'] = $data['bonus_and_service_award_only'];
			$this->view->render('payroll/download_payroll_register_bonus_service_award_only.php', $this->var);
			} else {
				// here

				// echo "<pre>";
				// var_dump($this->var);
				// echo "</pre>";
				$this->view->render('payroll/download_payroll_register.php', $this->var);		
			}	
		}

				
		
	}



	function download_cost_center() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$data = $_POST;

		$a_periods = explode("/", $data['cutoff_period']);
		$from      = trim($a_periods[0]);
		$to        = trim($a_periods[1]);

		$remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$add_bonus_to_earnings = false;
		$add_13th_month        = false;
		$add_converted_leave   = false;
		$tag_search        	   = false;
		$qry_employee_type = '';
		$is_filter_by_cost_center = false;
		$frequency_id = $data['frequency'];

		$is_filter_by_project_site = false;

		
		$getYear  = explode("-", $from);
		$year = trim($getYear[0]);
		
		$c = G_Weekly_Cutoff_Period_Finder::findAllByYear($year);

		foreach ($c as $value) {
			// echo $value->getStartDate();
			// echo "--";
			// echo $from;
			if($value->getStartDate() == $from){
				
				$year_tag = $value->getYearTag();
				$get_month = trim($getYear[1]);
				$get_char = $value->getCutoffCharacter();				 
				$cutoff_code = $year ."-".$get_month."-".$get_char;
			}
			// if($value->getStartDate() == $from ){
			// 	echo $value;
			// }
		}


		if( isset($data['q']) ){
			$qry_employee_type = trim(strtolower($data['q']));	
		}
		/*if( (isset($data['cost_center']) && $data['cost_center'] != '' && $data['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
		}*/

		if( (isset($data['project_site_id']) && $data['project_site_id'] != '' && $data['project_site_id'] != 'all')){
			$is_filter_by_project_site  = true;
		}

		if( isset($data['remove_resigned']) && $data['remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($data['remove_terminated']) && $data['remove_terminated'] == 1 ){
			$remove_terminated = true;	
		}
		if( isset($data['remove_endo']) && $data['remove_endo'] == 1 ){
			$remove_endo = true;	
		}
		if( isset($data['remove_inactive']) && $data['remove_inactive'] == 1 ){
			$remove_inactive = true;	
		}
		if( isset($data['add_bonus_service_award']) && $data['add_bonus_service_award'] == 1 ){
			$add_bonus_to_earnings = true;	
		}
		if( isset($data['add_13th_month']) && $data['add_13th_month'] == 1 ){
			$add_13th_month = true;	
		}
		if( isset($data['add_converted_leave']) && $data['add_converted_leave'] == 1 ){
			$add_converted_leave = true;	
		}
      	if( isset($data['tags']) && $data['tags'] != '' ){
        	$tag_search = true;
      	}

		$this->var['from'] = $from;
		$this->var['to']   = $to; 

		if (strtotime($from) && strtotime($to)) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($qry_employee_type == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($qry_employee_type == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
					$employee_type       = "";
				}
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type 		 = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type 		 = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
				$employee_type       = "";
			}

			if( $remove_resigned ){
				//$qry_add_on[] = " AND e.resignation_date < " . Model::safeSql($from);
				$qry_add_on[] = "(e.resignation_date = '0000-00-00' OR e.resignation_date = '')";
			}

			if( $remove_endo ){
				$qry_add_on[] = "(e.endo_date = '0000-00-00' OR e.endo_date = '')";
			}

			/*if($is_filter_by_cost_center){
				$qry_add_on[] = "(e.cost_center = '".$data['cost_center']."')";
			}*/

			if($is_filter_by_project_site){
				$qry_add_on[] = "(e.project_site_id = '".$data['project_site_id']."')";
			}

			if( $remove_terminated ){
				//$qry_add_on[] = " AND e.terminated_date <" . Model::safeSql($from);
				$qry_add_on[] = "(e.terminated_date = '0000-00-00' OR e.terminated_date = '')";
			}

			if( $remove_inactive ){
				$qry_add_on[] = "(e.inactive_date = '0000-00-00' OR e.inactive_date = '')";
			}	

	      	if($tag_search) {
		        $tags_query = explode(",", $data['tags']);
		        foreach( $tags_query as $t ){
		          	if($t != '') {
		            	$qry_tags[] = "(et.tags LIKE '%" . $t . "%')";    
		          	}
		        }
	      	}   

	      	//remove archive employee in report
			$qry_add_on[] = "(e.e_is_archive = 'No')";  

			//$qry_add_on[] = "(e.employee_code = '000523')";

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

      		if( !empty($qry_tags) ){
        		$is_confidential_qry .= " AND (" . implode(" OR ", $qry_tags) . ")";
      		}     			
			
			//unset($data['cutoff_period']);

			$qry = new Query_Builder();
			$qry_string = $qry->setQueryOptions($data)->usePrefix('p')->setLogicalOperator('AND')->buildSQLQuery();
			$fields   = array("e.id","e.employee_code","e.lastname","e.firstname","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.department_company_structure_id LIMIT 1)AS department_name","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.section_id LIMIT 1)AS section_name ","(SELECT status FROM ".EMPLOYMENT_STATUS." WHERE id = e.employment_status_id) AS employment_status, e.hired_date, e.marital_status, e.number_dependent, ps.name as cost_center");

			if ($data['frequency'] == '2') {
				$frequency_type = "Weekly"; 
				$employees = G_Employee_Helper::sqlGetWeeklyPayslipPeriodWithCustomQuery($from, $to, $is_confidential_qry, $qry_string, $fields, $order_by);
				$payslips  = G_Weekly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);

			}

			else if ($data['frequency'] == '3') {
				$frequency_type = "Monthly"; 
				$employees = G_Employee_Helper::sqlGetMonthlyPayslipPeriodWithCustomQuery($from, $to, $is_confidential_qry, $qry_string, $fields, $order_by);
				$payslips  = G_Monthly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);

			}

			else {
			$frequency_type = "Bi-Monthly"; 
				$employees = G_Employee_Helper::sqlGetPayslipPeriodWithCustomQuery($frequency_id,$from, $to, $is_confidential_qry, $qry_string, $fields, $order_by, $tag_search);
				$payslips  = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
			}

				
			//Group data by department
			$grouped_data = array();
			foreach( $employees as $employee ){
				if($employee['cost_center'] != '') {
					$grouped_data[$employee['cost_center']][] = $employee;
				} else {
					if($data['project_site_id'] == 'all'){
						$grouped_data[$employee['cost_center']][] = $employee;
					}
					//
				}
			}

		
			$fields = array('title');
			$gc = new G_Company_Structure(G_Company_Structure::PARENT_ID);
			$company = $gc->getDepartmentDetailsById($fields);
		
			$header['company_name']   = $company['title'];
            //Utilities::displayArray($payslips);
			$this->var['frequency_type'] = $frequency_type;
			$this->var['cutoff_code'] = $cutoff_code;
			$this->var['add_bonus_to_earnings'] = $add_bonus_to_earnings;
			$this->var['add_13th_month'] 		= $add_13th_month;
			$this->var['add_converted_leave'] 	= $add_converted_leave;
			$this->var['employee_type'] 		= $qry_employee_type;
			$this->var['grouped_data'] 			= $grouped_data;
			$this->var['employees'] 			= $employees;			
			$this->var['payslips']  			= $payslips;
			$this->var['header']      = $header;
			$this->var['total_employees'] = count($employees);
		}

		//General Reports / Shr Audit Trail
		list($p_year, $p_month, $p_day) = explode('-', $from);
        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
        if($p_day >= 16){
        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
        }
        else{
            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
     	}
		if($data['frequency'] == '1'){
			$frequency_name = 'Bi-Monthly';
		}
		else{
			$frequency_name = 'Weekly';	
		}

		if( isset($data['bonus_and_service_award_only']) && $data['bonus_and_service_award_only'] == 1 ){
			$this->var['bonus_service_award_only'] = $data['bonus_and_service_award_only'];
			$this->view->render('cost_center/download_cost_center_bonus_service_award_only.php', $this->var);	
		} else {
			if(isset($data['report_type']) && $data['report_type'] == 'summarized') {
				$this->view->render('cost_center/download_cost_center.php', $this->var);	
				
				//General Reports / Shr Audit Trail
				$report_type = ucwords($data['report_type']);
	  			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_GENERATE, $report_type.'  Payroll Register Report for  ', $frequency_name.' '. $cut_of_period, $from, $to, 1, '', '');

			}elseif(isset($data['report_type']) && $data['report_type'] == 'detailed_counter_check'){
				$this->view->render('cost_center/download_detailed_cost_center_counter_check.php', $this->var);	

				//General Reports / Shr Audit Trail
				$report_type = ucwords($data['report_type']);
	  			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_GENERATE, $report_type.'  Payroll Register Report for  ', $frequency_name.' '. $cut_of_period, $from, $to, 1, '', '');


			} else {
				//$this->view->render('cost_center/download_detailed_cost_center.php', $this->var);		
				$this->view->render('cost_center/download_detailed_cost_center2.php', $this->var);		

				//General Reports / Shr Audit Trail
				$report_type = ucwords($data['report_type']);
	  			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_GENERATE, $report_type.'  Payroll Register Report for  ', $frequency_name.' '. $cut_of_period, $from, $to, 1, '', '');


			}
			
		}			
		
	}

	function payroll_download_payroll_register() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$data = $_GET;		
		$from      = $data['from'];
		$to        = $data['to'];
		$this->var['from'] = $from;
		$this->var['to']   = $to; 
		


		if (strtotime($from) && strtotime($to)) {
			$employee_type = "";
			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				$is_confidential_qry = "";
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				$employee_type = "Confidential Employees";
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
				$employee_type = "Non-confidential Employees";
			}else{
				$is_confidential_qry = "";
			}

			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($_GET['q'] == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
					$employee_type = "Confidential Employees";
				}elseif($_GET['q'] == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
					$employee_type = "Non-confidential Employees";
				}else{
					$is_confidential_qry = "";
				}
			}

			//unset($data['cutoff_period']);
			$qry_string = '';
			$fields   = array("e.id","e.employee_code","e.lastname","e.firstname","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.department_company_structure_id LIMIT 1)AS department_name","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.section_id LIMIT 1)AS section_name","(SELECT status FROM ".EMPLOYMENT_STATUS." WHERE id = e.employment_status_id) AS employment_status");

			$employees = G_Employee_Helper::sqlGetPayslipPeriodWithCustomQuery($from, $to, $is_confidential_qry, $qry_string, $fields, $order_by);
			$payslips  = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);

			//Group data by department
			$grouped_data = array();

			foreach( $employees as $employee ){
				$grouped_data[$employee['employment_status']][$employee['department_name'] ." - ". $employee['section_name']][] = $employee;
			}

			//Utilities::displayArray($grouped_data);
			$this->var['employee_type'] = strtoupper($employee_type);
			$this->var['grouped_data'] = $grouped_data;
			$this->var['employees'] = $employees;			
			$this->var['payslips']  = $payslips;
			$this->var['total_employees'] = count($employees);
		}

		$this->view->render('payroll/download_payroll_register.php', $this->var);	
	}

	function download_payroll_register_depre() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$this->var['from'] = $from = $_GET['from'];
		$this->var['to'] = $to = $_GET['to'];
		
		if (strtotime($from) && strtotime($to)) {

			$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
			if($employee_access == Sprint_Modules::PERMISSION_05) {
				$is_confidential_qry = "";
			}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
				$is_confidential_qry = " AND (e.is_confidential = 1) ";	
			}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
				$is_confidential_qry = " AND (e.is_confidential = 0) ";
			}else{
				$is_confidential_qry = "";
			}

			if($employee_access == Sprint_Modules::PERMISSION_05) {
				if($_GET['q'] == "confidential") {
					$is_confidential_qry = " AND (e.is_confidential = 1) ";	
				}elseif($_GET['q'] == "non-confidential"){
					$is_confidential_qry = " AND (e.is_confidential = 0) ";
				}else{
					$is_confidential_qry = "";
				}
			}

			//$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($from);
			//$employees = G_Employee_Finder::findByPayslipPeriod($from, $to, $is_confidential_qry);			


			$order_by = "ORDER BY (SELECT title FROM " . COMPANY_STRUCTURE . " cs WHERE cs.id = e.department_company_structure_id ) ASC ";
			$fields   = array("e.id","e.employee_code","e.lastname","e.firstname","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.department_company_structure_id LIMIT 1)AS department_name","(SELECT title FROM " . COMPANY_STRUCTURE . " WHERE id = e.section_id LIMIT 1)AS section_name");
			$employees = G_Employee_Helper::sqlGetPayslipPeriodWithOptions($from, $to, $is_confidential_qry, $fields, $order_by);			
			$payslips  = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
			

			//Group data by department
			$grouped_data = array();
			foreach( $employees as $employee ){
				$grouped_data[$employee['department_name']][] = $employee;
			}

			$this->var['grouped_data'] = $grouped_data;
			$this->var['employees'] = $employees;			
			$this->var['payslips']  = $payslips;
			$this->var['total_employees'] = count($employees);
		}
				
		$this->view->render('payroll/download_payroll_register.php', $this->var);	
	}
}
?>