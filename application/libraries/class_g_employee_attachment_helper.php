<?php
class G_Employee_Attachment_Helper {
	public static function isIdExist(G_Employee_Attachment $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_ATTACHMENT ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	
	public static function findByEmployeeId($employee_id,$order_by,$limit) {
		$sql = "
			*
			FROM ". G_EMPLOYEE_ATTACHMENT ."
			WHERE a.employee_id=".$employee_id."
			
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		//echo $sql;
		$result = Model::runSql($sql,true);

		return $result;
	}

}
?>