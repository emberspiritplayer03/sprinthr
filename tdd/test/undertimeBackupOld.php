<?php
error_reporting(0);
class TestUndertime extends UnitTestCase {
	
	/*
		Pang Umaga Break
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';
		
		Pang Gabe Break
		$break_time_in = '00:00:00';
		$break_time_out = '01:00:00';
	*/
	function testUndertime_pangumaga_sameday_ang_inout1() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:42:00';
		$actual_time_out = '16:58:00';
		$break_time_in = '00:00:00';
		$break_time_out = '01:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours, 0.0333);
	}
	
	function testUndertime_pangumaga_sameday_ang_inout2() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:42:00';
		$actual_time_out = '16:38:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.3667);
	}
	
	function testUndertime_pangumaga_sameday_ang_inout3() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:42:00';
		$actual_time_out = '16:59:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.0167);
	}
	
	function testUndertime_pangumaga_sameday_ang_inout4() {
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '18:53:00';
		$actual_time_out = '04:59:00';
		$break_time_in = '00:00:00';
		$break_time_out = '01:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.0167);
	}	
	
	function testUndertime_pangumaga_sameday_ang_inout5() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:55:00';
		$actual_time_out = '16:38:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.3667);
	}
	
	function testUndertime_pangumaga_sameday_ang_inout6() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:36:00';
		$actual_time_out = '16:38:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.3667);
	}
	
	function testUndertime_pangumaga_sameday_ang_inout7() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:34:00';
		$actual_time_out = '16:38:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.3667);
	}
	
	function testUndertime_pangumaga_sameday_ang_inout10() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '08:08:00';
		$actual_time_out = '16:59:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours,0.0167);
	}
	
	function testUndertime_saglit_lang_pumasok_ng_umaga() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$actual_time_in = '07:00:00';
		$actual_time_out = '08:00:00';
		$break_time_in = '12:00:00';
		$break_time_out = '13:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours, 8.00);
	}
	
	function testUndertime_saglit_lang_pumasok_ng_gabi2() {
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '19:20:00';
		$actual_time_out = '21:00:00';
		$break_time_in = '00:00:00';
		$break_time_out = '01:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours, 7.00);
	}		
	
	function testUndertime_saglit_lang_pumasok_ng_gabi() {
		$scheduled_time_in = '20:00:00';
		$scheduled_time_out = '05:00:00';
		$actual_time_in = '19:02:00';
		$actual_time_out = '20:46:00';
		$break_time_in = '00:00:00';
		$break_time_out = '01:00:00';

		$u = new Undertime_Calculator;
		$u->setScheduledTimeIn($scheduled_time_in);
		$u->setScheduledTimeOut($scheduled_time_out);
		$u->setActualTimeIn($actual_time_in);
		$u->setActualTimeOut($actual_time_out);
		$u->setBreakTimeIn($break_time_in);
		$u->setBreakTimeOut($break_time_out);			
		$ut_hours = $u->computeUndertimeHours();
		$this->assertIdentical($ut_hours, 7.2333);
	}					
}
?>