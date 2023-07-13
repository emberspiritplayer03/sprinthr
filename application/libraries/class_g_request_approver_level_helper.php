<?php
class G_Request_Approver_Level_Helper {

    public static function isIdExist(G_Request_Approver_Level $grl) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUEST_APPROVERS_LEVEL ."
			WHERE id = ". Model::safeSql($gra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUEST_APPROVERS_LEVEL			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlApproversLevelByRequestApproversId( $request_approvers_id = 0, $fields = array(), $order_by = '', $limit = 0 ) {
		$sql_order_by = ($order_by != '') ? $order_by : '';
		$sql_limit    = ($limit > 0)? 'LIMIT ' . $limit : '';
		$sql_fields   = (!empty($fields)) ? implode(",", $fields) : ' * ';
		

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUEST_APPROVERS_LEVEL ." 
			WHERE request_approvers_id = " . Model::safeSql($request_approvers_id) . " 
			{$sql_order_by}
			{$sql_limit}
		";				
		$result = Model::runSql($sql,true);		
		return $result;	
	}
}
?>