<?php
class Benchmark_Leo_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	function index() {			

	}

	function import_ot() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		echo $file = BASE_PATH . 'files/files/import_overtime.xlsx';
		//$time = new Timesheet_Import($file);
		$time = new G_Overtime_Import_Pending($file);
		$time->import();		
	}
	
	function download_sss_report() {
		
		$company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$ci = G_Company_Info_Finder::findByCompanyStructureId($company_structure_id);
		
		// Headers
		$this->var['company_name'] 		= $cs->getTitle();
		$this->var['company_address'] 	= $ci->getAddress();
		$this->var['postal_code']		= $ci->getZipCode();
		$this->var['sss_number']		= $ci->getSssNumber();
		$this->var['phone_number']		= $ci->getPhone();
		
		$this->var['from']	= $_POST['date_from'];
		$this->var['to']	= $_POST['date_to'];
		$this->var['employee_counter'] = 0;

		$total 		= G_Employee_Helper::countTotalPayslipDateRange($_POST['date_from'],$_POST['date_to']);
		$pages		= floor($total/40);
		$employee_counter = 0;
		
		if($_POST['page_number'] == 'all') {
			for($i=1;$i<=$pages;$i++){ 
				$limit_start += 40; 
				$date = Tools::getGmtDate('Y-m-d');
				
				$this->var['filename']			= 'sss_r1a.xls';
				$this->var['current_page'] 		= $i;
				$this->var['total_pages']		= $pages;
				$this->var['payslip'] 			= $payslip = G_Employee_Finder::findByPayslipDateRange($_POST['date_from'], $_POST['date_to'],$limit_start . ',40');
				$this->var['total_records'] 	= $total;
				$this->view->render('benchmark/leo/download_sss_report.html.php',$this->var);
			}
		} else {
			$limit_start = (40 * $_POST['page_number']);
			$this->var['filename']			= 'sss_r1a.xls';
			$this->var['current_page'] 		= $_POST['page_number'];
			$this->var['total_pages']		= $pages;
			$this->var['payslip'] 			= $payslip = G_Employee_Finder::findByPayslipDateRange($_POST['date_from'], $_POST['date_to'],$limit_start . ',40');
			$this->var['total_records'] 	= $total;
			$this->view->render('benchmark/leo/download_sss_report.html.php',$this->var);
		}
		
	}
}
?>