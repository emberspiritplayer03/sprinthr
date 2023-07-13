<?php
class G_Custom_Overtime_Helper {
	public static function isIdExist(G_Custom_Overtime $co) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_CUSTOM_OVERTIME ."
			WHERE id = ". Model::safeSql($co->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlCountTotalApprovedAndDisapprovedCustomOvertimeByEmployeeIdAndDate($employee_id = 0, $date = '') {
		$sql = "
			SELECT COALESCE(COUNT(id),0) as total
			FROM " . G_CUSTOM_OVERTIME ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
				AND date =" . Model::safeSql($date) . "
				AND (status =" . Model::safeSql(G_Custom_Overtime::APPROVED) . " OR status =" . Model::safeSql(G_Custom_Overtime::DISAPPROVED) . ")
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	/**
	 * Get All employee approved custom overtime by date range method
	 *
	 *@param int employee_id, date start_date, date end_date, array fields
	 *@return array
	*/
	public static function getAllEmployeeApprovedCustomOvertimeByDateRange($employee_id = 0, $start_date = '', $end_date = '', $fields = array()) {
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$start_date = date("Y-m-d",strtotime($start_date));
		$end_date   = date("Y-m-d",strtotime($end_date));

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_CUSTOM_OVERTIME ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
				AND date BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date) . " 
				AND status =" . Model::safeSql(G_Custom_Overtime::APPROVED) . "
		";
		$result = Model::runSql($sql,true);
		$return = array();
		return $result;
	}

	/**
	 * Get All employee disapproved custom overtime by date range method
	 *
	 *@param int employee_id, date start_date, date end_date, array fields
	 *@return array
	*/
	public static function getAllEmployeeDisApprovedCustomOvertimeByDateRange($employee_id = 0, $start_date = '', $end_date = '', $fields = array()) {
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$start_date = date("Y-m-d",strtotime($start_date));
		$end_date   = date("Y-m-d",strtotime($end_date));

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_CUSTOM_OVERTIME ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
				AND date BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date) . " 
				AND status =" . Model::safeSql(G_Custom_Overtime::DISAPPROVED) . "
		";
		$result = Model::runSql($sql,true);
		$return = array();
		return $result;
	}	
}
?>