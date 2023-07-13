<?php
class G_Settings_Skills_Helper {
	public static function isIdExist(G_Settings_Skills $gss) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SKILLS ."
			WHERE id = ". Model::safeSql($gss->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SKILLS ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SKILLS ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlDistinctSkills($fields = array(), $order_by = '') {
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
			FROM " . SKILLS ."  						
			{$sql_order_by}
		";
		
		$rows = Model::runSql($sql,true);
		return $rows;
	}
}
?>