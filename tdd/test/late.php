<?php
error_reporting(0);
class TestLate extends UnitTestCase {
	
    function testLateDayShift()
	{
		$scheduled_time_in  = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in 	= '08:10:00';
		$actual_time_out 	= '18:56:00';			
		echo '<div style="margin-top: 1em; background-color: #c52b71; color: white;">Dayshift : ' .$scheduled_time_in. ' to ' .$scheduled_time_out. ' | Actual Time IN/OUT: ' .$actual_time_in. ' to ' .$actual_time_out. '</div>';
		
		if (Tools::isTimeNightShift($scheduled_time_in)) {
			$break_time_in  = '00:00:00';
			$break_time_out = '01:00:00';
		} else {
			$break_time_in  = '12:00:00';
			$break_time_out = '13:00:00';
		}
		$l = new Late_Calculator;
		$l->setGracePeriod(0);
		$l->setBreakTimeIn($break_time_in);
		$l->setBreakTimeOut($break_time_out);			
		$l->setScheduledTimeIn($scheduled_time_in);
		$l->setScheduledTimeOut($scheduled_time_out);
		$l->setActualTimeIn($actual_time_in);
		$l->setActualTimeOut($actual_time_out);

		$late_hours = $l->computeLateHours();
		$expected_output = .17;

		echo '<pre>';
		print_r($l);
		echo '</pre>';
		
		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'Output: ' . $late_hours . ' | ';
		echo 'Expected Output: ' . $expected_output;
		echo '</div>';
		
		$this->assertEqual($late_hours, $expected_output);
		echo '<hr />';
	}		

    function testLateNightShift()
	{
		$scheduled_time_in  = '18:00:00';
		$scheduled_time_out = '03:00:00';
		$actual_time_in 	= '19:10:00';
		$actual_time_out 	= '04:56:00';		
		echo '<div style="margin-top: 1em; background-color: #c52b71; color: white;">Dayshift : ' .$scheduled_time_in. ' to ' .$scheduled_time_out. ' | Actual Time IN/OUT: ' .$actual_time_in. ' to ' .$actual_time_out. '</div>';

		if (Tools::isTimeNightShift($scheduled_time_in)) {
			$break_time_in  = '00:00:00';
			$break_time_out = '01:00:00';
		} else {
			$break_time_in  = '12:00:00';
			$break_time_out = '13:00:00';
		}
		$l = new Late_Calculator;
		$l->setGracePeriod(0);
		$l->setBreakTimeIn($break_time_in);
		$l->setBreakTimeOut($break_time_out);			
		$l->setScheduledTimeIn($scheduled_time_in);
		$l->setScheduledTimeOut($scheduled_time_out);
		$l->setActualTimeIn($actual_time_in);
		$l->setActualTimeOut($actual_time_out);
		$late_hours = $l->computeLateHours();
		$expected_output = 0;
		
		echo '<pre>';
		print_r($l);
		echo '</pre>';
		
		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'Output: ' . $late_hours . ' | ';
		echo 'Expected Output: ' . $expected_output;
		echo '</div>';
		
		$this->assertEqual($late_hours, $expected_output);
		echo '<hr />';
	}	
}
?>