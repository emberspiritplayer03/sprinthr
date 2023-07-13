<?php
class Dtr_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		Jquery::loadMainJqueryFormSubmit();
		$this->var['records'] = G_Daily_Time_Record_Finder::findAllWithLimit(16);
		$this->view->setTemplate('template_blank.php');
		$this->view->render('dtr/index.php', $this->var);	
	}
	
	function refresh() {
		$this->var['records'] = G_Daily_Time_Record_Finder::findAllWithLimit(16);
		$this->view->noTemplate();
		$this->view->render('dtr/records.php', $this->var);	
	}	
	
	function punch() {
		$has_error = true;
		$d = new Daily_Time_Record;
		$code = $_GET['employee_code'];
		$e = G_Employee_Finder::findByEmployeeCode($code);
		if ($e) {
			$d->setEmployeeCode($code);
			$d->setEmployeeName($e->getName());
			$d->punch();
			$has_error = false;
		}		
		$return['has_error'] = $has_error;
		echo json_encode($return);
	}
}
?>