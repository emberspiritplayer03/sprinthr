<?php
class Reports_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->isLogin();
		$this->sprintHdrMenu(G_Sprint_Modules::EMPLOYEE, 'employee_reports');
		$this->validatePermission(G_Sprint_Modules::EMPLOYEE,'employee_reports','');
		Loader::appStyle('style.css');
		Loader::appMainScript('employee_request.js');
		Loader::appMainScript('employee_request_base.js');
	}

	function index() {
		$this->payslip();
	}
	
	function payslip() {
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		$current_year = date('Y');
        $c = G_Cutoff_Period_Finder::findAllDistinctYearTag();
        $this->var['cutoff_year'] = $c;

		$this->var['page_title'] = 'Reports';
		$this->view->setTemplate('template_employee_portal.php');
		$this->view->render('reports/payslip/payslip_form.php',$this->var);
	}

	function _ajax_load_cutoff_period_by_year() {
		$c = G_Cutoff_Period_Finder::findAllByYear($_GET['year']);
        $this->var['cutoff_periods'] = $c;
        $this->view->render('reports/payslip/_cutoff_periods.php',$this->var);
	}

	function download_payslip() {
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
        $cutoff = explode("/",$_POST['cutoff']);

        $this->var['from']       = $from = $cutoff[0];
        $this->var['to']         = $to = $cutoff[1];
        $this->var['gov_contri'] = array('SSS','Philhealth','Pagibig');
        if (strtotime($from) && strtotime($to)) {
            //$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($from);
            $c = G_Cutoff_Period_Finder::findByPeriod($from, $to);
            $year = $c->getYearTag();
            $month = date('d', strtotime($c->getStartDate()));
            $code = $c->getCutoffCharacter();

            $user_id = Utilities::decrypt($this->global_user_eid);

            $this->var['cutoff_code'] = $year .'-'. $month .'-'. $code;
            $this->var['employees'] = $employees = G_Employee_Finder::findByEmployeeIdPayslipPeriod($user_id, $from, $to);
            $payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
            $this->var['payslips'] = $payslips;
            $this->var['total_employees'] = count($employees);
        }
        $this->view->noTemplate();
        $this->view->render('reports/payslip/download_payslip.php', $this->var);
    }
	
}
?>