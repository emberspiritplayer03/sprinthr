<?php
class G_Employee_User_Helper {

    public static function isIdExist(G_Employee_User $geu) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_USER ."
			WHERE id = ". Model::safeSql($geu->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_USER			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlCountTotalRecordsIsNotArchive() {
		$sql = "
			SELECT COUNT(u.id) AS total
			FROM " . G_EMPLOYEE_USER . " u 
				LEFT JOIN " . ROLES . " r ON u.role_id = r.id AND r.is_archive =" . Model::safeSql(G_Role::NO) . "
				LEFT JOIN " . EMPLOYEE . " e ON u.employee_id = e.id AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " s ON u.employee_id = s.employee_id AND s.end_date = ''
			WHERE u.is_archive = " . Model::safeSql(G_Employee_User::NO) . "

		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlLoginIsUserNameAndPasswordExists( $username = '', $password = ''){
		$sql = "
			SELECT COUNT(id)AS total
			FROM " . G_EMPLOYEE_USER . "
			WHERE username =" . Model::safeSql($username) . "
				AND password =" . Model::safeSql($password) . "
				AND is_archive =" . Model::safeSql(G_Employee_User::NO) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlUserLoginInfoByUsernameAndPassword( $username = '', $password = ''){
		$sql = "
			SELECT e.id, e.company_structure_id, e.photo, e.hash,
				u.username, e.employee_code,
				CONCAT(e.firstname, ' ', e.lastname)AS employee_name, 
			   	r.name AS role_name, r.id AS role_id,
			   	s.name AS position
			FROM " . G_EMPLOYEE_USER . " u 
				LEFT JOIN " . ROLES . " r ON u.role_id = r.id AND r.is_archive =" . Model::safeSql(G_Role::NO) . "
				LEFT JOIN " . EMPLOYEE . " e ON u.employee_id = e.id AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " s ON u.employee_id = s.employee_id AND s.end_date = ''

			WHERE u.username =" . Model::safeSql($username) . "
				AND u.password =" . Model::safeSql($password) . "
				AND u.is_archive =" . Model::safeSql(G_Employee_User::NO) . "
			ORDER BY id DESC 
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlIsEmployeeIdExists( $employee_id = 0 ) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_USER . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsUsernameExists($id = 0, $username = '') {
		$condition = "";

		if( $id > 0 ){
			$condition = " AND id <> {$id}"; //Add condition if id is > 0
		}

		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_USER . "
			WHERE username =" . Model::safeSql($username) . "
			{$condition}
		";

		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlUserDataById( $id = 0 ) {
		$sql = "
			SELECT u.id, u.username, u.role_id, u.password,
				CONCAT(e.firstname, ' ', e.lastname)AS employee_name
			FROM " . G_EMPLOYEE_USER . " u
				LEFT JOIN " . EMPLOYEE . " e ON u.employee_id = e.id 
			WHERE u.id =" . Model::safeSql($id) . "			
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlAllUserIsNotArchive($order_by = '', $limit = '') {

		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		if( !empty($limit) ){
			$limit = "LIMIT {$limit}";
		}

		$sql = "
			SELECT u.id, u.company_structure_id, u.employee_id, u.username, u.password, u.role_id, u.date_created, u.last_modified,
				   CONCAT(e.firstname, ' ', e.lastname)AS employee_name, 
				   r.name AS role_name,
				   s.name AS position
			FROM " . G_EMPLOYEE_USER . " u 
				LEFT JOIN " . ROLES . " r ON u.role_id = r.id AND r.is_archive =" . Model::safeSql(G_Role::NO) . "
				LEFT JOIN " . EMPLOYEE . " e ON u.employee_id = e.id AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " s ON u.employee_id = s.employee_id AND s.end_date = ''
			WHERE u.is_archive = " . Model::safeSql(G_Employee_User::NO) . "

			". $order_by ."
			". $limit ."
		";

		$record = Model::runSql($sql,true);

		return $record;
	}
}
?>