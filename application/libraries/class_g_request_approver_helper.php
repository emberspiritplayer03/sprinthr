<?php
class G_Request_Approver_Helper {

    public static function isIdExist(G_Request_Approver $gra) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUEST_APPROVERS ."
			WHERE id = ". Model::safeSql($gra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COALESCE(COUNT(id),0) as total
			FROM " . REQUEST_APPROVERS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsIdExists($id = 0) {
		$is_exists = false;

		$sql = "
			SELECT COUNT(id) as total
			FROM " . REQUEST_APPROVERS . "
			WHERE id =" . Model::safeSql($id) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);

		if( $row['total'] > 0 ){
			$is_exists = true;
		}
		
		return $is_exists;
	}

	public static function sqlRequestApproversById( $id = 0, $fields = array(), $limit = 0 ) {
		$sql_order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$sql_limit    = ($limit > 0)? 'LIMIT ' . $limit : '';
		$sql_fields   = (!empty($fields)) ? implode(",", $fields) : ' * ';
		

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUEST_APPROVERS ." 
			WHERE id = " . Model::safeSql($id) . " 
			{$sql_order_by}
			{$sql_limit}
		";				
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlAllRequestApprovers( $fields = array() ) {
		$sql_order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$sql_limit    = ($limit!='')? 'LIMIT ' . $limit : '';
		$sql_fields   = (!empty($fields)) ? implode(",", $fields) : ' * ';
		

		$sql = "
			SELECT {$sql_fields}
			FROM " . REQUEST_APPROVERS ." m 
			{$sql_order_by}
			{$sql_limit}
		";				
		$result = Model::runSql($sql,true);		
		return $result;	
	}
}
?>