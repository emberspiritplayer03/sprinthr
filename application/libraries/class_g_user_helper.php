<?php
class G_User_Helper {
		
	public static function isIdExist(G_User $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_USER ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	public static function isUsernameExist($username) {
		$username = Model::safeSql($username);
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_USER ."
			WHERE username = ". trim(strtolower($username)) ."
		";
		//echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_USER ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByUsernamePassword($username='',$password='') {
		$sql = "SELECT * FROM g_user WHERE username=".Model::safeSql($username)." AND password=".Model::safeSql($password)."";
		$result = Model::runSql($sql,true);	
		if($result) {
			$return = true;	
		}else {
			$return = false;
		}
		
		return $return;
	}
	
	public static function isLogin()
	{
		$return = false;

		if ($_SESSION['sprint_hr']['company_structure_id'] && $_SESSION['sprint_hr']['username'] && $_SESSION['sprint_hr']['employee_id'])
		{
			unset($_SESSION['sprint_hr']['redirect_uri']);
			$return = true;
		}

		return $return;
	}
	
	
	public static function findAll($order_by, $limit) {
		$sql = "
			SELECT 
			u.id,
			CONCAT(e.lastname,', ', e.firstname) as employee_name,
			u.user_group_id,
			u.employee_id,
			u.employment_status,
			u.username,
			u.hash,
			u.password,
			u.module,
			u.date_entered,
			g.group_name
			FROM g_user u
			LEFT JOIN g_employee e ON e.id=u.employee_id
			LEFT JOIN g_user_group g ON g.id=u.user_group_id

			".$order_by."
			".$limit."
		";

		$record = Model::runSql($sql,true);

		return $record;
	}
	
	public static function findUserEmployee($q) {
		$sql = "
			  SELECT 
				u.id,
				CONCAT(e.firstname, ' ' , e.lastname) as employee_name,
				CONCAT(e.firstname, ' ' , e.lastname) as name,
				u.user_group_id,
				u.hash,
				u.employment_status
			FROM " . G_USER ." u LEFT JOIN " . EMPLOYEE . " e
			ON u.employee_id = e.id
			WHERE 
			CONCAT(e.firstname, ' ' , e.lastname) LIKE '%$q%'
		";	
		$record = Model::runSql($sql,true);
		return $record;
	}

}
?>