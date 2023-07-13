<?php
class G_Employee_Attachment_Manager {
	public static function save(G_Employee_Attachment $gcb) {
		if (G_Employee_Attachment_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_ATTACHMENT . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_ATTACHMENT . " ";
			$sql_end  = " ";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id 			= " . Model::safeSql($gcb->getEmployeeId()) . ",
			filename				= " . Model::safeSql($gcb->getFilename()) . ",
			description             = " . Model::safeSql($gcb->getDescription()) . ",
			size			        = " . Model::safeSql($gcb->getSize()) . ",
			type			        = " . Model::safeSql($gcb->getType()) . ",
			date_attached	        = " . Model::safeSql($gcb->getDateAttached()) . ",
			added_by			    = " . Model::safeSql($gcb->getAddedBy()) . ",
			screen			        = " . Model::safeSql($gcb->getScreen()) . "
			 "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Attachment $gcb){
		if(G_Employee_Attachment_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_ATTACHMENT ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
}
?>