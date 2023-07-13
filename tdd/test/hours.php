<?php
error_reporting(0);
class TestHours extends UnitTestCase {
	
	function testHoursDifferenceNightshift() {
		//$start = '16:56:00';
		//$end = '07:37:00';
		$start = '07:25:00';
		$end = '06:07:00';		
				
		$w = new Working_Hours_Calculator;
		$w->setTimeIn($start);
		$w->setTimeOut($end);
		$w->compute();

		//echo fcomputeHoursWorked($start, $end);
		//echo fcomputeHoursWorked($start, $end);
		//echo testcompute($start, $end);
		//echo Tools::getHoursDifference($start, $end);
	}	
}	
?>