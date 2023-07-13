<?php
class Employee_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function _import_error()
	{ 		
		$this->var['error'] = $_SESSION['hr']['error'];
		$this->view->noTemplate();
		$this->view->render('startup/employee/form/import_error.php',$this->var);	
	}
	
}
?>
