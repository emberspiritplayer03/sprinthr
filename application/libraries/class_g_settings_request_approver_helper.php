<?php
class G_Settings_Request_Approver_Helper {
	public static function isIdExist(G_Settings_Request_Approver $gsra) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST_APPROVERS ."
			WHERE id = ". Model::safeSql($gsra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isPositionEmployeeIdExistsInRequestId($settings_request_id,$position_employee_id,$type) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST_APPROVERS ."
			WHERE settings_request_id = " . Model::safeSql($settings_request_id) . " 
				AND position_employee_id = "  . Model::SafeSql($position_employee_id) . " 
				AND type = ". Model::safeSql($type) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByType(G_Settings_Request_Approver $gsra) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST_APPROVERS ."
			WHERE type = ". Model::safeSql($gsra->getType()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsBySettingsRequestId(G_Settings_Request $gsr) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST_APPROVERS ."
			WHERE settings_request_id = ". Model::safeSql($gsr->getType()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getAllApproversBySettingsRequestId($settings_request_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE settings_request_id =" . Model::safeSql($settings_request_id) . " 
			".$order_by."
			".$limit."		
		";
		
		
		
		$approvers = Model::runSql($sql,true);
		
		foreach($approvers as $a):
			$array[] = $a['id'];
		endforeach;
		
		return $array;
	}
}
?>