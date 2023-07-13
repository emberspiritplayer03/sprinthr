<?php

class G_Allowed_Ip extends Allowed_Ip {
	
	public function __construct() {
		
	}

	/*
		Usage:
		$ai = new G_Allowed_Ip();
		$ai->setEmployeeId(2);  //Optional
		$is_allowed = $ai->validateUserIp();

		@returns boolean (true/false)
	 */
	public function validateUserIp() {
		$client_ip = Tools::getUserIP();
		$ai_count = G_Allowed_Ip_Helper::countTotalRecords();

		if(isset($this->employee_id)) {
			$ai = G_Allowed_Ip_Helper::countAllowedIpByIpAddressAndEmployeeId($client_ip,$this->employee_id);
		}else{
			$ai = G_Allowed_Ip_Helper::countAllowedIpByIpAddress($client_ip);
		}
		
		if($ai > 0) {
			//allowed ip address from ip table
			return true;
		}elseif($ai_count == 0) {
			//if there is no ip set, all employee can access/file request
			return true;
		}elseif( ($client_ip == "::1") || ($client_ip == "127.0.0.1") ) {
			// allowed on localhost
			return true;
		}

		return false;
	}
							
	public function save() {
		return G_Allowed_Ip_Manager::save($this);
	}
		
	public function delete() {
		G_Allowed_Ip_Manager::delete($this);
	}
}
?>