<?php
class G_Request_Approver_Requestor_Helper {

    public static function isIdExist(G_Request_Approver_Requestor $grr) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUEST_APPROVERS_REQUESTORS ."
			WHERE id = ". Model::safeSql($grr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUEST_APPROVERS_REQUESTORS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getRequestorApproversByRequestorIdAndType( $requestor_id = 0, $type = '' ) {
		$data = array();
		if( !empty( $type ) && $requestor_id  > 0 ){
			$sql = "
				SELECT al.employee_id, al.employee_name, al.level 
				FROM  " . REQUEST_APPROVERS_REQUESTORS . " ar 
					LEFT JOIN " . REQUEST_APPROVERS_LEVEL . " al ON ar.request_approvers_id = al.request_approvers_id					
				WHERE ar.employee_department_group_id =" . Model::safeSql($requestor_id) . "
					AND ar.employee_department_group =" . Model::safeSql($type) . "
				ORDER BY al.level ASC
			";
			
			$result = Model::runSql($sql,true);
			$data = $result;			
		}	

		return $data;
	}

	public static function sqlApproversLevelByRequestApproversId( $request_approvers_id = 0, $fields = array(), $limit = 0 ) {
		$sql_order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$sql_limit    = ($limit > 0)? 'LIMIT ' . $limit : '';
		$sql_fields   = (!empty($fields)) ? implode(",", $fields) : ' * ';
		

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUEST_APPROVERS_REQUESTORS ." 
			WHERE request_approvers_id = " . Model::safeSql($request_approvers_id) . " 
			{$sql_order_by}
			{$sql_limit}
		";				
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}
}
?>