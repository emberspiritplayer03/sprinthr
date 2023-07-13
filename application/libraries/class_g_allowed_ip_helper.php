<?php
class G_Allowed_Ip_Helper {

    public static function isIdExist(G_Allowed_Ip $gra) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ALLOWED_IP ."
			WHERE id = ". Model::safeSql($gra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ALLOWED_IP			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countAllowedIpByIpAddressAndEmployeeId($client_ip,$employee_id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ALLOWED_IP ." 
			WHERE ip_address = ". Model::safeSql($client_ip) ."
			AND employee_id = ". Model::safeSql($employee_id) 
		;

		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countAllowedIpByIpAddress($client_ip) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ALLOWED_IP ." 
			WHERE ip_address = ". Model::safeSql($client_ip) 
		;
		
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
}
?>