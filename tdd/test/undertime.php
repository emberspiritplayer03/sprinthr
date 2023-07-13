<?php
error_reporting(0);
class TestUndertime extends UnitTestCase {

	function testUndertimeDayShift() {

		$scheduled_time_in 	= '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in 	= '07:42:00';
		$actual_time_out 	= '15:30:00';		

		echo '<div style="margin-top: 1em; background-color: #c52b71; color: white;">Dayshift : ' .$scheduled_time_in. ' to ' .$scheduled_time_out. ' | Actual Date IN/OUT: ' .$actual_time_in. ' to ' .$actual_time_out. '</div>';

		//morning shift breaktime
		$break_time_in  = '12:00:00';
		$break_time_out = '13:00:00';

        $u = new Undertime_Calculator;
        $u->setScheduledTimeIn($scheduled_time_in);
        $u->setScheduledTimeOut($scheduled_time_out);
        $u->setActualTimeIn($actual_time_in);
        $u->setActualTimeOut($actual_time_out);
        $u->setBreakTimeIn($break_time_in);
        $u->setBreakTimeOut($break_time_out);           
        
        $expected_output = 2.50;
        $expected_output_in_minutes = 90;
        $undertime_hours = $u->computeUndertimeHours();
        $undertime_hours_in_minutes = $undertime_hours * 60;

		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'Undertime Hours: ' . $undertime_hours . ' | ';
		echo 'Expected Output: ' . $expected_output;
		echo '<br />';
		echo '</div>';

		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'In Minutes Hours: ' . $undertime_hours_in_minutes . ' | ';
		echo 'Expected Output: ' . $expected_output_in_minutes;
		echo '<br />';
		echo '</div>';
		echo '<hr />';        

        $this->assertEqual($undertime_hours, $expected_output);
	}

	function testUndertimeNightShift() {

		$scheduled_time_in 	= '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in 	= '07:42:00';
		$actual_time_out 	= '13:30:00';		

		echo '<div style="margin-top: 1em; background-color: #c52b71; color: white;">Dayshift : ' .$scheduled_time_in. ' to ' .$scheduled_time_out. ' | Actual Date IN/OUT: ' .$actual_time_in. ' to ' .$actual_time_out. '</div>';

		//morning shift breaktime
		$break_time_in  = '12:00:00';
		$break_time_out = '13:00:00';

        $u = new Undertime_Calculator;
        $u->setScheduledTimeIn($scheduled_time_in);
        $u->setScheduledTimeOut($scheduled_time_out);
        $u->setActualTimeIn($actual_time_in);
        $u->setActualTimeOut($actual_time_out);
        $u->setBreakTimeIn($break_time_in);
        $u->setBreakTimeOut($break_time_out);           
        
        $expected_output = 3.50;
        $expected_output_in_minutes = 90;
        $undertime_hours = $u->computeUndertimeHours();
        $undertime_hours_in_minutes = $undertime_hours * 60;

		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'Undertime Hours: ' . $undertime_hours . ' | ';
		echo 'Expected Output: ' . $expected_output;
		echo '<br />';
		echo '</div>';

		echo '<div style="padding: 8px; margin-top: 1em; background-color: blue; color: white;">';
		echo 'In Minutes Hours: ' . $undertime_hours_in_minutes . ' | ';
		echo 'Expected Output: ' . $expected_output_in_minutes;
		echo '<br />';
		echo '</div>';
		echo '<hr />';        

        $this->assertEqual($undertime_hours, $expected_output);

	}	
}
?>