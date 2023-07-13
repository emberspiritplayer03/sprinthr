<?php
class G_Shr_Audit_Trail_Helper {
	
	public static function isIdExist(G_Shr_Audit_Trail $at) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SHR_AUDIT_TRAIL ."
			WHERE id = ". Model::safeSql($at->getShrId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getShrUserIPAndCountry(){		
		$user_ip = Tools::getRealIpAddr();				
		//$url     = "http://api.hostip.info/country.php?ip={$my_ip}&position=true";
		$user['ip']     = $user_ip;
		//$user['country']= file_get_contents($url);
		return $user; 
	}
	
	public static function countShrTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SHR_AUDIT_TRAIL ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getShrAuditTrailHRData($username, $role, $search_col, $search_field) {

		if($role == 'sprint_super_admin'){

			if($search_col == 'all'){
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'HR'
					ORDER BY id DESC
				";
			}
			else{
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'HR'
					AND ".$search_col." LIKE '%$search_field%'
					ORDER BY id DESC
				";
			}

		}
		else{

			if($search_col == 'all'){
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'HR'
					AND username = '$username'
					ORDER BY id DESC
				";
			}
			else{
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'HR'
					AND username = '$username'
					AND ".$search_col." LIKE '%$search_field%'
					ORDER BY id DESC
				";
			}

		}
			$records = Model::runSql($sql,true);
			return $records;
	}

	public static function getShrAuditTrailPayrollData($username, $role, $search_col, $search_field) {

		if($role == 'sprint_super_admin'){

			if($search_col == 'all'){
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'PAYROLL'
					ORDER BY id DESC
				";
			}
			else{
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'PAYROLL'
					AND ".$search_col." LIKE '%$search_field%'
					ORDER BY id DESC
				";
			}

		}
		else{

			if($search_col == 'all'){

				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'PAYROLL'
					AND username = '$username'
					ORDER BY id DESC
				";
			}
			else{
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'PAYROLL'
					AND username = '$username'
					AND ".$search_col." LIKE '%$search_field%'
					ORDER BY id DESC
				";
			}

		}
			$records = Model::runSql($sql,true);
			return $records;
	}

	public static function getShrAuditTrailTimeKeepingData($username, $role, $search_col, $search_field) {
		
		if($role == 'sprint_super_admin'){
			//echo $search_col;
			if($search_col == 'all'){

				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'TIMEKEEPING'
					ORDER BY id DESC
				";
			}
			else{
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'TIMEKEEPING'
					AND ".$search_col." LIKE '%$search_field%'
					ORDER BY id DESC
				";
			}
			
			
		}

		else{

			
			if($search_col == 'all'){
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'TIMEKEEPING'
					AND username = '$username'
					ORDER BY id DESC
				";
			}
			else{
				
				$sql = " SELECT * 
				FROM " . SHR_AUDIT_TRAIL ." 
					WHERE module = 'TIMEKEEPING'
					AND username = '$username'
					AND ".$search_col." LIKE '%$search_field%'
					ORDER BY id DESC
				";
			}

		}
	
		$records = Model::runSql($sql,true);
		return $records;
	}
			
	
}
?>