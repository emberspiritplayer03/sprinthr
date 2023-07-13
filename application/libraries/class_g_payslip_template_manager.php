<?php
class G_Payslip_Template_Manager {
	public static function save(G_Payslip_Template $e) {
		if (G_Payslip_Template_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_PAYSLIP_TEMPLATE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
			$sql_command = "update";
		}else{
			$sql_start = "INSERT INTO ". G_PAYSLIP_TEMPLATE . "";
			$sql_end   = "";		
			$sql_command = "insert";			
		}
		
		$sql = $sql_start ."
			SET
			template_name = " . Model::safeSql($e->getTemplateName()) .",
			is_default    = " . Model::safeSql($e->getIsDefault()) ."
			"
			. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		if($sql_command == "update") {
			return mysql_affected_rows();
		} else { return mysql_insert_id(); }
		
	}

	public static function clearDefaultTemplate() {
		$sql_start = "UPDATE ". G_PAYSLIP_TEMPLATE . "";
		$sql_end   = "";
		$sql = $sql_start ."
			SET
			is_default    = " . Model::safeSql(G_Payslip_Template::IS_DEFAULT_NO) ."
			"
			. $sql_end ."	
		
		";			

		Model::runSql($sql);
		return mysql_affected_rows();
	}

	public static function delete(G_Payslip_Template $e){
		if(G_Payslip_Template_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_PAYSLIP_TEMPLATE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>