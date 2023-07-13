<?php
class G_Settings_Leave_Credit_Helper {
	
	public static function isIdExist(G_Settings_Leave_Credit $u) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGG_LEAVE_CREDIT ."
			WHERE id = ". Model::safeSql($u->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGG_LEAVE_CREDIT ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getAllLeaveCredits() {
		$sql = "
			SELECT glc.id, glc.employment_years, glc.default_credit, glc.is_archived, gl.name, es.status 
			FROM " . G_SETTINGG_LEAVE_CREDIT . " glc, " . G_LEAVE . " gl, " . EMPLOYMENT_STATUS . " es
			WHERE (glc.leave_id = gl.id)AND(glc.employment_status_id = es.id)

		";
		$records = Model::runSql($sql, true);
		return $records;		
	}

	public static function getAllUniqueLeaveId() {
		$sql = "
			SELECT DISTINCT(leave_id)
			FROM " . G_SETTINGG_LEAVE_CREDIT . " 
			WHERE is_archived = 'No'
		";
		$records = Model::runSql($sql, true);
		return $records;		
	}

	public static function getLeaveDefaultCredit($ldata, $sort = array()) {
		$sql_order_by = '';

		if( $sort ){
			foreach( $sort as $key => $value ){
				$sql_order_by = "ORDER BY " . $key . " " . $value;
			}
		}

		$sql = "
			SELECT id, default_credit, employment_years 
			FROM " . G_SETTINGG_LEAVE_CREDIT . "
			WHERE employment_years <= ". Model::safeSql($ldata['employee_year_of_service']) ."
			AND leave_id = ". Model::safeSql($ldata['leave_id']) ." 
			AND employment_status_id = ". Model::safeSql($ldata['employment_status']) ." 			
			{$sql_order_by}
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['default_credit'];			
	}

}
?>