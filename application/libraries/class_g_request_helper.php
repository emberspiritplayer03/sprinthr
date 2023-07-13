<?php
class G_Request_Helper {

    public static function isIdExist(G_Request $gr) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUESTS ."
			WHERE id = ". Model::safeSql($gr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUESTS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlTotalApproversByRequestIdAndRequestType($request_id = 0, $request_type = '') {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUESTS ."
			WHERE request_id = ". Model::safeSql($request_id) ."
				AND request_type =" . Model::safeSql($request_type) . "
		";
				
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlGetNextApproverById($id = 0, $fields = array()) {
		$sql_fields = !empty($fields) ? implode(",", $fields) : ' * ';
		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUESTS . "
			WHERE id > " . Model::safeSql($id) . "
			ORDER BY id ASC 
			LIMIT 1
		";		
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlRequestDataSummaryByRequestIdAndRequestType($request_id = 0, $request_type = '') {
		$sql = "
			SELECT(
				SELECT COUNT(id) as total
				FROM " . REQUESTS . "
				WHERE request_id =" . Model::safeSql($request_id) . "
					AND request_type =" . Model::safeSql($request_type) . "
			)AS total_approvers,
			(
				SELECT COUNT(id) as total
				FROM " . REQUESTS . "
				WHERE request_id =" . Model::safeSql($request_id) . "
					AND status =" . Model::safeSql(G_Request::APPROVED) . "
					AND request_type =" . Model::safeSql($request_type) . "
			)AS total_approved,
			(
				SELECT COUNT(id) as total
				FROM " . REQUESTS . "
				WHERE request_id =" . Model::safeSql($request_id) . "
					AND status =" . Model::safeSql(G_Request::PENDING) . "
					AND request_type =" . Model::safeSql($request_type) . "
			)AS total_pending,
			(
				SELECT COUNT(id) as total
				FROM " . REQUESTS . "
				WHERE request_id =" . Model::safeSql($request_id) . "
					AND status =" . Model::safeSql(G_Request::DISAPPROVED) . "
					AND request_type =" . Model::safeSql($request_type) . "
			)AS total_disapproved

		";
		
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlFetchDataByRequestIdAndRequestType( $request_id = 0, $request_type = '', $fields = array(), $order_by = '', $limit = '' ) {
		$sql_order_by = ($order_by != '') ? $order_by  : '';		
		$sql_fields   = !empty($fields) ? implode(",", $fields) : ' * ';
		$sql_limit    = !empty($limit) ? $limit : '';

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUESTS ." 
			WHERE request_id = " . Model::safeSql($request_id) . " 
				AND request_type =" . Model::safeSql($request_type) . "
			{$sql_order_by}
			{$sql_limit}
		";		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlFetchDataByApproverIdAndRequestIdAndRequestType( $approver_id = 0, $request_id = 0, $request_type = '', $fields = array(), $order_by = '', $limit = '' ) {
		$sql_order_by = ($order_by != '') ? $order_by  : '';		
		$sql_fields   = !empty($fields) ? implode(",", $fields) : ' * ';
		$sql_limit    = !empty($limit) ? $limit : '';

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUESTS ." 
			WHERE approver_employee_id =" . Model::safeSql($approver_id) . "
				AND request_id =" . Model::safeSql($request_id) . " 
				AND request_type =" . Model::safeSql($request_type) . "
			{$sql_order_by}
			{$sql_limit}
		";		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlFetchDataByApproverIdAndRequestIdAndRequestTypeAndIsNotLock( $approver_id = 0, $request_id = 0, $request_type = '', $fields = array(), $order_by = '', $limit = '' ) {
		$sql_order_by = ($order_by != '') ? $order_by  : '';		
		$sql_fields   = !empty($fields) ? implode(",", $fields) : ' * ';
		$sql_limit    = !empty($limit) ? $limit : '';

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUESTS ." 
			WHERE approver_employee_id =" . Model::safeSql($approver_id) . "
				AND request_id =" . Model::safeSql($request_id) . " 
				AND request_type =" . Model::safeSql($request_type) . "
				AND is_lock =" . Model::safeSql(G_Request::NO) . "
			{$sql_order_by}
			{$sql_limit}
		";		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlGetPendingRequestByApproverEmployeeId( $approver_employee_id, $request_type = '', $fields = array(), $order_by = '' ) {
		$sql_order_by = ($order_by != '') ? $order_by  : '';		
		$sql_fields   = (!empty($fields)) ? implode(",", $fields) : ' * ';

		$sql_condition = ($request_type != '') ? " AND request_type = ".Model::safeSql($request_type) : '';	
		
		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUESTS ." 
			WHERE approver_employee_id = " . Model::safeSql($approver_employee_id) . " 
				AND status =" . Model::safeSql(G_Request::PENDING) . " 
				{$sql_condition}
			{$sql_order_by}
			{$sql_limit}
		";								
		$result = Model::runSql($sql,true);		
		return $result;	
	}

}
?>