<?php
class Candidate_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->login();
		Loader::appMainScript('employee.js');

		Loader::appStyle('style.css');
		$this->var['employee'] = 'selected';
		
		$this->var['company_structure_id'] = $_SESSION['hr']['company_structure_id'];	

	}

	function index()
	{
		echo "test";
	}
	
	function registration()
	{
		echo "test";
	}

	
}
?>