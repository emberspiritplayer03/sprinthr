<?php
class Dtr_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->validatePermission(G_Sprint_Modules::DTR,'dtr','');	
		Loader::appStyle('style.css');
	}
	
	function index() {
		Jquery::loadMainJqueryFormSubmit();
		$today = Tools::getGmtDate('Y-m-d');
		$yesterday = strtotime($today .' -1 day');
		$this->var['records'] = $records = G_Attendance_Log_Finder::findAllByPeriodAndLimit($today, $today);
		//$this->var['records_v2'] = $records = G_Attendance_Log_Finder_V2::findAllByPeriodAndLimit($today, $today);
		$this->var['project_sites'] = G_Project_Site::all();
		$this->var['activity_skills'] = G_Activity_Skills_Finder::findAllSkills();
		
		$this->view->setTemplate('template_plane.php');
		$this->view->render('dtr/index.php', $this->var);	
	}
	
	function refresh() {
		$today = Tools::getGmtDate('Y-m-d');
		$yesterday = strtotime($today .' -1 day');
		$this->var['records'] = G_Attendance_Log_Finder::findAllByPeriodAndLimit($today, $today);
		//$this->var['records_v2'] = G_Attendance_Log_Finder_V2::findAllByPeriodAndLimit($today, $today);
		$this->var['records_dtr'] = G_Attendance_Log_Finder_V2::findAllByPeriodAndLimit($today, $today);//G_Daily_Time_Record_Finder::findAllWithLimit(16);
		
		$this->view->noTemplate();
		$this->view->render('dtr/records.php', $this->var);	
	}

	function punch() {
		ob_start();
		$has_error = true;

		$code = $_GET['employee_code'];
		$type = $_GET['type'];
		$project_site_id = $_GET['project_site_id'];
		$activity_name = $_GET['activity_name'];

		$e = G_Employee_Finder::findByEmployeeCode($code);
		$e1 = G_Employee_Finder::findByEmployeeCode($code);
		if ($e) {
			$image = $e->getValidEmployeeImage();			
			$date  = Tools::getGmtDate('Y-m-d');
			$time  = Tools::getGmtDate('H:i:s');
			
		
			if (strtolower($type) == 'in') {
				$e->punchIn($date, $time);
				$e1->punchInWithProjectSiteAndActivity($date, $time, $project_site_id, $activity_name);
			} else if (strtolower($type) == 'out') {
				$e->punchOut($date, $time);
				$e1->punchOutWithProjectSiteAndActivity($date, $time, $project_site_id, $activity_name);
			}
	
			$has_error = false;
		}else{
			$e = new G_Employee();
		}

		$return['has_error'] = $has_error;
		$return['image']     = $e->getValidEmployeeImage();

		ob_end_clean();
		echo json_encode($return);
	}
}
?>