<?php
class Payslip_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainScript('payslip_base.js');
		Loader::appMainScript('payslip.js');			
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');

		$this->module = 'payroll';
		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'payroll');
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'payroll','');

		$this->var['employee'] = 'selected';
	}

	function index() {		
		G_Cutoff_Period_Helper::addNewPeriod();		
					
		$this->var['page_title'] = 'Manage Payslip';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/index.php', $this->var);	
	}

    function manage() {
        Jquery::loadMainInlineValidation2();
        Jquery::loadMainTipsy();
        Jquery::loadMainTextBoxList();
        Jquery::loadMainJqueryFormSubmit();
        Loader::appMainScript('reports.js');
        $ar_date['from'] = $this->var['from'] = $_GET['from'];
        $ar_date['to']   = $this->var['to'] = $_GET['to'];

        $this->var['page_title'] = 'Payslip';
        $this->var['start_date'] = $start_date = $_GET['from'];
        $this->var['query'] 	 = $query = $_GET['query'];

        $end_date = $this->var['end_date']   = $_GET['to'];
        $frequency_id = $this->var['frequency']   = $_GET['frequency'];

        $per_page = 10;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }

        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_confidential_qry = "";
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$is_confidential_qry = " e.is_confidential = 1 AND ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$is_confidential_qry = " e.is_confidential = 0 AND ";
		}else{
			$is_confidential_qry = "";
		}
        $s = G_Employee_Basic_Salary_History_Finder::findByDateAndFrequency($end_date, $frequency_id);
        
        $employee_ids = array();
        $employee_ids_qry = "";

        foreach ($s as $key => $data) {
        	$employee_ids[] = $data->employee_id;
        }

       	if (count($employee_ids) > 0) {
       		$employee_ids_qry = " e.id IN (".implode (",", $employee_ids).") AND ";
       	}

        if ($query != '') {
            if($_GET['s_exact']){
                $this->var['checked']   = 'checked="checked"';
                $this->var['employees']   = G_Employee_Finder::searchActiveByExactFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date,$is_confidential_qry, $employee_ids_qry);
                //$this->var['employees'] = G_Employee_Finder::searchActiveByExactFirstnameAndLastnameAndEmployeeCode($query);
            }else{
                $this->var['employees'] = G_Employee_Finder::searchAllByFirstnameAndLastnameAndEmployeeCode($query,$is_confidential_qry, $employee_ids_qry);//G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date);
                //$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
            }
        } else {
            $this->var['checked']   = '';
            $total_records = G_Employee_Helper::countAllActiveByDate($start_date,$is_confidential_qry, $employee_ids_qry);
            if($frequency_id == '3'){

            	$this->var['employees'] = $records = G_Employee_Finder::findAllActive($end_date, "{$start_record}, {$per_page}",$is_confidential_qry, $employee_ids_qry);
            }
            else{
            	 $this->var['employees'] = $records = G_Employee_Finder::findAllActive($start_date, "{$start_record}, {$per_page}",$is_confidential_qry, $employee_ids_qry);
            }
         
        }

        // PAGER
        require_once 'Pager.php';

        $params = array(
            'mode'       => 'Sliding',
            'perPage'    => $per_page,
            'itemData'   => $records,
        );

        $pager =& Pager::factory($params);

        unset($pager);

        $params = array(
            'mode'     => 'Sliding',
            'perPage'  => $per_page,
            'delta'    => 5,
            'totalItems' => $total_records
        );
        $pager =& Pager::factory($params);
        $links = $pager->getLinks();
        $this->var['pager_links'] = $links['all'];

        $this->var['action'] = url('payslip/manage');
        $this->view->setTemplate('payroll/template.php');
        $this->var['page_title'] = 'Payslip';

        $cutoff_id = Utilities::decrypt($_GET['hpid']);
        $previous_cutoff = G_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
        $next_cutoff = G_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);

        if ($previous_cutoff) {
            $previous_from = $previous_cutoff->getStartDate();
            $previous_to = $previous_cutoff->getEndDate();
            $previous_id = Utilities::encrypt($previous_cutoff->getId());
            $this->var['previous_cutoff_link'] = url("payslip/manage?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");
        }

        if ($next_cutoff) {
            $next_from = $next_cutoff->getStartDate();
            $next_to = $next_cutoff->getEndDate();
            $next_id = Utilities::encrypt($next_cutoff->getId());
            $this->var['next_cutoff_link'] = url("payslip/manage?from={$next_from}&to={$next_to}&hpid={$next_id}");
        }

        $this->view->render('payslip/manage.php',$this->var);
    }
	
	/*function manage() {
		Jquery::loadMainTipsy();	
		Jquery::loadMainInlineValidation2();
		
		$this->var['page_title'] = 'Manage Payslip';
		$this->var['from'] = $from = $_GET['from'];
		$this->var['to'] = $to = $_GET['to'];
		$this->var['salt'] = md5($from .'-'. $to);
		
		$this->var['is_generated'] = false;
		if (G_Payslip_Helper::countPeriod($from, $to) > 0) {
			$this->var['is_generated'] = true;
			$this->var['employees'] = G_Employee_Finder::findByPayslipPeriod($from, $to);
		}
		$this->var['payout_date'] = G_Payslip_Helper::getPeriodPayoutDate($from, $to);
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/manage.php', $this->var);	
	}*/

	function show_payslip() {		
		Jquery::loadMainTipsy();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		$this->var['encrypted_employee_id'] = $_GET['employee_id'];	
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_GET['employee_id']);
		$from = $this->var['from'] = $_GET['from'];
		$to = $this->var['to'] = $_GET['to'];
        $this->view->setTemplate('payroll/template.php');
		$e = Employee_Factory::get($employee_id);
		$frequency_id = $this->var['frequency'] = $_GET['frequency'];
		
		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			if($e->getIsConfidential() != 1) {
				redirect("payslip/manage?from={$from}&to={$to}");
			}
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			if($e->getIsConfidential() != 0) {
				redirect("payslip/manage?from={$from}&to={$to}");
			}
		}else{

		}

		$this->var['page_title'] =  "Payslip";
		$this->var['module_title'] =  ': <b class="mplynm">' .$e->getName(). '</b>';

		$from_month = date("m",strtotime($from));
		$to_month   = date("m",strtotime($to));

		$from_year  = date("Y",strtotime($from));
		$to_year    = date("Y",strtotime($to));

		if( $from_month == $to_month ){
			$new_from_date = date("F j", strtotime($from));
			$to_day = date("d",strtotime($to));
			$period = "<b>Period : </b> <span>{$new_from_date}</span> <b>to</b> <span>{$to_day}, $to_year</span>";
		}else{
			$new_from_date = date("F j", strtotime($from));
			$new_to_date   = date("F j", strtotime($to));
			$period = "<b>Period : </b> <span>{$new_from_date}</spa> <b>to</b> <span>{$new_to_date}, $to_year</span>";
		}

		//$this->var['module_title'] =  ': <b class="mplynm">' .$e->getName(). '</b>' . "<a class='gray_button title_back_button' href='" . url('payslip/manage?from='. $from .'&to='. $to). "'><i></i>Back to List</a>";"Payslip: ". '<b class="mplynm">' .$e->getName(). '</b>' . "<a class='gray_button title_back_button' href='" . url('payslip/manage?from='. $from .'&to='. $to). "'><i></i>Back to List</a>";
		//$this->var['period'] = '<b>Period:</b>'. ' <span>'.Tools::dateFormat($from).'</span>'. ' <strong>&nbsp;to&nbsp;</strong> '. '<span>'.Tools::dateFormat($to).'</span>';
		$this->var['period']   = $period;

		if ($frequency_id == 2) {
			$p = G_Weekly_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		}

		elseif ($frequency_id == 3) {
			$p = G_Monthly_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		}

		else {
			$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		}

        if (!$p) {
            $this->view->render('payslip/no_payslip.php', $this->var);
            exit;
        }

		if ($frequency_id == 2) {
			$ph = new G_Weekly_Payslip_Helper($p);
			$payslipDataBuilder = new G_Weekly_Payslip();
			$payslips 	= G_Weekly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
		}

		elseif ($frequency_id == 3) {
			$ph = new G_Monthly_Payslip_Helper($p);
			$payslipDataBuilder = new G_Monthly_Payslip();
			$payslips 	= G_Monthly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
		}

		else {
			$ph = new G_Payslip_Helper($p);
			$payslipDataBuilder = new G_Payslip();
			$payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
		}

		//loan balance
		$payslip_section_loan_balance    = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('loan_balance', 2, '', array($from, $to));
       $payslip_section_deduction       = $payslipDataBuilder->wrapPayslipArray($payslips[$employee_id])->getPayslipData('deductions', 2);

       $loan_balance_container = array();

       foreach ($payslip_section_loan_balance as $key => $l) {
            $loan_balance_container[$key]['label'] = $l['label'];
            $loan_balance_container[$key]['value'] = $l['value'] - $payslip_section_deduction[$key]['value']; 
       }

        $this->var['loan_balance_container'] = $loan_balance_container;

       // Utilities::displayArray($loan_balance_container);exit();

       //end loan balance

		$new_earnings   = $p->getBasicEarnings();
		
		$new_deductions = $p->getTardinessDeductions();
		$payslip_info   = $p->getEmployeeBasicPayslipInfo();
		// echo "<pre>";
		// var_dump($new_earnings);
		// echo "</pre>";
		//echo $ph->getLabel('employee_deduction') .'='. $ph->getValue('employee_deduction');

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
		//$this->var['total_earnings'] = $p->computeTotalEarnings();
		//$this->var['total_deductions'] = $p->computeTotalDeductions();
		//$this->var['total_allowance'] = $p->computeTotalEarnings(Earning::EARNING_TYPE_ALLOWANCE);
		$this->var['net_pay'] = $p->getNetPay();
		$this->var['gross_pay'] = $p->getGrossPay();
		$this->var['gov_deductions'] = array('SSS','Philhealth','Pagibig');
		// Employee Navigation		
		//$previous_employee_id = $this->var['previous_employee_id'] = G_Employee_Helper::getPreviousIdAlphabetic($employee_id);
		//$next_employee_id = $this->var['next_employee_id'] = G_Employee_Helper::getNextIdAlphabetic($employee_id);
		//$this->var['previous_encrypted_employee_id'] = Utilities::encrypt($previous_employee_id);
		//$this->var['next_encrypted_employee_id'] = Utilities::encrypt($next_employee_id);		
		
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/show_payslip.php', $this->var);		
	}

	function processed_payroll() {		
		Jquery::loadMainTipsy();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryDatatable(); 
		Jquery::loadMainBootStrapDropDown();

		$this->var['start_date'] = $_GET['from'];
		$this->var['end_date'] = $_GET['to'];
		$this->var['q']			= $_GET['q'];
		$this->var['page_title'] =  "Processed Payroll";
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/processed_payroll.php', $this->var);	
	}

	function _processed_payroll_list_dt() {	

		if(!empty($_POST['filter_operator']) && !empty($_POST['filter_amount']) && !empty($_POST['filter_field']) ) {
			$additional_qry .= " AND p.".$_POST['filter_field'].  $_POST['filter_operator']. " " .$_POST['filter_amount'];
		}	

		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$additional_qry .= "";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			if(strtolower($employee_access) != strtolower($_POST['q']." Employees")) {
				redirect("payroll_register/generation");
			}	
			$additional_qry .= " AND e.is_confidential = 1  ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			if(strtolower($employee_access) != strtolower($_POST['q']." Employees")) {
				redirect("payroll_register/generation");
			}	
			$additional_qry .= " AND e.is_confidential = 0  ";	
		}else{
			$additional_qry .= "";	
		}

		if($employee_access == Sprint_Modules::PERMISSION_05 || $employee_access == 1) {
			if($_POST['q'] == "confidential") {
				$additional_qry .= " AND e.is_confidential = 1  ";	
			}elseif($_POST['q'] == "non-confidential"){
				$additional_qry .= " AND e.is_confidential = 0  ";
			}else{
				$additional_qry .= "";
			}
		}
		
		$pr_payroll = new G_Payslip();
		$processed_payroll_data = $pr_payroll->getProcessedPayroll($_POST['from'], $_POST['to'], $additional_qry);

		$this->var['processed_payroll_data'] = $processed_payroll_data;
		$this->view->render('payslip/_processed_payroll_list_dt.php', $this->var);	
	}

	function _show_move_deduction_form() {
		$period = G_Cutoff_Period_Finder::findByPeriod($_POST['from'],$_POST['to']);
		if($period){
			$cutoff_id = $period->getId();
			for($i = 0; $i <= 5; $i++) {
				$c  = new G_Cutoff_Period();
				$c->setId($cutoff_id);
				$cutoff_data = $c->getNextCutOff();

				$cutoff_arr_data[] = array(
					"cutoff_id" => Utilities::encrypt($cutoff_data['id']),
					"label"		=> Tools::convertDateFormat($cutoff_data['period_start']) . ' to ' . Tools::convertDateFormat($cutoff_data['period_end'])
					);

				$cutoff_id = $cutoff_data['id'];
			}
			
			$this->var['cutoff_arr_data'] = $cutoff_arr_data;
			$this->var['action']		  = $_POST['action'];
			$this->view->render('payslip/_show_move_deduction_form.php', $this->var);
		}else{
			echo '<div class="alert alert-error">Invalid cutoff period.</div>';
		}
	}

	function hold_move_processed_payroll() {
		if(!empty($_POST['action']) && !empty($_POST['selected_deduction'])) {			
			$eed = new G_Excluded_Employee_Deduction();
			$return = $eed->excludeEmployeeDeduction($_POST);
		
		}else{
			$return['is_success'] = false;
			$return['message'] = "<div style='margin-left:0px; padding-top:9px' class='alert alert-error'>Please select at least one from the checkbox.</div>";
		}
		echo json_encode($return);
	}
	
	function export_payslip() {
		$employee_id = (int) $_GET['employee_id'];
		$from = $this->var['from'] = $_GET['from'];
		$to = $this->var['to'] = $_GET['to'];
		$e = $this->var['employee'] = Employee_Finder::findById($employee_id);
		$this->var['payout_date'] = Payslip_Helper::getPeriodPayoutDate($from, $to);
		
		Loader::appLibrary('pdf_writer');
		$this->var['payslip'] = $payslip = Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$this->var['ps'] = new Payslip_Helper($payslip);
		$this->view->render('payslip/export_payslip.php', $this->var);
	}

	function download_payslip_old() {
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);

        $this->var['from']       = $from = $_GET['from'];
        $this->var['to']         = $to = $_GET['to'];
        $this->var['gov_contri'] = array('SSS','Philhealth','Pagibig');
        if (strtotime($from) && strtotime($to)) {
            //$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($from);
            $c = G_Cutoff_Period_Finder::findByPeriod($from, $to);
            $year = $c->getYearTag();
            $month = date('d', strtotime($c->getStartDate()));
            $code = $c->getCutoffCharacter();

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

            $this->var['cutoff_code'] = $year .'-'. $month .'-'. $code;
            $this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to, $is_confidential_qry);
            $payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
            $this->var['payslips'] = $payslips;
            $this->var['total_employees'] = count($employees);
        }
        $this->view->noTemplate();
        $this->view->render('payslip/download_payslip.php', $this->var);		
	}

    function download_payslip() { //with template chooser
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);

        $remove_resigned   = false;
		$remove_terminated = false;
		$remove_endo       = false;
		$remove_inactive   = false;
		$yearly_bonus      = false;
		$bonus_service_award = false;
		$add_13th_month    = false;
		$show_converted_leaves = false;
		$qry_employee_type = '';
		$add_bonus_service_award = false;
		$frequency_id = 0;
		$is_filter_by_cost_center = false;

		if( isset($_GET['q']) ){
			$qry_employee_type = trim(strtolower($_GET['q']));	
		}

		if( (isset($_GET['cost_center']) && $_GET['cost_center'] != '' && $_GET['cost_center'] != 'all')){
			$is_filter_by_cost_center   = true;
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
		if( isset($_GET['yearly_bonus']) && $_GET['yearly_bonus'] == 1 ){
			$yearly_bonus = true;
		}	
		if( isset($_GET['bonus_service_award']) && $_GET['bonus_service_award'] == 1 ){
			$bonus_service_award = true;
		}	
		if( isset($_GET['add_13th_month_pay']) && $_GET['add_13th_month_pay'] == 1 ){
			$add_13th_month = true;
		}
		if( isset($_GET['show_converted_leaves_only']) && $_GET['show_converted_leaves_only'] == 1 ){
			$show_converted_leaves = true;
		}

		if( isset($_GET['add_bonus_service_award']) && $_GET['add_bonus_service_award'] == 1 ){
			$add_bonus_service_award = true;
		}

		if( isset($_GET['frequency']) ){
			$frequency_id = $_GET['frequency'];	
		}

        $this->var['from']       = $from = $_GET['from'];
        $this->var['to']         = $to   = $_GET['to'];
        $this->var['gov_contri'] = array('SSS','Philhealth','Pagibig');
        $this->var['frequency_id'] = $frequency_id;
        if (strtotime($from) && strtotime($to)) {
        	if ($frequency_id == 2) {
            $c = G_weekly_Cutoff_Period_Finder::findByPeriod($from, $to);
        	}

        	else if ($frequency_id == 3) {
            $c = G_Monthly_Cutoff_Period_Finder::findByPeriod($from, $to);
        	}

        	else {
            $c = G_Cutoff_Period_Finder::findByPeriod($from, $to);
        	}
            $year = $c->getYearTag();
            $month = date('m', strtotime($c->getStartDate()));
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

			if($is_filter_by_cost_center){
				$qry_add_on[] = "(e.cost_center = '".$_GET['cost_center']."')";
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
			
			//$qry_add_on[] = "(e.employee_code = 9942)";
			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}

			if ($frequency_id == 2) {
				$employees = G_Employee_Finder::findByWeeklyPayslipPeriodOrderByDepartmentCompanyStructure($from, $to, $is_confidential_qry);
  	}

  			elseif ($frequency_id == 3) {
				$employees = G_Employee_Finder::findByMonthlyPayslipPeriodOrderByDepartmentCompanyStructure($from, $to, $is_confidential_qry);
			  	}


  	else {
				$employees = G_Employee_Finder::findByPayslipPeriodOrderByDepartmentCompanyStructure($from, $to, $is_confidential_qry);
  	}

			$employees_group = array();
			foreach ($employees as $ed) {

				$employee_code = $ed->getEmployeeCode();
				$firstname     = trim($ed->getFirstName());
				$lastname      = trim($ed->getLastName());
				$employee_sec  = $ed->getSectionId();
    			$d = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($ed);
    			$sec = G_Company_Structure_Finder::findById($employee_sec);
			    if($sec) {
			      $p = G_Company_Structure_Finder::findParentId($sec->getParentId());
			      $sec_title = trim($sec->getTitle());
			      
			      if($p) {
			        $parent_sec_title = trim($p->getTitle());
			      }
			    }

			    if ($d) {
			      $department = trim($d->getName());
			    }

			    $employees_group[$department . ' ' . $sec_title ."-". $parent_sec_title . ' ' . $lastname . '-' . $employee_code] = $ed;

			}

			ksort($employees_group);
			if($code == "A"){
					$current_yr =  $year .'-'. $month;
					$month = date("m", strtotime("+1 month", strtotime($current_yr)));
			}
		 
			$this->var['show_converted_leaves_only'] = $_GET['show_converted_leaves_only']; //
		
			$this->var['show_converted_leaves'] = $show_converted_leaves; //
			$this->var['add_13th_month']    		 = $add_13th_month; //
			$this->var['bonus_service_award'] = $bonus_service_award;//
			$this->var['add_bonus_service_award'] = $add_bonus_service_award;//
			$this->var['yearly_bonus']		= $yearly_bonus; //
			$this->var['end_date']			= $_GET['to'];
            $this->var['cutoff_code'] 		= $year .'-'. $month .'-'. $code;
            $this->var['employees'] 		= $employees_group;

   if ($frequency_id == 2) {
    $payslips 						= G_Weekly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
  	}

  	else if ($frequency_id == 3) {
    $payslips 						= G_Monthly_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);

  	}

  	else {
    $payslips 						= G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
  	}
            $this->var['payslips'] 			= $payslips;
            $this->var['total_employees'] 	= count($employees);
        }

        $d_template = G_Payslip_Template_Helper::defaultTemplate();

        if(!empty($d_template)) {
        	$this->var['default_template'] = $d_template;
        }else{ $this->var['default_template'] = null; }

        $this->view->noTemplate();
        $this->view->render('payslip/download_payslip.php', $this->var);
    }

    function a_download_payslip() { //with template chooser

        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);

        $remove_resigned   = false;
		$remove_terminated = false;
		$qry_employee_type = '';

		if( isset($_GET['q']) ){
			$qry_employee_type = trim(strtolower($_GET['q']));	
		}

		if( isset($_GET['remove_resigned']) && $_GET['remove_resigned'] == 1 ){
			$remove_resigned   = true;
		}
		if( isset($_GET['remove_terminated']) && $_GET['remove_terminated'] == 1 ){
			$remove_terminated = true;
		}

        $this->var['from']       = $from = $_GET['from'];
        $this->var['to']         = $to   = $_GET['to'];
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

			if( !empty($qry_add_on) ){
				$is_confidential_qry .= " AND " . implode(" AND ", $qry_add_on);
			}
			
			$this->var['end_date']			= $_GET['to'];
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
        $this->view->render('payslip/download_payslip.php', $this->var);
    }
	
	function add_earning() {
		$this->var['action'] = url('payslip/_add_earning');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/add_earning_form.php', $this->var);
	}
	
	function add_deduction() {
		$this->var['action'] = url('payslip/_add_deduction');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/add_deduction_form.php', $this->var);
	}
	
	function change_deduction_amount() {
		$this->var['action'] = url('payslip/_change_deduction_amount');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		$this->var['amount'] = $_GET['amount'];
		$this->var['label'] = $_GET['label'];
		$this->var['variable'] = $_GET['variable'];
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/change_deduction_amount_form.php', $this->var);
	}
	
	function generate() {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$salt = $_POST['salt'];
		if (!empty($from) && !empty($to)) {
			if (md5($from .'-'. $to) != $salt) {
				exit('Invalid Salt');
			}
			G_Payslip_Helper::updatePayslipsByPeriod($from, $to);
		}		
	}

    function generate_payslip() {
    	   	
        $month = (int) $_POST['month'];
        $cutoff_number = (int) $_POST['cutoff_number'];
        $year = (int) $_POST['year'];
        $frequency_type_id = (int) $_POST['frequency_type_id'];
        
        $remove_inactive = false;
        if($_POST['remove_inactive']) {
        	$remove_inactive = true;
        }

        $selected_employee = "";
    	if(!isset($_POST['all_employee']) && !empty($_POST['selected_employee_id'])) {
    		$selected_employee = explode(",", $_POST['selected_employee_id']);
			$selected_employee = array_values(array_filter(array_unique($selected_employee)));
			foreach($selected_employee as $key => $value) {
				$ids[] = Utilities::decrypt($value); 
			}
			$selected_employee = implode(",",$ids);
    	}

        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$additional_qry = "";
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$additional_qry = " e.is_confidential = 1 AND ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$additional_qry = " e.is_confidential = 0 AND ";
		}else{
			$additional_qry = "";
		}

		if($employee_access == Sprint_Modules::PERMISSION_05) {
			if($_POST['q'] == "confidential") {
				$additional_qry = " e.is_confidential = 1 AND ";	
			}elseif($_POST['q'] == "non-confidential"){
				$additional_qry = " e.is_confidential = 0 AND ";
			}else{
				$additional_qry = "";
			}
		}

		if(isset($_POST['remove_resigned'])) {
			$additional_qry = $additional_qry . " e.resignation_date = '0000-00-00' AND ";
		}

		if(isset($_POST['remove_terminated'])) {
			$additional_qry = $additional_qry . " e.terminated_date = '0000-00-00' AND ";
		}

		if($remove_inactive) {
			$additional_qry = $additional_qry . " e.inactive_date = '0000-00-00' AND ";
		}		

        $c = new G_Company;
        $c->setFilteredEmployeeId($selected_employee);
        $c->setAdditionalQuery($additional_qry);

        $payslips = false;

        if($frequency_type_id == 1){
       		$payslips = $c->generatePayslip($month, $cutoff_number, $year);
        }

        elseif($frequency_type_id == 3){
        	$payslips = $c->generateMonthlyPayslip($month, $cutoff_number, $year);

        }

        else{
        	$payslips = $c->generateWeeklyPayslip($month,$cutoff_number,$year);
        }
      
        //start deleting inactive employee previous - start
        if($remove_inactive) {
	        if ($year == '') {
	            $year = Tools::getGmtDate('Y');
	        }

        if($frequency_type_id == 1){
	        $c = new G_Cutoff_Period();
	        $data = $c->expectedCutOffPeriodsByMonthAndYear($month, $year);        
	        
	        if( $cutoff_number == 1 ){
	            $from_date = $data[0]['start_date'];
	            $to_date   = $data[0]['end_date'];
	        }else{
	            $from_date = $data[1]['start_date'];
	            $to_date   = $data[1]['end_date'];
	        }  
        }


        else if($frequency_type_id == 3){

        	  $c = new G_Monthly_Cutoff_Period();
	         $data = $c->expectedCutOffPeriodsByMonthAndYear($month, $year);        
	        
	        if( $cutoff_number == 1 ){
	            $from_date = $data[0]['start_date'];
	            $to_date   = $data[0]['end_date'];
	        }


        }

        else{
        	$weekly_periods = new G_Weekly_Cutoff_Period();
	        $expected_weekly_cutoff = $weekly_periods->expectedWeeklyCutOffPeriodsByMonthAndYear($month,$year);

	        $from_date = $expected_weekly_cutoff[$cutoff_number - 1]['period_start'];
	        $to_date   = $expected_weekly_cutoff[$cutoff_number - 1]['period_end'];
        }    

	        $previous_inactive = G_Employee_Status_History_Finder::findInactiveEmployeeInBetweenDates($from_date, $to_date);
	        foreach($previous_inactive as $pkey => $pkeyd) {
	        	$employee_id = $pkeyd->getEmployeeId();
	            if($employee_id) {
	                $e_payslip = G_Payslip_Finder::findByEmployeeIdAndPeriod($employee_id, $from_date, $to_date);
	                if($e_payslip) {
	                	$counter_check_active = G_Employee_Status_History_Helper::findIfactiveEmployeeInBetweenDates($employee_id, $from_date, $to_date);
	                	if(!$counter_check_active) {

	                		$payslip_labels 		= $e_payslip->getLabels();
	                		$present_days_with_pay 	= "";
	                		foreach($payslip_labels as $lkey => $label) {
	                			if($label->getVariable() == 'present_days_with_pay') {
	                				$present_days_with_pay = $label->getValue();
	                			}
	                		}

	                		if( $present_days_with_pay = "" || $present_days_with_pay <= 0)  {
	                			$e_payslip->delete();
	                		}
	                		
	                	}
	                }
	            }
	        }
        }
		//start deleting inactive employee previous - end
		if($payslips) {        	
        	$return['is_success'] = true;
        	$return['message'] = "<div style='margin-left:-10px;' class='alert alert-success'>Payroll has been successfully generated. </div>";
        }else{        	
        	$return['is_success'] = false;
        	$return['message'] = "<div style='margin-left:-10px;' class='alert alert-error'>Unable to generate payroll. </div>";
        }
        echo json_encode($return);
    }
	
	function dialog_change_period_payout_date() {
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		$this->var['current_payout_date'] = $_GET['current_payout_date'];
		$this->view->noTemplate();
		$this->view->render('payslip/dialog_change_period_payout_date_form.php', $this->var);
	}
	
	function _add_earning() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$earning_type = $_POST['earning_type'];
		$label = $_POST['label'];
		$amount = $_POST['amount'];
		
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		if (!$ph->getLabel($label)) {
			$ear[] = $ear_obj = new Earning($label, $amount, Earning::TAXABLE, (int) $earning_type);
			$p->addOtherEarnings($ear);
			$gross_pay = $ph->computeTotalEarnings();
			$p->setGrossPay($gross_pay);
			$net_pay = $gross_pay - $ph->computeTotalDeductions();
			$p->setNetPay($net_pay);
			$p->save();
			$return['added'] = true;
		} else {
			$return['added'] = false;
			$return['already_exist'] = true;
		}
		echo json_encode($return);	
	}	
	
	function _add_deduction() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$label = $_POST['label'];
		$amount = $_POST['amount'];
		$deduction_type = (int) $_POST['deduction_type'];
		
		$e = Employee_Factory::get($employee_id);
		
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		if (!$ph->getLabel($label)) {
			$d[] = new Deduction($label, $amount, $deduction_type);
			$p->addOtherDeductions($d);
			$gross_pay = $ph->computeTotalEarnings();
			$p->setGrossPay($gross_pay);
			$net_pay = $gross_pay - $ph->computeTotalDeductions();
			$p->setNetPay($net_pay);			
			$p->save();
			$return['added'] = true;
		} else {
			$return['added'] = false;
			$return['already_exist'] = true;
		}							
		echo json_encode($return);			
	}
	
	function _remove_deduction() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$label = $_POST['label'];
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		$p->removeOtherDeduction($label);
		
		$gross_pay = $ph->computeTotalEarnings();
		$p->setGrossPay($gross_pay);	
		
		$net_pay = $gross_pay - $ph->computeTotalDeductions();
		$p->setNetPay($net_pay);	
			
		$p->save();
	
		$return['removed'] = true;
		echo json_encode($return);
	}
	
	function _remove_earning() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$label = $_POST['label'];
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		$p->removeOtherEarning($label);

		$gross_pay = $ph->computeTotalEarnings();
		$p->setGrossPay($gross_pay);	
		
		$net_pay = $gross_pay - $ph->computeTotalDeductions();
		$p->setNetPay($net_pay);	
			
		$p->save();
	
		$return['removed'] = true;
		echo json_encode($return);
	}
	
	function _update_employee_payslip() {
		$id = $_GET['employee_id'];	
		$employee_id = Utilities::decrypt($id);		
		$e = G_Employee_Finder::findById($employee_id);
		if ($e) {
			$from = $_GET['from'];
			$to = $_GET['to'];
			
			$is_updated = G_Payslip_Helper::updatePayslip($e, $from, $to);
			$return['is_updated'] = true;
			$return['message'] = 'Payslip has been updated';
		} else {
			$return['is_updated'] = false;
			$return['message'] = "Payslip has not been updated. There's no employee found";
		}
		echo json_encode($return);
	}
	
	function _update_payslips() {
		set_time_limit(999999999999999999999);
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		G_Payslip_Helper::updatePayslipsByPeriod($from, $to);
		$return['is_updated'] = true;
		$return['message'] = 'Payslips has been updated';
		echo json_encode($return);
	}			
	
	function _change_deduction_amount() {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$label = $_POST['label'];
		$variable = $_POST['variable'];
		$amount = (float) $_POST['amount'];		
		
		$e = Employee_Factory::get($employee_id);
		
		// Payables
		$pf = G_Payment_History_Finder::findByEmployeeAndPaymentNameAndDatePaid($e, $label, $to);
		if ($pf) {
			$pf->setAmountPaid($amount);
			$pf->save();
		}
				
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$p->removeDeduction($variable);
		$obj_deduct = new Deduction($label, $amount);
										
		switch ($variable):
			case 'philhealth':									
				$obj_deduct->setVariable('philhealth');									
				$p->setPhilhealth($amount);
			break;
			case 'pagibig':
				$obj_deduct->setVariable('pagibig');		
				$p->setPagibig($amount);
			break;
			case 'sss':
				$obj_deduct->setVariable('sss');									
				$p->setSSS($amount);
			break;
		endswitch;
		
		$p->addDeductions($obj_deduct);	
		//$p->setWithheldTax($p->computeTotalDeductions(Deduction::DEDUCTION_TYPE_TAX));
		//$net_pay = $p->computeNetPay();
		//$p->setNetPay($net_pay);
				
		$p->save();
		
		$return['changed'] = true;
		echo json_encode($return);		
	}

	function payslip_preview()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		//Employee
		$e['name'] 		        = 'Aala, Karen Grace';
		$e['employee_number']   = 'E10001';	
		//Pay Period
		$p['from']              = '2012-08-01';
		$p['to']			    = '2012-08-15';
		//Earnings
		$ea['basic_pay']	    = 5000.00;
		$ea['overtime']		    = 361.27;	
		//Deductions
		$de['sss']			    = 4.00;
		$de['philhealth']       = 9.00;
		$de['pagibig']          = 2296.00;
		$de['late']             = 11.94;
		$de['undertime']        = 0.00;
		$de['absent_amount']    = 0.00;
		$de['suspended_amount'] = 0.00;
		
		$pName = Generate_Payslip::payslip($e,$p,$ea,$de);			
		if($pName){
			$location = 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . "files/payslip/payslip.pdf";	
			header("Location: {$location}");
			//header('Location: http://gleent.local/gleent_hr_payroll/hr/files/payslip/payslip.pdf');
		}
	}
	
	function swift_send($a,$e,$p)
	{
		//Loader::appLibrary('swiftmailer/lib/swift_required');		
		$msg .= 'Hi <b>' . $e['name'] . '</b>,<br><br>';
		$msg .= '<p>Attached is your payslip from <b>' . $p['from'] . '</b> to <b>' . $p['to'] . '</b></p>';
		$msg .= '<p>Kindly check</p>';
		$msg .= '<p>Thank you</p>';
		$msg .= '<br /><p><i>This is an auto email. Do not reply.</i></p>';
		
		$subject       = '[SprintHr]Payslip';
		//$email         = 'bryan.yobi@gmail.com'; 
		$email         = 'marlito.dungog@gleent.com';
		$smtp          = 'mail.krikel.com';
		$port          = 26;
		$username      = 'support@krikel.com';
		$password      = 'Gl33ntxyz';
		$from['email'] = 'support@krikel.com';	
		$from['title'] = 'Sprint Hr Admin';		
		$send 	       = Tools::sendMailSwiftMailer($subject,$email,$msg,$smtp,$port,$username,$password,$from,$a);
		return $send;
	}
	
	function email()
	{
		//Employee
		$e['name'] 		        = 'Aala, Karen Grace';
		$e['employee_number']   = 'E12324-22';	
		//Pay Period
		$p['from']              = '2012-08-01';
		$p['to']			    = '2012-08-15';
		//Earnings
		$ea['basic_pay']	    = 5000.00;
		$ea['overtime']		    = 361.27;	
		//Deductions
		$de['sss']			    = 4.00;
		$de['philhealth']       = 9.00;
		$de['pagibig']          = 2296.00;
		$de['late']             = 11.94;
		$de['undertime']        = 0.00;
		$de['absent_amount']    = 0.00;
		$de['suspended_amount'] = 0.00;
		
		$attachment             = Generate_Payslip::payslip($e,$p,$ea,$de);			
		$num_sent			    = $this->swift_send($attachment,$e,$p);
		
		if($num_sent > 0){
			$json['error'] = 0;
		}else{$json['error'] = 1;}
		
		echo json_encode($json);
		
	}
}
?>