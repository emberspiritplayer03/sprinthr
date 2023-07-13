<?php
class G_Settings_Employment_Status_Helper {
    public static function generate($company_structure_id, $status_name) {
        $es = new G_Settings_Employment_Status;
        $es->setCompanyStructureId($company_structure_id);
        $es->setStatus($status_name);
        return $es;
    }

	public static function isIdExist(G_Settings_Employment_Status $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYMENT_STATUS ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYMENT_STATUS ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYMENT_STATUS ."
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlDataById( $id = 0, $fields = array() ){
    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields =  " * ";
    	}

    	$sql = "
            SELECT {$sql_fields} 
            FROM " . EMPLOYMENT_STATUS . " 
            WHERE id = " . Model::safeSql($id) . "            	
            ORDER BY id DESC
            LIMIT 1
        ";
        
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
    }

	public static function sqlAllEmploymentStatus($fields = array(), $order_by = '') {
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
			FROM " . EMPLOYMENT_STATUS ." cs 						
			{$sql_order_by}
		";
	
		$rows = Model::runSql($sql,true);
		return $rows;
	}

	public static function sqlGetEmploymentStatusByIds($ids = array(), $fields = array(), $order_by = '') {
    	$sql_fields   = " * ";
    	$sql_order_by = "";

    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}

    	if( !empty($order_by) ){
    		$sql_order_by = $order_by; 
    	}

    	if( !empty($ids) ){
    		$sql_ids = implode(",", $ids);
    		$sql = "
				SELECT {$sql_fields}
				FROM " . EMPLOYMENT_STATUS ." cs 						
				WHERE id IN({$sql_ids})
				{$sql_order_by}
			";
    	}else{
    		$sql = "
				SELECT {$sql_fields}
				FROM " . EMPLOYMENT_STATUS ." cs 						
				{$sql_order_by}
			";
    	}
	
		$rows = Model::runSql($sql,true);
		return $rows;
	}

	public static function sqlGetEmploymentStatusDataByTitle($employment_status = "", $fields = array()){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYMENT_STATUS . " 
			WHERE status IN({$employment_status})
			ORDER BY status ASC
		";
		$result = Model::runSql($sql,true);
		return $result;
	}
	
}
?>