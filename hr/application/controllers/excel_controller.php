<?php
class Excel_Controller extends Controller
{
	function __construct()
	{		
		parent::__construct();					
	}
	
	function test() {
		$this->var['employees'] = $employees = G_Employee_Finder::findAllActive();		
		$this->view->render('test/test.php', $this->var);	
	}
}
?>