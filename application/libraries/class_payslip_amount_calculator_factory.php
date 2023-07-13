<?php
class Payslip_Amount_Calculator_Factory {
	public static function get() {
		//return new Payslip_Amount_Calculator;	
		return new Payslip_Amount_Calculator_IM;
	}
}
?>