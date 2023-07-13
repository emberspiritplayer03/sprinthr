<?php
class G_Employee_Education_Helper {
		
	public static function isIdExist(G_Employee_Education $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EDUCATION ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlDistinctCourse($fields = array(), $order_by = '') {
    	$sql_fields   = " * ";
    	$sql_order_by = "";

    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}

    	if( !empty($order_by) ){
    		$sql_order_by = $order_by; 
    	}


		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EDUCATION ."  						
			{$sql_order_by}
		";
		
		$rows = Model::runSql($sql,true);
		return $rows;
	}

}
?>