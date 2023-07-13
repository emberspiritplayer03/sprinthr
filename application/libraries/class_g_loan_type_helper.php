<?php
class G_Loan_Type_Helper {
	public static function isIdExist(G_Loan_Type $glt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LOAN_TYPE ."
			WHERE id = ". Model::safeSql($glt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LOAN_TYPE ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	

	public static function sqlLoanTypeNameById($id = 0) {
		$sql = "
			SELECT loan_type
			FROM " . G_LOAN_TYPE ."
			WHERE id = ". Model::safeSql($id) ."
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['loan_type'];
	}

	public static function sqlGetLoanTypeDetailsById( $id = '', $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_LOAN_TYPE . "
			WHERE id =" . Model::safeSql($id) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlAllIsNotArchiveLoanTypes( $fields = array() ) {
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

        $sql = "
        	SELECT {$sql_fields}
			FROM " . G_LOAN_TYPE . "
			WHERE is_archive =" . Model::safeSql(G_Loan_Type::NO) . "
			ORDER BY id DESC 
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}
}
?>