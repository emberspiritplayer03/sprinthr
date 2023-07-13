<?php
class G_Holiday_Manager {	
	public static function save(G_Holiday $h) {		
		if ($h->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_HOLIDAY;
			$sql_end   = " WHERE id = ". Model::safeSql($h->getId());		
		} else {
			$action = 'insert';
			$public_id = uniqid();
			$sql_start = "INSERT INTO ". G_HOLIDAY;
			$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			holiday_type     = " . Model::safeSql($h->getType()) .",
			holiday_title	 = " . Model::safeSql($h->getTitle()) .",
			holiday_month 	 = " . Model::safeSql($h->getMonth()) .",
			holiday_day 	 = " . Model::safeSql($h->getDay()) .",
			holiday_year 	 = " . Model::safeSql($h->getYear()) ."
			". $sql_end ."		
		";			
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}	
	}
		
	public static function delete(G_Holiday $h) {
		$sql = "
			DELETE FROM ". G_HOLIDAY ."
			WHERE id = ". Model::safeSql($h->getId()) ."
		";		
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function addCompanyBranch(G_Company_Branch $b, G_Holiday $h) {
		$sql = "
			INSERT INTO ". G_HOLIDAY_BRANCH ." (holiday_id, company_branch_id)
			VALUES (". Model::safeSql($h->getId()) .", ". Model::safeSql($b->getId()) .")
		";
		
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		} else {
			return mysql_insert_id();
		}
	}
	
	public static function removeCompanyBranch(G_Company_Branch $b, G_Holiday $h) {
		$sql = "
			DELETE FROM ". G_HOLIDAY_BRANCH ."
			WHERE holiday_id = ". Model::safeSql($h->getId()) ."
			AND company_branch_id = ". Model::safeSql($b->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
}
?>