<?php
class G_Incentive_Leave_History_Helper {
	public static function isIdExist(G_Incentive_Leave_History $ilh) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . INCENTIVE_LEAVE_HISTORY ."
			WHERE id = ". Model::safeSql($ilh->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	/**
	* Returns id if month and year exists
	*
	* @param string month_number
	* @param string year
	* @return int id
	*/
	public static function isMonthNumberAndYearExists($month_number = '', $year = '') {
		$sql = "
			SELECT id 
			FROM " . INCENTIVE_LEAVE_HISTORY ."
			WHERE month_number =". Model::safeSql($month_number) ."
				AND year =" . Model::safeSql($year) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['id'];
	}

	/**
	* Get most least year
	*
	* @return array
	*/
	public static function getLeastYear() {
		$sql = "
			SELECT year
			FROM " . INCENTIVE_LEAVE_HISTORY ."
			ORDER BY year ASC
			LIMIT 1
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['year'];
	}
}
?>