<?php
class Benchmark_Let_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}

	function index() {
		//$e = G_Employee_Finder::findById(1);
		//$p = G_Payment_Finder::findById(1);
		//$ps = new G_Payment_Scheduler;
		//$ps->setPayment($p);
		//echo '<pre>';
		//print_r($p);
		
		echo number_format(10.7167, 2);
	}
}
?>