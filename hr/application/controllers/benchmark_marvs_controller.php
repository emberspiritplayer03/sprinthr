<?php
class Benchmark_Marvs_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	
	function testtest()
	{
		$e = G_Employee_Finder::findById(1);
		print_r($e);
		echo "test";
		$time = '9';
		$time_start = '8';
		$time_end = '10';
		$t =  Tools::isTimeBetweenHours($time,$time_start,$time_end);
		if($t) {
			echo "yes";	
		}else {
			echo "no";	
		}
	}
}
?>