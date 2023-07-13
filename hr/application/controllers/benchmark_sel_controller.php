<?php
class Benchmark_Sel_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}

	function test_mysqli() {
		$sql = "Select * from g_employee";

		$a = new Mysqli_Connect();
		$result = $a->query($sql,true);
		Utilities::displayArray($result);
		
	}

	function test_sync() {

		
		$a = new G_Sync_Data();
		if($a->check_connection()) {
			$result = $a->sync();
		}else{
			echo 'no database connection';
		}
		
		
	}

	function validate_ip_only()
	{
		//echo "Your IP : " . $client_ip = Tools::get_client_ip();
		$client_ip = Tools::getUserIP();

		echo "Your IP : " . $client_ip . '<br>';

		$ai = new G_Allowed_Ip();
		$is_allowed = $ai->validateUserIp();

		if($is_allowed) {
			echo "allowed";
		}else{
			echo "not allowed";
		}
	}
	
	function validate_ip_by_user()
	{
		//echo "Your IP : " . $client_ip = Tools::get_client_ip();
		$client_ip = Tools::getUserIP();
		$user_id = 2;

		echo "Your IP : " . $client_ip . '<br>';
		echo "Your EmpID: " . $user_id . '<br>';
		$ai = new G_Allowed_Ip();
		$ai->setEmployeeId($user_id);
		$is_allowed = $ai->validateUserIp();

		if($is_allowed) {
			echo "allowed";
		}else{
			echo "not allowed";
		}
	}

	function leave_general_rule() {
		$slg = new G_Settings_Leave_General();
		$slg->getAllUnusedLeaveCreditLastYear()->applyGeneralRule();
	}
}
?>